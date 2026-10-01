<?php
/**
 * 
 * Archive Template for Products (also handles search results)
 *
 * @package Loomaama
 * @version 8.6.0
 */

get_header(); 
// Check if this is a search request
$is_search = isset($_GET['s']) && !empty($_GET['s']);
$search_term = $is_search ? sanitize_text_field($_GET['s']) : '';

// Get current page for pagination
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$post_per_page = 14;

if($is_search){
    $post_per_page = -1;
}
$current_term = get_queried_object();

// Build the query args
$args = array(
    'post_type' => 'product',
    'posts_per_page' => $post_per_page,
    'paged' => $paged,
    'post_status' => 'publish',
    'tax_query' => array(
        array(
            'taxonomy' => 'product_visibility',
            'field' => 'name',
            'terms' => array('exclude-from-catalog', 'exclude-from-search'),
            'operator' => 'NOT IN',
        ),
    ),
);
// Check if the term is available and is a valid WP_Term object
if (isset($current_term) && is_a($current_term, 'WP_Term')) {
    $args['tax_query'][] = array(
        'taxonomy'         => $current_term->taxonomy,
        'field'            => 'term_id', 
        'terms'            => $current_term->term_id,
        'include_children' => true,
    );
}

// Add search parameters if this is a search
if ($is_search) {
    $args['s'] = $search_term;
}

$sale_products = new WP_Query($args);
$total_posts = $sale_products->post_count;
$product_count = 0;

$shop_page_id = wc_get_page_id('shop');
?>

