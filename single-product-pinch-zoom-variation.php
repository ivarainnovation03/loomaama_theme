<?php
/**
 * Custom single-product template.
 *
 * Features:
 * - Native click-to-zoom with click-again reset and drag-to-pan.
 * - Image-based colour/pattern choices for linked products and variations.
 * - Swiper navigation when more than four visual choices are available.
 * - Scoped, working navigation for the related-products carousel.
 *
 * Copy this file to the root of the active Loomaama theme.
 *
 * @package Loomaama
 * @version 9.0.0
 */

defined('ABSPATH') || exit;

/*
 * The old gallery script/lightbox is intentionally removed on this template.
 * Use a late enqueue callback because the supplied functions.php registers
 * these assets from its own wp_enqueue_scripts callback.
 */
$loomaama_remove_legacy_gallery_assets = static function () {
    wp_dequeue_style('photoswipe');
    wp_dequeue_style('photoswipe-css');
    wp_dequeue_script('photoswipe');
    wp_dequeue_script('photoswipe-lightbox');
    wp_dequeue_script('loomaama-product-gallery');
};
add_action('wp_enqueue_scripts', $loomaama_remove_legacy_gallery_assets, 999);

if (did_action('wp_enqueue_scripts')) {
    $loomaama_remove_legacy_gallery_assets();
}

get_header();

if (!function_exists('wc_get_product')) {
    get_footer();
    return;
}

