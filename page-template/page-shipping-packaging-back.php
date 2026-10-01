<?php
/**
 * Template Name: Shipping & Packaging
 *
 * @package Loomaama
 */

get_header();
?>
   <?php $hero_image = get_field('shipping_hero_image'); ?>
<main>
    <!-- HERO SECTION -->
    <section class="hero-editorial jarallax" <?php echo $hero_image ? 'style="background-image: url(\'' . esc_url($hero_image) . '\');"' : ''; ?>>
        <div class="hero-editorial-content reveal" data-aos="zoom-in" >
            <span class="eyebrow mb-4 d-block" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('shipping_hero_eyebrow') ?: ''); ?></span>
            <h1 class="headline display-3 mb-4 fw-bold" data-aos="fade-up" data-aos-delay="300"><?php echo esc_html(get_field('shipping_hero_title') ?: 'The Final Journey.'); ?></h1>
            <p class="fs-5 mx-auto mt-4" style="max-width: 700px; font-weight: 300; line-height: 1.8;" data-aos="fade-up" data-aos-delay="400">
                <?php echo esc_html(get_field('shipping_hero_subtitle') ?: 'How your garment travels from the Himalayas to your home. Zero plastic. Zero rush. Just quiet intention.'); ?>
            </p>
        </div>
     
      
    </section>

    <!-- SECTION 1: THE PACKAGING -->
    <section class=" my-md-5">
        <div class="container " data-aos="fade-up">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0 reveal">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('packaging_eyebrow') ?: ''); ?></span>
                    <h2 class="headline mb-4 display-6" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('packaging_title') ?: 'Wrapped in the Forest.'); ?></h2>
                    <div class="text-secondary fs-5 lh-lg mb-0" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('packaging_description') ?: '<p>We do not wrap natural masterpieces in synthetic plastic. Your garment arrives in a breathable sanctuary.</p><p>We use handmade Nepalese Lokta paper, secured with raw hemp twine, inside a fully compostable mailer. When you open your parcel, you will feel the texture of the mountains before you even touch the garment.</p>'); ?>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1 reveal" data-aos="fade-up" data-aos-delay="400">
                    <div style="aspect-ratio:3/2; overflow:hidden; border-radius:20px;">
                        <?php $packaging_image = get_field('packaging_image'); ?>
                        <img src="<?php echo esc_url($packaging_image ?: 'https://images.unsplash.com/photo-1586864387789-218afec5c1fb?q=80&w=1000&auto=format&fit=crop'); ?>" class="img-fluid w-100" style="aspect-ratio: 3/2; object-fit: cover;" alt="<?php echo esc_attr(get_field('packaging_image_alt') ?: 'Eco-friendly parcel wrapped in string'); ?>">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: GLOBAL SHIPPING -->
    <section class=" global-shipping-section" data-aos="fade-up">
        <i class="fa-solid fa-earth-americas decorative-globe"></i>
        <div class="container  position-relative z-2">
            <div class="row align-items-center text-center text-lg-start">
                <div class="col-lg-6 reveal">
                    <span class="eyebrow mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('shipping_eyebrow') ?: ''); ?></span>
                    <h2 class="headline display-6 mb-4 text-white" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('shipping_title') ?: 'Delivered with Grace.'); ?></h2>
                </div>
                <div class="col-lg-6 reveal">
                    <div class="fs-5 lh-lg mb-0" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('shipping_description') ?: ''); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: THE TIMELINE -->
    <section class=" my-md-5" style="background: var(--bg-editorial);" data-aos="fade-up">
        <div class="container ">
            <div class="row text-center mb-5 reveal">
                <div class="col-12">
                   <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('timeline_eyebrow') ?: ''); ?></span>
                    <h2 class="headline display-6 mb-4" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('timeline_title') ?: 'The Anticipation of Craft.'); ?></h2>
                   <p class="text-secondary fs-5 mx-auto lh-lg" style="max-width: 700px;" data-aos="fade-up" data-aos-delay="300">
                        <?php echo esc_html(get_field('timeline_description') ?: 'LOOMAAMA operates on the rhythm of the loom, not the speed of an algorithm. True craft takes patience.'); ?>
                    </p>
                </div>
            </div>
            
            <div class="row justify-content-center reveal" data-aos="fade-up" data-aos-delay="300">
                <div class="col-md-5">
                    <div class="timeline-card text-center" data-aos="fade-up" data-aos-delay="400">
                        <i class="<?php echo esc_attr(get_field('ready_harvest_icon') ?: 'fa-solid fa-box-open'); ?> timeline-icon" data-aos="fade-up" data-aos-delay="500"></i>
                        <h4 class="headline mb-3" data-aos="fade-up" data-aos-delay="600"><?php echo esc_html(get_field('ready_harvest_title') ?: 'Ready Harvest'); ?></h4>
                        <p class="text-secondary lh-lg mb-0" data-aos="fade-up" data-aos-delay="700">
                            <?php echo esc_html(get_field('ready_harvest_text') ?: 'Items currently in our studio, crafted from recent harvests, will depart Kathmandu within <strong class="text-dark">48 hours</strong> of your purchase.'); ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="timeline-card text-center" data-aos="fade-up" data-aos-delay="800">
                      <i class="<?php echo esc_attr(get_field('made_to_order_icon') ?: 'fa-solid fa-hourglass-half'); ?> timeline-icon" data-aos="fade-up" data-aos-delay="900"></i>
                        <h4 class="headline mb-3" data-aos="fade-up" data-aos-delay="1000"><?php echo esc_html(get_field('made_to_order_title') ?: 'Made to Order'); ?></h4>
                        <p class="text-secondary lh-lg mb-0" data-aos="fade-up" data-aos-delay="1100">
                            <?php echo esc_html(get_field('made_to_order_text') ?: 'For pieces that require dedicated weaving upon request, we will communicate the <strong class="text-dark">exact timeline</strong> to you immediately following your purchase.'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: RETURNS & EXCHANGES -->
    <section class="">
        <div class="container ">
            <div class="row align-items-center flex-lg-row-reverse">
                <div class="col-lg-6 mb-5 mb-lg-0 reveal" data-aos="fade-up" data-aos-delay="300">
                    <div class="return-policy-box">
                       <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="400"><?php echo esc_html(get_field('returns_eyebrow') ?: ''); ?></span>
                        <h2 class="headline display-6 mb-4" data-aos="fade-up" data-aos-delay="500"><?php echo esc_html(get_field('returns_title') ?: 'Honest Returns.'); ?></h2>
                        <div class="text-secondary fs-5 lh-lg mb-5" data-aos="fade-up" data-aos-delay="600">
                            <?php echo wp_kses_post(get_field('returns_description') ?: '<p>We want your LOOMAAMA piece to feel completely right. We accept unworn, unwashed returns within <strong class="text-dark">14 days</strong> of arrival.</p><p>Because we do not rely on automated warehouses, we handle every return personally. We simply ask that you fold and return the item with the same care and respect in which it was sent.</p>'); ?>
                        </div>
                       <a  data-aos="fade-up" data-aos-delay="700"href="<?php echo esc_url(get_field('returns_button_link') ?: wc_get_page_permalink('myaccount')); ?>" class="btn btn-outline-dark px-4 py-3 btn-lux headline w-100 text-center"><?php echo esc_html(get_field('returns_button_text') ?: 'Initiate a Return'); ?></a>
                    </div>
                </div>
                
                <div class="col-lg-6 reveal" data-aos="fade-up" data-aos-delay="800">
                    <div style="aspect-ratio:1/1; overflow:hidden; border-radius:20px;">
                    <?php $returns_image = get_field('returns_image'); ?>
                    <img src="<?php echo esc_url($returns_image ?: 'https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=1000&auto=format&fit=crop'); ?>" class="img-fluid w-100" style="aspect-ratio: 3/4; object-fit: cover;" alt="<?php echo esc_attr(get_field('returns_image_alt') ?: 'Folded natural fabric'); ?>">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CLOSING CTA -->
    <section class=" text-center" style="background: var(--bg-editorial); border-top: 1px solid var(--border);">
        <div class="container  reveal" data-aos="fade-up" data-aos-delay="200">
            <h2 class="headline display-5 mb-4"data-aos="fade-up" data-aos-delay="300"><?php echo esc_html(get_field('closing_title') ?: 'Ready to Bring the Wild Home?'); ?></h2>
            <p class="text-secondary fs-5 mx-auto mb-5 lh-lg" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="400">
                <?php echo esc_html(get_field('closing_text') ?: 'Experience the tactile truth of pure Himalayan fiber. Delivered globally, with grace.'); ?>
            </p>
            <a data-aos="fade-up" data-aos-delay="500" href="<?php echo esc_url(get_field('closing_button_link') ?: wc_get_page_permalink('shop')); ?>" class="cta-buttonbtn btn btn-dark px-5 py-3 btn-lux headline fs-6"><?php echo esc_html(get_field('closing_button_text') ?: 'Shop the Collection'); ?></a>
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
                speed: -1.0,
            });
        });
    }
    
    
});
</script>
<?php get_footer(); ?>