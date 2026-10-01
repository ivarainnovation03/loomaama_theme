<?php
/**
 * template name: Front Page
 * Luxury Editorial Home Template
 *
 * Inspired by the pacing and image-led presentation of contemporary luxury
 * boutiques while remaining fully driven by Loomaama/WooCommerce content.
 *
 * @package Loomaama
 */

get_header();

$shop_url = function_exists('wc_get_page_id') && wc_get_page_id('shop') > 0
    ? get_permalink(wc_get_page_id('shop'))
    : home_url('/shop/');

$resolve_image_url = static function ($value, $size = 'full') {
    if (empty($value)) {
        return '';
    }

    if (is_array($value)) {
        if (!empty($value['ID'])) {
            $url = wp_get_attachment_image_url((int) $value['ID'], $size);
            return $url ?: '';
        }

        if (!empty($value['id'])) {
            $url = wp_get_attachment_image_url((int) $value['id'], $size);
            return $url ?: '';
        }

        return !empty($value['url']) ? (string) $value['url'] : '';
    }

    if (is_numeric($value)) {
        $url = wp_get_attachment_image_url((int) $value, $size);
        return $url ?: '';
    }

    return esc_url_raw((string) $value);
};

$get_term_image = static function ($term, $size = 'loomaama-hero') {
    if (!$term || is_wp_error($term)) {
        return '';
    }

    $thumbnail_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true);
    if (!$thumbnail_id) {
        return '';
    }

    $url = wp_get_attachment_image_url($thumbnail_id, $size);
    return $url ?: '';
};

$latest_products = array();
$featured_products = array();

if (function_exists('wc_get_products')) {
    $latest_products = wc_get_products(array(
        'status'  => 'publish',
        'limit'   => 10,
        'orderby' => 'date',
        'order'   => 'DESC',
        'return'  => 'objects',
    ));

    $featured_products = wc_get_products(array(
        'status'   => 'publish',
        'featured' => true,
        'limit'    => 10,
        'orderby'  => 'date',
        'order'    => 'DESC',
        'return'   => 'objects',
    ));
}

$display_products = !empty($featured_products) ? $featured_products : $latest_products;

$product_categories = get_terms(array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'number'     => 6,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'exclude'    => array((int) get_option('default_product_cat')),
));

if (is_wp_error($product_categories)) {
    $product_categories = array();
}

$hero_image = $resolve_image_url(get_theme_mod('hero_image'), 'loomaama-hero');
if (!$hero_image) {
    $hero_image = $resolve_image_url(get_theme_mod('split_image'), 'loomaama-hero');
}
if (!$hero_image && !empty($display_products)) {
    $hero_image = wp_get_attachment_image_url($display_products[0]->get_image_id(), 'loomaama-hero');
}
if (!$hero_image) {
    $hero_image = LOOMAAMA_URI . '/assets/images/MODEL WARE.png';
}

$hero_eyebrow = get_theme_mod('hero_subtitle', __('The New Collection', 'loomaama'));
$hero_title   = get_theme_mod('hero_title', __('Born in the Himalayas. Shaped by hand.', 'loomaama'));
$hero_copy    = get_theme_mod('hero_description', __('Wild nettle, patient hands and enduring forms—an expression of quiet luxury rooted in place.', 'loomaama'));

$feature_panels = array();
foreach (array_slice($product_categories, 0, 2) as $index => $category) {
    $image = $get_term_image($category, 'loomaama-hero');

    if (!$image && isset($latest_products[$index])) {
        $image = wp_get_attachment_image_url($latest_products[$index]->get_image_id(), 'loomaama-hero');
    }

    $feature_panels[] = array(
        'title' => $category->name,
        'label' => $index === 0 ? __('New Arrivals', 'loomaama') : __('The Collection', 'loomaama'),
        'url'   => get_term_link($category),
        'image' => $image,
    );
}

$panel_fallbacks = array(
    array(
        'title' => __('For Her', 'loomaama'),
        'label' => __('New Arrivals', 'loomaama'),
        'url'   => $shop_url,
        'image' => LOOMAAMA_URI . '/assets/images/model-outer-image.jpg',
    ),
    array(
        'title' => __('For Him', 'loomaama'),
        'label' => __('The Collection', 'loomaama'),
        'url'   => $shop_url,
        'image' => LOOMAAMA_URI . '/assets/images/man-style.jpg',
    ),
);

for ($panel_index = count($feature_panels); $panel_index < 2; $panel_index++) {
    $feature_panels[] = $panel_fallbacks[$panel_index];
}

foreach ($feature_panels as $index => $panel) {
    if (empty($feature_panels[$index]['image'])) {
        $feature_panels[$index]['image'] = $panel_fallbacks[$index]['image'];
    }

    if (is_wp_error($feature_panels[$index]['url'])) {
        $feature_panels[$index]['url'] = $shop_url;
    }
}

$editorial_image = '';
if (isset($product_categories[2])) {
    $editorial_image = $get_term_image($product_categories[2], 'loomaama-hero');
}
if (!$editorial_image) {
    $editorial_image = $resolve_image_url(get_theme_mod('split_image'), 'loomaama-hero');
}
if (!$editorial_image) {
    $editorial_image = LOOMAAMA_URI . '/assets/images/sustainability.jpg';
}

$journal_posts = get_posts(array(
    'post_type'      => post_type_exists('artisan') ? 'artisan' : 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 2,
    'orderby'        => 'date',
    'order'          => 'DESC',
));
?>

