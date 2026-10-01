<?php
/**
 * Template Name: Our Standard
 * 
 * Description: A standalone page template for "Our Standard" with SCF support.
 */

get_header(); ?>

<main id="our-standard-main" class="os-wrapper">

    <?php
    // Get SCF fields
    $hero = get_field('os_hero');
    $intro = get_field('os_intro');
    $material_craft = get_field('os_material_craft');
    $touch = get_field('os_touch');
    $people = get_field('os_people');
    $truth_cards = get_field('os_truth_life_care');
    $closing = get_field('os_closing');

    // Hero
    $hero_eyebrow = $hero['eyebrow'] ?? 'Our Standards';
    $hero_title = $hero['title'] ?? 'The Loomaama<br>Standard';
    $hero_subtitle = $hero['subtitle'] ?? 'Wild nettle. Skilled hands. Nothing unnecessary.';
    $hero_button = $hero['button'] ?? 'Explore the Standards';
    $hero_image = $hero['image'] ?? '';

    // Intro
    $intro_eyebrow = $intro['eyebrow'] ?? 'The First Standard';
    $intro_title = $intro['title'] ?? 'Care begins before the garment.';
    $intro_text = $intro['text'] ?? 'Material, maker, body. Each one matters.';
    $intro_tiles = $intro['tiles'] ?? [];
    $intro_image = $intro['image'] ?? '';

    // Touch
    $touch_eyebrow = $touch['eyebrow'] ?? 'Touch';
    $touch_title = $touch['title'] ?? 'The hand is part of the finish.';
    $touch_text = $touch['text'] ?? '';
    $touch_detail_eyebrow = $touch['detail_eyebrow'] ?? 'Details';
    $touch_detail_title = $touch['detail_title'] ?? 'Texture tells the story.';
    $touch_detail_text = $touch['detail_text'] ?? 'Fibre. Loom. Hand. Cloth.';
    $touch_image = $touch['image'] ?? '';

    // People
    $people_eyebrow = $people['eyebrow'] ?? 'People';
    $people_title = $people['title'] ?? 'The maker stays visible.';
    $people_text = $people['text'] ?? '';
    $people_items = $people['items'] ?? [];
    $people_image_main = $people['image_main'] ?? '';
    $people_image_small_1 = $people['image_small_1'] ?? '';
    $people_image_small_2 = $people['image_small_2'] ?? '';

    // Closing
    $closing_eyebrow = $closing['eyebrow'] ?? '07 Continuity';
    $closing_title = $closing['title'] ?? 'Knowledge should continue.';
    $closing_text = $closing['text'] ?? '';
    $closing_sub_eyebrow = $closing['sub_eyebrow'] ?? 'Closing Standard';
    $closing_sub_title = $closing['sub_title'] ?? 'Care Is the Standard';
    $closing_sub_text = $closing['sub_text'] ?? '';
    $closing_tags = $closing['tags'] ?? [];
    $closing_button_primary = $closing['button_primary'] ?? 'Explore the Collection';
    $closing_button_primary_url = $closing['button_primary_url'] ?? '#';
    $closing_button_secondary = $closing['button_secondary'] ?? 'Meet the Artisans';
    $closing_button_secondary_url = $closing['button_secondary_url'] ?? '#';
    $closing_gallery = $closing['gallery'] ?? [];
    $closing_image = $closing['image'] ?? '';
    $closing_badge = $closing['badge'] ?? 'Craft with continuity';
    ?>

    <!-- Progress Bar -->
    <div class="os-progress" id="osProgress"></div>

    <!-- Hero Section -->
    <section class="os-hero">
        <?php if ($hero_image) : ?>
            <div class="os-hero-image">
                <img src="<?php echo esc_url($hero_image); ?>" alt="Hero background">
            </div>
        <?php endif; ?>
        <div class="os-hero-content reveal">
            <div class="os-eyebrow"><?php echo esc_html($hero_eyebrow); ?></div>
            <h1><?php echo wp_kses_post($hero_title); ?></h1>
            <p><?php echo esc_html($hero_subtitle); ?></p>
			<?php if($hero_button):?>
            <a href="<?php echo get_field("hero_link");?>" class="os-btn magnetic"><?php echo esc_html($hero_button); ?></a>
             <?php endif;?>
        </div>
       
    </section>

    <!-- Intro Section -->
    <section class="os-section os-section-lead reveal" id="intro">
        <div class="os-lead-copy">
            <div class="os-eyebrow" style="color:var(--os-accent)"><?php echo esc_html($intro_eyebrow); ?></div>
            <h2><?php echo esc_html($intro_title); ?></h2>
            <div class="os-accent-line"></div>
            <p><?php echo esc_html($intro_text); ?></p>
            <?php if ($intro_tiles && is_array($intro_tiles)) : ?>
                <div class="os-intro-grid">
                    <?php foreach ($intro_tiles as $tile) : ?>
                        <div class="os-intro-tile">
                            <?php if (!empty($tile['image'])) : ?>
                                <div class="os-intro-tile-photo">
                                    <img src="<?php echo esc_url($tile['image']); ?>" alt="<?php echo esc_attr($tile['title']); ?>">
                                </div>
                            <?php endif; ?>
                            <h3><?php echo esc_html($tile['title']); ?></h3>
                            <p style="margin-top:10px"><?php echo esc_html($tile['text']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="os-lead-photo">
            <span class="os-floating-caption">Opening Standard</span>
            <img src="<?php echo esc_url($intro_image); ?>" alt="Natural textile and fibre atmosphere">
        </div>
    </section>

    <!-- Material & Craft Cards -->
    <section class="os-section" id="material-craft">
        <div class="os-cards-grid">
            <?php if ($material_craft && is_array($material_craft)) : ?>
                <?php foreach ($material_craft as $card) : ?>
                    <article class="os-image-card reveal">
                        <div class="os-card-photo">
                            <span class="os-floating-caption"><?php echo esc_html($card['caption'] ?? ''); ?></span>
                            <img src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['title']); ?>">
                        </div>
                        <div class="os-card-content">
                            <div class="os-number"><?php echo esc_html($card['number']); ?></div>
                            <h3><?php echo esc_html($card['title']); ?></h3>
                            <?php echo wp_kses_post($card['text']); ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Touch / Premium Section -->
    <section class="os-section os-touch-premium" id="touch-details">
        <?php if ($touch_image) : ?>
            <div class="os-touch-image reveal">
                <img src="<?php echo esc_url($touch_image); ?>" alt="Artisan hands working on loom">
            </div>
        <?php endif; ?>

        <div class="os-touch-panel reveal">
            <div class="os-eyebrow" style="color:var(--os-accent)"><?php echo esc_html($touch_eyebrow); ?></div>
            <h2><?php echo esc_html($touch_title); ?></h2>
            <div class="os-accent-line"></div>
            <p><?php echo esc_html($touch_text); ?></p>

            <div class="os-touch-detail">
                <div class="os-eyebrow" style="color:var(--os-accent)"><?php echo esc_html($touch_detail_eyebrow); ?></div>
                <h3><?php echo esc_html($touch_detail_title); ?></h3>
                <p><?php echo esc_html($touch_detail_text); ?></p>
            </div>
        </div>
    </section>

    <!-- People Section -->
    <section class="os-section os-people-section" id="people">
        <div class="os-people-shell reveal">
            <div class="os-people-copy">
                <div class="os-eyebrow" style="color:rgba(250,249,247,.62)"><?php echo esc_html($people_eyebrow); ?></div>
                <h2><?php echo esc_html($people_title); ?></h2>
                <div class="os-accent-line"></div>
                <?php echo wp_kses_post($people_text); ?>

                <?php if ($people_items && is_array($people_items)) : ?>
                    <div class="os-people-grid">
                        <?php foreach ($people_items as $item) : ?>
                            <div class="os-people-item">
                                <strong><?php echo esc_html($item['title']); ?></strong>
                                <p><?php echo esc_html($item['text']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="os-people-visuals">
                <?php if ($people_image_main) : ?>
                    <div class="os-people-main-image">
                        <img src="<?php echo esc_url($people_image_main); ?>" alt="Artisan working">
                    </div>
                <?php endif; ?>
                <div class="os-people-small-grid">
                    <?php if ($people_image_small_1) : ?>
                        <div class="os-people-small-image">
                            <img src="<?php echo esc_url($people_image_small_1); ?>" alt="Hands weaving">
                        </div>
                    <?php endif; ?>
                    <?php if ($people_image_small_2) : ?>
                        <div class="os-people-small-image">
                            <img src="<?php echo esc_url($people_image_small_2); ?>" alt="Natural textile detail">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Truth, Life, Care Cards -->
    <section class="os-section" id="truth-life-care">
        <div class="os-cards-grid">
            <?php if ($truth_cards && is_array($truth_cards)) : ?>
                <?php foreach ($truth_cards as $card) : ?>
                    <article class="os-image-card reveal">
                        <div class="os-card-photo">
                            <span class="os-floating-caption"><?php echo esc_html($card['caption'] ?? ''); ?></span>
                            <img src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['title']); ?>">
                        </div>
                        <div class="os-card-content">
                            <div class="os-number"><?php echo esc_html($card['number']); ?></div>
                            <h3><?php echo esc_html($card['title']); ?></h3>
                            <?php echo wp_kses_post($card['text']); ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Closing / Continuity Section -->
    <section class="os-section os-final" id="continuity">
        <div class="os-closing-shell reveal">
            <div class="os-final-copy">
                <div class="os-eyebrow" style="color:var(--os-accent)"><?php echo esc_html($closing_eyebrow); ?></div>
                <h2><?php echo esc_html($closing_title); ?></h2>
                <div class="os-accent-line"></div>
                <p><?php echo esc_html($closing_text); ?></p>

                <div class="os-closing-block">
                    <div class="os-eyebrow" style="color:var(--os-accent)"><?php echo esc_html($closing_sub_eyebrow); ?></div>
                    <h3 style="font-size:clamp(22px,2.2vw,34px)"><?php echo esc_html($closing_sub_title); ?></h3>
                    <p style="margin-top:12px"><?php echo esc_html($closing_sub_text); ?></p>
                    <?php if ($closing_tags && is_array($closing_tags)) : ?>
                        <div class="os-closing-points">
                            <?php foreach ($closing_tags as $tag) : ?>
                                <span><?php echo esc_html($tag['tag']); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($closing_gallery && is_array($closing_gallery)) : ?>
                    <div class="os-closing-mini-gallery">
                        <?php foreach ($closing_gallery as $image) : ?>
                            <div class="os-closing-mini-image">
                                <img src="<?php echo esc_url($image); ?>" alt="Gallery image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="os-closing-actions">
                    <a href="<?php echo esc_url($closing_button_primary_url); ?>" class="os-btn magnetic"><?php echo esc_html($closing_button_primary); ?></a>
                    <a href="<?php echo esc_url($closing_button_secondary_url); ?>" class="os-btn magnetic"><?php echo esc_html($closing_button_secondary); ?></a>
                </div>
            </div>

            <?php if ($closing_image) : ?>
                <div class="os-final-visual">
                    <img src="<?php echo esc_url($closing_image); ?>" alt="Natural garment and textile composition">
                    <div class="os-final-badge"><?php echo esc_html($closing_badge); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Progress bar
    const progress = document.getElementById('osProgress');
    window.addEventListener('scroll', function() {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        const pct = max > 0 ? (window.scrollY / max) * 100 : 0;
        progress.style.width = pct + '%';
    }, { passive: true });

    // Reveal observer
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(function(el) {
        observer.observe(el);
    });

    // Magnetic buttons
    document.querySelectorAll('.os-btn').forEach(function(btn) {
        btn.addEventListener('mousemove', function(e) {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            btn.style.transform = 'translate(' + (x * 0.12) + 'px, ' + (y * 0.18) + 'px)';
        });
        btn.addEventListener('mouseleave', function() {
            btn.style.transform = '';
        });
    });
});
</script>