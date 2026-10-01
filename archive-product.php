<?php
/**
 * Editorial WooCommerce shop/archive template.
 *
 * Optional ACF fields on the Shop page:
 * - harvest_title
 * - harvest_subtitle
 * - shop_hero_image (image)
 * - shop_hero_kicker
 *
 * Optional ACF fields on product categories:
 * - banner_image (image)
 * - hero_image (image)
 *
 * @package Loomaama
 * @version 9.0.0
 */

defined('ABSPATH') || exit;

get_header();

/* --------------------------------------------------------------------------
 * Helpers
 * -------------------------------------------------------------------------- */
if (!function_exists('loomaama_shop_image_url')) {
    function loomaama_shop_image_url($value, $size = 'full') {
        if (empty($value)) {
            return '';
        }

        if (is_array($value)) {
            if (!empty($value['sizes'][$size])) {
                return esc_url_raw($value['sizes'][$size]);
            }
            return !empty($value['url']) ? esc_url_raw($value['url']) : '';
        }

        if (is_numeric($value)) {
            $image = wp_get_attachment_image_url((int) $value, $size);
            return $image ? esc_url_raw($image) : '';
        }

        return esc_url_raw($value);
    }
}

if (!function_exists('loomaama_shop_term_image')) {
    function loomaama_shop_term_image($term, $size = 'full') {
        if (!$term || is_wp_error($term)) {
            return '';
        }

        if (function_exists('get_field')) {
            foreach (array('banner_image', 'hero_image', 'category_banner') as $field_name) {
                $field = get_field($field_name, $term);
                $url = loomaama_shop_image_url($field, $size);
                if ($url) {
                    return $url;
                }
            }
        }

        $thumbnail_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true);
        if ($thumbnail_id) {
            $url = wp_get_attachment_image_url($thumbnail_id, $size);
            if ($url) {
                return $url;
            }
        }

        $fallback = new WP_Query(array(
            'post_type'              => 'product',
            'post_status'            => 'publish',
            'posts_per_page'         => 1,
            'fields'                 => 'ids',
            'no_found_rows'          => true,
            'ignore_sticky_posts'    => true,
            'tax_query'              => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
        ));

        if (!empty($fallback->posts[0])) {
            $url = get_the_post_thumbnail_url((int) $fallback->posts[0], $size);
            return $url ? esc_url_raw($url) : '';
        }

        return '';
    }
}

if (!function_exists('loomaama_editorial_product_card')) {
    function loomaama_editorial_product_card($product_id = 0, $extra_class = '') {
        $product = wc_get_product($product_id ?: get_the_ID());
        if (!$product) {
            return;
        }

        $product_id = $product->get_id();
        $permalink  = get_permalink($product_id);
        $title      = $product->get_name();
        $terms      = get_the_terms($product_id, 'product_cat');
        $category   = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : __('Collection', 'loomaama');

        $hover_url = '';
        if (function_exists('get_field')) {
            $hover_url = loomaama_shop_image_url(get_field('hover_image', $product_id), 'woocommerce_thumbnail');
        }

        if (!$hover_url) {
            $gallery_ids = $product->get_gallery_image_ids();
            if (!empty($gallery_ids[0])) {
                $hover_url = wp_get_attachment_image_url($gallery_ids[0], 'woocommerce_thumbnail');
            }
        }

        $regular = (float) $product->get_regular_price();
        $sale    = (float) $product->get_sale_price();
        $discount = ($regular > 0 && $sale > 0 && $sale < $regular)
            ? (int) round((($regular - $sale) / $regular) * 100)
            : 0;
        ?>
        <article class="es-product-card lux-card <?php echo esc_attr($extra_class); ?>">
            <div class="es-product-media lux-img">
                <a href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($title); ?>">
                    <?php
                    echo get_the_post_thumbnail(
                        $product_id,
                        'woocommerce_thumbnail',
                        array(
                            'class'   => 'es-product-image es-product-image-primary',
                            'loading' => 'lazy',
                            'alt'     => esc_attr($title),
                        )
                    );
                    ?>
                    <?php if ($hover_url) : ?>
                        <img class="es-product-image es-product-image-hover hover-overlay-img" src="<?php echo esc_url($hover_url); ?>" alt="" loading="lazy">
                    <?php endif; ?>
                </a>

                <div class="es-product-badges">
                    <?php if (!$product->is_in_stock()) : ?>
                        <span><?php esc_html_e('Sold out', 'loomaama'); ?></span>
                    <?php elseif ($discount) : ?>
                        <span><?php echo esc_html(sprintf(__('Save %d%%', 'loomaama'), $discount)); ?></span>
                    <?php elseif ($product->is_featured()) : ?>
                        <span><?php esc_html_e('Editor’s pick', 'loomaama'); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="es-product-info lux-content">
                <p class="es-product-category"><?php echo esc_html($category); ?></p>
                <div class="es-product-line">
                    <h3><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h3>
                    <div class="es-product-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                </div>
            </div>
        </article>
        <?php
    }
}