<main id="main" class="loomaama-luxury-home">
    <section class="lux-home-hero" aria-labelledby="lux-home-hero-title">
        <div class="lux-home-hero__media" aria-hidden="true">
            <img src="<?php echo esc_url($hero_image); ?>" alt="" fetchpriority="high">
        </div>
        <div class="lux-home-hero__veil"></div>

        <div class="lux-home-hero__content">
            <p class="lux-kicker"><?php echo esc_html($hero_eyebrow); ?></p>
            <h1 id="lux-home-hero-title"><?php echo esc_html($hero_title); ?></h1>
            <p class="lux-home-hero__copy"><?php echo esc_html($hero_copy); ?></p>
            <div class="lux-home-hero__actions">
                <a class="lux-link lux-link--light" href="<?php echo esc_url($shop_url); ?>">
                    <?php esc_html_e('Explore the Collection', 'loomaama'); ?>
                </a>
                <?php if (isset($product_categories[0])) : ?>
                    <a class="lux-link lux-link--light" href="<?php echo esc_url(get_term_link($product_categories[0])); ?>">
                        <?php esc_html_e('Discover New Arrivals', 'loomaama'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <a class="lux-home-hero__scroll" href="#lux-home-intro" aria-label="<?php esc_attr_e('Scroll to discover', 'loomaama'); ?>">
            <span><?php esc_html_e('Scroll to discover', 'loomaama'); ?></span>
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 4v15M6.5 13.5 12 19l5.5-5.5" fill="none" stroke="currentColor" stroke-width="1.25"/>
            </svg>
        </a>
    </section>

    <section id="lux-home-intro" class="lux-intro lux-reveal">
        <div class="lux-shell lux-shell--narrow">
            <p class="lux-kicker lux-kicker--dark"><?php esc_html_e('Himalayan Nettle · Handcrafted in Nepal', 'loomaama'); ?></p>
            <h2><?php esc_html_e('A wardrobe of natural refinement', 'loomaama'); ?></h2>
            <p><?php esc_html_e('Each Loomaama piece begins with wild Himalayan fibre and moves through many skilled hands. The result is clothing with texture, calm and a lasting relationship to the landscape that made it.', 'loomaama'); ?></p>
        </div>
    </section>

    <section class="lux-split-showcase" aria-label="<?php esc_attr_e('Featured collections', 'loomaama'); ?>">
        <?php foreach ($feature_panels as $panel) : ?>
            <article class="lux-split-card lux-reveal">
                <a href="<?php echo esc_url($panel['url']); ?>" class="lux-split-card__link" aria-label="<?php echo esc_attr(sprintf(__('Explore %s', 'loomaama'), $panel['title'])); ?>">
                    <div class="lux-split-card__media">
                        <img src="<?php echo esc_url($panel['image']); ?>" alt="<?php echo esc_attr($panel['title']); ?>" loading="lazy">
                    </div>
                    <div class="lux-split-card__shade"></div>
                    <div class="lux-split-card__content">
                        <p class="lux-kicker"><?php echo esc_html($panel['label']); ?></p>
                        <h2><?php echo esc_html($panel['title']); ?></h2>
                        <span class="lux-link lux-link--light"><?php esc_html_e('Discover', 'loomaama'); ?></span>
                    </div>
                </a>
            </article>
        <?php endforeach; ?>
    </section>

    <?php if (!empty($display_products)) : ?>
        <section class="lux-products lux-reveal" aria-labelledby="lux-seasonal-title">
            <div class="lux-shell">
                <header class="lux-section-head">
                    <div>
                        <p class="lux-kicker lux-kicker--dark"><?php esc_html_e('Seasonal Creations', 'loomaama'); ?></p>
                        <h2 id="lux-seasonal-title"><?php esc_html_e('New forms in natural fibre', 'loomaama'); ?></h2>
                    </div>
                    <div class="lux-slider-controls" aria-label="<?php esc_attr_e('Product carousel controls', 'loomaama'); ?>">
                        <button type="button" class="lux-slider-button lux-product-prev" aria-label="<?php esc_attr_e('Previous products', 'loomaama'); ?>">
                            <svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><path d="m14.5 5-7 7 7 7" fill="none" stroke="currentColor" stroke-width="1.25"/></svg>
                        </button>
                        <button type="button" class="lux-slider-button lux-product-next" aria-label="<?php esc_attr_e('Next products', 'loomaama'); ?>">
                            <svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><path d="m9.5 5 7 7-7 7" fill="none" stroke="currentColor" stroke-width="1.25"/></svg>
                        </button>
                    </div>
                </header>

                <div class="swiper lux-product-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($display_products as $product) :
                            if (!$product instanceof WC_Product) {
                                continue;
                            }

                            $product_id   = $product->get_id();
                            $product_url  = $product->get_permalink();
                            $product_name = $product->get_name();
                            $image_id     = $product->get_image_id();
                            $image_url    = $image_id ? wp_get_attachment_image_url($image_id, 'loomaama-product-large') : wc_placeholder_img_src('loomaama-product-large');
                            $terms        = wp_get_post_terms($product_id, 'product_cat');
                            $category     = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : __('Collection', 'loomaama');
                        ?>
                            <div class="swiper-slide">
                                <article class="lux-product-card">
                                    <a class="lux-product-card__media" href="<?php echo esc_url($product_url); ?>">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product_name); ?>" loading="lazy">
                                        <?php if ($product->is_on_sale()) : ?>
                                            <span class="lux-product-card__badge"><?php esc_html_e('Sale', 'loomaama'); ?></span>
                                        <?php endif; ?>
                                    </a>
                                    <div class="lux-product-card__meta">
                                        <p><?php echo esc_html($category); ?></p>
                                        <div class="lux-product-card__row">
                                            <h3><a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_name); ?></a></h3>
                                            <div class="lux-product-card__price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="lux-product-progress" aria-hidden="true"><span></span></div>
                </div>

                <div class="lux-products__footer">
                    <a class="lux-link lux-link--dark" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('View all creations', 'loomaama'); ?></a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="lux-editorial lux-reveal" aria-labelledby="lux-editorial-title">
        <div class="lux-editorial__media" aria-hidden="true">
            <img src="<?php echo esc_url($editorial_image); ?>" alt="" loading="lazy">
        </div>
        <div class="lux-editorial__shade"></div>
        <div class="lux-editorial__content">
            <p class="lux-kicker"><?php esc_html_e('The Art of Making', 'loomaama'); ?></p>
            <h2 id="lux-editorial-title"><?php esc_html_e('Every fibre carries the memory of a hand', 'loomaama'); ?></h2>
            <p><?php esc_html_e('Our process honours slowness: gathering, spinning, weaving and finishing with care so the material can remain honest and alive.', 'loomaama'); ?></p>
            <?php
            $making_page = get_page_by_path('the-making');
            $making_url  = $making_page ? get_permalink($making_page) : $shop_url;
            ?>
            <a class="lux-link lux-link--light" href="<?php echo esc_url($making_url); ?>"><?php esc_html_e('Discover our craft', 'loomaama'); ?></a>
        </div>
    </section>

    <?php if (!empty($product_categories)) : ?>
        <section class="lux-categories lux-reveal" aria-labelledby="lux-category-title">
            <div class="lux-shell">
                <header class="lux-section-head lux-section-head--center">
                    <div>
                        <p class="lux-kicker lux-kicker--dark"><?php esc_html_e('Explore Loomaama', 'loomaama'); ?></p>
                        <h2 id="lux-category-title"><?php esc_html_e('The collections', 'loomaama'); ?></h2>
                    </div>
                </header>

                <div class="lux-category-grid">
                    <?php foreach (array_slice($product_categories, 0, 3) as $index => $category) :
                        $category_image = $get_term_image($category, 'loomaama-product-large');
                        if (!$category_image && isset($latest_products[$index])) {
                            $category_image = wp_get_attachment_image_url($latest_products[$index]->get_image_id(), 'loomaama-product-large');
                        }
                        if (!$category_image) {
                            $fallback_images = array(
                                LOOMAAMA_URI . '/assets/images/model-outer-image.jpg',
                                LOOMAAMA_URI . '/assets/images/man-style.jpg',
                                LOOMAAMA_URI . '/assets/images/authenticate-sale.jpg',
                            );
                            $category_image = $fallback_images[$index] ?? $fallback_images[0];
                        }
                    ?>
                        <article class="lux-category-card">
                            <a href="<?php echo esc_url(get_term_link($category)); ?>">
                                <div class="lux-category-card__media">
                                    <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($category->name); ?>" loading="lazy">
                                </div>
                                <div class="lux-category-card__body">
                                    <h3><?php echo esc_html($category->name); ?></h3>
                                    <span class="lux-link lux-link--dark"><?php esc_html_e('Explore', 'loomaama'); ?></span>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($journal_posts)) : ?>
        <section class="lux-journal lux-reveal" aria-labelledby="lux-journal-title">
            <div class="lux-shell">
                <header class="lux-section-head">
                    <div>
                        <p class="lux-kicker lux-kicker--dark"><?php esc_html_e('Stories from Loomaama', 'loomaama'); ?></p>
                        <h2 id="lux-journal-title"><?php esc_html_e('People, place and material', 'loomaama'); ?></h2>
                    </div>
                </header>

                <div class="lux-journal-grid">
                    <?php foreach ($journal_posts as $journal_index => $journal_post) :
                        $journal_image = get_the_post_thumbnail_url($journal_post->ID, 'loomaama-hero');
                        if (!$journal_image) {
                            $journal_image = $journal_index === 0
                                ? LOOMAAMA_URI . '/assets/images/authenticate-sale.jpg'
                                : LOOMAAMA_URI . '/assets/images/sustainability.jpg';
                        }
                    ?>
                        <article class="lux-story-card<?php echo $journal_index === 0 ? ' lux-story-card--large' : ''; ?>">
                            <a href="<?php echo esc_url(get_permalink($journal_post)); ?>">
                                <div class="lux-story-card__media">
                                    <img src="<?php echo esc_url($journal_image); ?>" alt="<?php echo esc_attr(get_the_title($journal_post)); ?>" loading="lazy">
                                </div>
                                <div class="lux-story-card__body">
                                    <p class="lux-kicker lux-kicker--dark"><?php echo esc_html(get_the_date('', $journal_post)); ?></p>
                                    <h3><?php echo esc_html(get_the_title($journal_post)); ?></h3>
                                    <span class="lux-link lux-link--dark"><?php esc_html_e('Read the story', 'loomaama'); ?></span>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="lux-discovery lux-reveal" aria-labelledby="lux-discovery-title">
        <div class="lux-shell lux-shell--narrow">
            <p class="lux-kicker lux-kicker--dark"><?php esc_html_e('A personal journey', 'loomaama'); ?></p>
            <h2 id="lux-discovery-title"><?php esc_html_e('Explore the collection', 'loomaama'); ?></h2>
            <p><?php esc_html_e('Find pieces by name, material or category.', 'loomaama'); ?></p>

            <form class="lux-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <label class="screen-reader-text" for="lux-home-search"><?php esc_html_e('Search products', 'loomaama'); ?></label>
                <input id="lux-home-search" type="search" name="s" placeholder="<?php esc_attr_e('What are you looking for?', 'loomaama'); ?>" autocomplete="off">
                <input type="hidden" name="post_type" value="product">
                <button type="submit" aria-label="<?php esc_attr_e('Submit product search', 'loomaama'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.7" cy="10.7" r="6.7" fill="none" stroke="currentColor" stroke-width="1.25"/>
                        <path d="m15.7 15.7 4.3 4.3" fill="none" stroke="currentColor" stroke-width="1.25"/>
                    </svg>
                </button>
            </form>
        </div>
    </section>

    <section class="lux-newsletter lux-reveal" aria-labelledby="lux-newsletter-title">
        <div class="lux-shell lux-shell--narrow">
            <p class="lux-kicker lux-kicker--dark"><?php esc_html_e('Loomaama Letters', 'loomaama'); ?></p>
            <h2 id="lux-newsletter-title"><?php esc_html_e('Be inspired by new work and old knowledge', 'loomaama'); ?></h2>
            <p><?php esc_html_e('Receive material stories, artisan notes and first access to new collections.', 'loomaama'); ?></p>

            <?php if (function_exists('mc4wp_show_form')) : ?>
                <div class="lux-newsletter__plugin-form"><?php mc4wp_show_form(); ?></div>
            <?php else : ?>
                <form id="luxHomeNewsletter" class="lux-newsletter-form" method="post" novalidate>
                    <label class="screen-reader-text" for="luxHomeNewsletterEmail"><?php esc_html_e('Email address', 'loomaama'); ?></label>
                    <input id="luxHomeNewsletterEmail" type="email" name="email" placeholder="<?php esc_attr_e('Enter your email address', 'loomaama'); ?>" required>
                    <button type="submit"><?php esc_html_e('Subscribe', 'loomaama'); ?></button>
                </form>
                <div id="luxHomeNewsletterMessage" class="lux-newsletter-message" role="status" aria-live="polite"></div>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