<main class="reveal visible">
    <div class="container-fluid px-4 px-lg-5 mt-5">

        <!-- HERO / PAGE TITLE -->
        <div class="row pb-lg-4">
            <div class="col-12 text-center"> 
                <?php if ($is_search) : ?>
                    <h1 class="headline display-5 mb-4" style="font-weight: 600; letter-spacing: 0.15em;" data-aos="fade-up">
                        <?php _e('Search Results', 'loomaama'); ?>
                    </h1>
                    <p class="text-secondary mx-auto" style="max-width: 600px; font-size: 15px;" data-aos="fade-up">
                        <?php echo sprintf(__('%d product(s) found for "%s"', 'loomaama'), $sale_products->found_posts, esc_html($search_term)); ?>
                    </p>
                <?php else : ?>
                    <h1 class="headline display-5 mb-4" style="font-weight: 600; letter-spacing: 0.15em;" data-aos="fade-up">
                        <?php echo get_field('harvest_title', $shop_page_id); ?>
                    </h1>
                    <p class="text-secondary mx-auto" style="max-width: 600px; font-size: 15px;" data-aos="fade-up">
                        <?php echo get_field('harvest_subtitle', $shop_page_id) ?: __('Sustainable luxury, conscious design, and modern minimalism at exceptional values.', 'loomaama'); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- FILTER BAR -->
        <div class="row mb-5">
            <div class="col-12 col-md-3 "><?php 
                if (function_exists('woocommerce_breadcrumb')) {
                    woocommerce_breadcrumb();
                } 
            ?></div>
            <div class="col-12 d-none d-md-flex col-md-6 flex-wrap align-items-center justify-content-center gap-4 gap-md-5">
                <?php if (!$is_search) : ?>
                <div class="filter-container">
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'hide_empty' => true,
                        'number' => 5,
                    ));
                    ?>
                    <a href="<?php echo site_url('/shop'); ?>" class="filter-link active" data-filter="all"><?php _e('All', 'loomaama'); ?></a>
                    
                    <?php
                    if (!empty($categories) && !is_wp_error($categories)) {
                        $count = 0;
                        foreach ($categories as $category) {
                            if ($count < 4) {
                                ?>
                                <a href="<?php echo get_term_link($category->term_id);?>" class="filter-link" data-filter="<?php echo esc_attr($category->slug); ?>">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                                <?php
                            }
                            $count++;
                        }
                    } else {
                        ?>
                        <a href="#" class="filter-link" data-filter="best-sellers"><?php _e('Best-Sellers', 'loomaama'); ?></a>
                        <a href="#" class="filter-link" data-filter="clothing"><?php _e('Clothing', 'loomaama'); ?></a>
                        <a href="#" class="filter-link" data-filter="accessories"><?php _e('Accessories', 'loomaama'); ?></a>
                        <a href="#" class="filter-link" data-filter="up-to-50"><?php _e('Up to 50% Off', 'loomaama'); ?></a>
                    <?php } ?>
                </div>
                <?php endif;?>
            </div>
            <div class="col-12 col-md-3 filter-container d-flex justify-content-end mb-md-4">
                <button class="btn-lux filter-toggle-btn" id="filterToggle">
                    <span class="d-flex align-items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 4h18M6 9h12M9 14h6M12 19h0" />
                        </svg>
                        FILTERS
                    </span>
                </button>
            </div>
        </div>
        
        <!-- PRODUCT GRID -->
        <div class="row product-masonry main-product-masonry clearfix" id="sale-products">
            <?php
            if ($sale_products->have_posts()) :
                while ($sale_products->have_posts()) : $sale_products->the_post();
                    global $product;
                    $product_count++;
                    
                    $col_class = '';
                    if ($total_posts >= 10) {
                        if ($product_count === 1) {
                            $col_class .= ' d-change-product';
                        } elseif ($product_count === 6) {
                            $col_class .= 'reverse-change-product';
                        }
                    }
                    
                    $categories = wc_get_product_category_list($product->get_id());
                    $category_name = strip_tags($categories);
                    $first_category = explode(',', $category_name)[0];
                    
                    $discount = '';
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                    if ($regular_price && $sale_price && $regular_price > 0) {
                        $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                        
                    }

                    ?>
                    <div class="<?php echo $col_class; ?>" data-aos="fade-up">
                        <div class="lux-card">
                            <div class="lux-img position-relative">
                                <a href="<?php the_permalink(); ?>">
                                    <?php 
                                        $thumbnail_html = woocommerce_get_product_thumbnail('medium_large');
                                        $hover_image_url = '';
                                        if (function_exists('get_field') && get_field('hover_image')) {
                                            $hover_image_url = get_field('hover_image')['sizes']['medium_large'];
                                        }
                                        echo $thumbnail_html;                                      
                                        // Add hover overlay image
                                        if ($hover_image_url) {
                                            echo '<img class="hover-overlay-img" src="' . esc_attr($hover_image_url) . '" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
                                        }
                                    ?>
                                </a>
                                
                                <?php if ($product->is_on_sale() && $discount) : ?>
                                    <span class="badge-sale position-absolute top-0 start-0 m-3"><?php echo '-' . $discount . '%'; ?></span>
                                <?php endif; ?>
                                
                                <?php if ($product->is_featured()) : ?>
                                    <span class="badge-best position-absolute top-0 end-0 m-3"><?php _e('Best-Seller', 'loomaama'); ?></span>
                                <?php endif; ?>
                                
                                <?php if (!$product->is_in_stock()) : ?>
                                    <span class="badge-soldout position-absolute top-0 start-0 m-3"><?php _e('Sold out', 'loomaama'); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="lux-content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="lux-cat headline">
                                        <?php echo esc_html(strtoupper($first_category ?: __('SALE', 'loomaama'))); ?>
                                    </p>
                                   
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-start loom-title-the-price">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none flex-grow-1" title="<?php the_title(); ?>">
                                        <h6 class="mb-0 text-truncate" style="font-size: 13px;"><?php the_title(); ?></h6>
                                    </a>
                                    <span class="text-secondary <?php echo ($product->is_on_sale() && $discount) ? 'sale-on' : 'sale-off'; ?>" style="font-size: 13px;">
                                        <?php echo $product->get_price_html(); ?>
                                    </span>
                                </div>
                                
                               
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5" data-aos="fade-up">
                    <p class="text-secondary">
                        <?php if ($is_search) : ?>
                            <?php _e('No products found matching your search. Please try different keywords.', 'loomaama'); ?>
                        <?php else : ?>
                            <?php _e('No products on sale at the moment.', 'loomaama'); ?>
                        <?php endif; ?>
                    </p>
                    <?php if ($is_search) : ?>
                        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-outline-dark mt-3">
                            <?php _e('Browse All Products', 'loomaama'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Load More Button -->
        <?php if ($sale_products->max_num_pages > 1) : ?>
            <div class="row mt-5">
                <div class="col-md-12 justify-content-center d-flex mt-5">
                    <button class="btn btn-outline-dark px-4 py-3 btn-lux headline" id="load-more-sale" data-page="1" data-max="<?php echo $sale_products->max_num_pages; ?>">
                        <?php _e('Load More →', 'loomaama'); ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- EDITORIAL BANNER -->
        <div class="row my-5 py-5" data-aos="fade-up">
            <div class="col-12">
                <div class="p-5 text-center" style="background: var(--bg-editorial);">
                    <h4 class="headline mb-4" style="font-size: 24px; letter-spacing: 0.15em;" data-aos="fade-up">
                        <?php _e('TIMELESS STYLES AT EXCEPTIONAL VALUES', 'loomaama'); ?>
                    </h4>
                    <p class="text-secondary mx-auto" style="max-width: 600px;" data-aos="fade-up">
                        <?php _e('Limited quantities available. Complimentary shipping and returns on all orders.', 'loomaama'); ?>
                    </p>
                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-4 mt-4" data-aos="fade-up">
                        <span class="headline" style="font-size: 10px; color: var(--text-secondary);">
                            <i class="fa-regular fa-clock me-2"></i><?php _e('Limited time offers', 'loomaama'); ?>
                        </span>
                        <span class="headline" style="font-size: 10px; color: var(--text-secondary);">
                            <i class="fa-regular fa-truck me-2"></i><?php _e('Free shipping', 'loomaama'); ?>
                        </span>
                        <span class="headline" style="font-size: 10px; color: var(--text-secondary);">
                            <i class="fa-regular fa-rotate me-2"></i><?php _e('Easy returns', 'loomaama'); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTINUE GRID for more products -->
        <?php if ($sale_products->max_num_pages > 1) : ?>
            <div class="row g-4 g-lg-5 mt-2 mb-5 py-5">
                  <div class="swiper featured-slider">
                    <div class="swiper-wrapper">
                <?php
                $additional_args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 4,
                    'post__not_in' => wp_list_pluck($sale_products->posts, 'ID'),
                );
                
                $additional_products = new WP_Query($additional_args);
                
                if ($additional_products->have_posts()) :
                    while ($additional_products->have_posts()) : $additional_products->the_post();
                        global $product;
                        ?>
                        <div class="swiper-slide ">
                                <div class="lux-card">
                                    <div class="lux-img position-relative" style="height:auto;">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php 
                                                $thumbnail_html = woocommerce_get_product_thumbnail('medium_large');
                                                $hover_image_url = '';
                                                if (function_exists('get_field') && get_field('hover_image')) {
                                                    $hover_image_url = get_field('hover_image')['sizes']['medium_large'];
                                                }
                                                echo $thumbnail_html;                                      
                                                // Add hover overlay image
                                                if ($hover_image_url) {
                                                    echo '<img class="hover-overlay-img" src="' . esc_attr($hover_image_url) . '" alt="' . esc_attr(get_the_title()) . '" loading="lazy" >';
                                                }
                                            ?>
                                        </a>
                                        
                                        <?php if ($product->is_on_sale() && $discount) : ?>
                                            <span class="badge-sale position-absolute top-0 start-0 m-3"><?php echo '-' . $discount . '%'; ?></span>
                                        <?php endif; ?>
                                        
                                        <?php if ($product->is_featured()) : ?>
                                            <span class="badge-best position-absolute top-0 end-0 m-3"><?php _e('Best-Seller', 'loomaama'); ?></span>
                                        <?php endif; ?>
                                        
                                        <?php if (!$product->is_in_stock()) : ?>
                                            <span class="badge-soldout position-absolute top-0 start-0 m-3"><?php _e('Sold out', 'loomaama'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex justify-content-between loom-title-the-price">
                                        <p class="lux-cat headline">
                                            <?php 
                                            $categories = wc_get_product_category_list($product->get_id());
                                            $category_name = strip_tags($categories);
                                            $first_category = explode(',', $category_name)[0];
                                            echo esc_html(strtoupper($first_category ?: __('SALE', 'loomaama'))); 
                                            ?>
                                        </p>
                                      
                                    </div>
                                <div class="d-flex justify-content-between">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none loom-title-the-price">
                                        <h6 class="mb-0 text-truncate" style="font-size:13px;"><?php the_title(); ?></h6>
                                    </a>
                                    <span class="text-secondary" style="font-size:13px;"><?php echo $product->get_price_html(); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Filter Drawer (same as before) -->
<div id="filter-drawer" class="filter-drawer">
    <div class="drawer-header">
        <h5 class="headline mb-0"><?php _e('FILTERS', 'loomaama'); ?></h5>
        <button class="drawer-close" id="filterClose">×</button>
    </div>

    <div class="drawer-body">
        <!-- Categories Accordion Item -->
        <div class="accordion-filter-item">
            <div class="accordion-filter-header" data-aos="fade-up">
                <h6 class="filter-title mb-0"><?php _e('CATEGORIES', 'loomaama'); ?></h6>
                <span class="accordion-icon">+</span>
            </div>
            <div class="accordion-filter-content"  data-aos="fade-up">
                <div class="filter-options">
                    <?php
                    $product_categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'hide_empty' => true,
                    ));
                    
                    foreach ($product_categories as $category) : ?>
                        <label class="filter-option">
                            <input type="checkbox" class="filter-checkbox" value="<?php echo $category->slug; ?>">
                            <span><?php echo $category->name; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Sizes Accordion Item -->
        <div class="accordion-filter-item">
            <div class="accordion-filter-header" data-aos="fade-up">
                <h6 class="filter-title mb-0"><?php _e('SIZES', 'loomaama'); ?></h6>
                <span class="accordion-icon">+</span>
            </div>
            <div class="accordion-filter-content" data-aos="fade-up">
                <div class="size-grid">
                    <button class="size-btn" data-size="xs">XS</button>
                    <button class="size-btn" data-size="s">S</button>
                    <button class="size-btn" data-size="m">M</button>
                    <button class="size-btn" data-size="l">L</button>
                    <button class="size-btn" data-size="xl">XL</button>
                    <button class="size-btn" data-size="xxl">XXL</button>
                </div>
            </div>
        </div>
          <?php 
            $color_attributes = loomaama_get_color_attributes();
            if ($color_attributes):
            ?>           
        <!-- Colors Accordion Item -->
        <div class="accordion-filter-item">
            <div class="accordion-filter-header" data-aos="fade-up">
                <h6 class="filter-title mb-0"><?php _e('COLORS', 'loomaama'); ?></h6>
                <span class="accordion-icon">+</span>
            </div>
            <div class="accordion-filter-content" data-aos="fade-up">
                <div class="color-grid">    
                    <?php foreach ($color_attributes as $color):?>               
                    <button class="color-btn" style="background: <?php echo $color['hex']; ?>;" data-color="<?php echo $color['slug']; ?>" title="<?php echo $color['name']; ?>"></button>
                    <?php endforeach;?>                 
                </div>
            </div>
        </div>
        <?php endif;?>
         <!-- Colors Accordion Item -->
     
        <?php $pattern_attributes = loomaama_get_pattern_attributes_with_images(); ?>
        <?php if ($pattern_attributes):?>
        <div class="accordion-filter-item">
            <div class="accordion-filter-header" data-aos="fade-up">
                <h6 class="filter-title mb-0"><?php _e('PATTERNS', 'loomaama'); ?></h6>
                <span class="accordion-icon">+</span>
            </div>
            <div class="accordion-filter-content" data-aos="fade-up">
                <div class="pattern-grid"> 
                    <?php foreach ($pattern_attributes as $pattern):?>              
                    <button class="pattern-btn"  data-pattern="<?php echo $pattern['slug']; ?>" title="<?php echo $pattern['name']; ?>">
                        <img src="<?php echo esc_url($pattern['image']); ?>" alt="<?php echo esc_attr($pattern['name']); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                    </button>
                    <?php endforeach;?>
                </div>                   
            </div>
        </div>
        <?php endif;?>

        <!-- Price Range Accordion Item -->
        <div class="accordion-filter-item">
            <div class="accordion-filter-header" data-aos="fade-up">
                <h6 class="filter-title mb-0"><?php _e('PRICE RANGE', 'loomaama'); ?></h6>
                <span class="accordion-icon">+</span>
            </div>
            <div class="accordion-filter-content" data-aos="fade-up">
                <div class="price-range">
                    <div class="price-inputs">
                        <input type="number" class="price-input" id="min-price" placeholder="<?php _e('Min', 'loomaama'); ?>" value="0" min="0">
                        <span>—</span>
                        <input type="number" class="price-input" id="max-price" placeholder="<?php _e('Max', 'loomaama'); ?>" value="1000" min="0">
                    </div>
                    <div class="price-slider">
                        <input type="range" class="range-slider" id="price-min-slider" min="0" max="1000" value="0" step="10">
                        <input type="range" class="range-slider" id="price-max-slider" min="0" max="1000" value="1000" step="10">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sort By Accordion Item -->
        <div class="accordion-filter-item">
            <div class="accordion-filter-header" data-aos="fade-up">
                <h6 class="filter-title mb-0"><?php _e('SORT BY', 'loomaama'); ?></h6>
                <span class="accordion-icon">+</span>
            </div>
            <div class="accordion-filter-content" data-aos="fade-up">
                <div class="filter-group">
                    <ul class="sort-options-list p-0">
                        <li class="sort-option list-style-none">
                            <label>
                                <input type="radio" name="sort_by" class="sort-radio" value="popularity">
                                <span><?php _e('Sort by Popularity', 'loomaama'); ?></span>
                            </label>
                        </li>
                        <li class="sort-option list-style-none">
                            <label>
                                <input type="radio" name="sort_by" class="sort-radio" value="rating">
                                <span><?php _e('Sort by Average Rating', 'loomaama'); ?></span>
                            </label>
                        </li>
                        <li class="sort-option list-style-none">
                            <label>
                                <input type="radio" name="sort_by" class="sort-radio" value="latest">
                                <span><?php _e('Sort by Latest', 'loomaama'); ?></span>
                            </label>
                        </li>
                        <li class="sort-option list-style-none">
                            <label>
                                <input type="radio" name="sort_by" class="sort-radio" value="price_desc">
                                <span><?php _e('Sort by Price: High to Low', 'loomaama'); ?></span>
                            </label>
                        </li>
                        <li class="sort-option list-style-none">
                            <label>
                                <input type="radio" name="sort_by" class="sort-radio" value="price_asc">
                                <span><?php _e('Sort by Price: Low to High', 'loomaama'); ?></span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-actions">
        <button class="btn btn-dark w-100 mb-2" id="applyFilters"><?php _e('APPLY FILTERS', 'loomaama'); ?></button>
        <button class="btn btn-outline-dark w-100" id="clearFilters"><?php _e('CLEAR ALL', 'loomaama'); ?></button>
    </div>
