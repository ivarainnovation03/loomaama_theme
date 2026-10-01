<?php
/**
 * Template Name: Artisans
 *
 * @package Loomaama
 */

get_header();
?>
 <?php $hero_image = get_field('artisans_hero_image'); ?>
<main  class="jarallax" <?php echo $hero_image ? 'style="background-image: url(\'' . esc_url($hero_image) . '\');"' : ''; ?>>
    <!-- HERO SECTION -->
    <section class="hero-editorial " data-aos="zoom-in">
        <div class="hero-editorial-content reveal">
            <span class="eyebrow mb-4 d-block" data-aos="fade-up" data-aos-delay="100">
                <?php echo wp_kses_post(get_field('artisans_hero_eyebrow') ?: ''); ?>
            </span>
            <h1 class="headline display-3 mb-4 fw-bold" data-aos="fade-up" data-aos-delay="200">
                <?php echo wp_kses_post(get_field('artisans_hero_title') ?: 'The Hands Behind Every Thread.'); ?>
            </h1>
            <h5 class="headline fw-normal mb-4" data-aos="fade-up" data-aos-delay="300">
                <?php echo wp_kses_post(get_field('artisans_hero_subheading') ?: 'The Mountain Sends Its Best.'); ?>
            </h5>
            <p class=" mx-auto" style="max-width: 800px; font-weight: 300; line-height: 1.8;" data-aos="fade-up" data-aos-delay="400">
                <?php echo wp_kses_post(get_field('artisans_hero_subtitle') ?: 'Before this piece found you, it lived in someone\'s hands. Hands that are steady, skilled, and full of stories. They are our artisans whose skills have been passed down through generations. We think it is time you met them.'); ?>
            </p>
        </div>     
       
    </section>

    <!-- SECTION 1: WHY ARTISANS MATTER -->
    <section class=" my-md-5" data-aos="fade-up">
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-lg-0 reveal">
                   <span class="eyebrow text-overlay mb-3 d-block" data-aos="fade-up" data-aos-delay="100">
                        <?php echo wp_kses_post(get_field('artisans_intro_eyebrow') ?: ''); ?>
                    </span>
                    <h2 class="headline mb-4 display-6 text-white" data-aos="fade-up" data-aos-delay="200">
                        <?php echo wp_kses_post(get_field('artisans_intro_title') ?: 'Without Them,<br>There Is No LOOMAAMA.'); ?>
                    </h2>
                    <h5 class="headline text-overlay mb-4" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('artisans_intro_subheading') ?: 'The Artisan Is the Brand.'); ?>
                    </h5>
                </div>
                <div class="col-lg-6 offset-lg-1 reveal">
                    <div class="text-overlay fs-5 lh-lg" data-aos="fade-up" data-aos-delay="400">
                        <?php echo wp_kses_post(get_field('artisans_intro_text') ?: '<p>There is a genius individual behind each garment. Not a production line. A person. Someone who learned this craft from the people they loved most, who has spent years understanding how fiber moves, how thread behaves, how a loom speaks. Their skill is not a background story of LOOMAAMA. It is the foundation of it.</p><p class="fw-medium">No machine makes what they make. No factory holds what they know. And no garment carries meaning the way theirs does. This is why the artisan is everything to us. Not a supplier. Not a worker. A partner, a master, and the reason every LOOMAAMA piece feels the way it does.</p>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: ARTISAN PROFILES - LOADED FROM CUSTOM POST TYPE -->
    <section style="background: var(--bg-card);" data-aos="fade-up">
        <div class="container">
            <div class="row text-center mb-5 reveal">
                <div class="col-12">
                    <h2 class="headline display-6" data-aos="fade-up" data-aos-delay="100">
                        <?php echo wp_kses_post(get_field('artisans_profiles_title') ?: 'Meet the Masters'); ?>
                    </h2>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="swiper artisan-slider" data-aos="fade-up">
                <div class="swiper-wrapper">
                <?php 
                $delay = '';
                $featured_artisans = get_field('featured_artisans');
                
                if ($featured_artisans && !empty($featured_artisans)):
                    foreach ($featured_artisans as $key => $post):
                        setup_postdata($post);
                        $delay = 100 + ($key * 100);
                        
                        // Get artisan metadata from SCF
                        $artisan_id = $post->ID;
                        $years_crafting = get_post_meta($artisan_id, 'years_crafting', true);
                        $apprentices = get_post_meta($artisan_id, 'apprentices', true);
                        $quote = get_post_meta($artisan_id, 'quote', true);
                        
                        // Get taxonomies
                        $type_terms = get_the_terms($artisan_id, 'artisan-type');
                        $location_terms = get_the_terms($artisan_id, 'artisan-location');
                        
                        $artisan_type = ($type_terms && !is_wp_error($type_terms)) ? $type_terms[0]->name : 'Artisan';
                        $artisan_location = ($location_terms && !is_wp_error($location_terms)) ? $location_terms[0]->name : '';
                        
                        // Get featured image
                        $featured_image = get_the_post_thumbnail_url($artisan_id, 'loomaama-product-medium');
                ?>
                    <div class="swiper-slide" >
                        <div class="artisan-card h-100 d-flex flex-column">
                            <div class="artisan-img-wrapper" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay + 100); ?>">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if ($featured_image) : ?>
                                        <img src="<?php echo esc_url($featured_image); ?>" class="artisan-img" alt="<?php the_title_attribute(); ?>" style="aspect-ratio: 1 / 1;">
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-artisan.jpg'); ?>" class="artisan-img" alt="<?php the_title_attribute(); ?>">
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="p-3 p-xl-3 d-flex flex-column flex-grow-1">
                                <div class="mb-3 border-bottom pb-3">
                                    <h4 class="headline mb-1" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay + 200); ?>">
                                        <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none"><?php the_title(); ?></a>
                                    </h4>
                                    <p class=" small text-uppercase letter-spacing-1 mb-0 fw-bold">
                                        <?php echo wp_kses_post($artisan_type); ?>
                                        <?php if ($artisan_location): ?>
                                            &bull; <?php echo wp_kses_post($artisan_location); ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                
                                <!-- Stats -->
                                <?php if ($years_crafting || $apprentices): ?>
                                <div class="d-flex gap-3 mb-3">
                                    <?php if ($years_crafting): ?>
                                    <div class="text-center">
                                        <span class="d-block h6 mb-0 fw-bold"><?php echo wp_kses_post($years_crafting); ?></span>
                                        <span class="small text-muted"><?php _e('Years', 'loomaama'); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($apprentices): ?>
                                    <div class="text-center">
                                        <span class="d-block h6 mb-0 fw-bold"><?php echo wp_kses_post($apprentices); ?></span>
                                        <span class="small text-muted"><?php _e('Apprentices', 'loomaama'); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                
                                <p class="lh-lg mb-3 flex-grow-1" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay + 300); ?>">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                </p>
                                
                                <?php if ($quote): ?>
                                <div class="artisan-quote small text-muted font-italic mb-3" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay + 400); ?>">
                                    <?php echo wp_trim_words($quote, 12, '...'); ?>
                                </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline-dark rounded-0 mt-2 w-100 py-2 text-uppercase letter-spacing-wide" style="font-size: 11px;" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay + 500); ?>">
                                    <?php _e('Discover Artisan', 'loomaama'); ?>
                                    
                                </a>
                            </div>
                        </div>
                    </div>
                <?php 
                    endforeach;
                    wp_reset_postdata();?>
                    </div>
                <?php else:
                    // Fallback: Show latest artisans if none are featured
                    $latest_artisans = new WP_Query(array(
                        'post_type' => 'artisan',
                        'posts_per_page' => 6,
                        'post_status' => 'publish',
                    ));
                    
                    if ($latest_artisans->have_posts()):
                        while ($latest_artisans->have_posts()): $latest_artisans->the_post();
                            $delay = 100 + ($latest_artisans->current_post * 100);
                            $artisan_id = get_the_ID();
                            $years_crafting = get_post_meta($artisan_id, 'years_crafting', true);
                            $apprentices = get_post_meta($artisan_id, 'apprentices', true);
                            $quote = get_post_meta($artisan_id, 'quote', true);
                            $type_terms = get_the_terms($artisan_id, 'artisan-type');
                            $location_terms = get_the_terms($artisan_id, 'artisan-location');
                            $artisan_type = ($type_terms && !is_wp_error($type_terms)) ? $type_terms[0]->name : 'Artisan';
                            $artisan_location = ($location_terms && !is_wp_error($location_terms)) ? $location_terms[0]->name : '';
                            $featured_image = get_the_post_thumbnail_url($artisan_id, 'loomaama-product-medium');
                ?>
                <div class="swiper-slide" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
                    <div class="artisan-card h-100 d-flex flex-column">
                        <div class="artisan-img-wrapper">
                            <a href="<?php the_permalink(); ?>">
                                <?php if ($featured_image): ?>
                                    <img src="<?php echo esc_url($featured_image); ?>" class="artisan-img" alt="<?php the_title_attribute(); ?>" style="aspect-ratio: 1 / 1;">
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1544441892-794166f1e310?q=80&w=800&auto=format&fit=crop" class="artisan-img" alt="<?php the_title_attribute(); ?>">
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="p-3 p-xl-3 d-flex flex-column flex-grow-1">
                            <div class="mb-3 border-bottom pb-3">
                                <h4 class="headline mb-1"><?php the_title(); ?></h4>
                                <p class="text-secondary small text-uppercase letter-spacing-1 mb-0 fw-bold">
                                    <?php echo wp_kses_post($artisan_type); ?>
                                    <?php if ($artisan_location): ?>
                                        &bull; <?php echo wp_kses_post($artisan_location); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <?php if ($years_crafting || $apprentices): ?>
                            <div class="d-flex gap-3 mb-3">
                                <?php if ($years_crafting): ?>
                                <div class="text-center">
                                    <span class="d-block h6 mb-0 fw-bold"><?php echo wp_kses_post($years_crafting); ?></span>
                                    <span class="small text-muted">Years Crafting</span>
                                </div>
                                <?php endif; ?>
                                <?php if ($apprentices): ?>
                                <div class="text-center">
                                    <span class="d-block h6 mb-0 fw-bold"><?php echo wp_kses_post($apprentices); ?></span>
                                    <span class="small text-muted">Apprentices</span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <p class="text-secondary lh-lg mb-3 flex-grow-1"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                            <?php if ($quote): ?>
                            <div class="artisan-quote small text-muted font-italic mb-3">"<?php echo wp_trim_words($quote, 12, '...'); ?></div>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-dark rounded-0 mt-2 w-100 py-2 text-uppercase letter-spacing-wide" style="font-size: 11px;">
                                <?php _e('Discover Artisan', 'loomaama'); ?>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                        endwhile;
                        wp_reset_postdata();
                    endif;
                endif;
                ?>
                    </div>
                    <div class="swiper-pagination mt-4 position-relative"></div>
                    <div class="carousel-nav d-flex justify-content-center gap-2 mt-4">
                        <button class="btn-artisan-prev btn btn-outline-dark btn-sm p-2" aria-label="Previous artisan slide">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn-artisan-next btn btn-outline-dark btn-sm p-2" aria-label="Next artisan slide">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <?php $delay = isset($delay) ? $delay + 100 : 100; ?>
            <div class="text-center mt-5 reveal" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
                <a href="<?php echo esc_url(get_field('artisans_profiles_button_link') ?: get_post_type_archive_link('artisan')); ?>" class="btn btn-outline-dark px-5 py-3 btn-lux headline">
                    <?php echo wp_kses_post(get_field('artisans_profiles_button_text') ?: 'See All Artisan Stories'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION 3: THE PROFIT-SHARING MODEL -->
    <section class=" my-md-5" data-aos="fade-up">
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-lg-12 mb-5 mb-lg-0 reveal" data-aos="fade-up">
                   <span class="eyebrow text-overlay mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo wp_kses_post(get_field('profit_eyebrow') ?: ''); ?></span>
                    <h2 class="headline text-white display-6 mb-4" data-aos="fade-up" data-aos-delay="200"><?php echo wp_kses_post(get_field('profit_title') ?: 'Where Your Money Goes'); ?></h2>
                    <h5 class="headline text-overlay mb-4" data-aos="fade-up" data-aos-delay="300"><?php echo wp_kses_post(get_field('profit_subtitle') ?: 'Fair by Design. Transparent by Choice.'); ?></h5>
                    <div class="text-overlay lh-lg mb-4" data-aos="fade-up" data-aos-delay="400">
                        <?php echo wp_kses_post(get_field('profit_text') ?: '<p>LOOMAAMA operates on a transparent profit-sharing model. Here\'s exactly how your purchase is distributed. This is not charity. This is respect made visible.</p><p>Every purchase travels further than you might expect. Back to a weaver in the highlands of Nepal. Back to the hands that spent days on your garment. Back to a family that is a little more stable because of the choice you made today.</p><p>We built our pricing around one belief. The people who create the most should benefit the most. Not as a gesture. Not as a promise. As a practice, built into every single transaction from the beginning.</p>'); ?>
                    </div>
                </div>
                <?php /*
                <div class="col-lg-6 offset-lg-1 reveal" data-aos="fade-up">
                    <div class="p-4 p-md-5" style="background: #fff; border: 1px solid var(--border); box-shadow: 10px 10px 0px var(--bg-editorial);">
                        <h4 class="headline mb-5 text-center"><?php echo wp_kses_post(get_field('profit_breakdown_title') ?: 'Revenue Breakdown'); ?></h4>
                        
                        <?php 
                        $profit_artisan = get_field('profit_artisan') ?: 50;
                        $profit_ops = get_field('profit_ops') ?: 25;
                        $profit_growth = get_field('profit_growth') ?: 15;
                        $profit_founder = get_field('profit_founder') ?: 10;
                        ?>
                        <div class="profit-breakdown">
                            <div class="profit-item" data-aos="fade-up" data-aos-delay="100">
                                <div class="profit-header">
                                    <span><?php echo wp_kses_post(get_field('profit_artisan_label') ?: 'Artisan Wages + Materials'); ?></span>
                                    <span class="fw-bold" style="color: var(--wild-nettle);"><?php echo $profit_artisan; ?>%</span>
                                </div>
                                <div class="profit-bar-bg">
                                    <div class="profit-bar-fill artisan" style="width: <?php echo $profit_artisan; ?>%;"></div>
                                </div>
                                <small class="text-secondary mt-1"><?php echo wp_kses_post(get_field('profit_artisan_description') ?: 'The honest minimum for truly fair trade.'); ?></small>
                            </div>

                            <div class="profit-item" data-aos="fade-up" data-aos-delay="200">
                                <div class="profit-header">
                                    <span><?php echo wp_kses_post(get_field('profit_ops_label') ?: 'Operations & Logistics'); ?></span>
                                    <span class="fw-bold" style="color: #A69177;"><?php echo $profit_ops; ?>%</span>
                                </div>
                                <div class="profit-bar-bg">
                                    <div class="profit-bar-fill ops" style="width: <?php echo $profit_ops; ?>%;"></div>
                                </div>
                                <small class="text-secondary mt-1"><?php echo wp_kses_post(get_field('profit_ops_description') ?: 'Shipping, packaging, and everyday running costs.'); ?></small>
                            </div>

                            <div class="profit-item" data-aos="fade-up" data-aos-delay="300">
                                <div class="profit-header">
                                    <span><?php echo wp_kses_post(get_field('profit_growth_label') ?: 'Brand Growth & Marketing'); ?></span>
                                    <span class="fw-bold" style="color: #8A8A8A;"><?php echo $profit_growth; ?>%</span>
                                </div>
                                <div class="profit-bar-bg">
                                    <div class="profit-bar-fill brand" style="width: <?php echo $profit_growth; ?>%;"></div>
                                </div>
                                <small class="text-secondary mt-1"><?php echo wp_kses_post(get_field('profit_growth_description') ?: 'Reinvestment to reach more patrons.'); ?></small>
                            </div>

                            <div class="profit-item" data-aos="fade-up" data-aos-delay="400">
                                <div class="profit-header">
                                    <span><?php echo wp_kses_post(get_field('profit_founder_label') ?: 'Founder Margin'); ?></span>
                                    <span class="fw-bold" style="color: #4A4A4A;"><?php echo $profit_founder; ?>%</span>
                                </div>
                                <div class="profit-bar-bg">
                                    <div class="profit-bar-fill founder" style="width: <?php echo $profit_founder; ?>%;"></div>
                                </div>
                                <small class="text-secondary mt-1"><?php echo wp_kses_post(get_field('profit_founder_description') ?: 'Sustainable business management.'); ?></small>
                            </div>
                        </div>

                        <div class="stat-box" data-aos="fade-up" data-aos-delay="500">
                            <h5 class="headline mb-0 text-uppercase" style="letter-spacing: 0.1em; font-size: 1rem;">Average Artisan Wage</h5>
                            <p class="display-6 headline mt-2 mb-0 fw-bold" style="color: var(--wild-nettle);"><?php echo wp_kses_post(get_field('wage_multiplier_value') ?: '3x'); ?></p>
                            <p class="text-secondary mb-0 mt-1"><?php echo wp_kses_post(get_field('wage_multiplier_text') ?: 'Local minimum wage'); ?></p>
                        </div>
                    </div>
                </div>
                */?>
            </div>
        </div>
    </section>

   
   <!-- SECTION 4: COMMUNITY IMPACT -->
<section class="" style="background: var(--bg-card);" data-aos="fade-up">
    <div class="container ">
        <div class="row align-items-center flex-lg-row-reverse">
            <div class="col-lg-5 mb-5 mb-lg-0 reveal" data-aos="fade-up">
                <span class="eyebrow text-overlay mb-3 d-block"><?php echo wp_kses_post(get_field('community_eyebrow') ?: ''); ?></span>
                <h2 class="headline display-6 mb-4"data-aos="fade-up" data-aos-delay="200"><?php echo wp_kses_post(get_field('community_title') ?: 'Beyond the Garment.'); ?></h2>
                <h5 class="headline text-secondary mb-4" data-aos="fade-up" data-aos-delay="300"><?php echo wp_kses_post(get_field('community_subtitle') ?: 'Your Garment Did More Than You Know.'); ?></h5>
                <div class="text-secondary lh-lg mb-4" data-aos="fade-up" data-aos-delay="400">
                    <?php echo wp_kses_post(get_field('community_text') ?: '<p>A single garment touches more lives than you might imagine. When your piece was made, a family received fair wages. A child stayed in school. A weaver invested in her tools. An ancient craft found a reason to continue.</p><p>Your purchase not only motivated the artisan but it moved through an entire community, quietly and completely, the way all good things do.</p>'); ?>
                </div>
                
                <div class="my-5">
                    <p class="headline fw-bold mb-3" data-aos="fade-up" data-aos-delay="500"><?php echo wp_kses_post(get_field('community_impact_list_title') ?: 'In the past year, LOOMAAMA purchases have:'); ?></p>
                    <ul class="conditions-list mb-0">
                        <?php 
                        $impacts = get_field('community_impact');
                        if ($impacts):
                            foreach ($impacts as $key => $impact):
                                $delay = 500 + (($key + 1) * 100);
                        ?>
                        <li data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>"><i class="<?php echo esc_attr($impact['icon']); ?>"></i> <?php echo wp_kses_post($impact['text']); ?></li>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </div>
                
                <p class="headline fw-bold" style="letter-spacing: 0.05em;" data-aos="fade-up" data-aos-delay="500"><?php echo wp_kses_post(get_field('community_footer_text') ?: 'This is what ethical fashion looks like in practice.'); ?></p>
            </div>
            
            <div class="col-lg-6 reveal" data-aos="fade-up" data-aos-delay="200">
                <?php $community_image = get_field('community_image'); ?>
                <img src="<?php echo esc_url($community_image ?: 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1000&auto=format&fit=crop'); ?>" class="img-fluid w-100" style="object-fit: cover; height: 700px;" alt="<?php echo esc_attr(get_field('community_image_alt') ?: 'Community Gathering Nepal'); ?>">
            </div>
        </div>
    </div>
</section>

<!-- SECTION 5: THE APPRENTICESHIP PROGRAM -->
<section class=" my-md-5" data-aos="fade-up" data-aos-delay="200">
    <div class="container ">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0 reveal">
                <span class="eyebrow text-overlay mb-3 d-block" data-aos="fade-up" data-aos-delay="200"><?php echo wp_kses_post(get_field('apprentice_eyebrow') ?: ''); ?></span>
                <h2 class="headline text-white display-6 mb-4"  data-aos="fade-up" data-aos-delay="300"><?php echo wp_kses_post(get_field('apprentice_title') ?: 'Passing Down the Craft.'); ?></h2>
                <h5 class="headline text-overlay mb-4" data-aos="fade-up" data-aos-delay="400"><?php echo wp_kses_post(get_field('apprentice_subtitle') ?: 'The Thread Continues.'); ?></h5>
                <div class="text-overlay lh-lg mb-4" data-aos="fade-up" data-aos-delay="500">
                    <?php echo wp_kses_post(get_field('apprentice_text') ?: '<p>Traditional textile skills are disappearing as younger generations leave for the cities, taking centuries of knowledge with them. LOOMAAMA\'s apprenticeship program brings them back to the loom.</p><p>Young artisans are paired with master weavers and given a reason to stay. Because this craft is not just a livelihood. It is a legacy, and it deserves to live on.</p>'); ?>
                </div>
                <div class="p-4 border mt-4" style="border-color: var(--border) !important;" data-aos="fade-up" data-aos-delay="600">
                    <p class="headline text-white fs-5 mb-0 text-center">So far, <strong><?php echo wp_kses_post(get_field('apprentice_number') ?: '25'); ?> <?php echo wp_kses_post(get_field('apprentice_stat_text') ?: 'apprentices have completed training and are now earning income through their craft.'); ?></strong></p>
                </div>
            </div>
            
            <div class="col-lg-6 offset-lg-1 reveal" data-aos="fade-up" data-aos-delay="200">
                <?php $apprentice_image = get_field('apprentice_image'); ?>
                <img src="<?php echo esc_url($apprentice_image ?: 'https://images.unsplash.com/photo-1584447128309-b66b7a4d1b63?q=80&w=1000&auto=format&fit=crop'); ?>" class="img-fluid w-100" style="object-fit: cover; height: 500px;" alt="<?php echo esc_attr(get_field('apprentice_image_alt') ?: 'Master teaching apprentice'); ?>">
            </div>
        </div>
    </div>
</section>

   
<!-- SECTION 6: WORKING CONDITIONS -->
<section class="" style="background: var(--bg-card); border-top: 1px solid var(--border);" data-aos="fade-up" data-aos-delay="200">
    <div class="container ">
        <div class="row justify-content-center reveal">
            <div class="col-lg-8 text-center">
                <h2 class="headline display-6 mb-4" data-aos="fade-up" data-aos-delay="300"><?php echo wp_kses_post(get_field('standards_title') ?: 'How We Work'); ?></h2>
                <p class="text-secondary lh-lg mb-5" data-aos="fade-up" data-aos-delay="400">
                    <?php echo wp_kses_post(get_field('standards_text') ?: 'LOOMAAMA artisans work from home, on their own terms. They set their own hours, move at their own pace, and are paid per piece. No quotas. No factory floors. Just skilled hands working with dignity.'); ?>
                </p>
            </div>
        </div>
        
        <div class="row justify-content-center reveal" data-aos="fade-up" data-aos-delay="200">
            <div class="col-lg-6">
                <div class="bg-white p-4 p-md-5 border" style="border-color: var(--border) !important; box-shadow: 10px 10px 0px rgba(0,0,0,0.03);">
                    <h5 class="headline display-6 mb-4 text-uppercase letter-spacing-1 border-bottom pb-3" data-aos="fade-up" data-aos-delay="500"><?php echo wp_kses_post(get_field('standards_list_title') ?: 'Our Standards'); ?></h5>
                    <ul class="conditions-list mb-0 font-family-base fw-medium">
                        <?php 
                        $standards = get_field('standards_list');
                        if ($standards):
                            foreach ($standards as $key => $standard):
                                $delay = 500 + (($key + 1) * 100);
                        ?>
                        <li data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>"><i class="fa-solid fa-check"></i> <?php echo wp_kses_post($standard['item']); ?></li>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <li><i class="fa-solid fa-check"></i> Fair wages (3x local minimum)</li>
                        <li><i class="fa-solid fa-check"></i> Flexible schedules</li>
                        <li><i class="fa-solid fa-check"></i> Safe working conditions</li>
                        <li><i class="fa-solid fa-check"></i> No child labor</li>
                        <li><i class="fa-solid fa-check"></i> Transparent contracts</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CLOSING CTA -->
<section class="py-5 text-center" data-aos="fade-up" data-aos-delay="200">
    <div class="container  reveal">
        <span class="eyebrow text-white mb-3 d-block" data-aos="fade-up" data-aos-delay="300"><?php echo wp_kses_post(get_field('closing_eyebrow') ?: ''); ?></span>
        <h2 class="headline text-white display-5 mb-4" data-aos="fade-up" data-aos-delay="400"><?php echo wp_kses_post(get_field('closing_title') ?: 'The Impact Starts With You.'); ?></h2>
        <h5 class="headline text-overlay mb-4" data-aos="fade-up" data-aos-delay="500"><?php echo wp_kses_post(get_field('closing_subtitle') ?: 'Wear It. Feel It. Change It.'); ?></h5>
        <p class="text-overlay mx-auto mb-5 lh-lg" style="max-width: 700px;" data-aos="fade-up" data-aos-delay="600">
            <?php echo wp_kses_post(get_field('closing_text') ?: 'When you choose LOOMAAMA, you are choosing to stand behind skilled artisans, keep traditional craft alive, and be part of an economic model that puts people before profit. Your garment is proof that fashion can work differently. And it starts with you.'); ?>
        </p>
        <a href="<?php echo esc_url(get_field('closing_button_link') ?: wc_get_page_permalink('shop')); ?>" class="cta-button tn btn-dark px-5 py-3 btn-lux headline fs-6" data-aos="fade-up" data-aos-delay="700">
            <?php echo wp_kses_post(get_field('closing_button_text') ?: 'Shop the Collection'); ?>
        </a>
    </div>
</section>

</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure jarallax and video plugin are loaded
    if (typeof jarallax !== 'undefined') {
        // Re-initialize jarallax with video settings
        var elements = document.querySelectorAll('.jarallax');
        
        elements.forEach(function(element) {
            // Destroy existing instance if any
            if (element.jarallax) {
                element.jarallax.destroy();
            }
            
            // Initialize with video settings
            jarallax(document.querySelectorAll('.jarallax'), {
                speed: 0,
            });
        });
    }
    
    
});
</script>

<?php get_footer(); ?>