:root {
    --lux-ink: #24221f;
    --lux-muted: #6d6963;
    --lux-warm: #f4f1eb;
    --lux-sand: #e8e0d4;
    --lux-line: rgba(36, 34, 31, 0.2);
    --lux-white: #fff;
    --lux-display: Georgia, 'Times New Roman', serif;
    --lux-sans: Inter, Arial, sans-serif;
}

html {
    scroll-behavior: smooth;
}

body.home,
body.blog {
    background: var(--lux-white);
}

.loomaama-luxury-home {
    overflow: hidden;
    color: var(--lux-ink);
    background: var(--lux-white);
    font-family: var(--lux-sans);
}

.loomaama-luxury-home *,
.loomaama-luxury-home *::before,
.loomaama-luxury-home *::after {
    box-sizing: border-box;
}

.lux-shell {
    width: min(100% - 64px, 1500px);
    margin-inline: auto;
}

.lux-shell--narrow {
    width: min(100% - 48px, 860px);
}

.lux-kicker {
    margin: 0 0 18px;
    color: rgba(255,255,255,.92);
    font-family: var(--lux-sans);
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .18em;
    line-height: 1.4;
    text-transform: uppercase;
}

.lux-kicker--dark {
    color: var(--lux-muted);
}

.lux-link {
    position: relative;
    display: inline-flex;
    align-items: center;
    padding: 0 0 7px;
    color: inherit;
    font-family: var(--lux-sans);
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .14em;
    line-height: 1.4;
    text-decoration: none;
    text-transform: uppercase;
}