/* --------------------------------------------------------------------------
 * Archive state and query
 * -------------------------------------------------------------------------- */
$is_search = isset($_GET['s']) && '' !== trim(wp_unslash($_GET['s']));
$search_term = $is_search ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
$is_main_shop = is_shop() && !$is_search;
$current_term = get_queried_object();
$shop_page_id = wc_get_page_id('shop');
$paged = max(1, (int) get_query_var('paged'), isset($_GET['product-page']) ? absint($_GET['product-page']) : 1);

$selected_categories = isset($_GET['product_cat'])
    ? array_filter(array_map('sanitize_title', explode(',', wp_unslash($_GET['product_cat']))))
    : array();
$selected_sizes = isset($_GET['filter_size'])
    ? array_filter(array_map('sanitize_title', explode(',', wp_unslash($_GET['filter_size']))))
    : array();
$min_price = isset($_GET['min_price']) ? max(0, (float) wc_clean(wp_unslash($_GET['min_price']))) : '';
$max_price = isset($_GET['max_price']) ? max(0, (float) wc_clean(wp_unslash($_GET['max_price']))) : '';
$orderby   = isset($_GET['orderby']) ? sanitize_key(wp_unslash($_GET['orderby'])) : 'date';

$tax_query = array('relation' => 'AND');
if (taxonomy_exists('product_visibility')) {
    $tax_query[] = array(
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => array('exclude-from-catalog', 'exclude-from-search'),
        'operator' => 'NOT IN',
    );
}

if ($current_term instanceof WP_Term && in_array($current_term->taxonomy, array('product_cat', 'product_tag'), true)) {
    $tax_query[] = array(
        'taxonomy'         => $current_term->taxonomy,
        'field'            => 'term_id',
        'terms'            => array($current_term->term_id),
        'include_children' => true,
    );
}

if (!empty($selected_categories) && !($current_term instanceof WP_Term && 'product_cat' === $current_term->taxonomy)) {
    $tax_query[] = array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => $selected_categories,
    );
}

if (!empty($selected_sizes) && taxonomy_exists('pa_size')) {
    $tax_query[] = array(
        'taxonomy' => 'pa_size',
        'field'    => 'slug',
        'terms'    => $selected_sizes,
    );
}

$meta_query = array('relation' => 'AND');
if ('' !== $min_price || '' !== $max_price) {
    $price_min = '' !== $min_price ? $min_price : 0;
    $price_max = '' !== $max_price ? $max_price : 999999999;
    $meta_query[] = array(
        'key'     => '_price',
        'value'   => array($price_min, $price_max),
        'compare' => 'BETWEEN',
        'type'    => 'NUMERIC',
    );
}

$query_args = array(
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'posts_per_page'      => 16,
    'paged'               => $paged,
    'ignore_sticky_posts' => true,
    's'                   => $search_term,
    'tax_query'           => $tax_query,
    'meta_query'          => $meta_query,
);

switch ($orderby) {
    case 'popularity':
        $query_args['meta_key'] = 'total_sales';
        $query_args['orderby']  = 'meta_value_num';
        $query_args['order']    = 'DESC';
        break;
    case 'rating':
        $query_args['meta_key'] = '_wc_average_rating';
        $query_args['orderby']  = 'meta_value_num';
        $query_args['order']    = 'DESC';
        break;
    case 'price':
        $query_args['meta_key'] = '_price';
        $query_args['orderby']  = 'meta_value_num';
        $query_args['order']    = 'ASC';
        break;
    case 'price-desc':
        $query_args['meta_key'] = '_price';
        $query_args['orderby']  = 'meta_value_num';
        $query_args['order']    = 'DESC';
        break;
    default:
        $query_args['orderby'] = 'date';
        $query_args['order']   = 'DESC';
        break;
}

$catalog_query = new WP_Query($query_args);

$shop_categories = get_terms(array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
    'number'     => 8,
    'orderby'    => 'count',
    'order'      => 'DESC',
));
if (is_wp_error($shop_categories)) {
    $shop_categories = array();
}

/* --------------------------------------------------------------------------
 * Hero content
 * -------------------------------------------------------------------------- */
$hero_title = __('The Shop', 'loomaama');
$hero_copy  = __('A considered edit of enduring pieces, tactile materials, and everyday objects made to be kept.', 'loomaama');
$hero_kicker = __('The seasonal edit', 'loomaama');
$hero_image = '';

