<?php
/**
 * Template Name: Packaging Template
 *
 * @package Loomaama
 */

get_header();
?>

<main>
    <!-- HERO SECTION (from packaging.html) -->
    <header class="shipping-hero">
        <div class="shipping-hero-copy">
            <div class="eyebrow"><?php echo esc_html(get_field('shipping_hero_eyebrow') ?: 'Packaging'); ?></div>
            <h1><?php echo wp_kses_post(get_field('shipping_hero_title') ?: 'The Last Hand'); ?></h1>
            <p><?php echo wp_kses_post(get_field('shipping_hero_subtitle') ?: 'Nothing is rushed. Nothing is excessive. Only the final care a rare cloth deserves.'); ?></p>
        </div>
        <div class="shipping-hero-stage">
            <?php $hero_layer_a = get_field('shipping_hero_layer_a'); ?>
            <?php $hero_layer_b = get_field('shipping_hero_layer_b'); ?>
            <?php $hero_layer_c = get_field('shipping_hero_layer_c'); ?>
            <div class="package-layer layer-a" style="background-image: url('<?php echo esc_url($hero_layer_a ?: 'https://images.unsplash.com/photo-1515488764276-beab7607c1e6?auto=format&fit=crop&w=1200&q=80'); ?>');"></div>
            <div class="package-layer layer-b" style="background-image: url('<?php echo esc_url($hero_layer_b ?: 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1100&q=80'); ?>');"></div>
            <div class="layer-note" style="background-image: url('<?php echo esc_url($hero_layer_c ?: 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1100&q=80'); ?>');"></div>
        </div>
    </header>

    <!-- MANIFESTO SECTION -->
    <section class="shipping-manifesto">
        <div class="manifesto-card">
            <div class="eyebrow"><?php echo esc_html(get_field('manifesto_eyebrow') ?: 'Packaging Philosophy'); ?></div>
            <p><?php echo wp_kses_post(get_field('manifesto_text') ?: 'The wrapping is quiet because the garment carries the story.'); ?></p>
        </div>
        <div class="manifesto-image" style="background-image: url('<?php echo esc_url(get_field('manifesto_image') ?: 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=1500&q=80'); ?>');"></div>
    </section>

    <!-- SECTION 1: THE PACKAGING (kept from original) -->
    <section class="shipping-section my-md-5">
        <div class="container" data-aos="fade-up">
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

    <!-- ANATOMY SECTION (from packaging.html) -->
    <section class="shipping-anatomy">
        <div class="anatomy-grid">
            <div class="anatomy-visual">
                <div class="sheet one"></div>
                <div class="sheet two"></div>
                <div class="sheet three"></div>
                <div class="callout c1"><?php echo esc_html(get_field('anatomy_callout_1') ?: 'Outer Protection'); ?></div>
                <div class="callout c2"><?php echo esc_html(get_field('anatomy_callout_2') ?: 'Soft Inner Layer'); ?></div>
                <div class="callout c3"><?php echo esc_html(get_field('anatomy_callout_3') ?: 'Garment Resting Space'); ?></div>
            </div>
            <div>
                <div class="eyebrow"><?php echo esc_html(get_field('anatomy_eyebrow') ?: 'Anatomy of Arrival'); ?></div>
                <h2><?php echo wp_kses_post(get_field('anatomy_title') ?: 'Every layer has a job.'); ?></h2>
                <div class="anatomy-list">
                    <?php 
                    $anatomy_items = get_field('anatomy_items');
                    if ($anatomy_items):
                        foreach ($anatomy_items as $item):
                    ?>
                    <div class="anatomy-row">
                        <h3><?php echo esc_html($item['title']); ?></h3>
                        <p><?php echo esc_html($item['description']); ?></p>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <div class="anatomy-row"><h3>Protect the fibre</h3><p>Himalayan nettle cloth is kept safe from pressure, friction, and unnecessary handling.</p></div>
                    <div class="anatomy-row"><h3>Respect the fold</h3><p>The garment is placed so its natural fall is not forced flat.</p></div>
                    <div class="anatomy-row"><h3>Reduce the noise</h3><p>No loud excess. No meaningless decoration. Just what the garment needs.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- TACTILE GRID (from packaging.html) -->
    <section class="shipping-tactile">
        <?php 
        $tactile_items = get_field('tactile_items');
        if ($tactile_items):
            foreach ($tactile_items as $item):
        ?>
        <div class="tile" style="background-image: url('<?php echo esc_url($item['image']); ?>');">
            <span><?php echo esc_html($item['label']); ?></span>
        </div>
        <?php 
            endforeach;
        else:
        ?>
        <div class="tile t1" style="background-image: url('https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80');"><span>Cloth</span></div>
        <div class="tile t2" style="background-image: url('https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=900&q=80');"><span>Fold</span></div>
        <div class="tile t3" style="background-image: url('https://images.unsplash.com/photo-1515488764276-beab7607c1e6?auto=format&fit=crop&w=900&q=80');"><span>Texture</span></div>
        <div class="tile t4" style="background-image: url('https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=900&q=80');"><span>Arrival</span></div>
        <?php endif; ?>
    </section>

    <!-- RECEIPT / WHAT WE CHOOSE SECTION (from packaging.html) -->
    <section class="shipping-receipt">
        <div class="receipt-wrap">
            <div>
                <div class="eyebrow"><?php echo esc_html(get_field('receipt_eyebrow') ?: 'What We Choose'); ?></div>
                <h2><?php echo wp_kses_post(get_field('receipt_title') ?: 'A quieter kind of luxury.'); ?></h2>
                <p><?php echo wp_kses_post(get_field('receipt_text') ?: 'Each layer is chosen with care, so the garment arrives protected, composed, and ready to be lived in.'); ?></p>
            </div>
            <div class="receipt-card">
                <?php 
                $receipt_items = get_field('receipt_items');
                if ($receipt_items):
                    foreach ($receipt_items as $item):
                ?>
                <div class="receipt-line"><strong><?php echo esc_html($item['label']); ?></strong><span><?php echo esc_html($item['value']); ?></span></div>
                <?php 
                    endforeach;
                else:
                ?>
                <div class="receipt-line"><strong>Excess</strong><span>Reduced</span></div>
                <div class="receipt-line"><strong>Branding</strong><span>Quiet</span></div>
                <div class="receipt-line"><strong>Touch</strong><span>Soft</span></div>
                <div class="receipt-line"><strong>Purpose</strong><span>Protection</span></div>
                <div class="receipt-line"><strong>Finish</strong><span>Considered</span></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- LARGE TEXT SECTION (from packaging.html) -->
    <section class="shipping-large-text">
        <h2><?php echo wp_kses_post(get_field('large_text_quote') ?: 'The first touch should feel as considered as the last stitch.'); ?></h2>
    </section>

    <!-- SECTION 2: GLOBAL SHIPPING (kept from original - moved before path) -->
    <section class="shipping-global">
        <i class="fa-solid fa-earth-americas decorative-globe"></i>
        <div class="container position-relative z-2">
            <div class="row align-items-center text-center text-lg-start">
                <div class="col-lg-6 reveal">
                    <span class="eyebrow mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('shipping_eyebrow') ?: 'Borderless Patronage'); ?></span>
                    <h2 class="headline display-6 mb-4" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('shipping_title') ?: 'Delivered with Grace.'); ?></h2>
                </div>
                <div class="col-lg-6 reveal">
                    <div class="fs-5 lh-lg mb-0" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('shipping_description') ?: '<p>True luxury does not penalize distance. We offer <strong class="text-white">complimentary global shipping</strong> on all orders.</p><p>Your investment in our artisans covers the entire journey. You will receive full tracking details to follow its path from Kathmandu directly to your door.</p>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: THE TIMELINE (kept from original - moved before path) -->
    <section class="shipping-timeline">
        <div class="container">
            <div class="row text-center mb-5 reveal">
                <div class="col-12">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('timeline_eyebrow') ?: 'The Pace of the Forest'); ?></span>
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
                            <?php echo wp_kses_post(get_field('ready_harvest_text') ?: 'Items currently in our studio, crafted from recent harvests, will depart Kathmandu within <strong class="text-dark">48 hours</strong> of your purchase.'); ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="timeline-card text-center" data-aos="fade-up" data-aos-delay="800">
                        <i class="<?php echo esc_attr(get_field('made_to_order_icon') ?: 'fa-solid fa-hourglass-half'); ?> timeline-icon" data-aos="fade-up" data-aos-delay="900"></i>
                        <h4 class="headline mb-3" data-aos="fade-up" data-aos-delay="1000"><?php echo esc_html(get_field('made_to_order_title') ?: 'Made to Order'); ?></h4>
                        <p class="text-secondary lh-lg mb-0" data-aos="fade-up" data-aos-delay="1100">
                            <?php echo wp_kses_post(get_field('made_to_order_text') ?: 'For pieces that require dedicated weaving upon request, we will communicate the <strong class="text-dark">exact timeline</strong> to you immediately following your purchase.'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PATH / JOURNEY SECTION (from packaging.html) -->
    <section class="shipping-path ">
        <?php 
        $path_steps = get_field('path_steps');
        if ($path_steps):
            foreach ($path_steps as $step):
        ?>
        <div class="path-step">
            <div class="num"><?php echo esc_html($step['number']); ?></div>
            <h3><?php echo esc_html($step['title']); ?></h3>
            <p><?php echo esc_html($step['description']); ?></p>
        </div>
        <?php 
            endforeach;
        else:
        ?>
        <div class="path-step"><div class="num">01</div><h3>Check</h3><p>The garment is inspected before preparation.</p></div>
        <div class="path-step"><div class="num">02</div><h3>Rest</h3><p>The cloth is given space before folding.</p></div>
        <div class="path-step"><div class="num">03</div><h3>Fold</h3><p>The shape is protected without forcing the fabric.</p></div>
        <div class="path-step"><div class="num">04</div><h3>Wrap</h3><p>Only the needed layers are added.</p></div>
        <div class="path-step"><div class="num">05</div><h3>Send</h3><p>The final hand passes it to yours.</p></div>
        <?php endif; ?>
    </section>

    <!-- FINAL SECTION (from packaging.html) -->
    <section class="shipping-final bg-card">
        <div class="final-img" style="background-image: url('<?php echo esc_url(get_field('final_image') ?: 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1400&q=80'); ?>');"></div>
        <div class="final-copy">
            <div class="eyebrow"><?php echo esc_html(get_field('final_eyebrow') ?: 'Packaging Statement'); ?></div>
            <h2><?php echo wp_kses_post(get_field('final_title') ?: 'The garment is the centre.'); ?></h2>
            <p><?php echo wp_kses_post(get_field('final_text') ?: 'The garment remains the focus. Everything around it exists only to protect, preserve, and honour what has already been made.'); ?></p>
            <a class="shipping-button" href="<?php echo esc_url(get_field('final_button_link') ?: wc_get_page_permalink('shop')); ?>"><?php echo esc_html(get_field('final_button_text') ?: 'Become a Witness'); ?></a>
        </div>
    </section>

</main>

<?php get_footer(); ?>