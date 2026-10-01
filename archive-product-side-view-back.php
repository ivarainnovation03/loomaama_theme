<?php
/**
 * Product archive / shop template.
 *
 * @package Loomaama
 * @version 9.0.0
 */

defined('ABSPATH') || exit;

get_header();

$is_search  = is_search() || (isset($_GET['s']) && '' !== trim((string) $_GET['s']));
$search_term = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
$current_term = get_queried_object();
$shop_page_id = wc_get_page_id('shop');
$paged        = max(1, absint(get_query_var('paged')));

$selected_category = isset($_GET['product_cat']) ? sanitize_title(wp_unslash($_GET['product_cat'])) : '';
$selected_order    = isset($_GET['orderby']) ? sanitize_key(wp_unslash($_GET['orderby'])) : 'menu_order';
$min_price         = isset($_GET['min_price']) && '' !== $_GET['min_price'] ? max(0, (float) wp_unslash($_GET['min_price'])) : null;
$max_price         = isset($_GET['max_price']) && '' !== $_GET['max_price'] ? max(0, (float) wp_unslash($_GET['max_price'])) : null;
$in_stock_only     = isset($_GET['in_stock']) && '1' === sanitize_text_field(wp_unslash($_GET['in_stock']));
$on_sale_only      = isset($_GET['on_sale']) && '1' === sanitize_text_field(wp_unslash($_GET['on_sale']));

$allowed_orders = array('menu_order', 'popularity', 'rating', 'date', 'price', 'price-desc');
if (!in_array($selected_order, $allowed_orders, true)) {
    $selected_order = 'menu_order';
}

$query_args = array(
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'posts_per_page'      => 12,
    'paged'               => $paged,
    'ignore_sticky_posts' => true,
    'tax_query'           => array(
        array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => array('exclude-from-catalog'),
            'operator' => 'NOT IN',
        ),
    ),
    'meta_query'          => array(),
);

if ($is_search && '' !== $search_term) {
    $query_args['s'] = $search_term;
}

if ($current_term instanceof WP_Term && in_array($current_term->taxonomy, array('product_cat', 'product_tag'), true)) {
    $query_args['tax_query'][] = array(
        'taxonomy'         => $current_term->taxonomy,
        'field'            => 'term_id',
        'terms'            => absint($current_term->term_id),
        'include_children' => true,
    );
} elseif ('' !== $selected_category) {
    $query_args['tax_query'][] = array(
        'taxonomy'         => 'product_cat',
        'field'            => 'slug',
        'terms'            => $selected_category,
        'include_children' => true,
    );
}

if ($in_stock_only) {
    $query_args['meta_query'][] = array(
        'key'   => '_stock_status',
        'value' => 'instock',
    );
}

if (null !== $min_price || null !== $max_price) {
    $price_filter = array(
        'key'     => '_price',
        'type'    => 'DECIMAL',
        'compare' => 'BETWEEN',
        'value'   => array(null !== $min_price ? $min_price : 0, null !== $max_price ? $max_price : PHP_INT_MAX),
    );
    $query_args['meta_query'][] = $price_filter;
}

if ($on_sale_only) {
    $sale_ids = wc_get_product_ids_on_sale();
    $query_args['post__in'] = !empty($sale_ids) ? array_map('absint', $sale_ids) : array(0);
}

switch ($selected_order) {
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
    case 'date':
        $query_args['orderby'] = 'date';
        $query_args['order']   = 'DESC';
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
        $query_args['orderby'] = array('menu_order' => 'ASC', 'title' => 'ASC');
        break;
}

$products = new WP_Query($query_args);
$categories = get_terms(
    array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'number'     => 8,
    )
);

$hero_title = __('The Nettle Collection', 'loomaama');
$hero_text  = __('Quiet essentials made from wild Himalayan nettle, shaped slowly by hand and designed to live with you for years.', 'loomaama');

if (function_exists('get_field') && $shop_page_id > 0) {
    $custom_title = get_field('harvest_title', $shop_page_id);
    $custom_text  = get_field('harvest_subtitle', $shop_page_id);
    $hero_title   = $custom_title ? $custom_title : $hero_title;
    $hero_text    = $custom_text ? $custom_text : $hero_text;
}

