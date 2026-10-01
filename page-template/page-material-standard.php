<?php
/**
 * Template Name: Material Standard
 */

get_header(); ?>

<main id="material-standard-main" class="material-standard-wrapper">
    <div class="ms-progress" id="msProgress"></div>

    <?php
    // Get fields - these match your JSON
    $hero = get_field('ms_hero');
    $intro = get_field('ms_intro');
    $standard_grid = get_field('ms_standard_grid');
    $split = get_field('ms_split');
    $benefits = get_field('ms_benefits');
    $story = get_field('ms_story');
    $refusal = get_field('ms_refusal');
    $ticker_text = get_field('ms_ticker');
    $promise = get_field('ms_promise');
    $final = get_field('ms_final');
    ?>

    <!-- Hero -->
    <section class="ms-hero" style="background-image: url('<?php echo esc_url($hero['hero_image'] ?? ''); ?>');">
        <div class="ms-hero-overlay"></div>
        <div class="ms-hero-content reveal">
            <h1><?php echo wp_kses_post($hero['hero_title'] ?? 'Material<br>Standard'); ?></h1>
            <p><?php echo esc_html($hero['hero_subtitle'] ?? 'Loomaama exists to make clothing from one rare natural fibre...'); ?></p>
            <a href="https://loomaama.com.au/impact/" class="ms-btn magnetic"><?php echo esc_html($hero['hero_button'] ?? 'Become a Witness'); ?></a>
        </div>
    </section>

    <!-- Intro -->
    <section class="ms-section ms-intro reveal" id="ms-standard">
        <div class="ms-intro-copy">
            <h2><?php echo esc_html($intro['intro_title'] ?? 'Better Clothing Begins With Better Fibre'); ?></h2>
            <div class="ms-accent-line"></div>
        </div>
        <div class="ms-wide-photo">
            <img src="<?php echo esc_url($intro['intro_image'] ?? 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=2200&q=90'); ?>" alt="Intro image">
        </div>
    </section>

    <!-- Standard Grid -->
    <section class="ms-section">
        <div class="ms-standard-grid">
            <?php if ($standard_grid && is_array($standard_grid)) : ?>
                <?php foreach ($standard_grid as $index => $item) : ?>
                    <article class="ms-photo-card reveal">
                        <div class="ms-photo"><img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['label']); ?>"></div>
                        <div class="ms-caption">
                            <div class="ms-number"><?php echo esc_html(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></div>
                            <div class="ms-label"><?php echo esc_html($item['label']); ?></div>
                            <p><?php echo esc_html($item['description']); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Split -->
    <section class="ms-section ms-split" id="ms-plant">
        <div class="ms-split-photo reveal">
            <img src="<?php echo esc_url($split['split_image'] ?? 'https://images.unsplash.com/photo-1592878849122-facb97520f9e?auto=format&fit=crop&w=1800&q=90'); ?>" alt="Split image">
        </div>
        <div class="ms-split-copy reveal">
            <h2><?php echo wp_kses_post($split['split_title'] ?? 'One Fibre.<br>One Clear Choice.'); ?></h2>
            <div class="ms-accent-line"></div>
            <?php echo wp_kses_post($split['split_text'] ?? ''); ?>
            <?php if (!empty($split['split_lines']) && is_array($split['split_lines'])) : ?>
                <div class="ms-short-lines">
                    <?php foreach ($split['split_lines'] as $line) : ?>
                        <span><?php echo esc_html($line['line']); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <a href="https://loomaama.com.au/the-forest/" class="ms-btn magnetic"><?php echo esc_html($split['split_button'] ?? 'Explore The Forest'); ?></a>
        </div>
    </section>

    <!-- Benefits -->
    <section class="ms-section ms-benefit-grid" id="ms-nature">
        <?php if ($benefits && is_array($benefits)) : ?>
            <?php foreach ($benefits as $benefit) : ?>
                <article class="ms-photo-card reveal">
                    <div class="ms-photo wide"><img src="<?php echo esc_url($benefit['image']); ?>" alt="<?php echo esc_attr($benefit['label']); ?>"></div>
                    <div class="ms-caption">
                        <div class="ms-label"><?php echo esc_html($benefit['label']); ?></div>
                        <p><?php echo esc_html($benefit['description']); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <!-- Story -->
    <section class="ms-section ms-story" id="ms-hands">
        <div class="ms-story-copy reveal">
            <h2><?php echo wp_kses_post($story['story_title'] ?? 'The Fibre Is<br>Only Half<br>The Story.'); ?></h2>
            <div class="ms-accent-line"></div>
        </div>
        <div class="ms-wide-photo reveal">
            <img src="<?php echo esc_url($story['story_image'] ?? get_template_directory_uri() . '/assets/images/artisan_loom_hands.jpg'); ?>" alt="Artisan weaving">
        </div>
    </section>

    <!-- Refusal -->
    <section class="ms-section ms-refusal">
        <div class="ms-refusal-header reveal">
            <h2><?php echo esc_html($refusal['refusal_title'] ?? 'Our Standard Is Also About What We Leave Out.'); ?></h2>
            <div class="ms-accent-line"></div>
            <?php echo wp_kses_post($refusal['refusal_text'] ?? ''); ?>
        </div>
        <div class="ms-refusal-grid">
            <?php if (!empty($refusal['refusal_items']) && is_array($refusal['refusal_items'])) : ?>
                <?php foreach ($refusal['refusal_items'] as $item) : ?>
                    <article class="ms-refusal-card reveal" style="--card-bg:url('<?php echo esc_url($item['image']); ?>'); --card-pos:<?php echo esc_attr($item['position'] ?? 'center'); ?>;">
                        <h3><?php echo esc_html($item['title']); ?></h3>
                        <p><?php echo esc_html($item['description']); ?></p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

   
    <!-- Promise -->
    <section class="ms-section" id="ms-promise">
        <div class="ms-promise-intro reveal">
            <h2><?php echo esc_html($promise['promise_title'] ?? 'Better For Skin. Better For Earth. Better For The Hands That Make It.'); ?></h2>
        </div>
        <div class="ms-promise-grid">
            <?php if (!empty($promise['promise_items']) && is_array($promise['promise_items'])) : ?>
                <?php foreach ($promise['promise_items'] as $item) : ?>
                    <article class="ms-photo-card reveal">
                        <div class="ms-photo wide"><img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['label']); ?>"></div>
                        <div class="ms-caption">
                            <div class="ms-label"><?php echo esc_html($item['label']); ?></div>
                            <p><?php echo esc_html($item['description']); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Final -->
    <section class="ms-section ms-final">
        <div class="ms-final-copy reveal">
            <h2><?php echo wp_kses_post($final['final_title'] ?? 'Wear The Fibre<br>That Remembers<br>Where It Came From.'); ?></h2>
            <div class="ms-accent-line"></div>
            <a href="https://loomaama.com.au/shop" class="ms-btn magnetic"><?php echo esc_html($final['final_button'] ?? 'Become a Witness'); ?></a>
        </div>
        <div class="ms-final-photo reveal">
            <img src="<?php echo esc_url($final['final_image'] ?? 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=1900&q=90'); ?>" alt="Final image">
        </div>
    </section>
</main>

<div class="ms-lightbox" id="msLightbox">
    <img src="" alt="Expanded material image">
</div>

<?php get_footer(); ?>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Progress bar
    const progress = document.getElementById('msProgress');
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
    }, { threshold: 0.13 });
    document.querySelectorAll('.reveal').forEach(function(el) {
        observer.observe(el);
    });

    // Magnetic buttons
    document.querySelectorAll('.ms-btn').forEach(function(btn) {
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

    // Lightbox
    const lightbox = document.getElementById('msLightbox');
    const lightboxImg = lightbox.querySelector('img');
    document.querySelectorAll('.ms-photo-card img, .ms-wide-photo img, .ms-split-photo img, .ms-final-photo img').forEach(function(img) {
        img.addEventListener('click', function() {
            lightboxImg.src = img.src;
            lightbox.classList.add('is-open');
        });
    });
    lightbox.addEventListener('click', function() {
        lightbox.classList.remove('is-open');
        lightboxImg.src = '';
    });
});
</script>