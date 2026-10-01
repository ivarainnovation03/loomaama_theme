<?php
/**
 * Template Name: Sale Page
 *
 * @package Loomaama
 */

get_header(); ?>

<main style="margin-top: 120px;" class="reveal visible">
    <div class="container-fluid px-4 px-lg-5">

        <!-- HERO / PAGE TITLE – minimal, editorial -->
        <div class="row mb-5 pb-lg-4">
            <div class="col-12 text-center">
                <h1 class="headline display-5 mb-4" style="font-weight: 600; letter-spacing: 0.15em;">
                    <?php the_title(); ?>
                </h1>
                <p class="text-secondary mx-auto" style="max-width: 600px; font-size: 15px;">
                    <?php echo get_post_meta(get_the_ID(), 'subtitle', true) ?: __('Sustainable luxury, conscious design, and modern minimalism at exceptional values.', 'loomaama'); ?>
                </p>
            </div>
        </div>

        <!-- FILTER BAR – clean, uppercase, exactly like shop.html -->
       <div class="row mb-5">
        <div class="col-12 d-flex flex-wrap align-items-center justify-content-center gap-4 gap-md-5">
            <div class="filter-container">
                <?php
                // Get product categories
                $categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'number' => 5, // Limit to 5 categories
                ));
                
                // Always show "All" link first
                ?>
                <a href="#" class="filter-link active" data-filter="all"><?php _e('All', 'loomaama'); ?></a>
                
                <?php
                // Display up to 5 categories
                if (!empty($categories) && !is_wp_error($categories)) {
                    $count = 0;
                    foreach ($categories as $category) {
                        if ($count < 4) { // Show 4 categories (since "All" is the 5th)
                            ?>
                            <a href="#" class="filter-link" data-filter="<?php echo esc_attr($category->slug); ?>">
                                <?php echo esc_html($category->name); ?>
                            </a>
                            <?php
                        }
                        $count++;
                    }
                } else {
                    // Fallback if no categories found
                    ?>
                    <a href="#" class="filter-link" data-filter="best-sellers"><?php _e('Best-Sellers', 'loomaama'); ?></a>
                    <a href="#" class="filter-link" data-filter="clothing"><?php _e('Clothing', 'loomaama'); ?></a>
                    <a href="#" class="filter-link" data-filter="accessories"><?php _e('Accessories', 'loomaama'); ?></a>
                    <a href="#" class="filter-link" data-filter="up-to-50"><?php _e('Up to 50% Off', 'loomaama'); ?></a>
                <?php } ?>
        </div>
         <div class="filter-container d-flex justify-content-end mb-md-4">
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
        <!-- PRODUCT GRID – exactly like shop.html with 3 columns -->
        <div class="row product-masonry main-product-masonry clearfix" id="sale-products">
            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 15, // Multiple of 3 for better grid layout
                'paged' => $paged,
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => '_sale_price',
                        'value' => 0,
                        'compare' => '>',
                        'type' => 'numeric'
                    ),
                    array(
                        'key' => '_sale_prices',
                        'compare' => 'EXISTS'
                    )
                )
            );
            
            $sale_products = new WP_Query($args);
            $product_count = 0;
            
            if ($sale_products->have_posts()) :
                while ($sale_products->have_posts()) : $sale_products->the_post();
                    global $product;
                    $product_count++;
                    
                    // Determine column classes based on position for varied layout like shop.html
                    $col_class = 'col-12 col-md-6 col-lg-4';
                    
                  
                    // Get product categories
                    $categories = wc_get_product_category_list($product->get_id());
                    $category_name = strip_tags($categories);
                    $first_category = explode(',', $category_name)[0];
                    
                    // Calculate discount percentage
                    $discount = '';
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                    if ($regular_price && $sale_price && $regular_price > 0) {
                        $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                    }
                    ?>
                    <div class="<?php echo $col_class; ?> reveal">
                        <div class="lux-card">
                            <div class="lux-img position-relative">
                                <?php echo woocommerce_get_product_thumbnail('large'); ?>
                                
                                <?php if ($product->is_on_sale() && $discount) : ?>
                                    <span class="badge-sale position-absolute top-0 start-0 m-3">
                                        <?php echo '-' . $discount . '%'; ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($product->is_featured()) : ?>
                                    <span class="badge-best position-absolute top-0 end-0 m-3">
                                        <?php _e('Best-Seller', 'loomaama'); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (!$product->is_in_stock()) : ?>
                                    <span class="badge-soldout position-absolute top-0 start-0 m-3">
                                        <?php _e('Sold out', 'loomaama'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="lux-cat headline">
                                <?php echo esc_html(strtoupper($first_category ?: __('SALE', 'loomaama'))); ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-start">
                                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                    <h6 class="mb-0 text-truncate" style="font-size: 13px;"><?php the_title(); ?></h6>
                                </a>
                                <span class="text-secondary" style="font-size: 13px;">
                                    <?php echo $product->get_price_html(); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <p class="text-secondary"><?php _e('No products on sale at the moment.', 'loomaama'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Load More Button - exactly like shop.html -->
        <?php if ($sale_products->max_num_pages > 1) : ?>
            <div class="row mt-5">
                <div class="col-md-12 justify-content-center d-flex mt-5">
                    <button class="btn btn-outline-dark px-4 py-3 btn-lux headline" id="load-more-sale" data-page="1" data-max="<?php echo $sale_products->max_num_pages; ?>">
                        <?php _e('Load More →', 'loomaama'); ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- EDITORIAL BANNER "Timeless styles" section - exactly like shop.html -->
        <div class="row my-5 py-5">
            <div class="col-12">
                <div class="p-5 text-center" style="background: var(--bg-editorial);">
                    <h4 class="headline mb-4" style="font-size: 24px; letter-spacing: 0.15em;">
                        <?php _e('TIMELESS STYLES AT EXCEPTIONAL VALUES', 'loomaama'); ?>
                    </h4>
                    <p class="text-secondary mx-auto" style="max-width: 600px;">
                        <?php _e('Limited quantities available. Complimentary shipping and returns on all orders.', 'loomaama'); ?>
                    </p>
                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-4 mt-4">
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

        <!-- CONTINUE GRID for more products - exactly like shop.html -->
        <?php if ($sale_products->max_num_pages > 1) : ?>
        <div class="row mt-2 mb-5 py-5">
            <?php
            // Show additional products as placeholders for the "continue grid" style
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
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="lux-card">
                            <div class="lux-img position-relative">
                                <?php echo woocommerce_get_product_thumbnail('large'); ?>
                                <?php if (!$product->is_in_stock()) : ?>
                                    <span class="badge-soldout position-absolute top-0 start-0 m-3">
                                        <?php _e('Sold out', 'loomaama'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="lux-cat headline">
                                <?php 
                                $categories = wc_get_product_category_list($product->get_id());
                                $category_name = strip_tags($categories);
                                $first_category = explode(',', $category_name)[0];
                                echo esc_html(strtoupper($first_category ?: __('SALE', 'loomaama'))); 
                                ?>
                            </p>
                            <div class="d-flex justify-content-between">
                                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                    <h6 style="font-size:13px;"><?php the_title(); ?></h6>
                                </a>
                                <span class="text-secondary" style="font-size:13px;"><?php echo $product->get_price_html(); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</main>

<!-- Filter Drawer - exactly like shop.html -->
<div id="filter-drawer" class="filter-drawer">
    <div class="drawer-header">
        <h5 class="headline mb-0"><?php _e('FILTERS', 'loomaama'); ?></h5>
        <button class="drawer-close" id="filterClose">×</button>
    </div>

    <div class="drawer-body">
        <!-- Categories -->
        <div class="filter-section">
            <h6 class="filter-title"><?php _e('CATEGORIES', 'loomaama'); ?></h6>
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

        <!-- Sizes -->
        <div class="filter-section">
            <h6 class="filter-title"><?php _e('SIZES', 'loomaama'); ?></h6>
            <div class="size-grid">
                <button class="size-btn" data-size="xs">XS</button>
                <button class="size-btn" data-size="s">S</button>
                <button class="size-btn" data-size="m">M</button>
                <button class="size-btn" data-size="l">L</button>
                <button class="size-btn" data-size="xl">XL</button>
                <button class="size-btn" data-size="xxl">XXL</button>
            </div>
        </div>

        <!-- Colors -->
        <div class="filter-section">
            <h6 class="filter-title"><?php _e('COLORS', 'loomaama'); ?></h6>
            <div class="color-grid">
                <button class="color-btn" style="background: #000000;" data-color="black" title="<?php _e('Black', 'loomaama'); ?>"></button>
                <button class="color-btn" style="background: #9E8574;" data-color="beige" title="<?php _e('Beige', 'loomaama'); ?>"></button>
                <button class="color-btn" style="background: #8B4513;" data-color="brown" title="<?php _e('Brown', 'loomaama'); ?>"></button>
                <button class="color-btn" style="background: #6F4E37;" data-color="coffee" title="<?php _e('Coffee', 'loomaama'); ?>"></button>
                <button class="color-btn" style="background: #C0C0C0;" data-color="silver" title="<?php _e('Silver', 'loomaama'); ?>"></button>
                <button class="color-btn" style="background: #808080;" data-color="gray" title="<?php _e('Gray', 'loomaama'); ?>"></button>
                <button class="color-btn" style="background: #FFFFFF; border: 1px solid var(--border);" data-color="white" title="<?php _e('White', 'loomaama'); ?>"></button>
                <button class="color-btn" style="background: #000080;" data-color="navy" title="<?php _e('Navy', 'loomaama'); ?>"></button>
            </div>
        </div>

        <!-- Price Range -->
        <div class="filter-section">
            <h6 class="filter-title"><?php _e('PRICE RANGE', 'loomaama'); ?></h6>
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
        
        <!-- Discount Range -->
        <div class="filter-section">
            <h6 class="filter-title"><?php _e('DISCOUNT', 'loomaama'); ?></h6>
            <div class="filter-options">
                <label class="filter-option">
                    <input type="checkbox" class="filter-checkbox" value="10">
                    <span><?php _e('10% or more', 'loomaama'); ?></span>
                </label>
                <label class="filter-option">
                    <input type="checkbox" class="filter-checkbox" value="20">
                    <span><?php _e('20% or more', 'loomaama'); ?></span>
                </label>
                <label class="filter-option">
                    <input type="checkbox" class="filter-checkbox" value="30">
                    <span><?php _e('30% or more', 'loomaama'); ?></span>
                </label>
                <label class="filter-option">
                    <input type="checkbox" class="filter-checkbox" value="50">
                    <span><?php _e('50% or more', 'loomaama'); ?></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Filter Actions -->
    <div class="filter-actions">
        <button class="btn btn-dark w-100 mb-2" id="applyFilters"><?php _e('APPLY FILTERS', 'loomaama'); ?></button>
        <button class="btn btn-outline-dark w-100" id="clearFilters"><?php _e('CLEAR ALL', 'loomaama'); ?></button>
    </div>
</div>

<!-- Add required CSS for badges if not already in your theme -->
<style>
/* Sale badge */
.badge-sale {
    background: #dc3545;
    color: white;
    padding: 6px 12px;
    font-family: var(--font-headline);
    font-size: 10px;
    letter-spacing: 0.1em;
    border-radius: 0;
    z-index: 2;
}

/* Sold out badge */
.badge-soldout {
    background: #6c757d;
    color: white;
    padding: 6px 12px;
    font-family: var(--font-headline);
    font-size: 10px;
    letter-spacing: 0.1em;
    border-radius: 0;
    z-index: 2;
}

/* Best seller badge */
.badge-best {
    background: #000;
    color: white;
    padding: 6px 12px;
    font-family: var(--font-headline);
    font-size: 10px;
    letter-spacing: 0.1em;
    border-radius: 0;
    z-index: 2;
}
</style>

<script>
jQuery(document).ready(function($) {
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

    // Size button toggle
    $('.size-btn').on('click', function() {
        $(this).toggleClass('active');
    });

    // Color button toggle
    $('.color-btn').on('click', function() {
        $(this).toggleClass('active');
    });

    // Price range sliders
    $('#price-min-slider').on('input', function() {
        $('#min-price').val($(this).val());
    });

    $('#price-max-slider').on('input', function() {
        $('#max-price').val($(this).val());
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
        
        var minPrice = $('#min-price').val();
        var maxPrice = $('#max-price').val();
        
        // Get discount filters
        var discounts = [];
        $('.filter-checkbox:checked').each(function() {
            if ($(this).closest('.filter-section').find('.filter-title:contains("DISCOUNT")').length) {
                discounts.push(parseInt($(this).val()));
            }
        });
        
        // AJAX filter request
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'loomaama_filter_sale_products',
                categories: categories,
                sizes: sizes,
                colors: colors,
                min_price: minPrice,
                max_price: maxPrice,
                discounts: discounts,
                nonce: '<?php echo wp_create_nonce('loomaama_filter_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('#sale-products').html(response.data.html);
                    
                    // Reinitialize reveal animations
                    if (typeof observer !== 'undefined') {
                        document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
                    }
                }
            }
        });
        
        // Close drawer
        $('#filter-drawer').removeClass('active');
        $('body').removeClass('filter-open');
        $('#overlay').removeClass('active');
    });

    // Filter links (top filters)
    $('.filter-link').on('click', function(e) {
        e.preventDefault();
        $('.filter-link').removeClass('active');
        $(this).addClass('active');
        
        var filter = $(this).data('filter');
        
        // AJAX filter by category
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'loomaama_filter_sale_products',
                filter_type: filter,
                nonce: '<?php echo wp_create_nonce('loomaama_filter_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('#sale-products').html(response.data.html);
                    
                    // Reinitialize reveal animations
                    if (typeof observer !== 'undefined') {
                        document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
                    }
                }
            }
        });
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
                    
                    if (page >= max) {
                        button.remove();
                    } else {
                        button.text('<?php _e('Load More →', 'loomaama'); ?>');
                    }
                    
                    // Reinitialize reveal animations for new products
                    if (typeof observer !== 'undefined') {
                        document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
                    }
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