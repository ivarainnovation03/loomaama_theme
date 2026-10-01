<?php
/**
 * Template Name: loomaama Archive
 *
 * @package Loomaama
 */

get_header();
?>

<main>
    <!-- HERO SECTION -->
    <header class="archive-hero">
        <div class="archive-hero-inner">
            <div class="eyebrow"><?php echo esc_html(get_field('archive_hero_eyebrow') ?: 'loomaama Archive'); ?></div>
            <h1><?php echo wp_kses_post(get_field('archive_hero_title') ?: 'Company History'); ?></h1>
            <p><?php echo wp_kses_post(get_field('archive_hero_subtitle') ?: 'A record of wild nettle, handwoven cloth, people, place, and the quiet decisions that shaped loomaama.'); ?></p>
            <div class="caption"><?php echo wp_kses_post(get_field('archive_hero_caption') ?: 'Archive image: early nettle cloth study, recorded as part of loomaama\'s material language.'); ?></div>
        </div>
        <?php $hero_image = get_field('archive_hero_image'); ?>
        <?php if ($hero_image): ?>
        <style>
            .archive-hero { background-image: linear-gradient(rgba(0,0,0,0.18), rgba(0,0,0,0.28)), url('<?php echo esc_url($hero_image); ?>'); }
        </style>
        <?php endif; ?>
    </header>

    <!-- COMMITMENTS SECTION -->
    <section class="archive-commitments">
        <?php 
        $commitments = get_field('archive_commitments');
        if ($commitments):
            foreach ($commitments as $commitment):
        ?>
        <div class="commitment">
            <div class="commitment-icon">
                <?php if ($commitment['icon_class']): ?>
                    <i class="<?php echo esc_attr($commitment['icon_class']); ?>"></i>
                <?php endif; ?>
            </div>
            <p><?php echo esc_html($commitment['text']); ?></p>
        </div>
        <?php 
            endforeach;
        else:
        ?>
        <div class="commitment"><div class="commitment-icon"></div><p>We work with Himalayan nettle cloth.</p></div>
        <div class="commitment"><div class="commitment-icon"></div><p>We honour hand-guided making.</p></div>
        <div class="commitment"><div class="commitment-icon"></div><p>We keep the maker visible.</p></div>
        <div class="commitment"><div class="commitment-icon"></div><p>We protect the memory of place.</p></div>
        <div class="commitment"><div class="commitment-icon"></div><p>We design for longer life.</p></div>
        <?php endif; ?>
    </section>

    <!-- INTRO SECTION -->
    <section class="archive-section">
        <div class="archive-intro">
            <div class="eyebrow"><?php echo esc_html(get_field('archive_intro_eyebrow') ?: 'Our Archive'); ?></div>
            <h2><?php echo wp_kses_post(get_field('archive_intro_title') ?: 'Not a catalogue. A record of becoming.'); ?></h2>
            <p><?php echo wp_kses_post(get_field('archive_intro_text') ?: 'This page follows the material story behind loomaama: the plant before fibre, the fibre before thread, the thread before cloth, and the hands behind each finished piece.'); ?></p>
        </div>
    </section>

    <!-- TIMELINE SECTION -->
    <section class="archive-timeline">
        <?php 
        $timeline_items = get_field('archive_timeline');
        if ($timeline_items):
            foreach ($timeline_items as $index => $item):
                $year_num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                $image_position = $item['image_position'] ?? 'right';
        ?>
        <div class="time-block">
            <div class="year"><?php echo esc_html($year_num); ?></div>
            <div class="story <?php echo esc_attr($image_position === 'left' ? 'story-reverse' : ''); ?>">
                <div class="story-img" style="background-image: url('<?php echo esc_url($item['image']); ?>');"></div>
                <div class="story-copy">
                    <div class="meta"><?php echo esc_html($item['category']); ?></div>
                    <h3><?php echo esc_html($item['title']); ?></h3>
                    <p><?php echo wp_kses_post($item['description']); ?></p>
                </div>
            </div>
        </div>
        <?php 
            endforeach;
        else:
            // Default timeline items
        ?>
        <div class="time-block">
            <div class="year">01</div>
            <div class="story">
                <div class="story-img" style="background-image: url('https://images.unsplash.com/photo-1515488764276-beab7607c1e6?auto=format&fit=crop&w=1200&q=80');"></div>
                <div class="story-copy">
                    <div class="meta">Material Origin</div>
                    <h3>Raw Himalayan Nettle Fibre</h3>
                    <p>Before it becomes cloth, nettle begins as a strong wild fibre. The archive keeps this stage visible because the material is the foundation of every LOOMAAMA piece.</p>
                </div>
            </div>
        </div>
        <div class="time-block">
            <div class="year">02</div>
            <div class="story story-reverse">
                <div class="story-copy">
                    <div class="meta">Foundation Cloth</div>
                    <h3>The First Nettle Cloth</h3>
                    <p>Early cloth studies shaped our understanding of weight, texture, tension, and fall. These records are not samples alone. They are the beginning of a design language.</p>
                </div>
                <div class="story-img" style="background-image: url('https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=1200&q=80');"></div>
            </div>
        </div>
        <div class="time-block">
            <div class="year">03</div>
            <div class="story">
                <div class="story-img" style="background-image: url('https://images.unsplash.com/photo-1582735689369-4fe89db7114c?auto=format&fit=crop&w=1200&q=80');"></div>
                <div class="story-copy">
                    <div class="meta">People</div>
                    <h3>The Weaver's Hand</h3>
                    <p>LOOMAAMA exists because of skilled hands. The archive records movement, rhythm, tools, and the knowledge carried by artisans through practice.</p>
                </div>
            </div>
        </div>
        <div class="time-block">
            <div class="year">04</div>
            <div class="story story-reverse">
                <div class="story-copy">
                    <div class="meta">Place</div>
                    <h3>Where Nettle Grows Wild</h3>
                    <p>Place is part of the garment. The highland landscape, seasons, and plant life are recorded as part of the cloth's origin.</p>
                </div>
                <div class="story-img" style="background-image: url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80');"></div>
            </div>
        </div>
        <div class="time-block">
            <div class="year">05</div>
            <div class="story">
                <div class="story-img" style="background-image: url('https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1200&q=80');"></div>
                <div class="story-copy">
                    <div class="meta">Cloth Detail</div>
                    <h3>Texture, Knot, and Fall</h3>
                    <p>Close studies of surface and structure help us understand how handmade nettle cloth behaves on the body and changes with wear.</p>
                </div>
            </div>
        </div>
        <div class="time-block">
            <div class="year">06</div>
            <div class="story story-reverse">
                <div class="story-copy">
                    <div class="meta">Continuing Record</div>
                    <h3>Each Garment Adds Evidence</h3>
                    <p>The archive will continue as LOOMAAMA grows. Every new piece, artisan record, fibre study, and material experiment becomes part of the story.</p>
                </div>
                <div class="story-img" style="background-image: url('https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1200&q=80');"></div>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- FULL IMAGE SECTION -->
    <?php $full_image = get_field('archive_full_image'); ?>
    <section class="archive-full-image" style="background-image: linear-gradient(rgba(244,238,228,0.08), rgba(244,238,228,0.08)), url('<?php echo esc_url($full_image ?: 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=2000&q=80'); ?>');"></section>

    <!-- QUOTE SECTION -->
    <section class="archive-quote">
        <div class="eyebrow"><?php echo esc_html(get_field('archive_quote_eyebrow') ?: 'Archive Statement'); ?></div>
        <h2><?php echo wp_kses_post(get_field('archive_quote_text') ?: 'The cloth remembers the hand, the plant, and the place it came from.'); ?></h2>
        <p><?php echo wp_kses_post(get_field('archive_quote_subtext') ?: 'LOOMAAMA\'s archive exists to keep that memory visible.'); ?></p>
    </section>

    <!-- ARCHIVE INDEX SECTION -->
    <section class="archive-index">
        <div class="archive-intro">
            <div class="eyebrow"><?php echo esc_html(get_field('archive_index_eyebrow') ?: 'Archive Index'); ?></div>
            <h2><?php echo wp_kses_post(get_field('archive_index_title') ?: 'Records by Category'); ?></h2>
        </div>
        <div class="archive-list">
            <?php 
            $index_items = get_field('archive_index_items');
            if ($index_items):
                foreach ($index_items as $item):
            ?>
            <div class="archive-row">
                <span><?php echo esc_html($item['category']); ?></span>
                <strong><?php echo esc_html($item['title']); ?></strong>
                <p><?php echo esc_html($item['description']); ?></p>
            </div>
            <?php 
                endforeach;
            else:
            ?>
            <div class="archive-row"><span>Material</span><strong>Raw Himalayan Nettle Fibre</strong><p>Fibre study before spinning.</p></div>
            <div class="archive-row"><span>Material</span><strong>The First Nettle Cloth</strong><p>Foundation cloth and early material direction.</p></div>
            <div class="archive-row"><span>Process</span><strong>Thread Before Surface</strong><p>Line, tension, and structure.</p></div>
            <div class="archive-row"><span>People</span><strong>The Weaver's Hand</strong><p>Hands, tools, and craft knowledge.</p></div>
            <div class="archive-row"><span>Place</span><strong>Where Nettle Grows Wild</strong><p>Landscape, season, and origin.</p></div>
            <div class="archive-row"><span>Records</span><strong>Texture, Knot, and Fall</strong><p>Surface studies of handmade nettle cloth.</p></div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="archive-cta">
        <div class="eyebrow"><?php echo esc_html(get_field('archive_cta_eyebrow') ?: 'The Archive Will Continue'); ?></div>
        <h2><?php echo wp_kses_post(get_field('archive_cta_title') ?: 'Each garment adds another record.'); ?></h2>
        <p><?php echo wp_kses_post(get_field('archive_cta_text') ?: 'LOOMAAMA is built through material, place, and people. The archive keeps that story visible.'); ?></p>
        <a class="archive-button" href="<?php echo esc_url(get_field('archive_cta_button_link') ?: wc_get_page_permalink('shop')); ?>"><?php echo esc_html(get_field('archive_cta_button_text') ?: 'Become a Witness'); ?></a>
    </section>
</main>

<?php get_footer(); ?>