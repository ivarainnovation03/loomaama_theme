<?php
/**
 * 
 *
 * @package Loomaama
 */

get_header();
?>

<main>
    <!-- HERO SECTION -->
    <section class="hero-editorial"  data-aos="zoom-in" data-aos-delay="100">
        <div class="hero-editorial-content reveal">
            <h1 data-aos="fade-up" data-aos-delay="300" class="headline display-3 mb-4 fw-bold"><?php echo wp_kses_post(get_field('care_hero_title') ?: 'Respect the Memory of Hands.'); ?></h1>
            <p class="fs-5 mx-auto" data-aos="fade-up" data-aos-delay="400" style="max-width: 600px; font-weight: 300; line-height: 1.8;">
                <?php echo wp_kses_post(get_field('care_hero_subtitle') ?: 'Your LOOMAAMA garment was made with care. Here\'s how to care for it in return.'); ?>
            </p>
        </div>
        <?php $hero_image = get_field('care_hero_image'); ?>
        <?php if ($hero_image): ?>
        <style>
            .hero-editorial { background-image: url('<?php echo esc_url($hero_image); ?>'); }
        </style>
        <?php endif; ?>
    </section>

    <!-- SECTION 1: WHY CARE MATTERS -->
    <section class="py-5 my-md-5">
        <div class="container" data-aos="fade-up">
            <div class="row align-items-center flex-lg-row-reverse">
                <div class="col-lg-6  mb-lg-0 reveal">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="200">
                        <?php echo wp_kses_post(get_field('care_section_title') ?: ''); ?>
                    </span>
                    <h2 class="headline mb-4" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('care_section_heading') ?: 'Natural Fibers Age Beautifully; If You Let Them.'); ?>
                    </h2>
                    <div class="text-secondary fs-5 lh-lg mb-4 drop-cap" data-aos="fade-up" data-aos-delay="400">
                        <?php echo wp_kses_post(get_field('care_section_text') ?: '<p>Synthetic fabrics are built for quick, repetitive washing. Natural fibers require a gentler touch. When properly maintained, they softening with wear, develop a unique character, and can last for decades.</p><p>LOOMAAMA garments are crafted to be worn for years instead of just a single season. A little mindful care guarantees they reach their full, beautiful potential.</p>'); ?>
                    </div>
                </div>
                <div class="col-lg-6 reveal"data-aos="fade-up" data-aos-delay="400">
                    <div style="aspect-ratio:3/2; overflow:hidden; border-radius:20px;">
                    <?php $section_image = get_field('care_section_image'); ?>
                    <img src="<?php echo esc_url($section_image ?: 'https://images.unsplash.com/photo-1544441892-794166f1e310?q=80&w=1000&auto=format&fit=crop'); ?>" class="img-fluid w-100" style="aspect-ratio: 3/2; object-fit: cover;" alt="Soft natural fibers">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: GENERAL CARE PRINCIPLES -->
    <section class="py-5" style="background: var(--bg-card);" data-aos="fade-up">
        <div class="container">
            <div class="row text-center mb-3 reveal">
                <div class="col-12">
                   <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('care_rules_eyebrow') ?: ''); ?></span>
                    <h2 class="headline display-5" data-aos="fade-up" data-aos-delay="300"><?php echo esc_html(get_field('care_rules_title') ?: 'The Golden Rules'); ?></h2>
                </div>
            </div>

            <div class="row justify-content-center reveal">
                <?php 
                $rules = get_field('care_rules');
                if ($rules): 
                    foreach ($rules as $key => $rule): 
                        $delay = 300 + ($key * 100);
                ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="rule-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                        <div class="rule-number"><?php echo str_pad($rule['rule_number'], 2, '0', STR_PAD_LEFT); ?></div>
                        <div class="rule-content">
                            <h4 class="headline mb-3"><?php echo esc_html($rule['rule_title']); ?></h4>
                            <p class="text-secondary lh-lg mb-0"><?php echo esc_html($rule['rule_description']); ?></p>
                        </div>
                    </div>
                </div>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
        </div>
    </section>

    <!-- SECTION 3: FIBER-SPECIFIC CARE -->
    <section class="py-5 my-md-5">
        <div class="container  reveal">
            <div class="row">
                <div class="col-12">
                    
                    <!-- Tabs Navigation -->
                    <div class="care-tabs-nav justify-content-lg-center" role="tablist">
                        <?php 
                        $fibers = get_field('care_fibers');
                        if ($fibers):
                            $first = true;
                            foreach ($fibers as $index => $fiber): 
                        ?>
                        <button class="care-tab-btn <?php echo $first ? 'active' : ''; ?>" id="nav-<?php echo sanitize_title($fiber['fiber_name']); ?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo sanitize_title($fiber['fiber_name']); ?>" type="button" role="tab"><?php echo esc_html($fiber['fiber_name']); ?></button>
                        <?php 
                            $first = false;
                            endforeach;
                        endif;
                        ?>
                    </div>

                    <!-- Tabs Content -->
                    <div class="tab-content border p-4 p-md-5" style="border-color: var(--border) !important;">
                        <?php 
                        $fibers = get_field('care_fibers');
                        if ($fibers):
                            $first = true;
                            foreach ($fibers as $index => $fiber): 
                        ?>
                        <div class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>" id="nav-<?php echo sanitize_title($fiber['fiber_name']); ?>" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-4 mb-5 mb-lg-0 pe-lg-5 border-end-lg" style="border-color: var(--border) !important;">
                                    <h2 class="headline mb-3" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html($fiber['fiber_title']); ?></h2>
                                    <div class="text-secondary lh-lg fs-5" data-aos="fade-up" data-aos-delay="300">
                                        <?php echo wp_kses_post($fiber['fiber_description']); ?>
                                    </div>
                                </div>
                                <div class="col-lg-8 ps-lg-5" data-aos="fade-up" data-aos-delay="400">
                                    <div class="row">
                                        <!-- Washing -->
                                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                                            <div class="care-detail-item">
                                                <div class="care-icon"><i class="fa-solid fa-hands-bubbles"></i></div>
                                                <div>
                                                    <h5 class="headline fs-6 mb-3">Washing</h5>
                                                    <ul class="care-list">
                                                        <?php 
                                                        $washing_lines = explode("\n", $fiber['washing']);
                                                        foreach ($washing_lines as $line):
                                                            if (trim($line)):
                                                        ?>
                                                        <li><?php echo esc_html(trim($line)); ?></li>
                                                        <?php 
                                                            endif;
                                                        endforeach;
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Drying -->
                                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="600">
                                            <div class="care-detail-item">
                                                <div class="care-icon"><i class="fa-solid fa-wind"></i></div>
                                                <div>
                                                    <h5 class="headline fs-6 mb-3">Drying</h5>
                                                    <ul class="care-list">
                                                        <?php 
                                                        $drying_lines = explode("\n", $fiber['drying']);
                                                        foreach ($drying_lines as $line):
                                                            if (trim($line)):
                                                        ?>
                                                        <li><?php echo esc_html(trim($line)); ?></li>
                                                        <?php 
                                                            endif;
                                                        endforeach;
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Ironing -->
                                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="700">
                                            <div class="care-detail-item">
                                                <div class="care-icon"><i class="fa-solid fa-shirt"></i></div>
                                                <div>
                                                    <h5 class="headline fs-6 mb-3">Ironing</h5>
                                                    <ul class="care-list">
                                                        <?php 
                                                        $ironing_lines = explode("\n", $fiber['ironing']);
                                                        foreach ($ironing_lines as $line):
                                                            if (trim($line)):
                                                        ?>
                                                        <li><?php echo esc_html(trim($line)); ?></li>
                                                        <?php 
                                                            endif;
                                                        endforeach;
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Storage -->
                                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="800">
                                            <div class="care-detail-item">
                                                <div class="care-icon"><i class="fa-solid fa-box-open"></i></div>
                                                <div>
                                                    <h5 class="headline fs-6 mb-3">Storage</h5>
                                                    <ul class="care-list">
                                                        <?php 
                                                        $storage_lines = explode("\n", $fiber['storage']);
                                                        foreach ($storage_lines as $line):
                                                            if (trim($line)):
                                                        ?>
                                                        <li><?php echo esc_html(trim($line)); ?></li>
                                                        <?php 
                                                            endif;
                                                        endforeach;
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                            $first = false;
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- CLOSING CTA -->
<section class=" text-center" style="background: var(--bg-card);">
    <div class="container reveal" data-aos="fade-up" data-aos-delay="200">
        <h2 class="headline display-4 mb-4" data-aos="fade-up" data-aos-delay="300"><?php echo esc_html(get_field('care_closing_title') ?: 'Wear Well. Care Well.'); ?></h2>
        <p class="text-secondary fs-5 mx-auto mb-5 lh-lg" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="300">
            <?php echo esc_html(get_field('care_closing_text') ?: 'A well-cared-for garment rewards you with years of comfort and beauty. Thank you for choosing to wear something made with intention.'); ?>
        </p>
        <a data-aos="fade-up" data-aos-delay="400" href="<?php echo esc_url(get_field('care_closing_button_link') ?: wc_get_page_permalink('shop')); ?>" class="cta-button btn btn-dark px-5 py-3 btn-lux "><?php echo esc_html(get_field('care_closing_button_text') ?: 'Shop the Collection'); ?></a>
    </div>
</section>
</main>

<?php get_footer(); ?>