.lux-link::after {
    position: absolute;
    right: 0;
    bottom: 0;
    left: 0;
    height: 1px;
    background: currentColor;
    content: '';
    transform: scaleX(1);
    transform-origin: left;
    transition: transform .4s cubic-bezier(.22,1,.36,1);
}

.lux-link:hover::after,
.lux-link:focus-visible::after {
    transform: scaleX(.35);
}

.lux-link--light {
    color: var(--lux-white);
}

.lux-link--dark {
    color: var(--lux-ink);
}

/* Transparent campaign header on the home template. */
body.home .main-header,
body.blog .main-header {
    padding-top: 23px !important;
    padding-bottom: 23px !important;
    border-bottom: 1px solid rgba(255,255,255,.25);
    background: transparent;
    box-shadow: none;
    transition: background-color .4s ease, border-color .4s ease, padding .4s ease, color .4s ease;
}

body.home .main-header:not(.luxury-header-scrolled),
body.blog .main-header:not(.luxury-header-scrolled) {
    color: #fff;
}

body.home .main-header:not(.luxury-header-scrolled) .nav-top > .menu-item > a,
body.blog .main-header:not(.luxury-header-scrolled) .nav-top > .menu-item > a,
body.home .main-header:not(.luxury-header-scrolled) .nav-link-top,
body.blog .main-header:not(.luxury-header-scrolled) .nav-link-top,
body.home .main-header:not(.luxury-header-scrolled) .menu-btn,
body.blog .main-header:not(.luxury-header-scrolled) .menu-btn {
    color: #fff !important;
}

