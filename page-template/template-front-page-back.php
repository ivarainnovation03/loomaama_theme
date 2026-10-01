<?php


//FIX THE BUGS IN THIS FILE FIRST

/**
 * 
 * 
 *
 * @package Loomaama
 */

get_header(); ?>

<main>
    <?php
    // Hero Section
   
    
    // Display homepage widgets (drag and drop sections)
    if (is_active_sidebar('homepage-sections')) {
        dynamic_sidebar('homepage-sections');
    } else {

    
        $shop_url = function_exists('wc_get_page_id') 
            ? get_permalink(wc_get_page_id('shop')) 
            : '#'; 
                    
        // Default content if no widgets
         get_template_part('template-parts/hero-section');
        ?>
        
        <!-- Quote Section -->
        <section class="py-5 text-center reveal">
            <div class="container py-5">
                <div class="col-md-8 offset-md-2">
                    <h6 class="headline text-secondary mb-4">
                        <?php echo esc_html(get_theme_mod('quote_subtitle', __('Nettle. Reclaimed.', 'loomaama'))); ?>
                    </h6>
                    <blockquote class="blockquote-luxury">
                        <p><?php echo esc_html(get_theme_mod('quote_text', __('From the slopes of the Himalayas to the hands of our artisans. Crafting timeless pieces with nature\'s rarest fiber', 'loomaama'))); ?></p>
                    </blockquote>
                   
                    <a href="<?php echo esc_url($shop_url); ?>" class="btn btn-link-dark px-4 py-3">
                        <?php _e('Shop the Collection →', 'loomaama'); ?>
                    </a>               
                 
                </div>
            </div>
        </section>

        <!-- Hero Split -->
        <section class="py-5 reveal card-section">
            <div class="container-fluid px-4 px-lg-5">
                <div class="row align-items-center">
                    <div class="col-lg-4 py-5">
                        <h2 class="headline mb-4">
                            <?php echo esc_html(get_theme_mod('split_title', __('A choreography of nettle and artisan hand', 'loomaama'))); ?>
                        </h2>
                        <p class="text-secondary mb-4">
                            <?php echo esc_html(get_theme_mod('split_text', __('Forged at the intersection of nature\'s thorns and the artisan\'s touch. Raw elements, elevated by hand into timeless form.', 'loomaama'))); ?>
                        </p>
                        <a href="<?php echo esc_url($shop_url); ?>" class="btn btn-outline-dark px-4 py-3 btn-lux headline">
                            <?php _e('Discover Collection →', 'loomaama'); ?>
                        </a>
                    </div>
                    <div class="col-lg-8">
                       <?php
                        $alt_text = '';
                        $split_image = get_theme_mod('split_image');

                        if ($split_image) {
                            $alt_text = get_post_meta($split_image, '_wp_attachment_image_alt', true);

                            if (empty($alt_text)) {
                                $alt_text = get_the_title($split_image);
                            }

                          
                        }
                        ?>

                        <?php if ($split_image) : ?>
                            <figure>
                            <img src="<?php echo esc_url($split_image); ?>" class="img-fluid w-100" alt="<?php echo ($alt_text)?:'A choreography of nettle and artisan hand';?>">
                            </figure>
                        <?php else : ?>
                            <figure>
                            <img src="<?php echo esc_url(LOOMAAMA_URI . '/assets/images/MODEL WARE.png'); ?>" class="img-fluid w-100" alt="A choreography of nettle">
                            </figure>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products Carousel -->
        <section class="py-5 reveal">
            <div class="container-fluid px-4 py-5 px-lg-5">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="Seasonal-edit-content">
                            <span class="headline text-secondary mb-3 d-block" style="font-size: 10px;"><?php _e('The Seasonal Edit', 'loomaama'); ?></span>
                            <h3 class="headline"><?php _e('TOGETHER WE\'RE RESHAPING THE TEXTILES INDUSTRY', 'loomaama'); ?></h3>
                            <p class="text-secondary">
                                <?php _e('We are paving the way for a new era in the textile industry by offering premium products made from organically grown and ethically made cotton, creating a positive impact on the people and the planet.', 'loomaama'); ?>
                            </p>
                            <a href="<?php echo esc_url($shop_url); ?>" class="btn btn-outline-dark px-4 py-3 btn-lux headline mt-3">
                                <?php _e('VIEW COLLECTION', 'loomaama'); ?> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="swiper featured-slider">
                            <div class="swiper-wrapper">
                                <?php
                          
                                    $featured_products = function_exists('wc_get_products') 
                                        ? wc_get_products(array(
                                            'tax_query' => array( array( 'taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured' ) ),
                                            'orderby' => 'date',
                                            'order' => 'DESC',
                                            'limit'    => 6,
                                        )) 
                                        : array();

                                    foreach ($featured_products as $product) :
                                        $product_id = $product->get_id();
                                        $product_image = wp_get_attachment_image_url($product->get_image_id(), 'loomaama-product-large');
                                        $product_name = $product->get_name();
                                        $product_price = $product->get_price_html();
                                        $product_categories = wp_get_post_terms($product_id, 'product_cat');
                                        $category_name = !empty($product_categories) ? $product_categories[0]->name : __('NETTLE', 'loomaama');
                                    ?>                                   
                                    <div class="swiper-slide">
                                        <div class="lux-card">
                                            <div class="lux-img">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php 
                                                        $thumbnail_html = woocommerce_get_product_thumbnail('large');
                                                        if (function_exists('get_field') && get_field('hover_image')) {
                                                        $hover_image_url = get_field('hover_image')['url'];
                                                        $thumbnail_html = str_replace('<img', '<img data-hover-image="' . esc_attr($hover_image_url) . '"', $thumbnail_html);
                                                        }

                                                        echo $thumbnail_html;
                                                    ?>
                                                </a>
                                            </div>
                                             <div class="d-flex justify-content-between loom-title-the-price">
                                                <p class="lux-cat headline"><?php echo esc_html(strtoupper($category_name)); ?></p>
                                                <?php echo loomaama_render_color_variant_dots($product->get_id()); ?>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="text-decoration-none">
                                                    <h6 class="mb-0 text-truncate"><?php echo esc_html($product_name); ?></h6>
                                                </a>
                                                <?php echo $product_price; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Collections Grid -->
        <section class="py-5 reveal">
            <div class="container">
                <div class="title-section mb-5">
                    <h3 class="headline"><span class="d-block"><?php _e('Discover', 'loomaama'); ?></span> <?php _e('The Collections', 'loomaama'); ?></h3>
                </div>

                <div class="row align-items-stretch custom-3x2">
                    <?php
                    $product_categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'hide_empty' => true,
                        'number' => 3,
                    ));
                    
                    if (!empty($product_categories)) :
                        foreach ($product_categories as $index => $category) :
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            $image = wp_get_attachment_url($thumbnail_id);
                            $count = $category->count;
                            
                            if ($index === 0) : ?>
                                <div class="col-lg-8 p-0">
                                    <div class="product-item full-height bg-gray">
                                        <a href="<?php echo esc_url(get_term_link($category)); ?>" class="btn btn-outline-dark product-category">
                                            <?php echo esc_html($category->name); ?> <span><?php echo sprintf(_n('%s item', '%s items', $count, 'loomaama'), $count); ?></span>
                                        </a>
                                        <img src="<?php echo esc_url($image ?: LOOMAAMA_URI . '/assets/images/model-outer-image.jpg'); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4 p-0">
                            <?php else : ?>
                                <div class="product-item sm-height bg-gray">
                                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="btn btn-outline-dark product-category">
                                        <?php echo esc_html($category->name); ?> <span><?php echo sprintf(_n('%s item', '%s items', $count, 'loomaama'), $count); ?></span>
                                    </a>
                                    <img src="<?php echo esc_url($image ?: LOOMAAMA_URI . '/assets/images/man-style.jpg'); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                </div>
                                <?php if ($index === count($product_categories) - 1) echo '</div>'; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Quote Section -->
        <section class="py-5 text-center reveal" style="background:#F7F3EE;">
            <div class="container py-5">
                <h6 class="headline text-secondary mb-4">
                    <?php _e('Our Philosophy', 'loomaama'); ?>
                </h6>
                <blockquote class="headline mb-4">
                    <?php _e('"A circular future where every fiber is accounted for and every hand is valued"', 'loomaama'); ?>
                </blockquote>
                <p class="headline text-secondary ">— <?php _e('Loomaama', 'loomaama'); ?></p>
            </div>
        </section>

        <!-- Image + Text Split -->
        <section class="row reveal">
            <div class="col-lg-6">
                <img src="<?php echo esc_url(LOOMAAMA_URI . '/assets/images/model-outer-image.jpg'); ?>" class="img-fluid w-100" style="height:calc(100vh - 80px);object-fit:cover;">
            </div>
            <div class="col-lg-6 d-flex align-items-center p-5">
                <div>
                    <h3 class="headline mb-4"><?php _e('The Architecture of Care', 'loomaama'); ?></h3>
                    <p class="text-secondary mb-4">
                        <?php _e('Every interaction should be an opportunity for sensory exploration.', 'loomaama'); ?>
                    </p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('store-locator'))); ?>" class="headline border-bottom pb-1 text-dark text-decoration-none">
                        <?php _e('Store Locator', 'loomaama'); ?>
                    </a>
                </div>
            </div>
        </section>

        <!-- New Season Arrivals -->
        <section class="py-5 reveal">
            <div class="container-fluid px-4 px-lg-5">
                <div class="row mb-4">
                    <div class="col">
                        <h3 class="headline"><?php _e('New Season Arrivals', 'loomaama'); ?></h3>
                    </div>
                    <div class="col-auto">
                        <div class="carousel-nav d-none d-md-flex gap-2">
                            <button class="btn-arrival-prev btn btn-outline-dark btn-sm p-2">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="btn-arrival-next btn btn-outline-dark btn-sm p-2">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="swiper arrival-slider">
                    <div class="swiper-wrapper">
                        <?php
                        $new_products = wc_get_products(array(
                            'limit' => 8,
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'status' => 'publish',
                        ));
                        
                        foreach ($new_products as $product) :
                            $product_id = $product->get_id();
                            $product_image = wp_get_attachment_image_url($product->get_image_id(), 'loomaama-product-large');
                            $product_name = $product->get_name();
                            $product_price = $product->get_price_html();
                            $product_categories = wp_get_post_terms($product_id, 'product_cat');
                            $category_name = !empty($product_categories) ? $product_categories[0]->name : __('NETTLE', 'loomaama');
                            ?>
                            <div class="swiper-slide">
                                <div class="lux-card">
                                    <div class="lux-img">
                                        <a href="<?php the_permalink(); ?>">
                                             <?php 
                                                $thumbnail_html = woocommerce_get_product_thumbnail('large');
                                                if (function_exists('get_field') && get_field('hover_image')) {
                                                $hover_image_url = get_field('hover_image')['url'];
                                                $thumbnail_html = str_replace('<img', '<img data-hover-image="' . esc_attr($hover_image_url) . '"', $thumbnail_html);
                                                }

                                                echo $thumbnail_html;
                                            ?>
                                        </a>
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
                                        <?php echo loomaama_render_color_variant_dots($product->get_id()); ?>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="text-decoration-none">
                                            <h6 class="mb-0 text-truncate"><?php echo esc_html($product_name); ?></h6>
                                        </a>
                                        <?php echo $product_price; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination mt-4 position-relative"></div>
                </div>
            </div>
        </section>

        <!-- Sustainability Section -->
        <section class="editorial-section">
            <div class="editorial-content">
                <p class="section-subtitle" data-page="sustainability"><?php _e('OUR COMMITMENT', 'loomaama'); ?></p>
                <h2 class="editorial-title"><?php _e('SUSTAINABILITY AT THE CORE', 'loomaama'); ?></h2>
                <p class="mb-5 text-secondary" style="line-height: 1.8;">
                    <?php _e('Every garment tells a story of radical transparency. From our regenerative farms to your closet, we ensure the highest standards of animal welfare, environmental protection, and social responsibility.', 'loomaama'); ?>
                </p>

                <div class="row mt-5 pt-5">
                    <div class="col-md-6 mb-4">
                        <div class="d-flex align-items-center gap-3 mb-3" style="background: url('<?php echo esc_url(LOOMAAMA_URI . '/assets/images/authenticate-sale.jpg'); ?>'); position: relative; background-size: cover; aspect-ratio: 2/3;">
                            <div class="p-4 editorial-card">
                                <h6 class="section-title mb-3"><?php _e('Authenticate Sale', 'loomaama'); ?></h6>
                                <p class="text-secondary"><?php _e('Join us in building a circular future through Authenticated Resale', 'loomaama'); ?></p>
                                <a href="#" class="text-dark text-decoration-underline"><?php _e('Learn More', 'loomaama'); ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="d-flex align-items-center gap-3 mb-3" style="background: url('<?php echo esc_url(LOOMAAMA_URI . '/assets/images/sustainability.jpg'); ?>'); position: relative; background-size: cover; aspect-ratio: 2/3;">
                            <div class="p-4 editorial-card">
                                <h6 class="section-title mb-3"><?php _e('SUSTAINABILITY', 'loomaama'); ?></h6>
                                <p class="text-secondary"><?php _e('Our philosophy, policies, materials, and suppliers', 'loomaama'); ?></p>
                                <a href="#" class="learn-more-link"><?php _e('Learn More →', 'loomaama'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>
</main>

<?php
get_footer();