if ($is_search) {
    $hero_title = __('Search results', 'loomaama');
    $hero_copy = sprintf(
        _n('%1$d piece found for “%2$s”.', '%1$d pieces found for “%2$s”.', $catalog_query->found_posts, 'loomaama'),
        $catalog_query->found_posts,
        $search_term
    );
    $hero_kicker = __('Discover', 'loomaama');
} elseif ($current_term instanceof WP_Term && in_array($current_term->taxonomy, array('product_cat', 'product_tag'), true)) {
    $hero_title = $current_term->name;
    $hero_copy = term_description($current_term->term_id, $current_term->taxonomy);
    $hero_copy = $hero_copy ? wp_strip_all_tags($hero_copy) : __('Explore the complete collection, selected for material, form, and everyday ease.', 'loomaama');
    $hero_kicker = __('Collection', 'loomaama');
    $hero_image = loomaama_shop_term_image($current_term, 'full');
} else {
    if (function_exists('get_field') && $shop_page_id > 0) {
        $hero_title = get_field('harvest_title', $shop_page_id) ?: $hero_title;
        $hero_copy = get_field('harvest_subtitle', $shop_page_id) ?: $hero_copy;
        $hero_kicker = get_field('shop_hero_kicker', $shop_page_id) ?: $hero_kicker;

        foreach (array('shop_hero_image', 'hero_image', 'harvest_image') as $field_name) {
            $hero_image = loomaama_shop_image_url(get_field($field_name, $shop_page_id), 'full');
            if ($hero_image) {
                break;
            }
        }
    }

    if (!$hero_image && $shop_page_id > 0) {
        $hero_image = get_the_post_thumbnail_url($shop_page_id, 'full');
    }
}

if (!$hero_image && !empty($shop_categories[0])) {
    $hero_image = loomaama_shop_term_image($shop_categories[0], 'full');
}
if (!$hero_image && !empty($catalog_query->posts[0])) {
    $hero_image = get_the_post_thumbnail_url($catalog_query->posts[0]->ID, 'full');
}

$filter_categories = get_terms(array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'orderby'    => 'name',
));
if (is_wp_error($filter_categories)) {
    $filter_categories = array();
}

$size_terms = taxonomy_exists('pa_size') ? get_terms(array(
    'taxonomy'   => 'pa_size',
    'hide_empty' => true,
    'orderby'    => 'menu_order',
)) : array();
if (is_wp_error($size_terms)) {
    $size_terms = array();
}
?>