body.home .main-header:not(.luxury-header-scrolled) .hamburger-lines span,
body.blog .main-header:not(.luxury-header-scrolled) .hamburger-lines span {
    background-color: #fff !important;
}

body.home .main-header:not(.luxury-header-scrolled) .brand-logo img,
body.blog .main-header:not(.luxury-header-scrolled) .brand-logo img {
    filter: brightness(0) invert(1);
}

body.home .main-header.luxury-header-scrolled,
body.blog .main-header.luxury-header-scrolled {
    padding-top: 15px !important;
    padding-bottom: 15px !important;
    border-color: rgba(36,34,31,.12);
    background: rgba(255,255,255,.96);
    color: var(--lux-ink);
    backdrop-filter: blur(16px);
}

/* Hero */
.lux-home-hero {
    position: relative;
    min-height: 100svh;
    isolation: isolate;
    color: var(--lux-white);
    background: #312f2b;
}

.lux-home-hero__media,
.lux-home-hero__veil {
    position: absolute;
    inset: 0;
}

.lux-home-hero__media {
    z-index: -2;
    overflow: hidden;
}

.lux-home-hero__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transform: scale(1.025);
    animation: luxHeroIn 1.8s cubic-bezier(.22,1,.36,1) forwards;
}

.lux-home-hero__veil {
    z-index: -1;
    background:
        linear-gradient(180deg, rgba(20,19,17,.34) 0%, rgba(20,19,17,.04) 42%, rgba(20,19,17,.53) 100%),
        linear-gradient(90deg, rgba(20,19,17,.22), transparent 62%);
}

.lux-home-hero__content {
    position: absolute;
    bottom: clamp(105px, 14vh, 165px);
    left: clamp(24px, 5vw, 86px);
    width: min(820px, calc(100% - 48px));
}

.lux-home-hero__content h1 {
    max-width: 820px;
    margin: 0;
    color: var(--lux-white);
    font-family: var(--lux-display);
    font-size: clamp(50px, 6.7vw, 110px);
    font-weight: 400;
    letter-spacing: -.035em;
    line-height: .95;
}

.lux-home-hero__copy {
    max-width: 590px;
    margin: 28px 0 0;
    color: rgba(255,255,255,.9);
    font-size: clamp(15px, 1.15vw, 18px);
    line-height: 1.7;
}

.lux-home-hero__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 36px;
    margin-top: 34px;
}

.lux-home-hero__scroll {
    position: absolute;
    right: clamp(24px, 4vw, 66px);
    bottom: 35px;
    display: flex;
    align-items: center;
    gap: 11px;
    color: rgba(255,255,255,.82);
    font-size: 9px;
    letter-spacing: .16em;
    text-decoration: none;
    text-transform: uppercase;
}

.lux-home-hero__scroll svg {
    animation: luxScroll 1.8s ease-in-out infinite;
}

/* Intro */
.lux-intro {
    padding: clamp(110px, 13vw, 200px) 0;
    text-align: center;
}

.lux-intro h2,
.lux-section-head h2,
.lux-discovery h2,
.lux-newsletter h2 {
    margin: 0;
    color: var(--lux-ink);
    font-family: var(--lux-display);
    font-size: clamp(42px, 5vw, 76px);
    font-weight: 400;
    letter-spacing: -.025em;
    line-height: 1.05;
}

.lux-intro p:last-child {
    max-width: 720px;
    margin: 34px auto 0;
    color: var(--lux-muted);
    font-size: 16px;
    line-height: 1.9;
}

/* Split campaign cards */
.lux-split-showcase {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.lux-split-card,
.lux-split-card__link {
    position: relative;
    min-height: min(82vw, 900px);
}

.lux-split-card__link {
    display: block;
    overflow: hidden;
    color: var(--lux-white);
    text-decoration: none;
}

.lux-split-card__media,
.lux-split-card__shade {
    position: absolute;
    inset: 0;
}

.lux-split-card__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 1.2s cubic-bezier(.22,1,.36,1);
}

.lux-split-card__shade {
    background: linear-gradient(180deg, transparent 48%, rgba(18,17,15,.62) 100%);
}

.lux-split-card__content {
    position: absolute;
    right: clamp(24px, 4vw, 64px);
    bottom: clamp(38px, 5vw, 74px);
    left: clamp(24px, 4vw, 64px);
    text-align: center;
}

.lux-split-card__content h2 {
    margin: 0 0 24px;
    color: var(--lux-white);
    font-family: var(--lux-display);
    font-size: clamp(38px, 4.2vw, 70px);
    font-weight: 400;
    line-height: 1.05;
}

.lux-split-card__link:hover .lux-split-card__media img,
.lux-split-card__link:focus-visible .lux-split-card__media img {
    transform: scale(1.035);
}

/* Products */
.lux-products,
.lux-categories,
.lux-journal {
    padding: clamp(100px, 10vw, 165px) 0;
}

.lux-products {
    background: #fff;
}

.lux-section-head {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 30px;
    margin-bottom: clamp(50px, 6vw, 88px);
}

.lux-section-head--center {
    justify-content: center;
    text-align: center;
}

.lux-slider-controls {
    display: flex;
    gap: 10px;
}

