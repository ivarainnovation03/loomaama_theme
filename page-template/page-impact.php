<?php
/**
 * Template Name: Impact Page Template
 * Description: A page showcasing LOOMAAMA's standards, commitments, and impact.
 *
 * @package Loomaama
 */

get_header();

$hero_section = get_field('hero_section') ?: [];
$intro_section = get_field('intro_section') ?: [];
$standard_cards = get_field('standard_cards') ?: [];
$image_grid_section = get_field('image_grid_section') ?: [];
$commitments_section = get_field('commitments_section') ?: [];
$split_story_section = get_field('split_story_section') ?: [];
$timeline_section = get_field('timeline_section') ?: [];
$break_image_section = get_field('break_image_section') ?: [];
$statement_section = get_field('statement_section') ?: [];
$cta_section = get_field('cta_section') ?: [];

?>

<main id="standard-main">
    <!-- HERO SECTION -->
    <section class="standard-hero">
        <div class="hero-wrapper">
            <div class="hero-content" data-aos="fade-up" data-aos-duration="900">
                <span class="eyebrow"><?php echo esc_html($hero_section['standard_eyebrow'] ?? 'Our Standard'); ?></span>
                <h1 class="display-3 headline"><?php echo esc_html($hero_section['standard_title'] ?? 'Luxury is the discipline of making only what deserves to exist.'); ?></h1>
                <div class="hero-description">
                    <?php echo wp_kses_post($hero_section['standard_description'] ?? '<p>LOOMAAMA is measured by the care it protects, the knowledge it carries, and the trace it leaves behind.</p>'); ?>
                </div>
            </div>
            <div class="hero-image-wrapper">
                <?php 
                $hero_image = $hero_section['standard_hero_image'] ?? null;
                if ($hero_image) : ?>
                    <img src="<?php echo esc_url($hero_image['url']); ?>" alt="<?php echo esc_attr($hero_image['alt'] ?: 'Himalayan landscape'); ?>" class="hero-image">
                <?php else : ?>
                    <div class="hero-placeholder"></div>
                <?php endif; ?>
            </div>
        </div>
       
    </section>

    <!-- INTRODUCTION SECTION -->
    <section class="intro-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4" data-aos="fade-right">
                    <span class="eyebrow"><?php echo esc_html($intro_section['intro_eyebrow'] ?? 'What impact means here'); ?></span>
                </div>
                <div class="col-lg-8" data-aos="fade-left">
                    <p class=" display-5"><?php echo esc_html($intro_section['intro_lead'] ?? 'Impact is not a claim. It is the quiet evidence left behind by better choices.'); ?></p>
                    <div class="intro-body">
                        <?php echo wp_kses_post($intro_section['intro_body'] ?? '<p>At LOOMAAMA, every piece begins with wild Himalayan nettle and the people who understand its nature. The result is not only clothing. It is continuity, dignity, and a more considered way of making.</p>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CARDS SECTION (Three Pillars) -->
    <section class="cards-section">
        <div class="container">
            <div class="row">
                <?php 
                $cards = $standard_cards;
                if ($cards) : 
                    $card_index = 1;
                    foreach ($cards as $card) : ?>
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo $card_index * 100; ?>">
                            <div class="standard-card">
                                <div class="card-number"><?php echo sprintf("%02d", $card_index); ?></div>
                                <h3><?php echo esc_html($card['title']); ?></h3>
                                <p><?php echo esc_html($card['description']); ?></p>
                            </div>
                        </div>
                <?php 
                        $card_index++;
                    endforeach;
                else : ?>
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="standard-card"><div class="card-number">01</div><h3>Material with origin</h3><p>Wild Himalayan nettle is not chosen for trend. It is chosen for truth.</p></div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="standard-card"><div class="card-number">02</div><h3>The maker is visible</h3><p>LOOMAAMA does not hide the hands behind the garment. Craft is authorship.</p></div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="standard-card"><div class="card-number">03</div><h3>Inheritance in motion</h3><p>We support apprenticeship because living craft cannot survive as memory alone.</p></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- IMAGE GRID SECTION -->
    <section class="image-grid-section">
        <div class="container">
            <div class="grid-heading" data-aos="fade-up">
                <span class="eyebrow"><?php echo esc_html($image_grid_section['grid_eyebrow'] ?? 'Seen in the work'); ?></span>
                <p class="display-6"><?php echo esc_html($image_grid_section['grid_lead'] ?? 'The work is seen in texture, in restraint, and in the human rhythm of cloth.'); ?></p>
            </div>
            <div class="image-grid" data-aos="fade-up" data-aos-delay="100">
                <div class="grid-main">
                    <?php 
                    $grid_image_main = $image_grid_section['grid_image_main'] ?? null;
                    if ($grid_image_main) : ?>
                        <img src="<?php echo esc_url($grid_image_main['url']); ?>" alt="<?php echo esc_attr($grid_image_main['alt'] ?: 'Forest origin'); ?>">
                        <?php if ($grid_caption_main = $image_grid_section['grid_caption_main'] ?? '') : ?>
                            <div class="grid-caption"><?php echo esc_html($grid_caption_main); ?></div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="grid-placeholder main"></div>
                        <div class="grid-caption">Forest origin</div>
                    <?php endif; ?>
                </div>
                <div class="grid-stack">
                    <div class="grid-stack-item">
                        <?php 
                        $grid_image_top = $image_grid_section['grid_image_top'] ?? null;
                        if ($grid_image_top) : ?>
                            <img src="<?php echo esc_url($grid_image_top['url']); ?>" alt="<?php echo esc_attr($grid_image_top['alt'] ?: 'Plant fibre'); ?>">
                            <?php if ($grid_caption_top = $image_grid_section['grid_caption_top'] ?? '') : ?>
                                <div class="grid-caption"><?php echo esc_html($grid_caption_top); ?></div>
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="grid-placeholder small"></div>
                            <div class="grid-caption">Plant fibre</div>
                        <?php endif; ?>
                    </div>
                    <div class="grid-stack-item">
                        <?php 
                        $grid_image_bottom = $image_grid_section['grid_image_bottom'] ?? null;
                        if ($grid_image_bottom) : ?>
                            <img src="<?php echo esc_url($grid_image_bottom['url']); ?>" alt="<?php echo esc_attr($grid_image_bottom['alt'] ?: 'Human skill'); ?>">
                            <?php if ($grid_caption_bottom = $image_grid_section['grid_caption_bottom'] ?? '') : ?>
                                <div class="grid-caption"><?php echo esc_html($grid_caption_bottom); ?></div>
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="grid-placeholder small"></div>
                            <div class="grid-caption">Human skill</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- COMMITMENTS SECTION (Dark Band) -->
    <section class="commitments-section">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-5" data-aos="fade-right">
                    <span class="eyebrow light"><?php echo esc_html($commitments_section['commitments_eyebrow'] ?? 'Our commitments'); ?></span>
                    <h2 class="commitments-title"><?php echo esc_html($commitments_section['commitments_title'] ?? 'A beautiful object must be responsible for its own existence.'); ?></h2>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="commitments-list">
                        <?php 
                        $commitments = $commitments_section['commitments_list'] ?? [];
                        if ($commitments) : 
                            foreach ($commitments as $commitment) : ?>
                                <div class="commit-item">
                                    <h3><?php echo esc_html($commitment['title']); ?></h3>
                                    <p><?php echo esc_html($commitment['description']); ?></p>
                                </div>
                        <?php endforeach; 
                        else : ?>
                            <div class="commit-item"><h3>Trace the origin</h3><p>We follow the fibre from forest to cloth, preserving the story of place, process, and skilled hands.</p></div>
                            <div class="commit-item"><h3>Honour the maker</h3><p>The artisan is not behind LOOMAAMA. The artisan is within it.</p></div>
                            <div class="commit-item"><h3>Practice restraint</h3><p>Nothing is added only to impress. Every detail must earn its place.</p></div>
                            <div class="commit-item"><h3>Become more transparent</h3><p>Growth must bring clearer records, not louder promises.</p></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SPLIT STORY SECTION -->
    <section class="split-story">
        <div class="split-wrapper">
            <div class="split-image">
                <?php 
                $split_image = $split_story_section['split_story_image'] ?? null;
                if ($split_image) : ?> 
                    <img src="<?php echo esc_url($split_image['url']); ?>" alt="<?php echo esc_attr($split_image['alt'] ?: 'Tall forest trees'); ?>">
                <?php else : ?>
                    <div class="split-placeholder"></div>
                <?php endif; ?>
            </div>
            <div class="split-content">
                <span class="eyebrow"><?php echo esc_html($split_story_section['split_eyebrow'] ?? 'Forest to cloth'); ?></span>
                <h2><?php echo esc_html($split_story_section['split_title'] ?? 'Before the loom, there is the land.'); ?></h2>
                <p><?php echo esc_html($split_story_section['split_text'] ?? 'The fibre carries altitude, season, touch, and time. LOOMAAMA keeps that presence close to the skin.'); ?></p>
            </div>
        </div>
    </section>

    <!-- TIMELINE SECTION -->
    <section class="timeline-section">
        <div class="container">
            <div class="timeline-header" data-aos="fade-up">
                <div>
                    <span class="eyebrow"><?php echo esc_html($timeline_section['timeline_eyebrow'] ?? 'Our path forward'); ?></span>
                    <h2><?php echo esc_html($timeline_section['timeline_title'] ?? 'Our promises must become evidence.'); ?></h2>
                </div>
                <p class="timeline-subtitle"><?php echo esc_html($timeline_section['timeline_subtitle'] ?? 'This is the standard we choose to be measured against as LOOMAAMA grows.'); ?></p>
            </div>

            <div class="timeline-items">
                <?php 
                $timeline_items = $timeline_section['timeline_items'] ?? [];
                if ($timeline_items) : 
                    foreach ($timeline_items as $item) : ?> 
                        <div class="timeline-row" data-aos="fade-up">
                            <div class="timeline-year"><?php echo esc_html($item['year']); ?></div>
                            <div class="timeline-title-item"><?php echo esc_html($item['title']); ?></div>
                            <div class="timeline-description"><?php echo esc_html($item['description']); ?></div>
                        </div>
                <?php endforeach; 
                else : ?>
                    <div class="timeline-row" data-aos="fade-up"><div class="timeline-year">Now</div><div class="timeline-title-item">Guard the origin</div><div class="timeline-description">Protect the connection between fibre, forest, artisan, and finished garment.</div></div>
                    <div class="timeline-row" data-aos="fade-up"><div class="timeline-year">Next</div><div class="timeline-title-item">Carry the craft forward</div><div class="timeline-description">Support learning between experienced makers and the next generation of artisans.</div></div>
                    <div class="timeline-row" data-aos="fade-up"><div class="timeline-year">Future</div><div class="timeline-title-item">Make the record visible</div><div class="timeline-description">Share clearer proof of material choices, maker participation, training, and garment life.</div></div>
                <?php endif; ?>
            </div>

            <!-- PROOF GRID (Four Pillars) -->
            <div class="proof-grid" data-aos="fade-up">
                <?php 
                $proof_items = $timeline_section['proof_items'] ?? [];
                if ($proof_items) : 
                    foreach ($proof_items as $proof) : ?> 
                        <div class="proof-card">
                            <span><?php echo esc_html($proof['label']); ?></span>
                            <p><?php echo esc_html($proof['text']); ?></p>
                        </div>
                <?php endforeach; 
                else : ?>
                    <div class="proof-card"><span>Wild</span><p>Himalayan nettle gives every piece a material language rooted in place.</p></div>
                    <div class="proof-card"><span>Hand</span><p>Every textile carries the rhythm of preparation, weaving, finishing, and touch.</p></div>
                    <div class="proof-card"><span>Soil</span><p>What touches the skin should remember its relationship with the earth.</p></div>
                    <div class="proof-card"><span>Next</span><p>Tradition survives when knowledge continues through younger hands.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- IMAGE BREAK SECTION -->
    <section class="image-break-section">
        <?php 
        $break_image_large = $break_image_section['break_large_image'] ?? null;
        if ($break_image_large) : ?> 
            <img src="<?php echo esc_url($break_image_large['url']); ?>" alt="<?php echo esc_attr($break_image_large['alt'] ?: 'Mountain forest landscape'); ?>" class="full-width-image">
        <?php else : ?>
            <div class="break-placeholder-large"></div>
        <?php endif; ?>
    </section>

    <!-- STANDARD STATEMENT SECTION -->
    <section class="standard-statement">
        <div class="container">
            <div class="row">
                <div class="col-lg-4" data-aos="fade-right">
                    <span class="eyebrow"><?php echo esc_html($statement_section['statement_eyebrow'] ?? 'The LOOMAAMA standard'); ?></span>
                </div>
                <div class="col-lg-8" data-aos="fade-left">
                    <p class="statement-lead"><?php echo esc_html($statement_section['statement_lead'] ?? 'Proof, not promises.'); ?></p>
                    <div class="statement-body">
                        <?php echo wp_kses_post($statement_section['statement_body'] ?? '<p>LOOMAAMA was built to return value to the forest, to the fibre, and to the hands that keep Himalayan nettle alive.</p>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta-section">
        <div class="container text-center">
            <h2 data-aos="fade-up"><?php echo esc_html($cta_section['cta_title'] ?? 'Become a witness.'); ?></h2>
            <p data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($cta_section['cta_text'] ?? 'Wear the forest, the fibre, and the hand that shaped it.'); ?></p>
            <a href="<?php echo esc_url($cta_section['cta_button_url'] ?? wc_get_page_permalink('shop')); ?>" class="btn btn-outline-light" data-aos="fade-up" data-aos-delay="200">
                <?php echo esc_html($cta_section['cta_button_text'] ?? 'Explore the collection'); ?>
            </a>
        </div>
    </section>
</main>



<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 800, once: true, offset: 100 });
    }
});
</script>

<?php get_footer(); ?>