if ($is_search) {
    $hero_title = __('Search the collection', 'loomaama');
    $hero_text  = sprintf(
        /* translators: 1: result count, 2: search query. */
        _n('%1$d piece found for “%2$s”.', '%1$d pieces found for “%2$s”.', $products->found_posts, 'loomaama'),
        $products->found_posts,
        $search_term
    );
} elseif ($current_term instanceof WP_Term) {
    $hero_title = $current_term->name;
    $hero_text  = $current_term->description ? wp_strip_all_tags($current_term->description) : $hero_text;
}

$shop_url = get_permalink($shop_page_id);
if (!$shop_url) {
    $shop_url = home_url('/shop/');
}
?>

<style>
:root {
    --loom-ink: #24231f;
    --loom-muted: #706e66;
    --loom-line: #dedbd2;
    --loom-paper: #f5f2ea;
    --loom-wash: #ebe6da;
    --loom-white: #fffdf8;
}

.loomaama-shop { color: var(--loom-ink); background: var(--loom-white); }
.loomaama-shop * { box-sizing: border-box; }
.loomaama-shop__hero { padding: clamp(5rem, 10vw, 9rem) 1.5rem clamp(3.5rem, 7vw, 6rem); background: linear-gradient(120deg, var(--loom-paper), #ece5d7); text-align: center; }
.loomaama-shop__eyebrow { margin: 0 0 1.2rem; font-size: .72rem; letter-spacing: .28em; text-transform: uppercase; color: var(--loom-muted); }
.loomaama-shop__title { max-width: 1000px; margin: 0 auto; font-family: inherit; font-size: clamp(2.5rem, 6vw, 5.8rem); font-weight: 400; line-height: .98; letter-spacing: -.04em; }
.loomaama-shop__intro { max-width: 680px; margin: 1.5rem auto 0; color: var(--loom-muted); font-size: clamp(1rem, 1.5vw, 1.15rem); line-height: 1.75; }
.loomaama-shop__wrap { width: min(1500px, calc(100% - 2rem)); margin: 0 auto; padding: 2rem 0 6rem; }
.loomaama-shop__category-nav { display: flex; gap: .65rem; overflow-x: auto; padding: .25rem 0 1.75rem; scrollbar-width: none; }
.loomaama-shop__category-nav::-webkit-scrollbar { display: none; }
.loomaama-shop__category-link { flex: 0 0 auto; padding: .75rem 1.1rem; border: 1px solid var(--loom-line); border-radius: 999px; color: var(--loom-ink); text-decoration: none; font-size: .8rem; letter-spacing: .08em; text-transform: uppercase; transition: .2s ease; }
.loomaama-shop__category-link:hover, .loomaama-shop__category-link.is-active { background: var(--loom-ink); border-color: var(--loom-ink); color: #fff; }
.loomaama-shop__toolbar { display: grid; grid-template-columns: auto 1fr auto; gap: 1rem; align-items: center; padding: 1rem 0 1.4rem; border-top: 1px solid var(--loom-line); }
.loomaama-shop__count { color: var(--loom-muted); font-size: .86rem; }
.loomaama-shop__filter-button, .loomaama-shop__sort { min-height: 44px; border: 1px solid var(--loom-line); background: transparent; color: var(--loom-ink); padding: .7rem 1rem; font: inherit; font-size: .8rem; letter-spacing: .08em; text-transform: uppercase; }
.loomaama-shop__filter-button { display: inline-flex; align-items: center; gap: .6rem; cursor: pointer; }
.loomaama-shop__sort { justify-self: end; }
.loomaama-shop__body { display: grid; grid-template-columns: 270px minmax(0, 1fr); gap: clamp(2rem, 4vw, 4.5rem); }
.loomaama-shop__filters { position: sticky; top: 1rem; align-self: start; padding: 1.4rem; background: var(--loom-paper); }
.loomaama-shop__filter-heading { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.loomaama-shop__filter-heading h2 { margin: 0; font-size: .9rem; letter-spacing: .14em; text-transform: uppercase; }
.loomaama-shop__close { display: none; border: 0; background: none; font-size: 1.6rem; cursor: pointer; }
.loomaama-shop__field { padding: 1rem 0; border-top: 1px solid var(--loom-line); }
.loomaama-shop__field:first-of-type { border-top: 0; }
.loomaama-shop__field label, .loomaama-shop__legend { display: block; margin-bottom: .7rem; font-size: .72rem; letter-spacing: .13em; text-transform: uppercase; }
.loomaama-shop__field select, .loomaama-shop__field input[type="number"] { width: 100%; min-height: 44px; border: 1px solid var(--loom-line); background: var(--loom-white); padding: .7rem; color: var(--loom-ink); }
.loomaama-shop__price-row { display: grid; grid-template-columns: 1fr 1fr; gap: .6rem; }
.loomaama-shop__check { display: flex; align-items: center; gap: .6rem; margin: .7rem 0; font-size: .9rem; }
.loomaama-shop__actions { display: grid; gap: .65rem; margin-top: 1rem; }
.loomaama-shop__apply, .loomaama-shop__reset { display: inline-flex; justify-content: center; align-items: center; min-height: 46px; border: 1px solid var(--loom-ink); padding: .75rem 1rem; text-decoration: none; text-transform: uppercase; letter-spacing: .12em; font-size: .75rem; cursor: pointer; }
.loomaama-shop__apply { background: var(--loom-ink); color: #fff; }
.loomaama-shop__reset { background: transparent; color: var(--loom-ink); }
.loomaama-shop__grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: clamp(1.5rem, 3vw, 3.2rem) clamp(1rem, 2vw, 2rem); }
.loomaama-product { min-width: 0; }
.loomaama-product__media { position: relative; overflow: hidden; aspect-ratio: 4 / 5; background: var(--loom-paper); }
.loomaama-product__media > a { display: block; width: 100%; height: 100%; }
.loomaama-product__media img { width: 100%; height: 100%; object-fit: cover; transition: transform .7s cubic-bezier(.2,.7,.2,1), opacity .35s ease; }
.loomaama-product:hover .loomaama-product__media img:first-child { transform: scale(1.025); }
.loomaama-product__hover-image { position: absolute; inset: 0; opacity: 0; }
.loomaama-product:hover .loomaama-product__hover-image { opacity: 1; }
.loomaama-product__badges { position: absolute; top: .8rem; left: .8rem; display: flex; gap: .4rem; flex-wrap: wrap; pointer-events: none; }
.loomaama-product__badge { padding: .43rem .62rem; background: rgba(255,253,248,.92); font-size: .64rem; letter-spacing: .1em; text-transform: uppercase; }
.loomaama-product__badge--dark { background: var(--loom-ink); color: #fff; }
.loomaama-product__content { padding-top: 1rem; }
.loomaama-product__meta { display: flex; justify-content: space-between; gap: .75rem; align-items: baseline; }
.loomaama-product__category { margin: 0 0 .45rem; color: var(--loom-muted); font-size: .66rem; letter-spacing: .14em; text-transform: uppercase; }
.loomaama-product__title { margin: 0; font-size: 1rem; font-weight: 400; line-height: 1.4; }
.loomaama-product__title a { color: inherit; text-decoration: none; }
.loomaama-product__price { flex: 0 0 auto; font-size: .92rem; white-space: nowrap; }
.loomaama-product__price del { color: var(--loom-muted); margin-right: .3rem; }
.loomaama-product__price ins { text-decoration: none; }
.loomaama-product__note { margin: .5rem 0 0; color: var(--loom-muted); font-size: .78rem; }
.loomaama-shop__empty { grid-column: 1 / -1; padding: 5rem 1.5rem; background: var(--loom-paper); text-align: center; }
.loomaama-shop__empty h2 { margin-top: 0; font-size: 2rem; font-weight: 400; }
.loomaama-shop__pagination { margin-top: 4rem; }
.loomaama-shop__pagination .page-numbers { display: flex; justify-content: center; gap: .45rem; padding: 0; list-style: none; }
.loomaama-shop__pagination a, .loomaama-shop__pagination span { display: inline-flex; align-items: center; justify-content: center; min-width: 42px; height: 42px; border: 1px solid var(--loom-line); color: var(--loom-ink); text-decoration: none; }
.loomaama-shop__pagination .current { background: var(--loom-ink); color: #fff; border-color: var(--loom-ink); }
.loomaama-shop__overlay { display: none; }

@media (max-width: 1100px) {
    .loomaama-shop__body { grid-template-columns: 1fr; }
    .loomaama-shop__filters { position: fixed; z-index: 10002; top: 0; right: 0; width: min(390px, 92vw); height: 100vh; overflow-y: auto; transform: translateX(105%); transition: transform .3s ease; box-shadow: -20px 0 50px rgba(0,0,0,.15); }
    .loomaama-shop__filters.is-open { transform: translateX(0); }
    .loomaama-shop__close { display: inline-block; }
    .loomaama-shop__overlay { position: fixed; z-index: 10001; inset: 0; display: block; background: rgba(20,20,18,.42); opacity: 0; visibility: hidden; transition: .25s ease; }
    .loomaama-shop__overlay.is-open { opacity: 1; visibility: visible; }
    .loomaama-shop__grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
@media (max-width: 760px) {
    .loomaama-shop__hero { text-align: left; }
    .loomaama-shop__title, .loomaama-shop__intro { margin-left: 0; }
    .loomaama-shop__toolbar { grid-template-columns: 1fr auto; }
    .loomaama-shop__count { grid-column: 1 / -1; grid-row: 2; }
    .loomaama-shop__sort { max-width: 165px; }
    .loomaama-shop__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 480px) {
    .loomaama-shop__wrap { width: min(100% - 1.25rem, 1500px); }
    .loomaama-shop__grid { gap: 1.6rem .65rem; }
    .loomaama-product__content { padding-top: .75rem; }
    .loomaama-product__meta { display: block; }
    .loomaama-product__price { display: block; margin-top: .35rem; }
}
</style>

<main class="loomaama-shop" id="main-content">
    <header class="loomaama-shop__hero">
        <p class="loomaama-shop__eyebrow"><?php esc_html_e('Wild Himalayan Nettle · Made Slowly', 'loomaama'); ?></p>
        <h1 class="loomaama-shop__title"><?php echo esc_html($hero_title); ?></h1>
        <p class="loomaama-shop__intro"><?php echo esc_html($hero_text); ?></p>
    </header>

    <div class="loomaama-shop__wrap">
        <?php if (!$is_search && !is_wp_error($categories) && !empty($categories)) : ?>
            <nav class="loomaama-shop__category-nav" aria-label="<?php esc_attr_e('Product categories', 'loomaama'); ?>">
                <a class="loomaama-shop__category-link <?php echo '' === $selected_category && !is_product_category() ? 'is-active' : ''; ?>" href="<?php echo esc_url($shop_url); ?>">
                    <?php esc_html_e('All pieces', 'loomaama'); ?>
                </a>
                <?php foreach ($categories as $category) :
                    $category_link = get_term_link($category);
                    if (is_wp_error($category_link)) {
                        continue;
                    }
                    $is_active = ($current_term instanceof WP_Term && absint($current_term->term_id) === absint($category->term_id)) || $selected_category === $category->slug;
                ?>
                    <a class="loomaama-shop__category-link <?php echo $is_active ? 'is-active' : ''; ?>" href="<?php echo esc_url($category_link); ?>">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <div class="loomaama-shop__toolbar">
            <button class="loomaama-shop__filter-button" type="button" id="loomaama-filter-open" aria-controls="loomaama-shop-filters" aria-expanded="false">
                <span aria-hidden="true">☰</span>
                <?php esc_html_e('Refine', 'loomaama'); ?>
            </button>
            <p class="loomaama-shop__count">
                <?php
                printf(
                    esc_html(_n('%s piece', '%s pieces', $products->found_posts, 'loomaama')),
                    esc_html(number_format_i18n($products->found_posts))
                );
                ?>
            </p>
            <select class="loomaama-shop__sort" id="loomaama-sort" aria-label="<?php esc_attr_e('Sort products', 'loomaama'); ?>">
                <option value="menu_order" <?php selected($selected_order, 'menu_order'); ?>><?php esc_html_e('Curated', 'loomaama'); ?></option>
                <option value="date" <?php selected($selected_order, 'date'); ?>><?php esc_html_e('Newest', 'loomaama'); ?></option>
                <option value="popularity" <?php selected($selected_order, 'popularity'); ?>><?php esc_html_e('Most loved', 'loomaama'); ?></option>
                <option value="rating" <?php selected($selected_order, 'rating'); ?>><?php esc_html_e('Top rated', 'loomaama'); ?></option>
                <option value="price" <?php selected($selected_order, 'price'); ?>><?php esc_html_e('Price: low to high', 'loomaama'); ?></option>
                <option value="price-desc" <?php selected($selected_order, 'price-desc'); ?>><?php esc_html_e('Price: high to low', 'loomaama'); ?></option>
            </select>
        </div>

        <div class="loomaama-shop__body">
            <aside class="loomaama-shop__filters" id="loomaama-shop-filters" aria-label="<?php esc_attr_e('Shop filters', 'loomaama'); ?>">
                <div class="loomaama-shop__filter-heading">
                    <h2><?php esc_html_e('Refine the collection', 'loomaama'); ?></h2>
                    <button class="loomaama-shop__close" id="loomaama-filter-close" type="button" aria-label="<?php esc_attr_e('Close filters', 'loomaama'); ?>">×</button>
                </div>

                <form method="get" action="<?php echo esc_url($shop_url); ?>">
                    <?php if ($is_search && '' !== $search_term) : ?>
                        <input type="hidden" name="s" value="<?php echo esc_attr($search_term); ?>">
                        <input type="hidden" name="post_type" value="product">
                    <?php endif; ?>

                    <?php if (!($current_term instanceof WP_Term) && !is_wp_error($categories) && !empty($categories)) : ?>
                        <div class="loomaama-shop__field">
                            <label for="loomaama-category"><?php esc_html_e('Category', 'loomaama'); ?></label>
                            <select name="product_cat" id="loomaama-category">
                                <option value=""><?php esc_html_e('All categories', 'loomaama'); ?></option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($selected_category, $category->slug); ?>>
                                        <?php echo esc_html($category->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="loomaama-shop__field">
                        <span class="loomaama-shop__legend"><?php esc_html_e('Price range', 'loomaama'); ?></span>
                        <div class="loomaama-shop__price-row">
                            <input type="number" min="0" step="1" name="min_price" value="<?php echo null !== $min_price ? esc_attr($min_price) : ''; ?>" placeholder="<?php esc_attr_e('Min', 'loomaama'); ?>" aria-label="<?php esc_attr_e('Minimum price', 'loomaama'); ?>">
                            <input type="number" min="0" step="1" name="max_price" value="<?php echo null !== $max_price ? esc_attr($max_price) : ''; ?>" placeholder="<?php esc_attr_e('Max', 'loomaama'); ?>" aria-label="<?php esc_attr_e('Maximum price', 'loomaama'); ?>">
                        </div>
                    </div>

                    <div class="loomaama-shop__field">
                        <span class="loomaama-shop__legend"><?php esc_html_e('Availability', 'loomaama'); ?></span>
                        <label class="loomaama-shop__check">
                            <input type="checkbox" name="in_stock" value="1" <?php checked($in_stock_only); ?>>
                            <span><?php esc_html_e('In stock only', 'loomaama'); ?></span>
                        </label>
                        <label class="loomaama-shop__check">
                            <input type="checkbox" name="on_sale" value="1" <?php checked($on_sale_only); ?>>
                            <span><?php esc_html_e('On sale', 'loomaama'); ?></span>
                        </label>
                    </div>

                    <input type="hidden" name="orderby" value="<?php echo esc_attr($selected_order); ?>">
                    <div class="loomaama-shop__actions">
                        <button class="loomaama-shop__apply" type="submit"><?php esc_html_e('Apply filters', 'loomaama'); ?></button>
                        <a class="loomaama-shop__reset" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Clear all', 'loomaama'); ?></a>
                    </div>
                </form>
            </aside>

            <section aria-label="<?php esc_attr_e('Products', 'loomaama'); ?>">
                <div class="loomaama-shop__grid">
                    <?php if ($products->have_posts()) : ?>
                        <?php while ($products->have_posts()) : $products->the_post();
                            $product = wc_get_product(get_the_ID());
                            if (!$product) {
                                continue;
                            }

                            $product_terms = get_the_terms($product->get_id(), 'product_cat');
                            $category_name = (!is_wp_error($product_terms) && !empty($product_terms)) ? $product_terms[0]->name : __('Collection', 'loomaama');
                            $product_link  = get_permalink($product->get_id());
                            $image_id      = $product->get_image_id();
                            $hover_image   = '';

                            if (function_exists('get_field')) {
                                $hover_field = get_field('hover_image', $product->get_id());
                                if (is_array($hover_field) && !empty($hover_field['sizes']['large'])) {
                                    $hover_image = $hover_field['sizes']['large'];
                                } elseif (is_numeric($hover_field)) {
                                    $hover_image = wp_get_attachment_image_url(absint($hover_field), 'large');
                                }
                            }

                            $discount = 0;
                            if ($product->is_on_sale() && $product->get_regular_price() && $product->get_sale_price()) {
                                $regular = (float) $product->get_regular_price();
                                $sale    = (float) $product->get_sale_price();
                                if ($regular > 0 && $sale < $regular) {
                                    $discount = (int) round((($regular - $sale) / $regular) * 100);
                                }
                            }
                        ?>
                            <article class="loomaama-product">
                                <div class="loomaama-product__media">
                                    <a href="<?php echo esc_url($product_link); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                                        <?php
                                        if ($image_id) {
                                            echo wp_get_attachment_image($image_id, 'loomaama-product-large', false, array('loading' => 'lazy'));
                                        } else {
                                            echo wc_placeholder_img('loomaama-product-large');
                                        }
                                        if ($hover_image) {
                                            printf(
                                                '<img class="loomaama-product__hover-image" src="%1$s" alt="" loading="lazy">',
                                                esc_url($hover_image)
                                            );
                                        }
                                        ?>
                                    </a>
                                    <div class="loomaama-product__badges">
                                        <?php if (!$product->is_in_stock()) : ?>
                                            <span class="loomaama-product__badge loomaama-product__badge--dark"><?php esc_html_e('Sold out', 'loomaama'); ?></span>
                                        <?php elseif ($discount > 0) : ?>
                                            <span class="loomaama-product__badge"><?php echo esc_html(sprintf(__('Save %d%%', 'loomaama'), $discount)); ?></span>
                                        <?php endif; ?>
                                        <?php if ($product->is_featured()) : ?>
                                            <span class="loomaama-product__badge"><?php esc_html_e('Signature', 'loomaama'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="loomaama-product__content">
                                    <p class="loomaama-product__category"><?php echo esc_html($category_name); ?></p>
                                    <div class="loomaama-product__meta">
                                        <h2 class="loomaama-product__title"><a href="<?php echo esc_url($product_link); ?>"><?php echo esc_html(get_the_title()); ?></a></h2>
                                        <div class="loomaama-product__price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                                    </div>
                                    <?php if ($product->is_in_stock()) : ?>
                                        <p class="loomaama-product__note"><?php esc_html_e('Hand-finished in Nepal', 'loomaama'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <div class="loomaama-shop__empty">
                            <h2><?php esc_html_e('No pieces found', 'loomaama'); ?></h2>
                            <p><?php esc_html_e('Try widening your filters or return to the full collection.', 'loomaama'); ?></p>
                            <a class="loomaama-shop__apply" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('View all pieces', 'loomaama'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($products->max_num_pages > 1) : ?>
                    <nav class="loomaama-shop__pagination" aria-label="<?php esc_attr_e('Product pagination', 'loomaama'); ?>">
                        <?php
                        echo wp_kses_post(
                            paginate_links(
                                array(
                                    'total'     => $products->max_num_pages,
                                    'current'   => $paged,
                                    'type'      => 'list',
                                    'prev_text' => __('←', 'loomaama'),
                                    'next_text' => __('→', 'loomaama'),
                                    'add_args'  => array_filter(
                                        array(
                                            's'           => $search_term,
                                            'product_cat' => $selected_category,
                                            'orderby'     => $selected_order,
                                            'min_price'   => $min_price,
                                            'max_price'   => $max_price,
                                            'in_stock'    => $in_stock_only ? '1' : '',
                                            'on_sale'     => $on_sale_only ? '1' : '',
                                        ),
                                        static function ($value) {
                                            return null !== $value && '' !== $value;
                                        }
                                    ),
                                )
                            )
                        );
                        ?>
                    </nav>
                <?php endif; ?>
            </section>
        </div>
    </div>
    <div class="loomaama-shop__overlay" id="loomaama-filter-overlay" aria-hidden="true"></div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const panel = document.getElementById('loomaama-shop-filters');
    const openButton = document.getElementById('loomaama-filter-open');
    const closeButton = document.getElementById('loomaama-filter-close');
    const overlay = document.getElementById('loomaama-filter-overlay');
    const sort = document.getElementById('loomaama-sort');

    function setFilterState(open) {
        if (!panel || !overlay || !openButton) return;
        panel.classList.toggle('is-open', open);
        overlay.classList.toggle('is-open', open);
        openButton.setAttribute('aria-expanded', open ? 'true' : 'false');
        document.body.style.overflow = open ? 'hidden' : '';
    }

    if (openButton) openButton.addEventListener('click', function () { setFilterState(true); });
    if (closeButton) closeButton.addEventListener('click', function () { setFilterState(false); });
    if (overlay) overlay.addEventListener('click', function () { setFilterState(false); });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') setFilterState(false);
    });

    if (sort) {
        sort.addEventListener('change', function () {
            const url = new URL(window.location.href);
            url.searchParams.set('orderby', this.value);
            url.searchParams.delete('paged');
            window.location.href = url.toString();
        });
    }
});
</script>

<?php
wp_reset_postdata();
get_footer();