.lux-slider-button {
    display: inline-grid;
    width: 48px;
    height: 48px;
    padding: 0;
    border: 1px solid var(--lux-line);
    border-radius: 50%;
    color: var(--lux-ink);
    background: transparent;
    place-items: center;
    transition: color .25s ease, background .25s ease, border-color .25s ease;
}

.lux-slider-button:hover,
.lux-slider-button:focus-visible {
    border-color: var(--lux-ink);
    color: #fff;
    background: var(--lux-ink);
}

.lux-product-slider {
    overflow: visible;
}

.lux-product-card__media {
    position: relative;
    display: block;
    overflow: hidden;
    aspect-ratio: 4 / 5.4;
    background: #f2f0ec;
}

.lux-product-card__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .8s cubic-bezier(.22,1,.36,1);
}

.lux-product-card__media:hover img,
.lux-product-card__media:focus-visible img {
    transform: scale(1.025);
}

.lux-product-card__badge {
    position: absolute;
    top: 18px;
    left: 18px;
    padding: 7px 10px;
    color: var(--lux-ink);
    background: rgba(255,255,255,.9);
    font-size: 9px;
    letter-spacing: .13em;
    text-transform: uppercase;
}

.lux-product-card__meta {
    padding-top: 20px;
}

.lux-product-card__meta > p {
    margin: 0 0 9px;
    color: var(--lux-muted);
    font-size: 9px;
    letter-spacing: .14em;
    text-transform: uppercase;
}

.lux-product-card__row {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: start;
    gap: 20px;
}

.lux-product-card h3 {
    min-width: 0;
    margin: 0;
    font-family: var(--lux-sans);
    font-size: 13px;
    font-weight: 400;
    line-height: 1.5;
}

.lux-product-card h3 a {
    color: var(--lux-ink);
    text-decoration: none;
}

.lux-product-card__price,
.lux-product-card__price .amount {
    color: var(--lux-ink);
    font-size: 12px;
    white-space: nowrap;
}

.lux-product-card__price del {
    margin-right: 6px;
    color: var(--lux-muted);
}

.lux-product-card__price ins {
    text-decoration: none;
}

.lux-product-progress {
    position: relative;
    height: 1px;
    margin-top: 56px;
    overflow: hidden;
    background: rgba(36,34,31,.16);
}

.lux-product-progress span {
    display: block;
    width: 18%;
    height: 100%;
    background: var(--lux-ink);
    transform-origin: left center;
    transition: width .4s cubic-bezier(.22,1,.36,1), transform .4s cubic-bezier(.22,1,.36,1);
}

.lux-products__footer {
    margin-top: 45px;
    text-align: center;
}

/* Editorial full-bleed story */
.lux-editorial {
    position: relative;
    min-height: min(85vw, 960px);
    display: grid;
    align-items: end;
    isolation: isolate;
    color: var(--lux-white);
    background: #302d28;
}

.lux-editorial__media,
.lux-editorial__shade {
    position: absolute;
    inset: 0;
}

.lux-editorial__media {
    z-index: -2;
}

.lux-editorial__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lux-editorial__shade {
    z-index: -1;
    background: linear-gradient(180deg, rgba(17,16,14,.05) 30%, rgba(17,16,14,.68) 100%);
}

.lux-editorial__content {
    width: min(100% - 48px, 760px);
    margin: 0 0 clamp(58px, 8vw, 120px) clamp(24px, 7vw, 112px);
}

.lux-editorial__content h2 {
    margin: 0;
    color: var(--lux-white);
    font-family: var(--lux-display);
    font-size: clamp(46px, 6vw, 92px);
    font-weight: 400;
    letter-spacing: -.03em;
    line-height: 1;
}

.lux-editorial__content > p:not(.lux-kicker) {
    max-width: 570px;
    margin: 28px 0 32px;
    color: rgba(255,255,255,.88);
    font-size: 15px;
    line-height: 1.8;
}

/* Category cards */
.lux-categories {
    background: var(--lux-warm);
}

.lux-category-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}

.lux-category-card a {
    display: block;
    color: var(--lux-ink);
    text-decoration: none;
}

.lux-category-card__media {
    overflow: hidden;
    aspect-ratio: 3 / 4.2;
    background: #ddd8d0;
}

.lux-category-card__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .9s cubic-bezier(.22,1,.36,1);
}

.lux-category-card a:hover img,
.lux-category-card a:focus-visible img {
    transform: scale(1.03);
}

.lux-category-card__body {
    padding: 25px 4px 0;
    text-align: center;
}

.lux-category-card h3 {
    margin: 0 0 15px;
    font-family: var(--lux-display);
    font-size: clamp(26px, 2.4vw, 38px);
    font-weight: 400;
}

/* Journal */
.lux-journal-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.45fr) minmax(0, .75fr);
    align-items: start;
    gap: clamp(24px, 4vw, 68px);
}

.lux-story-card a {
    display: block;
    color: var(--lux-ink);
    text-decoration: none;
}

.lux-story-card__media {
    overflow: hidden;
    aspect-ratio: 4 / 5;
    background: #efede9;
}

.lux-story-card--large .lux-story-card__media {
    aspect-ratio: 5 / 4;
}

.lux-story-card__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .9s cubic-bezier(.22,1,.36,1);
}

.lux-story-card a:hover img,
.lux-story-card a:focus-visible img {
    transform: scale(1.025);
}

.lux-story-card__body {
    padding-top: 25px;
}

