<?php
/**
 * Template Name: The Making
 * Description: A cinematic page showcasing the craft, materials, and the making process.
 *
 * @package Loomaama
 */

get_header();

$making_hero_section = get_field('making_hero_section') ?: [];
$making_strip_section = get_field('making_strip') ?: [];
$atelier_section = get_field('atelier_section') ?: [];
$making_chapters = get_field('making_chapters') ?: [];
$deep_section = get_field('deep_section') ?: [];
$why_section = get_field('why_section') ?: [];
$closing_section = get_field('closing_section') ?: [];

?>

<main id="making-main">
    <!-- HERO SECTION -->
    <section class="making-hero">
        <div class="container-fluid px-0 position-relative">
            <div class="hero-grid">
                <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
                    <div class="hero-inner">
                        <span class="eyebrow"><?php echo esc_html($making_hero_section['making_eyebrow'] ?? 'CRAFT & MATERIALS'); ?></span>
                        <h1 class="display-3"><?php echo esc_html($making_hero_section['making_main_title'] ?? 'The Making'); ?></h1>
                        <div class="hero-subtitle">
                            <?php echo wp_kses_post($making_hero_section['making_subtitle'] ?? '<p>Plant. Hand. Form.</p>'); ?>
                        </div>
                        <div class="hero-description">
                            <?php echo wp_kses_post($making_hero_section['making_description'] ?? '<p>A slow conversion of raw plant matter into clothing with presence.</p>'); ?>
                        </div>
                        <div class="hero-buttons">
                            <?php 
                            $primary_btn = $making_hero_section['making_primary_button'] ?? [];
                            $secondary_btn = $making_hero_section['making_secondary_button'] ?? [];
                            ?>
                            <a href="<?php echo esc_url($primary_btn['url'] ?? wc_get_page_permalink('shop')); ?>" class="btn btn-primary">
                                <?php echo esc_html($primary_btn['title'] ?? 'Discover the Pieces'); ?>
                            </a>
                            <a href="<?php echo esc_url($secondary_btn['url'] ?? 'https://loomaama.com.au/the-forest/'); ?>" class="btn btn-outline">
                                <?php echo esc_html($secondary_btn['title'] ?? 'Explore Himalayan Nettle'); ?>
                               
                            </a>
                        </div>
                    </div>
                </div>
                <div class="hero-media" data-aos="zoom-in" data-aos-duration="1200">
                    <?php 
                    $hero_image = $making_hero_section['making_hero_image'] ?? null;
                    if ($hero_image) : ?>
                        <div class="hero-image-wrapper">
                            <img src="<?php echo esc_url($hero_image['url']); ?>" alt="<?php echo esc_attr($hero_image['alt'] ?: 'Hero visual: hand, fibre, and garment'); ?>" class="hero-image">
                            <?php if ($hero_caption = $making_hero_section['making_hero_caption'] ?? '') : ?>
                                <div class="image-caption"><?php echo esc_html($hero_caption); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <div class="hero-placeholder editorial-image"></div>
                    <?php endif; ?>
                </div>
            </div>
           
        </div>
    </section>

    <!-- STRIP / ETHOS BAR -->
    <section class="making-strip">
        <div class="container-fluid">
            <div class="strip-grid">
                <?php 
                $strip_items = $making_strip_section['making_strip_items'] ?? [];
                if ($strip_items) : 
                    // Display items twice for infinite scroll effect
                    for ($loop = 0; $loop < 10; $loop++) :
                        foreach ($strip_items as $item) : ?>
                            <div class="strip-item"><?php echo esc_html($item['text']); ?></div>
                        <?php endforeach;
                    endfor;
                else : 
                    // Fallback items displayed twice
                    $fallback_items = ['Observe', 'Refine', 'Build', 'Resolve', 'Wear'];
                    for ($loop = 0; $loop < 10; $loop++) :
                        foreach ($fallback_items as $item) : ?>
                            <div class="strip-item"><?php echo esc_html($item); ?></div>
                        <?php endforeach;
                    endfor;
                endif; ?>
            </div>
        </div>
    </section>

    <!-- ATELIER NOTE SECTION -->
    <section class="making-atelier">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="sticky-note">
                        <span class="eyebrow"><?php echo esc_html($atelier_section['atelier_eyebrow'] ?? 'Atelier Note'); ?></span>
                        <h2><?php echo esc_html($atelier_section['atelier_title'] ?? 'Not assembled. Composed.'); ?></h2>
                    </div>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <p class="display-6"><?php echo esc_html($atelier_section['atelier_lead'] ?? 'Before a Loomaama piece reaches the body, it passes through quiet decisions.'); ?></p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="copy"><?php echo esc_html($atelier_section['atelier_text_left'] ?? 'What to keep. What to remove. Where to add tension. Where to allow ease. When to stop.'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="copy"><?php echo esc_html($atelier_section['atelier_text_right'] ?? 'The work is physical, but never careless. Every movement has a purpose.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- NARRATIVE CHAPTERS (Repeater Field) -->
    <section class="making-chapters">
        <?php 
        $chapters = $making_chapters;
        if ($chapters) : 
            $chapter_index = 0;
            foreach ($chapters as $chapter) : 
                $alignment = ($chapter_index % 2 == 0) ? 'default' : 'flex-row-reverse';
                $image = $chapter['image'];
                ?>
                <div class="chapter-block" data-aos="fade-up">
                    <div class="container">
                        <div class="row align-items-center <?php echo esc_attr($alignment); ?> ">
                            <div class="col-lg-6 chapter-media">
                                <?php if ($image) : ?>
                                    <div class="chapter-image-wrapper">
                                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?: $chapter['title']); ?>" class="chapter-image">
                                        <?php if ($chapter['caption']) : ?>
                                            <div class="image-caption"><?php echo esc_html($chapter['caption']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="chapter-placeholder editorial-image"></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-6 chapter-content">
                                <span class="eyebrow"><?php echo esc_html($chapter['eyebrow']); ?></span>
                                <h2><?php echo esc_html($chapter['title']); ?></h2>
                                <div class="chapter-text"><?php echo wp_kses_post($chapter['description']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php 
                $chapter_index++;
            endforeach;
        else : ?>
            <!-- Default fallback chapters if ACF repeater is empty -->
            <div class="chapter-block chapter-default" data-aos="fade-up"><div class="container"><div class="row align-items-center g-5"><div class="col-lg-6 chapter-media"><div class="chapter-placeholder editorial-image"></div></div><div class="col-lg-6 chapter-content"><span class="eyebrow">01 — Material Reading</span><h2>The fibre speaks before the design does.</h2><div class="chapter-text"><p>Every Loomaama piece begins with observation. The fibre is handled before it is shaped.</p></div></div></div></div></div>
            <div class="chapter-block chapter-reverse" data-aos="fade-up"><div class="container"><div class="row align-items-center g-5"><div class="col-lg-6 chapter-media"><div class="chapter-placeholder editorial-image"></div></div><div class="col-lg-6 chapter-content"><span class="eyebrow">02 — The First Edit</span><h2>Rawness is not removed. It is refined.</h2><div class="chapter-text"><p>The fibre is cleaned, loosened, arranged, and prepared until it can move from raw state into a workable form.</p></div></div></div></div></div>
        <?php endif; ?>
    </section>

    <!-- DECISIONS / DEEP SECTION -->
    <section class="making-deep">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="sticky-note">
                        <span class="eyebrow"><?php echo esc_html($deep_section['deep_eyebrow'] ?? 'What the wearer receives'); ?></span>
                        <h2 class="deep-title"><?php echo esc_html($deep_section['deep_title'] ?? 'Not only a garment. A sequence of decisions.'); ?></h2>
                    </div>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="decision-list">
                        <?php 
                        $decisions = $deep_section['making_decisions'] ?? [];
                        if ($decisions) :
                            foreach ($decisions as $index => $decision) : ?>
                                <div class="decision-row">
                                    <div class="decision-number"><?php echo sprintf("%02d", $index + 1); ?></div>
                                    <p class="decision-text"><?php echo esc_html($decision['text']); ?></p>
                                </div>
                        <?php endforeach;
                        else : ?>
                            <div class="decision-row"><div class="decision-number">01</div><p class="decision-text">The judgement used to select the fibre.</p></div>
                            <div class="decision-row"><div class="decision-number">02</div><p class="decision-text">The restraint used to prepare it.</p></div>
                            <div class="decision-row"><div class="decision-number">03</div><p class="decision-text">The tension used to form it.</p></div>
                            <div class="decision-row"><div class="decision-number">04</div><p class="decision-text">The patience used to shape it.</p></div>
                            <div class="decision-row"><div class="decision-number">05</div><p class="decision-text">The attention used to complete it.</p></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WHY IT MATTERS SECTION -->
    <section class="making-why">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="zoom-in">
                    <?php 
                    $why_image = $why_section['why_image'] ?? null;
                    if ($why_image) : ?>
                        <div class="why-image-wrapper">
                            <img src="<?php echo esc_url($why_image['url']); ?>" alt="<?php echo esc_attr($why_image['alt'] ?: 'Finished piece: composed, balanced'); ?>" class="why-image">
                        </div>
                    <?php else : ?>
                        <div class="hero-placeholder editorial-image tall"></div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <span class="eyebrow"><?php echo esc_html($why_section['why_eyebrow'] ?? 'Why it matters'); ?></span>
                    <h2 class="why-title"><?php echo esc_html($why_section['why_title'] ?? 'The future of luxury will belong to what has depth.'); ?></h2>
                    <div class="why-text">
                        <?php 
                        $why_paragraphs = $why_section['why_paragraphs'] ?? [];
                        if ($why_paragraphs) {
                            foreach ($why_paragraphs as $paragraph) {
                                echo '<p class="copy">' . esc_html($paragraph['paragraph']) . '</p>';
                            }
                        } else { ?>
                            <p class="copy">Fast clothing is built to arrive quickly. Loomaama is built to remain.</p>
                            <p class="copy">The making process is slower because the material deserves to be understood before it is transformed.</p>
                            <p class="quote-line">Production repeats. Making interprets.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CLOSING CTA SECTION -->
    <section class="making-closing">
        <div class="container text-center">
            <span class="eyebrow"><?php echo esc_html($closing_section['closing_eyebrow'] ?? 'Closing'); ?></span>
            <h2 class="display-5"><?php echo esc_html($closing_section['closing_title'] ?? 'From living fibre to lasting form.'); ?></h2>
            <div class="closing-text">
                <?php 
                $closing_text = $closing_section['closing_text'] ?? '';
                if ($closing_text) {
                    echo '<p class="copy">' . esc_html($closing_text) . '</p>';
                } else { ?>
                    <p class="copy">The making of Loomaama is a practice of listening: to the material, to the hand, and to the garment as it takes shape.</p>
                    <p class="copy">Nothing is added without purpose. Nothing is rushed for convenience. Nothing is finished until the piece feels resolved.</p>
                <?php } ?>
            </div>
            <div class="closing-buttons">
                <a href="<?php echo esc_url($closing_section['closing_button_primary_url'] ?? wc_get_page_permalink('shop')); ?>" class="btn btn-primary">
                    <?php echo esc_html($closing_section['closing_button_primary_text'] ?? 'Explore the Collection'); ?>
                </a>
                <a href="<?php echo esc_url($closing_section['closing_button_secondary_url'] ?? '#'); ?>" class="btn btn-outline">
                    <?php echo esc_html($closing_section['closing_button_secondary_text'] ?? 'Meet the Artisans'); ?>
                   
                </a>
            </div>
        </div>
    </section>
</main>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '#/' && href !== '') {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });
});
</script>

<?php get_footer(); ?>