<style id="loomaama-editorial-shop-styles">
    .editorial-shop {
        --es-ink: #171512;
        --es-muted: #716d67;
        --es-paper: #fff;
        --es-soft: #f3f2ef;
        --es-line: rgba(23, 21, 18, .14);
        color: var(--es-ink);
        background: var(--es-paper);
    }
    .editorial-shop *, .editorial-shop *::before, .editorial-shop *::after { box-sizing: border-box; }
    .es-wrap { width: min(100% - 40px, 1500px); margin-inline: auto; }
    .es-hero {
        position: relative;
        min-height: min(78vh, 880px);
        display: grid;
        align-items: end;
        overflow: hidden;
        isolation: isolate;
        background: #27231f;
    }
    .es-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -2;
        background-image: var(--hero-image);
        background-size: cover;
        background-position: center;
        transform: scale(1.01);
    }
    .es-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -1;
        background: linear-gradient(180deg, rgba(0,0,0,.05) 25%, rgba(0,0,0,.62) 100%);
    }
    .es-hero--compact { min-height: min(58vh, 650px); }
    .es-hero-content { max-width: 760px; color: #fff; padding: clamp(120px, 19vw, 255px) 0 clamp(48px, 8vw, 96px); }
    .es-kicker, .es-section-kicker, .es-product-category, .es-story-number {
        margin: 0 0 14px;
        font-size: 10px;
        line-height: 1.2;
        letter-spacing: .19em;
        text-transform: uppercase;
        font-weight: 600;
    }
    .es-hero h1 {
        margin: 0;
        max-width: 12ch;
        font-family: Georgia, 'Times New Roman', serif;
        font-weight: 400;
        font-size: clamp(44px, 7.2vw, 112px);
        line-height: .93;
        letter-spacing: -.045em;
    }
    .es-hero-copy { max-width: 580px; margin: 24px 0 0; font-size: clamp(14px, 1.4vw, 18px); line-height: 1.65; color: rgba(255,255,255,.88); }
    .es-hero-action {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-top: 28px;
        color: #fff;
        border-bottom: 1px solid rgba(255,255,255,.72);
        padding-bottom: 6px;
        font-size: 11px;
        letter-spacing: .16em;
        text-transform: uppercase;
        text-decoration: none;
    }
    .es-intro, .es-catalog, .es-stories { padding: clamp(70px, 9vw, 130px) 0; }
    .es-intro { padding-bottom: clamp(45px, 6vw, 80px); }
    .es-section-head {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 24px;
        margin-bottom: 28px;
    }
    .es-section-head h2, .es-story-copy h2 {
        margin: 0;
        font-family: Georgia, 'Times New Roman', serif;
        font-weight: 400;
        font-size: clamp(30px, 4vw, 58px);
        letter-spacing: -.035em;
        line-height: 1;
    }
    .es-section-head p { max-width: 490px; margin: 0; color: var(--es-muted); font-size: 13px; line-height: 1.65; }
    .es-category-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 3px; }
    .es-category-card {
        position: relative;
        min-height: clamp(300px, 36vw, 560px);
        overflow: hidden;
        background: var(--es-soft);
        color: #fff;
    }
    .es-category-card img { width: 100%; height: 100%; object-fit: cover; transition: transform .8s cubic-bezier(.2,.65,.3,1); }
    .es-category-card::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 50%, rgba(0,0,0,.52)); }
    .es-category-card:hover img { transform: scale(1.035); }
    .es-category-card-content { position: absolute; z-index: 2; left: 20px; right: 20px; bottom: 20px; display: flex; align-items: end; justify-content: space-between; gap: 12px; }
    .es-category-card h3 { margin: 0; font-family: Georgia, 'Times New Roman', serif; font-size: clamp(22px, 2.4vw, 38px); font-weight: 400; }
    .es-category-card span { font-size: 10px; text-transform: uppercase; letter-spacing: .14em; }
    .es-toolbar {
        position: sticky;
        top: 0;
        z-index: 30;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 15px 0;
        border-top: 1px solid var(--es-line);
        border-bottom: 1px solid var(--es-line);
        background: rgba(255,255,255,.94);
        backdrop-filter: blur(12px);
    }
    body.admin-bar .es-toolbar { top: 32px; }
    .es-toolbar-meta { color: var(--es-muted); font-size: 11px; letter-spacing: .1em; text-transform: uppercase; }
    .es-toolbar-actions { display: flex; align-items: center; gap: 10px; }
    .es-filter-button, .es-sort-select {
        min-height: 40px;
        border: 1px solid var(--es-ink);
        background: transparent;
        color: var(--es-ink);
        padding: 0 15px;
        border-radius: 0;
        font-size: 10px;
        letter-spacing: .15em;
        text-transform: uppercase;
    }
    .es-filter-button { display: inline-flex; align-items: center; gap: 10px; cursor: pointer; }
    .es-sort-select { min-width: 170px; appearance: none; background-image: linear-gradient(45deg, transparent 50%, currentColor 50%),linear-gradient(135deg, currentColor 50%, transparent 50%); background-position: calc(100% - 15px) 17px,calc(100% - 11px) 17px; background-size: 4px 4px; background-repeat: no-repeat; }
    .es-catalog { padding-top: clamp(50px, 7vw, 95px); }
    .es-product-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); column-gap: 4px; row-gap: clamp(32px, 5vw, 70px); }
    .es-product-card { min-width: 0; }
    .es-product-media { position: relative; overflow: hidden; aspect-ratio: 4 / 5; background: var(--es-soft); }
    .es-product-media > a { display: block; width: 100%; height: 100%; }
    .es-product-image { display: block; width: 100%; height: 100%; object-fit: cover; transition: opacity .45s ease, transform .8s cubic-bezier(.2,.65,.3,1); }
    .es-product-image-hover { position: absolute; inset: 0; opacity: 0; }
    .es-product-card:hover .es-product-image-primary { opacity: 0; transform: scale(1.02); }
    .es-product-card:hover .es-product-image-hover { opacity: 1; transform: scale(1.02); }
    .es-product-badges { position: absolute; z-index: 3; left: 12px; top: 12px; display: flex; flex-wrap: wrap; gap: 5px; pointer-events: none; }
    .es-product-badges span { background: rgba(255,255,255,.9); color: var(--es-ink); padding: 7px 9px; font-size: 9px; letter-spacing: .12em; text-transform: uppercase; }
    .es-product-info { padding: 12px 4px 0; }
    .es-product-category { margin-bottom: 7px; color: var(--es-muted); font-size: 8px; }
    .es-product-line { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 14px; align-items: start; }
    .es-product-line h3 { margin: 0; min-width: 0; font-family: inherit; font-size: 12px; font-weight: 500; line-height: 1.4; }
    .es-product-line h3 a { display: block; color: inherit; text-decoration: none; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .es-product-price { font-size: 11px; line-height: 1.4; white-space: nowrap; }
    .es-product-price del { color: var(--es-muted); margin-right: 4px; }
    .es-product-price ins { text-decoration: none; }
    .es-empty { grid-column: 1 / -1; padding: 90px 20px; text-align: center; background: var(--es-soft); }
    .es-empty h2 { font-family: Georgia, 'Times New Roman', serif; font-weight: 400; }
    .es-pagination { display: flex; justify-content: center; gap: 7px; margin-top: 70px; }
    .es-pagination .page-numbers { display: grid; place-items: center; min-width: 38px; height: 38px; padding: 0 10px; border: 1px solid var(--es-line); color: var(--es-ink); text-decoration: none; font-size: 11px; }
    .es-pagination .current { color: #fff; background: var(--es-ink); border-color: var(--es-ink); }
    .es-stories { background: #f7f6f3; }
    .es-story + .es-story { margin-top: clamp(80px, 12vw, 170px); }
    .es-story-visual { position: relative; min-height: min(68vw, 820px); overflow: hidden; background: #d8d4ce; }
    .es-story-visual img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
    .es-story-copy { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: end; padding: 30px 0 26px; }
    .es-story-description { max-width: 520px; margin: 0 0 4px auto; color: var(--es-muted); font-size: 13px; line-height: 1.7; }
    .es-story-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 4px; }
    .es-story-link { display: inline-flex; margin-top: 18px; color: inherit; text-decoration: none; border-bottom: 1px solid currentColor; padding-bottom: 4px; font-size: 10px; text-transform: uppercase; letter-spacing: .14em; }
    .es-filter-drawer {
        position: fixed;
        z-index: 1100;
        top: 0;
        right: 0;
        width: min(440px, 94vw);
        height: 100dvh;
        display: flex;
        flex-direction: column;
        background: #fff;
        transform: translateX(102%);
        transition: transform .42s cubic-bezier(.7,0,.2,1);
        box-shadow: -25px 0 60px rgba(0,0,0,.14);
    }
    .es-filter-drawer.is-open { transform: translateX(0); }
    .es-drawer-head { display: flex; align-items: center; justify-content: space-between; min-height: 78px; padding: 0 25px; border-bottom: 1px solid var(--es-line); }
    .es-drawer-head h2 { margin: 0; font-size: 12px; text-transform: uppercase; letter-spacing: .17em; }
    .es-drawer-close { border: 0; background: transparent; font-size: 27px; line-height: 1; cursor: pointer; }
    .es-drawer-body { flex: 1; overflow-y: auto; padding: 0 25px; }
    .es-filter-group { border-bottom: 1px solid var(--es-line); }
    .es-filter-group summary { display: flex; align-items: center; justify-content: space-between; cursor: pointer; list-style: none; padding: 22px 0; font-size: 10px; letter-spacing: .16em; text-transform: uppercase; }
    .es-filter-group summary::-webkit-details-marker { display: none; }
    .es-filter-group summary::after { content: '+'; font-size: 18px; font-weight: 300; }
    .es-filter-group[open] summary::after { content: '−'; }
    .es-filter-options { display: grid; gap: 12px; padding: 0 0 24px; }
    .es-filter-option { display: flex; align-items: center; gap: 10px; font-size: 13px; cursor: pointer; }
    .es-filter-option input { width: 15px; height: 15px; accent-color: var(--es-ink); }
    .es-size-options { display: grid; grid-template-columns: repeat(4, 1fr); gap: 7px; padding-bottom: 24px; }
    .es-size-option { position: relative; }
    .es-size-option input { position: absolute; opacity: 0; }
    .es-size-option span { display: grid; place-items: center; min-height: 39px; border: 1px solid var(--es-line); font-size: 11px; cursor: pointer; }
    .es-size-option input:checked + span { background: var(--es-ink); border-color: var(--es-ink); color: #fff; }
    .es-price-row { display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center; padding-bottom: 24px; }
    .es-price-row input { width: 100%; min-height: 42px; border: 1px solid var(--es-line); border-radius: 0; padding: 0 11px; }
    .es-drawer-actions { padding: 18px 25px; border-top: 1px solid var(--es-line); background: #fff; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .es-drawer-actions button { min-height: 46px; border: 1px solid var(--es-ink); border-radius: 0; font-size: 10px; text-transform: uppercase; letter-spacing: .14em; cursor: pointer; }
    .es-apply { background: var(--es-ink); color: #fff; }
    .es-clear { background: #fff; color: var(--es-ink); }
    .es-overlay { position: fixed; z-index: 1090; inset: 0; border: 0; background: rgba(0,0,0,.42); opacity: 0; visibility: hidden; transition: .3s ease; }
    .es-overlay.is-open { opacity: 1; visibility: visible; }
    body.es-filter-open { overflow: hidden; }
    @media (max-width: 991px) {
        .es-wrap { width: min(100% - 28px, 1500px); }
        .es-category-grid, .es-product-grid, .es-story-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .es-story-copy { grid-template-columns: 1fr; gap: 16px; }
        .es-story-description { margin-left: 0; }
        .es-toolbar { top: 0; }
        body.admin-bar .es-toolbar { top: 46px; }
    }
    @media (max-width: 767px) {
        .es-wrap { width: min(100% - 20px, 1500px); }
        .es-hero { min-height: 72svh; }
        .es-hero--compact { min-height: 58svh; }
        .es-hero-content { padding-bottom: 42px; }
        .es-hero h1 { font-size: clamp(43px, 16vw, 76px); }
        .es-section-head { display: block; }
        .es-section-head p { margin-top: 15px; }
        .es-category-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .es-category-card { min-height: 58vw; }
        .es-category-card-content { left: 13px; right: 13px; bottom: 13px; }
        .es-category-card-content span { display: none; }
        .es-toolbar-meta { display: none; }
        .es-toolbar-actions { width: 100%; }
        .es-filter-button, .es-sort-select { flex: 1; min-width: 0; }
        .es-product-grid, .es-story-grid { column-gap: 3px; row-gap: 36px; }
        .es-product-info { padding-top: 9px; }
        .es-product-line { display: block; }
        .es-product-line h3 { font-size: 11px; }
        .es-product-price { margin-top: 4px; font-size: 10px; }
        .es-story-visual { min-height: 116vw; }
        .es-story-copy { padding-top: 22px; }
        .es-story-grid .es-product-card:nth-child(n+5) { display: none; }
    }
    @media (max-width: 420px) {
        .es-sort-select { width: 54%; }
        .es-filter-button { width: 46%; }
        .es-category-card h3 { font-size: 20px; }
        .es-product-badges { left: 7px; top: 7px; }
        .es-product-badges span { padding: 5px 6px; font-size: 7px; }
    }
</style>

<main class="editorial-shop reveal visible">
    <section class="es-hero <?php echo $is_main_shop ? '' : 'es-hero--compact'; ?>" style="--hero-image: url('<?php echo esc_url($hero_image); ?>');">
        <div class="es-wrap">
            <div class="es-hero-content" data-aos="fade-up">
                <p class="es-kicker"><?php echo esc_html($hero_kicker); ?></p>
                <h1><?php echo esc_html($hero_title); ?></h1>
                <p class="es-hero-copy"><?php echo esc_html($hero_copy); ?></p>
                <a class="es-hero-action" href="#shop-catalog">
                    <?php esc_html_e('Explore the collection', 'loomaama'); ?>
                    <span aria-hidden="true">↓</span>
                </a>
            </div>
        </div>
    </section>

    <?php if ($is_main_shop && !empty($shop_categories)) : ?>
        <section class="es-intro">
            <div class="es-wrap">
                <div class="es-section-head" data-aos="fade-up">
                    <div>
                        <p class="es-section-kicker"><?php esc_html_e('Shop by world', 'loomaama'); ?></p>
                        <h2><?php esc_html_e('The wardrobe, considered.', 'loomaama'); ?></h2>
                    </div>
                    <p><?php esc_html_e('Explore a seasonal edit across clothing, accessories, and objects—presented as a series of visual stories.', 'loomaama'); ?></p>
                </div>

                <div class="es-category-grid">
                    <?php foreach (array_slice($shop_categories, 0, 4) as $category) :
                        $category_image = loomaama_shop_term_image($category, 'large');
                        ?>
                        <a class="es-category-card" href="<?php echo esc_url(get_term_link($category)); ?>" data-aos="fade-up">
                            <?php if ($category_image) : ?>
                                <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($category->name); ?>" loading="lazy">
                            <?php endif; ?>
                            <span class="es-category-card-content">
                                <h3><?php echo esc_html($category->name); ?></h3>
                                <span><?php echo esc_html(sprintf(_n('%d piece', '%d pieces', $category->count, 'loomaama'), $category->count)); ?></span>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <div id="shop-catalog" class="es-wrap">
        <div class="es-toolbar">
            <div class="es-toolbar-meta">
                <?php
                echo esc_html(sprintf(
                    _n('%d piece', '%d pieces', $catalog_query->found_posts, 'loomaama'),
                    $catalog_query->found_posts
                ));
                ?>
            </div>
            <div class="es-toolbar-actions">
                <button type="button" class="es-filter-button" id="esFilterOpen" aria-controls="esFilterDrawer" aria-expanded="false">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 5h16M7 12h10M10 19h4"/></svg>
                    <?php esc_html_e('Filter', 'loomaama'); ?>
                </button>
                <select class="es-sort-select" id="esSortSelect" aria-label="<?php esc_attr_e('Sort products', 'loomaama'); ?>">
                    <option value="date" <?php selected($orderby, 'date'); ?>><?php esc_html_e('Newest', 'loomaama'); ?></option>
                    <option value="popularity" <?php selected($orderby, 'popularity'); ?>><?php esc_html_e('Most popular', 'loomaama'); ?></option>
                    <option value="rating" <?php selected($orderby, 'rating'); ?>><?php esc_html_e('Top rated', 'loomaama'); ?></option>
                    <option value="price" <?php selected($orderby, 'price'); ?>><?php esc_html_e('Price: low to high', 'loomaama'); ?></option>
                    <option value="price-desc" <?php selected($orderby, 'price-desc'); ?>><?php esc_html_e('Price: high to low', 'loomaama'); ?></option>
                </select>
            </div>
        </div>
    </div>

    <section class="es-catalog">
        <div class="es-wrap">
            <div class="es-section-head" data-aos="fade-up">
                <div>
                    <p class="es-section-kicker"><?php echo $is_search ? esc_html__('Your search', 'loomaama') : esc_html__('The complete edit', 'loomaama'); ?></p>
                    <h2><?php echo $is_search ? esc_html__('Pieces to discover', 'loomaama') : esc_html__('Shop all', 'loomaama'); ?></h2>
                </div>
                <p><?php esc_html_e('A clean, gallery-led view designed to let materials, proportion, and detail do the talking.', 'loomaama'); ?></p>
            </div>

            <div class="es-product-grid" id="sale-products">
                <?php if ($catalog_query->have_posts()) : ?>
                    <?php while ($catalog_query->have_posts()) : $catalog_query->the_post(); ?>
                        <?php loomaama_editorial_product_card(get_the_ID()); ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="es-empty">
                        <p class="es-section-kicker"><?php esc_html_e('No results', 'loomaama'); ?></p>
                        <h2><?php esc_html_e('Nothing matched this edit.', 'loomaama'); ?></h2>
                        <p><?php esc_html_e('Clear the filters or try a different search.', 'loomaama'); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($catalog_query->max_num_pages > 1) : ?>
                <nav class="es-pagination" aria-label="<?php esc_attr_e('Product pagination', 'loomaama'); ?>">
                    <?php
                    echo wp_kses_post(paginate_links(array(
                        'total'     => $catalog_query->max_num_pages,
                        'current'   => $paged,
                        'mid_size'  => 1,
                        'prev_text' => '←',
                        'next_text' => '→',
                        'add_args'  => array_filter(array(
                            's'           => $search_term,
                            'product_cat' => !empty($selected_categories) ? implode(',', $selected_categories) : '',
                            'filter_size' => !empty($selected_sizes) ? implode(',', $selected_sizes) : '',
                            'min_price'   => $min_price,
                            'max_price'   => $max_price,
                            'orderby'     => $orderby,
                        )),
                    )));
                    ?>
                </nav>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($is_main_shop && !empty($shop_categories)) : ?>
        <section class="es-stories">
            <div class="es-wrap">
                <?php
                $story_categories = array_slice($shop_categories, 0, 3);
                foreach ($story_categories as $story_index => $story_category) :
                    $story_image = loomaama_shop_term_image($story_category, 'full');
                    $story_description = term_description($story_category->term_id, 'product_cat');
                    $story_description = $story_description
                        ? wp_strip_all_tags($story_description)
                        : __('A focused selection shaped by texture, utility, and an effortless point of view.', 'loomaama');

                    $story_products = new WP_Query(array(
                        'post_type'           => 'product',
                        'post_status'         => 'publish',
                        'posts_per_page'      => 4,
                        'ignore_sticky_posts' => true,
                        'no_found_rows'       => true,
                        'tax_query'           => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'term_id',
                                'terms'    => $story_category->term_id,
                            ),
                        ),
                    ));
                    ?>
                    <article class="es-story">
                        <?php if ($story_image) : ?>
                            <a class="es-story-visual" href="<?php echo esc_url(get_term_link($story_category)); ?>" data-aos="fade-up">
                                <img src="<?php echo esc_url($story_image); ?>" alt="<?php echo esc_attr($story_category->name); ?>" loading="lazy">
                            </a>
                        <?php endif; ?>

                        <div class="es-story-copy" data-aos="fade-up">
                            <div>
                                <p class="es-story-number"><?php echo esc_html(sprintf('%02d / %02d', $story_index + 1, count($story_categories))); ?></p>
                                <h2><?php echo esc_html($story_category->name); ?></h2>
                            </div>
                            <div>
                                <p class="es-story-description"><?php echo esc_html($story_description); ?></p>
                                <a class="es-story-link" href="<?php echo esc_url(get_term_link($story_category)); ?>"><?php esc_html_e('View collection', 'loomaama'); ?></a>
                            </div>
                        </div>

                        <?php if ($story_products->have_posts()) : ?>
                            <div class="es-story-grid">
                                <?php while ($story_products->have_posts()) : $story_products->the_post(); ?>
                                    <?php loomaama_editorial_product_card(get_the_ID()); ?>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </article>
                    <?php wp_reset_postdata(); ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<aside class="es-filter-drawer" id="esFilterDrawer" aria-hidden="true">
    <div class="es-drawer-head">
        <h2><?php esc_html_e('Refine the edit', 'loomaama'); ?></h2>
        <button type="button" class="es-drawer-close" id="esFilterClose" aria-label="<?php esc_attr_e('Close filters', 'loomaama'); ?>">×</button>
    </div>

    <div class="es-drawer-body">
        <?php if (!empty($filter_categories)) : ?>
            <details class="es-filter-group" open>
                <summary><?php esc_html_e('Categories', 'loomaama'); ?></summary>
                <div class="es-filter-options">
                    <?php foreach ($filter_categories as $filter_category) : ?>
                        <label class="es-filter-option">
                            <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($filter_category->slug); ?>" <?php checked(in_array($filter_category->slug, $selected_categories, true)); ?>>
                            <span><?php echo esc_html($filter_category->name); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </details>
        <?php endif; ?>

        <?php if (!empty($size_terms)) : ?>
            <details class="es-filter-group">
                <summary><?php esc_html_e('Size', 'loomaama'); ?></summary>
                <div class="es-size-options">
                    <?php foreach ($size_terms as $size_term) : ?>
                        <label class="es-size-option">
                            <input type="checkbox" name="filter_size[]" value="<?php echo esc_attr($size_term->slug); ?>" <?php checked(in_array($size_term->slug, $selected_sizes, true)); ?>>
                            <span><?php echo esc_html($size_term->name); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </details>
        <?php endif; ?>

        <details class="es-filter-group">
            <summary><?php esc_html_e('Price', 'loomaama'); ?></summary>
            <div class="es-price-row">
                <input type="number" id="esMinPrice" min="0" step="1" placeholder="<?php esc_attr_e('Min', 'loomaama'); ?>" value="<?php echo esc_attr($min_price); ?>">
                <span>—</span>
                <input type="number" id="esMaxPrice" min="0" step="1" placeholder="<?php esc_attr_e('Max', 'loomaama'); ?>" value="<?php echo esc_attr($max_price); ?>">
            </div>
        </details>
    </div>

    <div class="es-drawer-actions">
        <button type="button" class="es-clear" id="esClearFilters"><?php esc_html_e('Clear', 'loomaama'); ?></button>
        <button type="button" class="es-apply" id="esApplyFilters"><?php esc_html_e('Apply filters', 'loomaama'); ?></button>
    </div>
</aside>
<button type="button" class="es-overlay" id="esFilterOverlay" aria-label="<?php esc_attr_e('Close filters', 'loomaama'); ?>"></button>

<script>
(function () {
    'use strict';

    const drawer = document.getElementById('esFilterDrawer');
    const overlay = document.getElementById('esFilterOverlay');
    const openButton = document.getElementById('esFilterOpen');
    const closeButton = document.getElementById('esFilterClose');
    const applyButton = document.getElementById('esApplyFilters');
    const clearButton = document.getElementById('esClearFilters');
    const sortSelect = document.getElementById('esSortSelect');

    function setDrawer(open) {
        if (!drawer || !overlay || !openButton) return;
        drawer.classList.toggle('is-open', open);
        overlay.classList.toggle('is-open', open);
        document.body.classList.toggle('es-filter-open', open);
        drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
        openButton.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open && closeButton) closeButton.focus();
    }

    function selectedValues(selector) {
        return Array.from(document.querySelectorAll(selector + ':checked')).map(function (input) {
            return input.value;
        });
    }

    function updateUrl(options) {
        const url = new URL(window.location.href);
        Object.keys(options).forEach(function (key) {
            const value = options[key];
            if (value === '' || value === null || (Array.isArray(value) && !value.length)) {
                url.searchParams.delete(key);
            } else {
                url.searchParams.set(key, Array.isArray(value) ? value.join(',') : value);
            }
        });
        url.searchParams.delete('product-page');
        window.location.href = url.toString();
    }

    if (openButton) openButton.addEventListener('click', function () { setDrawer(true); });
    if (closeButton) closeButton.addEventListener('click', function () { setDrawer(false); });
    if (overlay) overlay.addEventListener('click', function () { setDrawer(false); });

    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            updateUrl({ orderby: sortSelect.value });
        });
    }

    if (applyButton) {
        applyButton.addEventListener('click', function () {
            updateUrl({
                product_cat: selectedValues('input[name="product_cat[]"]'),
                filter_size: selectedValues('input[name="filter_size[]"]'),
                min_price: document.getElementById('esMinPrice').value.trim(),
                max_price: document.getElementById('esMaxPrice').value.trim()
            });
        });
    }

    if (clearButton) {
        clearButton.addEventListener('click', function () {
            document.querySelectorAll('#esFilterDrawer input[type="checkbox"]').forEach(function (input) {
                input.checked = false;
            });
            document.getElementById('esMinPrice').value = '';
            document.getElementById('esMaxPrice').value = '';
            updateUrl({ product_cat: '', filter_size: '', min_price: '', max_price: '' });
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') setDrawer(false);
    });
})();
</script>

<?php
wp_reset_postdata();
get_footer();