.lux-story-card h3 {
    max-width: 680px;
    margin: 0 0 22px;
    font-family: var(--lux-display);
    font-size: clamp(30px, 3.2vw, 52px);
    font-weight: 400;
    line-height: 1.12;
}

.lux-story-card:not(.lux-story-card--large) h3 {
    font-size: clamp(27px, 2.5vw, 40px);
}

/* Search and newsletter */
.lux-discovery,
.lux-newsletter {
    padding: clamp(105px, 12vw, 180px) 0;
    text-align: center;
}

.lux-discovery {
    border-top: 1px solid var(--lux-line);
    background: #fff;
}

.lux-discovery > .lux-shell > p:not(.lux-kicker),
.lux-newsletter > .lux-shell > p:not(.lux-kicker) {
    margin: 24px 0 0;
    color: var(--lux-muted);
    font-size: 15px;
    line-height: 1.7;
}

.lux-search {
    position: relative;
    margin-top: 48px;
    border-bottom: 1px solid var(--lux-ink);
}

.lux-search input {
    width: 100%;
    padding: 18px 52px 18px 0;
    border: 0;
    border-radius: 0;
    outline: 0;
    color: var(--lux-ink);
    background: transparent;
    font-family: var(--lux-display);
    font-size: clamp(22px, 3vw, 38px);
}

.lux-search input::placeholder {
    color: #9b9791;
    opacity: 1;
}

.lux-search button {
    position: absolute;
    top: 50%;
    right: 0;
    display: grid;
    width: 42px;
    height: 42px;
    padding: 0;
    border: 0;
    color: var(--lux-ink);
    background: transparent;
    transform: translateY(-50%);
    place-items: center;
}

.lux-newsletter {
    background: var(--lux-warm);
}

.lux-newsletter-form {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    max-width: 650px;
    margin: 48px auto 0;
    border-bottom: 1px solid var(--lux-ink);
}

.lux-newsletter-form input {
    min-width: 0;
    padding: 17px 0;
    border: 0;
    outline: 0;
    color: var(--lux-ink);
    background: transparent;
    font-size: 14px;
}

.lux-newsletter-form input::placeholder {
    color: #827d76;
    opacity: 1;
}

.lux-newsletter-form button {
    padding: 17px 0 17px 28px;
    border: 0;
    color: var(--lux-ink);
    background: transparent;
    font-size: 10px;
    font-weight: 500;
    letter-spacing: .14em;
    text-transform: uppercase;
}

.lux-newsletter-message {
    min-height: 24px;
    margin-top: 20px;
    color: var(--lux-muted);
    font-size: 13px;
}

.lux-newsletter-message.is-error {
    color: #8d3028;
}

.lux-newsletter__plugin-form {
    max-width: 650px;
    margin: 48px auto 0;
}

/* Reveal motion */
.lux-reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity .9s cubic-bezier(.22,1,.36,1), transform .9s cubic-bezier(.22,1,.36,1);
}

.lux-reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}

@keyframes luxHeroIn {
    from { opacity: .78; transform: scale(1.09); }
    to { opacity: 1; transform: scale(1.025); }
}

@keyframes luxScroll {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(6px); }
}

@media (max-width: 1199px) {
    body.home .main-header,
    body.blog .main-header {
        padding-top: 17px !important;
        padding-bottom: 17px !important;
    }

    .lux-shell {
        width: min(100% - 40px, 1500px);
    }
}

@media (max-width: 900px) {
    .lux-split-showcase {
        grid-template-columns: 1fr;
    }

    .lux-split-card,
    .lux-split-card__link {
        min-height: 116vw;
        max-height: 820px;
    }

    .lux-category-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .lux-category-card:last-child {
        grid-column: 1 / -1;
        width: calc(50% - 9px);
        margin-inline: auto;
    }

    .lux-journal-grid {
        grid-template-columns: 1fr;
    }

    .lux-story-card--large .lux-story-card__media,
    .lux-story-card__media {
        aspect-ratio: 4 / 3;
    }
}

@media (max-width: 700px) {
    .lux-shell,
    .lux-shell--narrow {
        width: calc(100% - 32px);
    }

    .lux-home-hero__content {
        bottom: 105px;
        left: 20px;
        width: calc(100% - 40px);
    }

    .lux-home-hero__content h1 {
        font-size: clamp(46px, 14vw, 68px);
    }

    .lux-home-hero__copy {
        max-width: 95%;
        margin-top: 22px;
        font-size: 14px;
        line-height: 1.65;
    }

    .lux-home-hero__actions {
        gap: 25px;
        margin-top: 27px;
    }

    .lux-home-hero__scroll {
        display: none;
    }

    .lux-intro {
        padding: 92px 0;
    }

    .lux-intro h2,
    .lux-section-head h2,
    .lux-discovery h2,
    .lux-newsletter h2 {
        font-size: clamp(38px, 12vw, 54px);
    }

    .lux-intro p:last-child {
        margin-top: 25px;
        font-size: 14px;
        line-height: 1.8;
    }

    .lux-split-card,
    .lux-split-card__link {
        min-height: 145vw;
    }

    .lux-products,
    .lux-categories,
    .lux-journal {
        padding: 88px 0;
    }

    .lux-section-head {
        align-items: flex-start;
        margin-bottom: 45px;
    }

    .lux-slider-controls {
        display: none;
    }

    .lux-product-card__row {
        grid-template-columns: 1fr;
        gap: 7px;
    }

    .lux-editorial {
        min-height: 150vw;
    }

    .lux-editorial__content {
        width: calc(100% - 40px);
        margin: 0 20px 54px;
    }

    .lux-editorial__content h2 {
        font-size: clamp(42px, 13vw, 62px);
    }

    .lux-category-grid {
        grid-template-columns: 1fr;
        gap: 58px;
    }

    .lux-category-card:last-child {
        grid-column: auto;
        width: 100%;
    }

    .lux-category-card__media {
        aspect-ratio: 4 / 5;
    }

    .lux-story-card--large .lux-story-card__media,
    .lux-story-card__media {
        aspect-ratio: 4 / 4.5;
    }

    .lux-discovery,
    .lux-newsletter {
        padding: 90px 0;
    }

    .lux-newsletter-form {
        grid-template-columns: 1fr;
        border-bottom: 0;
    }

    .lux-newsletter-form input {
        border-bottom: 1px solid var(--lux-ink);
        text-align: center;
    }

    .lux-newsletter-form button {
        justify-self: center;
        padding: 21px 0 0;
    }
}

