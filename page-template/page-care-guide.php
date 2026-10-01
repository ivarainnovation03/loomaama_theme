<?php
/**
 * Template Name: Care Guide
 *
 * @package Loomaama
 */

get_header();
?>

<main>
        <!-- HERO SECTION - SPLIT LAYOUT -->
    <section class="hero-editorial" data-aos="zoom-in" data-aos-delay="100">
        <div class="hero-editorial-content reveal" data-aos="fade-up" data-aos-delay="200">
            <div class="eyebrow"><?php echo esc_html(get_field('care_hero_eyebrow') ?: 'Care Guide'); ?></div>
            <h1 class="headline" data-aos="fade-up" data-aos-delay="300">
                <?php echo wp_kses_post(get_field('care_hero_title') ?: 'Make It Last Beautifully'); ?>
            </h1>
            <p data-aos="fade-up" data-aos-delay="400">
                <?php echo wp_kses_post(get_field('care_hero_subtitle') ?: 'Care is the final part of making. Your garment was made slowly. Care for it with the same attention.'); ?>
            </p>
        </div>
        <div class="hero-editorial-image">
            <?php $hero_image = get_field('care_hero_image'); ?>
            <?php if ($hero_image): ?>
                <img src="<?php echo esc_url($hero_image); ?>" alt="LOOMAAMA garment care">
            <?php else: ?>
                <img src="https://images.unsplash.com/photo-1515488764276-beab7607c1e6?auto=format&fit=crop&w=1600&q=80" alt="Care guide image">
            <?php endif; ?>
        </div>
    </section>

    <!-- SECTION 1: MACHINE WASH -->
    <section class="section split">
        <div class="image-card img-machine" 
             style="background-image: url('<?php echo esc_url(get_field('care_machine_image') ?: 'https://images.unsplash.com/photo-1604076913837-52ab5629fba9?auto=format&fit=crop&w=1400&q=80'); ?>');">
        </div>
        <div class="content-block">
            <div class="eyebrow"><?php echo esc_html(get_field('care_machine_eyebrow') ?: 'Machine Wash'); ?></div>
            <h2><?php echo wp_kses_post(get_field('care_machine_title') ?: 'When Needed, Wash Gently'); ?></h2>
            <p class="lead"><?php echo wp_kses_post(get_field('care_machine_lead') ?: 'Use the gentlest setting to protect the natural structure of Himalayan nettle cloth.'); ?></p>
            <div class="steps">
                <?php 
                $machine_steps = get_field('care_machine_steps');
                if ($machine_steps):
                    $step_num = 1;
                    foreach ($machine_steps as $step):
                ?>
                <div class="step">
                    <div class="num"><?php echo str_pad($step_num, 2, '0', STR_PAD_LEFT); ?></div>
                    <div><?php echo esc_html($step['step_text']); ?></div>
                </div>
                <?php 
                    $step_num++;
                    endforeach;
                else:
                    // Default steps
                    $default_steps = [
                        'Turn the garment inside out.',
                        'Place it inside a delicate laundry bag.',
                        'Use cold water and select a gentle cycle.',
                        'Use a mild natural detergent.',
                        'Do not use bleach, fabric softener, or harsh chemicals.',
                        'Remove promptly and reshape while damp.'
                    ];
                    foreach ($default_steps as $idx => $step_text):
                ?>
                <div class="step">
                    <div class="num"><?php echo str_pad($idx+1, 2, '0', STR_PAD_LEFT); ?></div>
                    <div><?php echo esc_html($step_text); ?></div>
                </div>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
        </div>
    </section>

    <!-- SECTION 2: HAND WASH -->
    <section class="section split full">
        <div class="content-block">
            <div class="eyebrow"><?php echo esc_html(get_field('care_hand_eyebrow') ?: 'Hand Wash'); ?></div>
            <h2><?php echo wp_kses_post(get_field('care_hand_title') ?: 'The Most Respectful Care'); ?></h2>
            <p class="lead"><?php echo wp_kses_post(get_field('care_hand_lead') ?: 'Hand washing is preferred when your garment needs a slower, softer ritual of care.'); ?></p>
            <div class="steps">
                <?php 
                $hand_steps = get_field('care_hand_steps');
                if ($hand_steps):
                    $step_num = 1;
                    foreach ($hand_steps as $step):
                ?>
                <div class="step">
                    <div class="num"><?php echo str_pad($step_num, 2, '0', STR_PAD_LEFT); ?></div>
                    <div><?php echo esc_html($step['step_text']); ?></div>
                </div>
                <?php 
                    $step_num++;
                    endforeach;
                else:
                    $default_steps = [
                        'Fill a clean basin with cool water.',
                        'Add a small amount of mild detergent.',
                        'Move the garment gently through the water.',
                        'Do not scrub, twist, or wring.',
                        'Rinse with cool water.',
                        'Press out excess water softly by hand.'
                    ];
                    foreach ($default_steps as $idx => $step_text):
                ?>
                <div class="step">
                    <div class="num"><?php echo str_pad($idx+1, 2, '0', STR_PAD_LEFT); ?></div>
                    <div><?php echo esc_html($step_text); ?></div>
                </div>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
        </div>
        <div class="image-card img-hand" 
             style="background-image: url('<?php echo esc_url(get_field('care_hand_image') ?: 'https://images.unsplash.com/photo-1582735689369-4fe89db7114c?auto=format&fit=crop&w=1400&q=80'); ?>');">
        </div>
    </section>

    <!-- SECTION 3: DRY NATURALLY -->
    <section class="section center">
        <div class="eyebrow"><?php echo esc_html(get_field('care_dry_eyebrow') ?: 'Dry Naturally'); ?></div>
        <h2><?php echo wp_kses_post(get_field('care_dry_title') ?: 'Let Air Finish the Process'); ?></h2>
        <p class="lead"><?php echo wp_kses_post(get_field('care_dry_lead') ?: 'Lay flat, reshape gently, and dry in shade with natural airflow. Avoid tumble drying and strong direct sunlight.'); ?></p>
        <div class="full-image" 
             style="background-image: url('<?php echo esc_url(get_field('care_dry_image') ?: 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1800&q=80'); ?>');">
        </div>
    </section>

    <!-- SECTION 4: TWO CARDS (Refresh & Storage) -->
    <section class="section">
        <div class="two-card">
            <div class="card">
                <div class="eyebrow"><?php echo esc_html(get_field('care_refresh_eyebrow') ?: 'Refresh Between Wears'); ?></div>
                <h2><?php echo wp_kses_post(get_field('care_refresh_title') ?: 'Not Every Wear Needs a Wash'); ?></h2>
                <p><?php echo wp_kses_post(get_field('care_refresh_text') ?: 'Hang your garment in fresh air, away from harsh dust. Nettle cloth benefits from storage, breath, and time.'); ?></p>
            </div>
            <div class="card">
                <div class="eyebrow"><?php echo esc_html(get_field('care_storage_eyebrow') ?: 'Storage'); ?></div>
                <h2><?php echo wp_kses_post(get_field('care_storage_title') ?: 'Store With Care'); ?></h2>
                <p><?php echo wp_kses_post(get_field('care_storage_text') ?: 'Make sure the garment is completely dry. Fold heavier pieces instead of hanging them. Keep in a cool, dry place and use breathable cotton or fabric storage bags. Avoid plastic covers for long-term storage.'); ?></p>
            </div>
        </div>
    </section>

    <!-- SECTION 5: DO / DO NOT NOTES -->
    <section class="section full">
        <div class="notes">
            <div class="note-box">
                <h3><?php echo esc_html(get_field('care_do_title') ?: 'Do'); ?></h3>
                <?php 
                $do_items = get_field('care_do_items');
                if ($do_items):
                    foreach ($do_items as $item):
                ?>
                <div><?php echo esc_html($item['item_text']); ?></div>
                <?php 
                    endforeach;
                else:
                    $default_dos = ['Cold wash', 'Use mild detergent', 'Air dry', 'Store dry', 'Handle gently'];
                    foreach ($default_dos as $do_text):
                ?>
                <div><?php echo esc_html($do_text); ?></div>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
            <div class="note-box">
                <h3><?php echo esc_html(get_field('care_dont_title') ?: 'Do Not'); ?></h3>
                <?php 
                $dont_items = get_field('care_dont_items');
                if ($dont_items):
                    foreach ($dont_items as $item):
                ?>
                <div><?php echo esc_html($item['item_text']); ?></div>
                <?php 
                    endforeach;
                else:
                    $default_donts = ['Bleach', 'Tumble dry', 'Wring', 'Use fabric softener', 'Dry under harsh sunlight'];
                    foreach ($default_donts as $dont_text):
                ?>
                <div><?php echo esc_html($dont_text); ?></div>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
        </div>
    </section>

    <!-- CLOSING CTA -->
    <section class="cta bg-card">
        <div class="eyebrow"><?php echo esc_html(get_field('care_cta_eyebrow') ?: 'Made to Last Beyond Seasons'); ?></div>
        <h2><?php echo wp_kses_post(get_field('care_cta_title') ?: 'Wear Well. Care Well.'); ?></h2>
        <p><?php echo wp_kses_post(get_field('care_cta_text') ?: 'A well-cared-for garment rewards you with years of comfort and beauty.'); ?></p>
        <p><?php echo wp_kses_post(get_field('care_cta_subtext') ?: 'Need help caring for your garment?<br>Contact LOOMAAMA for personal garment care guidance.'); ?></p>
        <a class="button" href="<?php echo esc_url(get_field('care_cta_button_link') ?: '#'); ?>">
            <?php echo esc_html(get_field('care_cta_button_text') ?: 'Become a Witness'); ?>
        </a>
    </section>
</main>

<?php get_footer(); ?>