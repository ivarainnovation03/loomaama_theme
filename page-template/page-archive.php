<?php
/**
 * Template Name: Archive
 *
 * @package Loomaama
 */

get_header();
?>

<main>
    <!-- HERO SECTION (Split Screen) -->
    <section class="hero-split pb-0" data-aos="zoom-in" data-aos-delay="100">
        <?php 
        $left_image = get_field('archive_left_image');
        $right_image = get_field('archive_right_image');
        ?>
        <?php if ($left_image && $right_image): ?>
        <div class="split-pane pane-left" style="background-image: url('<?php echo esc_url($left_image ?: 'https://images.unsplash.com/photo-1542385151-efd9000785a0?q=80&w=1500&auto=format&fit=crop'); ?>');"></div>
        <div class="split-pane pane-right" style="background-image: url('<?php echo esc_url($right_image ?: 'https://images.unsplash.com/photo-1532453288672-3a27e9be9efd?q=80&w=1500&auto=format&fit=crop'); ?>');"></div>
        <?php elseif ($left_image): ?>
        <div class="split-pane pane-left" style="background-image: url('<?php echo esc_url($left_image); ?>');"></div>
        <?php elseif ($right_image): ?>
        <div class="split-pane pane-right" style="background-image: url('<?php echo esc_url($right_image); ?>');"></div>
        <?php endif; ?>
        <div class="hero-split-content reveal" data-aos="fade-up" data-aos-delay="200">
            <div class="hero-content-wrap px-3">
           <span class="eyebrow mb-4 d-block" data-aos="fade-up" data-aos-delay="300"><?php echo esc_html(get_field('archive_hero_eyebrow') ?: ''); ?></span>
            <h1 class="headline display-3 mb-4 fw-bold" data-aos="fade-up" data-aos-delay="400"><?php echo esc_html(get_field('archive_hero_title') ?: 'From Fiber to Fabric:<br>The Journey of a Garment'); ?></h1>
            <p class="fs-5 mx-auto mt-4" style="max-width: 700px; font-weight: 300; line-height: 1.8;" data-aos="fade-up" data-aos-delay="500">
                <?php echo esc_html(get_field('archive_hero_subtitle') ?: 'Every LOOMAAMA piece passes through many hands and takes weeks to complete. This is what that process looks like, from the first harvest to the final stitch.'); ?>
            </p>
            </div>
        </div>
    </section>

    <!-- SECTION 1: THE PROCESS OVERVIEW -->
    <section class=" my-md-5">
        <div class="container ">
            <div class="row text-center reveal" data-aos="fade-up">
                <div class="col-12">
                   <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('process_eyebrow') ?: ''); ?></span>
                    <h2 class="headline display-6 mb-4" data-aos="fade-up" data-aos-delay="200"><?php echo wp_kses_post(get_field('process_title') ?: 'How a LOOMAAMA Garment is Made'); ?></h2>
                    <p class="text-secondary fs-5 mx-auto lh-lg" style="max-width: 700px;" data-aos="fade-up" data-aos-delay="300">
                        <?php echo esc_html(get_field('process_subtitle') ?: 'From harvest to finished piece, a single LOOMAAMA garment passes through 8-12 artisans and takes 3-6 weeks to complete. Here\'s how it happens.'); ?>
                    </p>
                </div>
            </div>

            <!-- Horizontal Timeline -->
            <div class="row  reveal" data-aos="fade-up">
                <div class="col-12">
                    <div class="timeline-container">
                        <div class="timeline">
                            <?php 
                            $steps = get_field('process_steps');
                            if ($steps):
                                foreach ($steps as $key => $step):
                                    $delay = ($key + 1) * 100;
                            ?>
                            <div class="timeline-step" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                <div class="timeline-icon"><i class="<?php echo esc_attr($step['step_icon']); ?>"></i></div>
                                <span class="step-number"><?php echo str_pad($step['step_number'], 2, '0', STR_PAD_LEFT); ?></span>
                                <h6 class="headline mb-0"><?php echo esc_html($step['step_title']); ?></h6>
                            </div>
                            <?php 
                                endforeach;
                            else:
                            ?>
                            <div class="timeline-step">
                                <div class="timeline-icon"><i class="fa-solid fa-leaf"></i></div>
                                <span class="step-number">01</span>
                                <h6 class="headline mb-0">Harvest</h6>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon"><i class="fa-solid fa-hands"></i></div>
                                <span class="step-number">02</span>
                                <h6 class="headline mb-0">Processing</h6>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon"><i class="fa-solid fa-fan"></i></div>
                                <span class="step-number">03</span>
                                <h6 class="headline mb-0">Spinning</h6>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon"><i class="fa-solid fa-droplet"></i></div>
                                <span class="step-number">04</span>
                                <h6 class="headline mb-0">Dyeing</h6>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon"><i class="fa-solid fa-layer-group"></i></div>
                                <span class="step-number">05</span>
                                <h6 class="headline mb-0">Warping</h6>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon"><i class="fa-solid fa-scroll"></i></div>
                                <span class="step-number">06</span>
                                <h6 class="headline mb-0">Weaving</h6>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon"><i class="fa-solid fa-scissors"></i></div>
                                <span class="step-number">07</span>
                                <h6 class="headline mb-0">Finishing</h6>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon"><i class="fa-solid fa-check-double"></i></div>
                                <span class="step-number">08</span>
                                <h6 class="headline mb-0">Quality Check</h6>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: STEP-BY-STEP BREAKDOWN -->
    <?php 
    $detailed_steps = get_field('detailed_steps');
    if ($detailed_steps):
        foreach ($detailed_steps as $index => $step):
            $bg_class = ($step['step_bg_style'] == 'editorial') ? 'bg-editorial' : 'bg-white';
    ?>
    <section class=" step-section <?php echo $bg_class; ?>" data-aos="fade-up">
        <div class="container py-5">
            <div class="row align-items-center <?php echo ($index % 2 == 1) ? 'flex-lg-row-reverse' : ''; ?>">
                <div class="col-lg-5 mb-5 mb-lg-0 reveal" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-badge" data-aos="fade-up" data-aos-delay="200">
                        <?php echo $step['step_badge']; ?>
                    </div>
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="300">
                        <?php echo esc_html($step['step_eyebrow']); ?>
                    </span>
                    <h2 class="headline display-6 mb-3" data-aos="fade-up" data-aos-delay="400">
                        <?php echo esc_html($step['step_heading']); ?>
                    </h2>
                    <?php if ($step['step_subheading']): ?>
                    <h5 class="headline text-secondary mb-4" data-aos="fade-up" data-aos-delay="500">
                        <?php echo esc_html($step['step_subheading']); ?>
                    </h5>
                    <?php endif; ?>
                    <div class="text-secondary lh-lg mb-0">
                        <?php echo wp_kses_post($step['step_description']); ?>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1 reveal" data-aos="fade-up" data-aos-delay="600">
                    <div class="step-images">
                        <?php if ($step['step_image_1']): ?>
                        <div class="step-img-wrap" data-aos="fade-up" data-aos-delay="700">
                            <img src="<?php echo esc_url($step['step_image_1']); ?>" class="step-img" alt="<?php echo esc_attr($step['step_heading']); ?>">
                        </div>
                        <?php endif; ?>
                        <?php if ($step['step_image_2']): ?>
                        <div class="step-img-wrap" style="transform: translateY(30px);" data-aos="fade-up" data-aos-delay="800">
                            <img src="<?php echo esc_url($step['step_image_2']); ?>" class="step-img" alt="<?php echo esc_attr($step['step_heading']); ?>">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php 
        endforeach;
    endif;
    ?>

    <!-- SECTION 3: WHY HANDMADE MATTERS -->
    <section class=" my-md-5" style="border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);" data-aos="fade-up">
        <div class="container ">
            <div class="row justify-content-center text-center reveal">
                <div class="col-lg-8">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100">
                        <?php echo esc_html(get_field('handmade_eyebrow') ?: ''); ?>
                    </span>
                    <h2 class="headline mb-4 display-6" data-aos="fade-up" data-aos-delay="200">
                        <?php echo esc_html(get_field('handmade_title') ?: 'What Machines Can\'t Replicate'); ?>
                    </h2>
                    <div class="text-secondary fs-5 lh-lg" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('handmade_text') ?: '<p>Handmade fabric carries something machine-made never will. Slight variations in thickness, texture, and tension. A drape that moves differently. A surface that tells the truth about how it was made.</p><p class="text-main fs-5 lh-lg mb-4 fw-bold">These are not flaws. They are fingerprints.</p><p>Machine-made fabric is uniform, predictable, and silent. LOOMAAMA fabric breathes, ages beautifully, and carries the quiet presence of the person who made it.</p><p class="fw-medium">That is the difference. And once you feel it, you will not go back.</p>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: TIME AS VALUE -->
    <section class=" my-md-5" data-aos="fade-up">
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 reveal">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100">
                        <?php echo esc_html(get_field('time_eyebrow') ?: ''); ?>
                    </span>
                    <h2 class="headline mb-4 display-6" data-aos="fade-up" data-aos-delay="200">
                        <?php echo esc_html(get_field('time_title') ?: 'Why It Takes Weeks,<br>Not Hours.'); ?>
                    </h2>
                    <div class="text-secondary lh-lg mb-0 fs-5" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('time_description') ?: '<p>A single LOOMAAMA garment represents 40 to 60 hours of skilled human labor. Not because the process is inefficient. Because it cannot be shortened without losing what makes it worth wearing.</p><p>Fast fashion prioritises speed. We prioritise the garment. The result is clothing that does not fall apart after a season, because it was meant to stay longer.</p>'); ?>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 reveal" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-box">
                        <h5 class="headline mb-0 text-uppercase small"><?php echo esc_html(get_field('time_text') ?: 'Average time from fiber to finished garment'); ?></h5>
                        <p class="display-6 headline mt-3 mb-0 fw-bold" style="color: var(--wild-nettle);"><?php echo esc_html(get_field('time_stat') ?: '3-6 Weeks'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: TRADITIONAL TOOLS -->
    <section class="" style="background: var(--bg-editorial);" data-aos="fade-up">
        <div class="container ">
            <div class="row mb-5 reveal">
                <div class="order-1 order-lg-2 col-lg-6">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100">
                        <?php echo esc_html(get_field('tools_eyebrow') ?: ''); ?>
                    </span>
                    <h2 class="headline mb-4 display-6" data-aos="fade-up" data-aos-delay="200">
                        <?php echo esc_html(get_field('tools_title') ?: 'The Tools of the Trade'); ?>
                    </h2>
                    <div class="text-secondary lh-lg" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('tools_text') ?: '<p>LOOMAAMA artisans work with hand spindles, wooden looms, and simple sewing machines. Tools that have been passed down through generations alongside the skill. No industrial machinery. No automation. No shortcuts.</p><p class="fw-medium">These tools are not outdated. They are precise. And in the right hands, they produce something no modern machine has ever been able to match.</p>'); ?>
                    </div>
                </div>
            
                <div class="order-1 order-lg-1 col-lg-6">
                    <div class="tool-grid" data-aos="fade-up" data-aos-delay="400">
                        <?php 
                        $tool_images = get_field('tool_images');
                        if ($tool_images):
                            foreach ($tool_images as $key => $image):
                                $delay = ($key + 1) * 100;
                              
                        ?>
                        <div class="tool-item" style="background-image: url('<?php echo esc_url($image['url']); ?>');" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>"></div>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <div class="tool-item" style="background-image: url('https://images.unsplash.com/photo-1544441892-794166f1e310?q=80&w=600&auto=format&fit=crop');"></div>
                        <div class="tool-item" style="background-image: url('https://images.unsplash.com/photo-1516224498413-84ecf3a1e7fc?q=80&w=600&auto=format&fit=crop');"></div>
                        <div class="tool-item" style="background-image: url('https://images.unsplash.com/photo-1584447128309-b66b7a4d1b63?q=80&w=600&auto=format&fit=crop');"></div>
                        <div class="tool-item" style="background-image: url('https://images.unsplash.com/photo-1605518216938-7c31b7b14ad0?q=80&w=600&auto=format&fit=crop');"></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CLOSING CTA -->
    <section class=" mb-5 text-center" data-aos="fade-up">
        <div class="container  reveal">
            <h2 class="headline display-5 mb-4" data-aos="fade-up" data-aos-delay="100">
                <?php echo esc_html(get_field('closing_title') ?: 'See the Process in Action'); ?>
            </h2>
            <p class="text-secondary fs-5 mx-auto mb-5 lh-lg" data-aos="fade-up" data-aos-delay="200" style="max-width: 600px;">
                <?php echo esc_html(get_field('closing_text') ?: 'Follow our journal for behind-the-scenes stories, artisan profiles, and deep dives into traditional craft.'); ?>
            </p>
            <?php 
            $button_link = get_field('closing_button_link');
            $button_text = get_field('closing_button_text');
            ?>
            <a href="<?php echo esc_url($button_link ?: '#'); ?>" class="cta-button btn btn btn-dark px-5 py-3 btn-lux headline fs-6" data-aos="fade-up" data-aos-delay="300">
                <?php echo esc_html($button_text ?: 'Read the Journal'); ?>
            </a>
        </div>
    </section>

</main>

<?php get_footer(); ?>