@media (prefers-reduced-motion: reduce) {
    html {
        scroll-behavior: auto;
    }

    .lux-home-hero__media img,
    .lux-home-hero__scroll svg,
    .lux-reveal,
    .lux-split-card__media img,
    .lux-product-card__media img,
    .lux-category-card__media img,
    .lux-story-card__media img {
        animation: none !important;
        transition: none !important;
        transform: none !important;
    }

    .lux-reveal {
        opacity: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const header = document.querySelector('.main-header');
    const progress = document.querySelector('.lux-product-progress span');

    const updateHeader = function () {
        if (!header) return;
        header.classList.toggle('luxury-header-scrolled', window.scrollY > 40);
    };

    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });

    const revealElements = document.querySelectorAll('.lux-reveal');
    if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        const revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -5% 0px' });

        revealElements.forEach(function (element) {
            revealObserver.observe(element);
        });
    } else {
        revealElements.forEach(function (element) {
            element.classList.add('is-visible');
        });
    }

    if (typeof Swiper !== 'undefined' && document.querySelector('.lux-product-slider')) {
        const productSlider = new Swiper('.lux-product-slider', {
            slidesPerView: 1.2,
            spaceBetween: 14,
            speed: 700,
            grabCursor: true,
            watchOverflow: true,
            navigation: {
                nextEl: '.lux-product-next',
                prevEl: '.lux-product-prev'
            },
            breakpoints: {
                560: { slidesPerView: 2.15, spaceBetween: 18 },
                900: { slidesPerView: 3.15, spaceBetween: 22 },
                1280: { slidesPerView: 4.05, spaceBetween: 26 }
            },
            on: {
                init: function () {
                    updateProgress(this);
                },
                slideChange: function () {
                    updateProgress(this);
                },
                resize: function () {
                    updateProgress(this);
                }
            }
        });

        function updateProgress(swiper) {
            if (!progress) return;
            const total = Math.max(1, swiper.slides.length - swiper.slidesPerView + 1);
            const visibleFraction = Math.min(1, swiper.slidesPerView / swiper.slides.length);
            const trackFraction = Math.max(0.12, visibleFraction);
            const maxTravel = 1 - trackFraction;
            const current = total > 1 ? Math.min(1, swiper.activeIndex / (total - 1)) : 0;
            progress.style.width = (trackFraction * 100) + '%';
            progress.style.transform = 'translateX(' + ((maxTravel / trackFraction) * current * 100) + '%)';
        }
    }

    const newsletterForm = document.getElementById('luxHomeNewsletter');
    const newsletterEmail = document.getElementById('luxHomeNewsletterEmail');
    const newsletterMessage = document.getElementById('luxHomeNewsletterMessage');

    if (newsletterForm && newsletterEmail && newsletterMessage) {
        newsletterForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            const email = newsletterEmail.value.trim();
            newsletterMessage.classList.remove('is-error');

            if (!/^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/.test(email)) {
                newsletterMessage.textContent = '<?php echo esc_js(__('Please enter a valid email address.', 'loomaama')); ?>';
                newsletterMessage.classList.add('is-error');
                newsletterEmail.focus();
                return;
            }

            const submitButton = newsletterForm.querySelector('button[type="submit"]');
            const originalLabel = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = '<?php echo esc_js(__('Joining…', 'loomaama')); ?>';

            const body = new URLSearchParams();
            body.append('action', 'loomaama_subscribe');
            body.append('email', email);
            body.append('nonce', '<?php echo esc_js(wp_create_nonce('loomaama_subscribe_nonce')); ?>');

            try {
                const response = await fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: body.toString()
                });
                const result = await response.json();

                if (result.success) {
                    newsletterMessage.textContent = (result.data && result.data.message)
                        ? result.data.message
                        : '<?php echo esc_js(__('Thank you for subscribing.', 'loomaama')); ?>';
                    newsletterEmail.value = '';
                } else {
                    newsletterMessage.textContent = (result.data && result.data.message)
                        ? result.data.message
                        : '<?php echo esc_js(__('Something went wrong. Please try again.', 'loomaama')); ?>';
                    newsletterMessage.classList.add('is-error');
                }
            } catch (error) {
                newsletterMessage.textContent = '<?php echo esc_js(__('Network error. Please try again.', 'loomaama')); ?>';
                newsletterMessage.classList.add('is-error');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = originalLabel;
            }
        });
    }
});
</script>

<?php get_footer(); ?>