while (have_posts()) :
    the_post();

    global $product;

    if (!$product || !is_a($product, 'WC_Product')) {
        $product = wc_get_product(get_the_ID());
    }

    if (!$product) {
        continue;
    }

    $product_id = $product->get_id();

    /**
     * Convert an attachment ID, ACF image array, or direct URL into one
     * consistent gallery item.
     */
    $loomaama_make_image = static function ($image) use ($product_id) {
        $attachment_id = 0;
        $direct_url    = '';
        $direct_alt    = '';

        if (is_numeric($image)) {
            $attachment_id = absint($image);
        } elseif (is_array($image)) {
            $attachment_id = absint($image['ID'] ?? $image['id'] ?? $image['attachment_id'] ?? 0);
            $direct_url    = isset($image['url']) ? (string) $image['url'] : '';
            $direct_alt    = isset($image['alt']) ? (string) $image['alt'] : '';
        } elseif (is_string($image)) {
            $direct_url = $image;
        }

        if ($attachment_id) {
            $full_url    = wp_get_attachment_image_url($attachment_id, 'full');
            $display_url = wp_get_attachment_image_url($attachment_id, 'loomaama-product-large');
            $thumb_url   = wp_get_attachment_image_url($attachment_id, 'woocommerce_thumbnail');
            $alt         = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

            if (!$full_url) {
                return null;
            }

            return array(
                'id'     => $attachment_id,
                'url'    => $display_url ?: $full_url,
                'full'   => $full_url,
                'thumb'  => $thumb_url ?: ($display_url ?: $full_url),
                'alt'    => $alt ?: get_the_title($product_id),
                'srcset' => wp_get_attachment_image_srcset($attachment_id, 'loomaama-product-large') ?: '',
                'sizes'  => wp_get_attachment_image_sizes($attachment_id, 'loomaama-product-large') ?: '(max-width: 991px) 100vw, 55vw',
            );
        }

        if ($direct_url) {
            return array(
                'id'     => 0,
                'url'    => esc_url_raw($direct_url),
                'full'   => esc_url_raw($direct_url),
                'thumb'  => esc_url_raw($direct_url),
                'alt'    => $direct_alt ?: get_the_title($product_id),
                'srcset' => '',
                'sizes'  => '',
            );
        }

        return null;
    };

    /** Build and de-duplicate a gallery. */
    $loomaama_build_gallery = static function ($items, $fallback = array()) use ($loomaama_make_image) {
        $gallery = array();
        $seen    = array();

        foreach ((array) $items as $item) {
            $image = $loomaama_make_image($item);
            if (!$image || empty($image['url'])) {
                continue;
            }

            $key = $image['id'] ? 'id:' . $image['id'] : 'url:' . $image['url'];
            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $gallery[]  = $image;
        }

        if (!$gallery && $fallback) {
            return $fallback;
        }

        return $gallery;
    };

    /** Determine whether a WooCommerce attribute is visual. */
    $loomaama_is_visual_attribute = static function ($attribute_name) {
        $normalized = strtolower((string) $attribute_name);
        $normalized = str_replace(array('attribute_', 'pa_', '-', ' '), array('', '', '_', '_'), $normalized);

        return false !== strpos($normalized, 'color')
            || false !== strpos($normalized, 'colour')
            || false !== strpos($normalized, 'pattern');
    };

    /** Get a readable value for aria-label/title only; it is not displayed. */
    $loomaama_attribute_value_label = static function ($attribute_key, $value) {
        $taxonomy = str_replace('attribute_', '', (string) $attribute_key);
        if (taxonomy_exists($taxonomy)) {
            $term = get_term_by('slug', $value, $taxonomy);
            if ($term && !is_wp_error($term)) {
                return $term->name;
            }
        }

        return wc_attribute_label($taxonomy) . ': ' . str_replace('-', ' ', (string) $value);
    };

    $featured_id     = $product->get_image_id();
    $base_gallery_ids = array_filter(array_merge(array($featured_id), $product->get_gallery_image_ids()));
    $base_gallery     = $loomaama_build_gallery($base_gallery_ids);

    if (!$base_gallery) {
        $placeholder = wc_placeholder_img_src('woocommerce_single');
        $base_gallery = array(
            array(
                'id'     => 0,
                'url'    => $placeholder,
                'full'   => $placeholder,
                'thumb'  => $placeholder,
                'alt'    => get_the_title($product_id),
                'srcset' => '',
                'sizes'  => '',
            ),
        );
    }

    $base_content = array(
        'short_description' => wp_kses_post($product->get_short_description()),
        'long_description'  => wp_kses_post($product->get_description()),
        'product_details'   => function_exists('get_field') ? wp_kses_post((string) get_field('product_details', $product_id)) : '',
        'artisan'           => function_exists('get_field') ? wp_kses_post((string) get_field('artisan_story', $product_id)) : '',
        'care'              => function_exists('get_field') ? wp_kses_post((string) get_field('care_description', $product_id)) : '',
        'sensory'           => function_exists('get_field') ? wp_kses_post((string) get_field('sensory_description', $product_id)) : '',
    );

    if (!$base_content['product_details']) {
        $base_content['product_details'] = $base_content['long_description'];
    }

    /* Build image-based controls and gallery/content payloads for WC variations. */
    $visual_choices        = array();
    $variation_gallery_map = array();
    $variation_content_map = array();

    if ($product->is_type('variable')) {
        foreach ($product->get_available_variations() as $available_variation) {
            $variation_id = absint($available_variation['variation_id'] ?? 0);
            if (!$variation_id) {
                continue;
            }

            $variation_product = wc_get_product($variation_id);
            if (!$variation_product) {
                continue;
            }

            $visual_attributes = array();
            $choice_labels     = array();

            foreach ((array) ($available_variation['attributes'] ?? array()) as $attribute_key => $attribute_value) {
                if (!$attribute_value || !$loomaama_is_visual_attribute($attribute_key)) {
                    continue;
                }

                $visual_attributes[$attribute_key] = $attribute_value;
                $choice_labels[] = $loomaama_attribute_value_label($attribute_key, $attribute_value);
            }

            $variation_gallery_items = array();
            if ($variation_product->get_image_id()) {
                $variation_gallery_items[] = $variation_product->get_image_id();
            }

            $custom_variation_gallery = array();
            if (function_exists('get_field')) {
                $custom_variation_gallery = get_field('variation_gallery', $variation_id);
            } else {
                $custom_variation_gallery = get_post_meta($variation_id, 'variation_gallery', true);
            }

            if (is_string($custom_variation_gallery) && preg_match('/^\s*\d+(\s*,\s*\d+)*\s*$/', $custom_variation_gallery)) {
                $custom_variation_gallery = array_map('absint', explode(',', $custom_variation_gallery));
            }

            if ($custom_variation_gallery) {
                $variation_gallery_items = array_merge($variation_gallery_items, (array) $custom_variation_gallery);
            }

            $variation_gallery = $loomaama_build_gallery($variation_gallery_items, $base_gallery);
            $variation_gallery_map[$variation_id] = $variation_gallery;

            $variation_content = $base_content;
            if (function_exists('get_field')) {
                $field_map = array(
                    'short_description' => 'variation_short_description',
                    'long_description'  => 'variation_long_description',
                    'product_details'   => 'variation_product_details',
                    'artisan'           => 'variation_artisan_story',
                    'care'              => 'variation_care_description',
                    'sensory'           => 'variation_sensory_description',
                );

                foreach ($field_map as $content_key => $field_name) {
                    $field_value = get_field($field_name, $variation_id);
                    if ($field_value && trim(wp_strip_all_tags((string) $field_value))) {
                        $variation_content[$content_key] = wp_kses_post((string) $field_value);
                    }
                }
            }
            $variation_content_map[$variation_id] = $variation_content;

            if (!$visual_attributes) {
                continue;
            }

            ksort($visual_attributes);
            $choice_key = wp_json_encode($visual_attributes);

            if (!isset($visual_choices[$choice_key])) {
                $visual_choices[$choice_key] = array(
                    'variation_id' => $variation_id,
                    'attributes'   => $visual_attributes,
                    'label'        => implode(', ', $choice_labels),
                    'image'        => $variation_gallery[0],
                    'gallery'      => $variation_gallery,
                );
            }
        }
    }

    /*
     * Build image-only links for separate products in the same design group.
     * This replaces the old visible colour dots and pattern names.
     */
    $linked_product_choices = array();
    $design_group = get_post_meta($product_id, 'design_group_id', true);

    if ($design_group) {
        $linked_product_ids = get_posts(
            array(
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
                'meta_key'       => 'design_group_id',
                'meta_value'     => $design_group,
                'fields'         => 'ids',
                'no_found_rows'  => true,
            )
        );

        foreach ($linked_product_ids as $linked_product_id) {
            $linked_product = wc_get_product($linked_product_id);
            if (!$linked_product || !$linked_product->is_visible()) {
                continue;
            }

            $linked_image = $loomaama_make_image($linked_product->get_image_id());
            if (!$linked_image) {
                continue;
            }

            $silent_labels = array();
            foreach (array('pa_color', 'pa_pattern') as $taxonomy) {
                $terms = wp_get_post_terms($linked_product_id, $taxonomy, array('fields' => 'names'));
                if (!is_wp_error($terms) && $terms) {
                    $silent_labels = array_merge($silent_labels, $terms);
                }
            }

            $linked_product_choices[] = array(
                'product_id' => $linked_product_id,
                'url'        => get_permalink($linked_product_id),
                'label'      => $silent_labels ? implode(', ', $silent_labels) : $linked_product->get_name(),
                'image'      => $linked_image,
                'current'    => $linked_product_id === $product_id,
            );
        }
    }

    $first_image = $base_gallery[0];
    $categories  = wc_get_product_category_list($product_id, ', ');
    $content_availability = array(
        'short_description' => (bool) trim(wp_strip_all_tags($base_content['short_description'])),
        'product_details'   => (bool) trim(wp_strip_all_tags($base_content['product_details'])),
        'artisan'           => (bool) trim(wp_strip_all_tags($base_content['artisan'])),
        'care'              => (bool) trim(wp_strip_all_tags($base_content['care'])),
        'sensory'           => (bool) trim(wp_strip_all_tags($base_content['sensory'])),
    );

    foreach ($variation_content_map as $variation_content_item) {
        foreach (array_keys($content_availability) as $content_key) {
            if (!$content_availability[$content_key] && !empty($variation_content_item[$content_key])) {
                $content_availability[$content_key] = (bool) trim(wp_strip_all_tags($variation_content_item[$content_key]));
            }
        }
    }

    do_action('woocommerce_before_single_product');

    if (post_password_required()) {
        echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        do_action('woocommerce_after_single_product');
        continue;
    }
    ?>

    <style id="loomaama-single-product-styles">
        .loomaama-single-product {
            --loom-accent: #9e8574;
            --loom-ink: #27231f;
            --loom-muted: #746f69;
            --loom-line: #ded8d1;
            --loom-surface: #f4eee4;
            color: var(--loom-ink);
            font-family: Inter, sans-serif;
            margin-top: 118px;
        }
        .loomaama-single-product h1,
        .loomaama-single-product h2,
        .loomaama-single-product h3,
        .loomaama-single-product .headline {
            font-family: "Work Sans", sans-serif;
        }
        .loomaama-product-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.12fr) minmax(340px, .88fr);
            gap: clamp(36px, 6vw, 96px);
            align-items: start;
        }
        .loomaama-product-gallery {
            min-width: 0;
        }
        .loomaama-zoom-stage {
            position: relative;
            aspect-ratio: 4 / 5;
            overflow: hidden;
            background: var(--loom-surface);
            cursor: zoom-in;
            touch-action: pan-y;
            user-select: none;
            -webkit-user-select: none;
        }
        .loomaama-zoom-stage.is-zoomed {
            cursor: grab;
            touch-action: none;
        }
        .loomaama-zoom-stage.is-dragging {
            cursor: grabbing;
        }
        .loomaama-main-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transform: translate3d(0, 0, 0) scale(1);
            transform-origin: center center;
            transition: transform 220ms ease;
            pointer-events: none;
            will-change: transform;
        }
        .loomaama-zoom-stage.is-dragging .loomaama-main-image {
            transition: none;
        }
        .loomaama-zoom-hint {
            position: absolute;
            right: 18px;
            bottom: 18px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border: 1px solid rgba(39, 35, 31, .16);
            background: rgba(255, 255, 255, .88);
            color: var(--loom-ink);
            font-size: 10px;
            letter-spacing: .12em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
            pointer-events: none;
        }
        .loomaama-zoom-stage.is-zoomed .loomaama-zoom-hint span {
            font-size: 0;
        }
        .loomaama-zoom-stage.is-zoomed .loomaama-zoom-hint span::after {
            content: "Click to reset";
            font-size: 10px;
        }
        .loomaama-thumbs-shell,
        .loomaama-option-shell,
        .loomaama-related-shell {
            position: relative;
        }
        .loomaama-thumbs-shell {
            margin-top: 14px;
            padding: 0 34px;
        }
        .loomaama-thumbs-swiper:not(.swiper-initialized) .swiper-wrapper,
        .loomaama-option-swiper:not(.swiper-initialized) .swiper-wrapper {
            display: flex;
            gap: 10px;
        }
        .loomaama-thumbs-swiper:not(.swiper-initialized) .swiper-slide,
        .loomaama-option-swiper:not(.swiper-initialized) .swiper-slide {
            width: calc((100% - 30px) / 4);
            flex: 0 0 calc((100% - 30px) / 4);
        }
        .loomaama-thumb,
        .loomaama-image-choice {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 5;
            display: block;
            padding: 0;
            overflow: hidden;
            border: 1px solid transparent;
            background: var(--loom-surface);
            cursor: pointer;
        }
        .loomaama-thumb img,
        .loomaama-image-choice img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }
        .loomaama-thumb.is-active,
        .loomaama-image-choice.is-active,
        .loomaama-image-choice[aria-current="true"],
        .loomaama-image-choice[aria-pressed="true"] {
            border-color: var(--loom-ink);
        }
        .loomaama-image-choice.is-active::after,
        .loomaama-image-choice[aria-current="true"]::after,
        .loomaama-image-choice[aria-pressed="true"]::after {
            content: "";
            position: absolute;
            right: 7px;
            bottom: 7px;
            width: 9px;
            height: 9px;
            border: 2px solid #fff;
            border-radius: 50%;
            background: var(--loom-ink);
            box-shadow: 0 0 0 1px rgba(0, 0, 0, .15);
        }
        .loomaama-slider-arrow {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid var(--loom-line);
            border-radius: 50%;
            background: #fff;
            color: var(--loom-ink);
            cursor: pointer;
            transition: border-color 160ms ease, opacity 160ms ease;
        }
        .loomaama-slider-arrow:hover:not(:disabled) {
            border-color: var(--loom-ink);
        }
        .loomaama-slider-arrow:disabled,
        .loomaama-slider-arrow.swiper-button-disabled {
            opacity: .28;
            cursor: default;
        }
        .loomaama-thumbs-prev,
        .loomaama-thumbs-next {
            position: absolute;
            top: 50%;
            z-index: 4;
            transform: translateY(-50%);
        }
        .loomaama-thumbs-prev { left: -2px; }
        .loomaama-thumbs-next { right: -2px; }
        .loomaama-product-summary {
            position: sticky;
            top: 118px;
        }
        .loomaama-breadcrumbs {
            margin-bottom: 24px;
            color: var(--loom-muted);
            font-size: 11px;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .loomaama-breadcrumbs a {
            color: inherit;
            text-decoration: none;
        }
        .loomaama-category {
            margin: 0 0 12px;
            color: var(--loom-muted);
            font-size: 11px;
            letter-spacing: .14em;
            text-transform: uppercase;
        }
        .loomaama-product-summary .product_title {
            margin: 0 0 14px;
            font-size: clamp(30px, 3.2vw, 48px);
            font-weight: 500;
            letter-spacing: -.025em;
            line-height: 1.08;
        }
        .loomaama-product-summary .price {
            margin: 0 0 24px;
            color: var(--loom-ink);
            font-size: 17px;
        }
        .loomaama-product-summary .price del {
            color: var(--loom-muted);
            margin-right: 8px;
        }
        .loomaama-product-summary .woocommerce-product-details__short-description {
            color: var(--loom-muted);
            font-size: 14px;
            line-height: 1.75;
        }
        .loomaama-visual-options {
            margin: 28px 0;
            padding-top: 22px;
            border-top: 1px solid var(--loom-line);
        }
        .loomaama-option-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 14px;
        }
        .loomaama-option-heading h3 {
            margin: 0;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .14em;
            text-transform: uppercase;
        }
        .loomaama-option-controls,
        .loomaama-related-controls {
            display: flex;
            gap: 8px;
        }
        .loomaama-option-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
        }
        .loomaama-product-summary form.cart {
            margin: 28px 0 0;
        }
        .loomaama-product-summary form.cart .quantity {
            margin-right: 10px;
        }
        .loomaama-product-summary form.cart .qty {
            min-height: 52px;
            border: 1px solid var(--loom-line);
            background: transparent;
        }
        .loomaama-product-summary .single_add_to_cart_button {
            min-height: 52px;
            padding: 12px 28px;
            border: 1px solid var(--loom-ink);
            border-radius: 0;
            background: var(--loom-ink);
            color: #fff;
            font-family: "Work Sans", sans-serif;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .13em;
            text-transform: uppercase;
        }
        .loomaama-product-summary table.variations {
            width: 100%;
            margin-bottom: 20px;
        }
        .loomaama-product-summary table.variations th,
        .loomaama-product-summary table.variations td {
            display: block;
            width: 100%;
            padding: 0;
            text-align: left;
        }
        .loomaama-product-summary table.variations th {
            margin: 17px 0 8px;
            font-family: "Work Sans", sans-serif;
            font-size: 10px;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .loomaama-product-summary table.variations select {
            width: 100%;
            min-height: 48px;
            padding: 0 14px;
            border: 1px solid var(--loom-line);
            border-radius: 0;
            background: #fff;
        }
        /* Hide text/select UI for visual attributes; JS also applies this class. */
        .loomaama-product-summary tr.loomaama-visual-attribute-row,
        .loomaama-product-summary tr:has(select[name*="attribute_pa_color"]),
        .loomaama-product-summary tr:has(select[name*="attribute_color"]),
        .loomaama-product-summary tr:has(select[name*="attribute_pa_colour"]),
        .loomaama-product-summary tr:has(select[name*="attribute_colour"]),
        .loomaama-product-summary tr:has(select[name*="attribute_pa_pattern"]),
        .loomaama-product-summary tr:has(select[name*="attribute_pattern"]) {
            display: none !important;
        }
        .loomaama-product-summary .reset_variations {
            display: inline-block;
            margin-top: 10px;
            color: var(--loom-muted);
            font-size: 11px;
        }
        .loomaama-product-summary .product_meta {
            display: grid;
            gap: 8px;
            margin-top: 24px;
            color: var(--loom-muted);
            font-size: 11px;
        }
        .loomaama-details {
            margin-top: clamp(56px, 8vw, 110px);
            border-top: 1px solid var(--loom-line);
        }
        .loomaama-detail-item {
            border-bottom: 1px solid var(--loom-line);
        }
        .loomaama-detail-item summary {
            position: relative;
            padding: 22px 42px 22px 0;
            list-style: none;
            cursor: pointer;
            font-family: "Work Sans", sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .loomaama-detail-item summary::-webkit-details-marker { display: none; }
        .loomaama-detail-item summary::before,
        .loomaama-detail-item summary::after {
            content: "";
            position: absolute;
            top: 50%;
            right: 4px;
            width: 14px;
            height: 1px;
            background: currentColor;
        }
        .loomaama-detail-item summary::after {
            transform: rotate(90deg);
            transition: transform 160ms ease;
        }
        .loomaama-detail-item[open] summary::after { transform: rotate(0); }
        .loomaama-detail-content {
            max-width: 780px;
            padding: 0 0 26px;
            color: var(--loom-muted);
            font-size: 14px;
            line-height: 1.8;
        }
        .loomaama-related-products {
            margin-top: clamp(64px, 9vw, 120px);
            padding: clamp(54px, 7vw, 90px) 0;
            border-top: 1px solid var(--loom-line);
        }
        .loomaama-related-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 24px;
            margin-bottom: 30px;
        }
        .loomaama-related-header h2 {
            margin: 0;
            font-size: clamp(24px, 3vw, 38px);
            font-weight: 500;
            letter-spacing: -.02em;
        }
        .loomaama-related-card a {
            color: inherit;
            text-decoration: none;
        }
        .loomaama-related-image {
            aspect-ratio: 4 / 5;
            overflow: hidden;
            background: var(--loom-surface);
        }
        .loomaama-related-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transition: transform 350ms ease;
        }
        .loomaama-related-card:hover .loomaama-related-image img {
            transform: scale(1.025);
        }
        .loomaama-related-copy {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            padding-top: 13px;
        }
        .loomaama-related-copy h3 {
            margin: 0;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.4;
        }
        .loomaama-related-copy .price {
            flex: 0 0 auto;
            color: var(--loom-muted);
            font-size: 12px;
        }
        .loomaama-related-copy .price del { margin-right: 4px; }
        @media (max-width: 991px) {
            .loomaama-single-product { margin-top: 88px; }
            .loomaama-product-layout { grid-template-columns: 1fr; gap: 40px; }
            .loomaama-product-summary { position: static; }
        }
        @media (max-width: 575px) {
            .loomaama-single-product .container-fluid { padding-left: 16px !important; padding-right: 16px !important; }
            .loomaama-thumbs-shell { padding: 0; }
            .loomaama-thumbs-prev,
            .loomaama-thumbs-next { display: none !important; }
            .loomaama-option-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 7px; }
            .loomaama-related-header { align-items: center; }
            .loomaama-zoom-hint { right: 12px; bottom: 12px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .loomaama-main-image,
            .loomaama-related-image img { transition: none; }
        }
    </style>

    <main class="loomaama-single-product reveal visible">
        <div class="container-fluid px-4 px-lg-5">
            <div class="loomaama-breadcrumbs">
                <?php woocommerce_breadcrumb(array('delimiter' => ' / ')); ?>
            </div>

            <article id="product-<?php the_ID(); ?>" <?php wc_product_class('loomaama-product', $product); ?>>
                <div class="loomaama-product-layout">
                    <section class="loomaama-product-gallery" aria-label="<?php esc_attr_e('Product gallery', 'loomaama'); ?>">
                        <div class="loomaama-zoom-stage" tabindex="0" role="button" aria-pressed="false" aria-label="<?php esc_attr_e('Click to zoom product image; drag while zoomed; click again to reset', 'loomaama'); ?>">
                            <img
                                id="loomaama-main-product-image"
                                class="loomaama-main-image"
                                src="<?php echo esc_url($first_image['url']); ?>"
                                data-full="<?php echo esc_url($first_image['full']); ?>"
                                <?php if ($first_image['srcset']) : ?>srcset="<?php echo esc_attr($first_image['srcset']); ?>"<?php endif; ?>
                                <?php if ($first_image['sizes']) : ?>sizes="<?php echo esc_attr($first_image['sizes']); ?>"<?php endif; ?>
                                alt="<?php echo esc_attr($first_image['alt']); ?>"
                                loading="eager"
                                fetchpriority="high"
                                decoding="async"
                                draggable="false"
                            >
                            <span class="loomaama-zoom-hint" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5M11 8v6M8 11h6"></path>
                                </svg>
                                <span><?php esc_html_e('Click to zoom', 'loomaama'); ?></span>
                            </span>
                        </div>

                        <div class="loomaama-thumbs-shell" <?php echo count($base_gallery) < 2 ? 'hidden' : ''; ?>>
                            <button class="loomaama-slider-arrow loomaama-thumbs-prev" type="button" aria-label="<?php esc_attr_e('Previous product image', 'loomaama'); ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>
                            </button>
                            <div class="swiper loomaama-thumbs-swiper" data-count="<?php echo esc_attr(count($base_gallery)); ?>">
                                <div class="swiper-wrapper loomaama-thumbs-list">
                                    <?php foreach ($base_gallery as $image_index => $gallery_image) : ?>
                                        <div class="swiper-slide">
                                            <button class="loomaama-thumb <?php echo 0 === $image_index ? 'is-active' : ''; ?>" type="button" data-image-index="<?php echo esc_attr($image_index); ?>" aria-label="<?php echo esc_attr(sprintf(__('View product image %d', 'loomaama'), $image_index + 1)); ?>">
                                                <img src="<?php echo esc_url($gallery_image['thumb']); ?>" alt="" loading="lazy" decoding="async">
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <button class="loomaama-slider-arrow loomaama-thumbs-next" type="button" aria-label="<?php esc_attr_e('Next product image', 'loomaama'); ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
                            </button>
                        </div>
                    </section>

                    <section class="loomaama-product-summary summary entry-summary">
                        <?php if ($categories) : ?>
                            <p class="loomaama-category"><?php echo wp_kses_post($categories); ?></p>
                        <?php endif; ?>

                        <?php woocommerce_template_single_title(); ?>
                        <?php woocommerce_template_single_rating(); ?>
                        <?php woocommerce_template_single_price(); ?>

                        <?php if ($content_availability['short_description']) : ?>
                            <div class="woocommerce-product-details__short-description js-variation-short-description">
                                <?php echo $base_content['short_description']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                        <?php endif; ?>

                        <?php if (count($linked_product_choices) > 1) :
                            $linked_is_slider = count($linked_product_choices) > 4;
                            ?>
                            <div class="loomaama-visual-options">
                                <div class="loomaama-option-heading">
                                    <h3><?php esc_html_e('Available options', 'loomaama'); ?></h3>
                                    <?php if ($linked_is_slider) : ?>
                                        <div class="loomaama-option-controls">
                                            <button class="loomaama-slider-arrow loomaama-option-prev" type="button" aria-label="<?php esc_attr_e('Previous option', 'loomaama'); ?>">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>
                                            </button>
                                            <button class="loomaama-slider-arrow loomaama-option-next" type="button" aria-label="<?php esc_attr_e('Next option', 'loomaama'); ?>">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if ($linked_is_slider) : ?>
                                    <div class="loomaama-option-shell">
                                        <div class="swiper loomaama-option-swiper" data-slider="true">
                                            <div class="swiper-wrapper">
                                                <?php foreach ($linked_product_choices as $choice) : ?>
                                                    <div class="swiper-slide">
                                                        <a class="loomaama-image-choice" href="<?php echo esc_url($choice['url']); ?>" aria-label="<?php echo esc_attr($choice['label']); ?>" aria-current="<?php echo $choice['current'] ? 'true' : 'false'; ?>">
                                                            <img src="<?php echo esc_url($choice['image']['thumb']); ?>" alt="" loading="lazy" decoding="async">
                                                        </a>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="loomaama-option-grid">
                                        <?php foreach ($linked_product_choices as $choice) : ?>
                                            <a class="loomaama-image-choice" href="<?php echo esc_url($choice['url']); ?>" aria-label="<?php echo esc_attr($choice['label']); ?>" aria-current="<?php echo $choice['current'] ? 'true' : 'false'; ?>">
                                                <img src="<?php echo esc_url($choice['image']['thumb']); ?>" alt="" loading="lazy" decoding="async">
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($visual_choices) :
                            $visual_is_slider = count($visual_choices) > 4;
                            ?>
                            <div class="loomaama-visual-options loomaama-variation-image-options">
                                <div class="loomaama-option-heading">
                                    <h3><?php esc_html_e('Choose an option', 'loomaama'); ?></h3>
                                    <?php if ($visual_is_slider) : ?>
                                        <div class="loomaama-option-controls">
                                            <button class="loomaama-slider-arrow loomaama-option-prev" type="button" aria-label="<?php esc_attr_e('Previous variation', 'loomaama'); ?>">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>
                                            </button>
                                            <button class="loomaama-slider-arrow loomaama-option-next" type="button" aria-label="<?php esc_attr_e('Next variation', 'loomaama'); ?>">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if ($visual_is_slider) : ?>
                                    <div class="loomaama-option-shell">
                                        <div class="swiper loomaama-option-swiper" data-slider="true">
                                            <div class="swiper-wrapper">
                                                <?php foreach ($visual_choices as $choice) : ?>
                                                    <div class="swiper-slide">
                                                        <button
                                                            class="loomaama-image-choice js-variation-image-choice"
                                                            type="button"
                                                            aria-label="<?php echo esc_attr($choice['label']); ?>"
                                                            aria-pressed="false"
                                                            data-variation-id="<?php echo esc_attr($choice['variation_id']); ?>"
                                                            data-attributes="<?php echo esc_attr(wp_json_encode($choice['attributes'])); ?>"
                                                            data-gallery="<?php echo esc_attr(wp_json_encode($choice['gallery'])); ?>"
                                                        >
                                                            <img src="<?php echo esc_url($choice['image']['thumb']); ?>" alt="" loading="lazy" decoding="async">
                                                        </button>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="loomaama-option-grid">
                                        <?php foreach ($visual_choices as $choice) : ?>
                                            <button
                                                class="loomaama-image-choice js-variation-image-choice"
                                                type="button"
                                                aria-label="<?php echo esc_attr($choice['label']); ?>"
                                                aria-pressed="false"
                                                data-variation-id="<?php echo esc_attr($choice['variation_id']); ?>"
                                                data-attributes="<?php echo esc_attr(wp_json_encode($choice['attributes'])); ?>"
                                                data-gallery="<?php echo esc_attr(wp_json_encode($choice['gallery'])); ?>"
                                            >
                                                <img src="<?php echo esc_url($choice['image']['thumb']); ?>" alt="" loading="lazy" decoding="async">
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php woocommerce_template_single_add_to_cart(); ?>
                        <?php woocommerce_template_single_meta(); ?>
                    </section>
                </div>

                <?php
                $detail_sections = array(
                    'product_details' => __('Details & Fit', 'loomaama'),
                    'artisan'         => __('Artisan Story', 'loomaama'),
                    'care'            => __('Care', 'loomaama'),
                    'sensory'         => __('The Feel', 'loomaama'),
                );
                ?>
                <section class="loomaama-details" aria-label="<?php esc_attr_e('Product information', 'loomaama'); ?>">
                    <?php foreach ($detail_sections as $detail_key => $detail_label) :
                        if (empty($content_availability[$detail_key])) {
                            continue;
                        }
                        ?>
                        <details class="loomaama-detail-item" <?php echo 'product_details' === $detail_key ? 'open' : ''; ?>>
                            <summary><?php echo esc_html($detail_label); ?></summary>
                            <div class="loomaama-detail-content js-variation-content" data-content-key="<?php echo esc_attr($detail_key); ?>">
                                <?php echo $base_content[$detail_key]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                        </details>
                    <?php endforeach; ?>
                    <details class="loomaama-detail-item">
                        <summary><?php esc_html_e('Delivery & Returns', 'loomaama'); ?></summary>
                        <div class="loomaama-detail-content">
                            <p><?php esc_html_e('Returns are accepted within 14 days of receipt, subject to the returns policy.', 'loomaama'); ?></p>
                        </div>
                    </details>
                </section>

                <?php
                $related_ids = wc_get_related_products($product_id, 8, array($product_id));
                $related_products = array();

                foreach ($related_ids as $related_id) {
                    $related_product = wc_get_product($related_id);
                    if ($related_product && $related_product->is_visible()) {
                        $related_products[] = $related_product;
                    }
                }
                ?>

                <?php if ($related_products) : ?>
                    <section class="loomaama-related-products" aria-labelledby="loomaama-related-heading">
                        <div class="loomaama-related-header">
                            <h2 id="loomaama-related-heading"><?php esc_html_e('You may also like', 'loomaama'); ?></h2>
                            <div class="loomaama-related-controls">
                                <button class="loomaama-slider-arrow loomaama-related-prev" type="button" aria-label="<?php esc_attr_e('Previous related products', 'loomaama'); ?>">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>
                                </button>
                                <button class="loomaama-slider-arrow loomaama-related-next" type="button" aria-label="<?php esc_attr_e('Next related products', 'loomaama'); ?>">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div class="loomaama-related-shell">
                            <div class="swiper loomaama-related-swiper">
                                <div class="swiper-wrapper">
                                    <?php foreach ($related_products as $related_product) : ?>
                                        <div class="swiper-slide">
                                            <article class="loomaama-related-card">
                                                <a href="<?php echo esc_url($related_product->get_permalink()); ?>">
                                                    <div class="loomaama-related-image">
                                                        <?php
                                                        echo $related_product->get_image(
                                                            'loomaama-product-medium',
                                                            array(
                                                                'loading'  => 'lazy',
                                                                'decoding' => 'async',
                                                            )
                                                        ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                        ?>
                                                    </div>
                                                    <div class="loomaama-related-copy">
                                                        <h3><?php echo esc_html($related_product->get_name()); ?></h3>
                                                        <span class="price"><?php echo wp_kses_post($related_product->get_price_html()); ?></span>
                                                    </div>
                                                </a>
                                            </article>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
            </article>
        </div>
    </main>

    <script type="application/json" id="loomaama-base-gallery-data"><?php echo wp_json_encode($base_gallery, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
    <script type="application/json" id="loomaama-variation-gallery-data"><?php echo wp_json_encode($variation_gallery_map, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
    <script type="application/json" id="loomaama-base-content-data"><?php echo wp_json_encode($base_content, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
    <script type="application/json" id="loomaama-variation-content-data"><?php echo wp_json_encode($variation_content_map, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>

    <script id="loomaama-single-product-script">
    document.addEventListener('DOMContentLoaded', function () {
        'use strict';

        const root = document.querySelector('.loomaama-single-product');
        if (!root) return;

        const readJSON = function (id, fallback) {
            const node = document.getElementById(id);
            if (!node) return fallback;
            try {
                return JSON.parse(node.textContent) || fallback;
            } catch (error) {
                return fallback;
            }
        };

        const baseGallery = readJSON('loomaama-base-gallery-data', []);
        const variationGalleries = readJSON('loomaama-variation-gallery-data', {});
        const baseContent = readJSON('loomaama-base-content-data', {});
        const variationContent = readJSON('loomaama-variation-content-data', {});
        let currentGallery = baseGallery.slice();
        let thumbsSwiper = null;

        const stage = root.querySelector('.loomaama-zoom-stage');
        const mainImage = root.querySelector('.loomaama-main-image');
        const thumbsShell = root.querySelector('.loomaama-thumbs-shell');
        const thumbsContainer = root.querySelector('.loomaama-thumbs-swiper');
        const thumbsList = root.querySelector('.loomaama-thumbs-list');

        /* Click to zoom, second click to reset, drag the parent stage to pan. */
        const zoom = (function () {
            if (!stage || !mainImage) return { reset: function () {} };

            const zoomLevel = 2.35;
            let scale = 1;
            let panX = 0;
            let panY = 0;
            let startX = 0;
            let startY = 0;
            let startPanX = 0;
            let startPanY = 0;
            let pointerId = null;
            let moved = false;

            const clampPan = function () {
                const maxX = Math.max(0, (mainImage.clientWidth * scale - stage.clientWidth) / 2);
                const maxY = Math.max(0, (mainImage.clientHeight * scale - stage.clientHeight) / 2);
                panX = Math.max(-maxX, Math.min(maxX, panX));
                panY = Math.max(-maxY, Math.min(maxY, panY));
            };

            const render = function (animate) {
                clampPan();
                mainImage.style.transition = animate ? 'transform 220ms ease' : 'none';
                mainImage.style.transform = 'translate3d(' + panX + 'px,' + panY + 'px,0) scale(' + scale + ')';
                stage.classList.toggle('is-zoomed', scale > 1);
                stage.setAttribute('aria-pressed', scale > 1 ? 'true' : 'false');
            };

            const reset = function () {
                scale = 1;
                panX = 0;
                panY = 0;
                stage.classList.remove('is-dragging');
                render(true);
            };

            const zoomAt = function (clientX, clientY) {
                const rect = stage.getBoundingClientRect();
                if (mainImage.dataset.full) {
                    mainImage.removeAttribute('srcset');
                    mainImage.removeAttribute('sizes');
                    mainImage.src = mainImage.dataset.full;
                }
                scale = zoomLevel;
                panX = (rect.width / 2 - (clientX - rect.left)) * (scale - 1);
                panY = (rect.height / 2 - (clientY - rect.top)) * (scale - 1);
                render(true);
            };

            stage.addEventListener('pointerdown', function (event) {
                if (event.pointerType === 'mouse' && event.button !== 0) return;
                pointerId = event.pointerId;
                startX = event.clientX;
                startY = event.clientY;
                startPanX = panX;
                startPanY = panY;
                moved = false;
                stage.setPointerCapture(pointerId);
                if (scale > 1) stage.classList.add('is-dragging');
            });

            stage.addEventListener('pointermove', function (event) {
                if (event.pointerId !== pointerId || scale <= 1) return;
                const deltaX = event.clientX - startX;
                const deltaY = event.clientY - startY;
                if (Math.abs(deltaX) > 4 || Math.abs(deltaY) > 4) moved = true;
                panX = startPanX + deltaX;
                panY = startPanY + deltaY;
                render(false);
            });

            const endPointer = function (event) {
                if (event.pointerId !== pointerId) return;
                if (stage.hasPointerCapture(pointerId)) stage.releasePointerCapture(pointerId);
                stage.classList.remove('is-dragging');

                if (!moved) {
                    if (scale > 1) reset();
                    else zoomAt(event.clientX, event.clientY);
                }
                pointerId = null;
            };

            stage.addEventListener('pointerup', endPointer);
            stage.addEventListener('pointercancel', function () {
                pointerId = null;
                stage.classList.remove('is-dragging');
            });
            stage.addEventListener('dragstart', function (event) { event.preventDefault(); });
            stage.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    reset();
                } else if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    if (scale > 1) reset();
                    else {
                        const rect = stage.getBoundingClientRect();
                        zoomAt(rect.left + rect.width / 2, rect.top + rect.height / 2);
                    }
                }
            });
            window.addEventListener('resize', function () {
                if (scale > 1) render(false);
            });

            return { reset: reset };
        }());

        const setMainImage = function (index) {
            const image = currentGallery[index];
            if (!image || !mainImage) return;

            mainImage.src = image.url || image.full;
            mainImage.dataset.full = image.full || image.url || '';
            mainImage.alt = image.alt || '';

            if (image.srcset) mainImage.setAttribute('srcset', image.srcset);
            else mainImage.removeAttribute('srcset');

            if (image.sizes) mainImage.setAttribute('sizes', image.sizes);
            else mainImage.removeAttribute('sizes');

            root.querySelectorAll('.loomaama-thumb').forEach(function (button) {
                button.classList.toggle('is-active', Number(button.dataset.imageIndex) === index);
            });
            zoom.reset();
        };

        const initThumbs = function () {
            if (!thumbsContainer || typeof window.Swiper === 'undefined') return;
            if (thumbsSwiper) {
                thumbsSwiper.destroy(true, true);
                thumbsSwiper = null;
            }

            const count = currentGallery.length;
            const prev = root.querySelector('.loomaama-thumbs-prev');
            const next = root.querySelector('.loomaama-thumbs-next');
            if (prev) prev.hidden = count <= 4;
            if (next) next.hidden = count <= 4;

            if (count > 4) {
                thumbsSwiper = new window.Swiper(thumbsContainer, {
                    slidesPerView: 4,
                    spaceBetween: 10,
                    watchOverflow: true,
                    navigation: { prevEl: prev, nextEl: next },
                });
            }
        };

        const renderGallery = function (gallery) {
            if (!Array.isArray(gallery) || !gallery.length) gallery = baseGallery;
            currentGallery = gallery.slice();

            if (thumbsShell) thumbsShell.hidden = currentGallery.length < 2;
            if (thumbsList) {
                thumbsList.innerHTML = currentGallery.map(function (image, index) {
                    const active = index === 0 ? ' is-active' : '';
                    const src = image.thumb || image.url || image.full || '';
                    return '<div class="swiper-slide"><button class="loomaama-thumb' + active + '" type="button" data-image-index="' + index + '" aria-label="View product image ' + (index + 1) + '"><img src="' + escapeHTML(src) + '" alt="" loading="lazy" decoding="async"></button></div>';
                }).join('');
            }
            setMainImage(0);
            initThumbs();
        };

        function escapeHTML(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        if (thumbsList) {
            thumbsList.addEventListener('click', function (event) {
                const button = event.target.closest('.loomaama-thumb');
                if (!button) return;
                setMainImage(Number(button.dataset.imageIndex || 0));
            });
        }
        initThumbs();

        /* Every visual-choice rail gets its own scoped navigation elements. */
        if (typeof window.Swiper !== 'undefined') {
            root.querySelectorAll('.loomaama-option-swiper[data-slider="true"]').forEach(function (container) {
                const optionsBlock = container.closest('.loomaama-visual-options');
                new window.Swiper(container, {
                    slidesPerView: 4,
                    spaceBetween: 10,
                    watchOverflow: true,
                    navigation: {
                        prevEl: optionsBlock ? optionsBlock.querySelector('.loomaama-option-prev') : null,
                        nextEl: optionsBlock ? optionsBlock.querySelector('.loomaama-option-next') : null,
                    },
                });
            });

            const relatedContainer = root.querySelector('.loomaama-related-swiper');
            if (relatedContainer) {
                new window.Swiper(relatedContainer, {
                    slidesPerView: 1.2,
                    spaceBetween: 14,
                    watchOverflow: true,
                    navigation: {
                        prevEl: root.querySelector('.loomaama-related-prev'),
                        nextEl: root.querySelector('.loomaama-related-next'),
                    },
                    breakpoints: {
                        576: { slidesPerView: 2.15, spaceBetween: 18 },
                        992: { slidesPerView: 4, spaceBetween: 24 },
                    },
                });
            }
        }

        const variationForm = root.querySelector('form.variations_form');
        const visualChoiceButtons = root.querySelectorAll('.js-variation-image-choice');

        const isVisualAttributeName = function (name) {
            const normalized = String(name || '').toLowerCase().replace('attribute_', '').replace('pa_', '');
            return normalized.indexOf('color') !== -1
                || normalized.indexOf('colour') !== -1
                || normalized.indexOf('pattern') !== -1;
        };

        const applyVariationContent = function (variationId) {
            const content = variationId && variationContent[String(variationId)]
                ? variationContent[String(variationId)]
                : baseContent;

            const shortDescription = root.querySelector('.js-variation-short-description');
            if (shortDescription && typeof content.short_description === 'string') {
                shortDescription.innerHTML = content.short_description;
            }

            root.querySelectorAll('.js-variation-content').forEach(function (node) {
                const key = node.dataset.contentKey;
                if (typeof content[key] === 'string') node.innerHTML = content[key];
            });
        };

        if (variationForm) {
            variationForm.querySelectorAll('select').forEach(function (select) {
                if (isVisualAttributeName(select.name)) {
                    const row = select.closest('tr');
                    if (row) row.classList.add('loomaama-visual-attribute-row');
                }
            });

            const findSelect = function (name) {
                return Array.prototype.find.call(variationForm.querySelectorAll('select'), function (select) {
                    return select.name === name;
                });
            };

            const syncActiveChoice = function () {
                visualChoiceButtons.forEach(function (button) {
                    let attributes = {};
                    try { attributes = JSON.parse(button.dataset.attributes || '{}'); } catch (error) { attributes = {}; }

                    const selected = Object.keys(attributes).every(function (attributeName) {
                        const select = findSelect(attributeName);
                        return select && select.value === attributes[attributeName];
                    });
                    button.classList.toggle('is-active', selected);
                    button.setAttribute('aria-pressed', selected ? 'true' : 'false');
                });
            };

            visualChoiceButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    let attributes = {};
                    let gallery = [];
                    try { attributes = JSON.parse(button.dataset.attributes || '{}'); } catch (error) { attributes = {}; }
                    try { gallery = JSON.parse(button.dataset.gallery || '[]'); } catch (error) { gallery = []; }

                    Object.keys(attributes).forEach(function (attributeName) {
                        const select = findSelect(attributeName);
                        if (!select) return;
                        select.value = attributes[attributeName];
                        if (window.jQuery) window.jQuery(select).trigger('change');
                        else select.dispatchEvent(new Event('change', { bubbles: true }));
                    });

                    syncActiveChoice();
                    renderGallery(gallery);
                    applyVariationContent(button.dataset.variationId);
                });
            });

            variationForm.addEventListener('change', syncActiveChoice);

            if (window.jQuery) {
                const $form = window.jQuery(variationForm);
                $form.on('found_variation', function (event, variation) {
                    if (!variation || !variation.variation_id) return;
                    const variationId = String(variation.variation_id);
                    if (variationGalleries[variationId]) renderGallery(variationGalleries[variationId]);
                    applyVariationContent(variationId);
                    syncActiveChoice();
                });
                $form.on('reset_data hide_variation', function () {
                    renderGallery(baseGallery);
                    applyVariationContent(null);
                    visualChoiceButtons.forEach(function (button) {
                        button.classList.remove('is-active');
                        button.setAttribute('aria-pressed', 'false');
                    });
                });
            }

            syncActiveChoice();
        }
    });
    </script>

    <?php
    do_action('woocommerce_after_single_product');
endwhile;

get_footer();