</div>

<script>
jQuery(document).ready(function($) {

    $(document).trigger('hover_images_refresh');
    
    // Filter Drawer Toggle
    $('#filterToggle').on('click', function(e) {
        e.preventDefault();
        $('#filter-drawer').addClass('active');
        $('body').addClass('filter-open');
        $('#overlay').addClass('active');
    });

    $('#filterClose, #overlay').on('click', function() {
        $('#filter-drawer').removeClass('active');
        $('body').removeClass('filter-open');
        $('#overlay').removeClass('active');
    });

    // Accordion functionality
    $('.accordion-filter-header').on('click', function() {
        var parentItem = $(this).closest('.accordion-filter-item');
        parentItem.toggleClass('active');
    });

    // Size button toggle
    $('.size-btn').on('click', function() {
        $(this).toggleClass('active');
    });

    // Color button toggle
    $('.color-btn').on('click', function() {
        $(this).toggleClass('active');
    });
     $('.pattern-btn').on('click', function() {
        $(this).toggleClass('active');
    });

    // Price range sliders
    $('#price-min-slider').on('input', function() {
        $('#min-price').val($(this).val());
        $('#price-max-slider').attr('min', $(this).val());
    });

    $('#price-max-slider').on('input', function() {
        $('#max-price').val($(this).val());
        $('#price-min-slider').attr('max', $(this).val());
       
    });

    $('#min-price').on('input', function() {
        var val = parseInt($(this).val()) || 0;
        var max = parseInt($('#max-price').val()) || 1000;
        if (val > max) {
            val = max;
            $(this).val(val);
        }
        $('#price-min-slider').val(val);
    });

    $('#max-price').on('input', function() {
        var val = parseInt($(this).val()) || 0;
        var min = parseInt($('#min-price').val()) || 0;
        if (val < min) {
            val = min;
            $(this).val(val);
        }
        $('#price-max-slider').val(val);
    });
    $('#max-price').on('change', function() {
        console.log('Max price changed');
        var val = parseInt($(this).val()) || 0;
        var min = parseInt($('#min-price').val()) || 0;
        if (val < min) {
            val = min;
            $('#price-min-slider').attr('max', val);
        }
       
    });

    // Clear all filters
    $('#clearFilters').on('click', function() {
        $('.filter-checkbox').prop('checked', false);
        $('.size-btn, .color-btn').removeClass('active');
        $('#min-price, #price-min-slider').val('0');
        $('#max-price, #price-max-slider').val('1000');
    });

    // Apply filters
    $('#applyFilters').on('click', function() {
        var categories = [];
        $('.filter-checkbox:checked').each(function() {
            categories.push($(this).val());
        });
        
        var sizes = [];
        $('.size-btn.active').each(function() {
            sizes.push($(this).data('size'));
        });
        
        var colors = [];
        $('.color-btn.active').each(function() {
            colors.push($(this).data('color'));
        });
         var patterns = [];
        $('.pattern-btn.active').each(function() {
            patterns.push($(this).data('pattern'));
        });
        
        var minPrice = $('#min-price').val();
        var maxPrice = $('#max-price').val();
        var sort_by = $('input[name="sort_by"]:checked').val();
        var urlParams = new URLSearchParams(window.location.search);
        var searchTerm = urlParams.get('s') || '';
    
        if (!sort_by) {
            sort_by = 'latest';
        }
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'loomaama_filter_sale_products',
                categories: categories,
                sizes: sizes,
                colors: colors,
                patterns: patterns,
                min_price: minPrice,
                max_price: maxPrice,
                sort_by: sort_by,
                search_term: searchTerm,
                nonce: '<?php echo wp_create_nonce('loomaama_filter_nonce'); ?>'
            },
           success: function(response) {
                if (response.success) {
                    $('#sale-products').html(response.data.html);
                    
                    // Reinitialize reveal animations
                    if (typeof observer !== 'undefined') {
                        document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
                    }
                    
                    // Trigger hover handler refresh
                    if (window.loomaamaHoverHandler) {
                        window.loomaamaHoverHandler.refresh();
                    }
                    $(document).trigger('hover_images_refresh');
                }
            }
        });
        
        $('#filter-drawer').removeClass('active');
        $('body').removeClass('filter-open');
        $('#overlay').removeClass('active');
    });

    // Load more products
    $('#load-more-sale').on('click', function() {
        var button = $(this);
        var page = parseInt(button.data('page')) + 1;
        var max = button.data('max');
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'loomaama_load_more_sale',
                page: page,
                nonce: '<?php echo wp_create_nonce('loomaama_nonce'); ?>'
            },
            beforeSend: function() {
                button.text('<?php _e('Loading...', 'loomaama'); ?>');
            },
            success: function(response) {
                if (response.success) {
                    $('#sale-products').append(response.data.html);
                    button.data('page', page);
                    if (page >= max) button.remove();
                    else button.text('<?php _e('Load More →', 'loomaama'); ?>');
                    if (typeof observer !== 'undefined') {
                        document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
                    }
                     // Trigger hover handler refresh
                    if (window.loomaamaHoverHandler) {
                        window.loomaamaHoverHandler.refresh();
                    }
                    $(document).trigger('hover_images_refresh');
                            }
                        }
                    });
    });

    // Close on escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#filter-drawer').hasClass('active')) {
            $('#filter-drawer').removeClass('active');
            $('body').removeClass('filter-open');
            $('#overlay').removeClass('active');
        }
    });
});
</script>

<?php
wp_reset_postdata();
get_footer();
?>