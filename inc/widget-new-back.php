<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}



// -------------------------------------------------------------------
// 2. HELPER: IMAGE URL HANDLER
// -------------------------------------------------------------------
function loomaama_get_image_url( $image_field, $default ) {
    if ( ! empty( $image_field ) && is_numeric( $image_field ) ) {
        $image_src = wp_get_attachment_image_url( $image_field, 'full' );
        if ( $image_src ) {
            return $image_src;
        }
    }
    return $default;
}

// -------------------------------------------------------------------
// 3. HELPER: SOUND TOGGLE SVG
// -------------------------------------------------------------------
function loomaama_sound_icons() {
    ob_start();
    ?>
    <svg class="sound-icon sound-off" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M3 18v-6a9 9 0 0 1 18 0v6" />
        <path d="M3 18h18" />
        <path d="M12 3v3" />
        <path d="M8 21l4-3 4 3" />
    </svg>
    <svg class="sound-icon sound-on" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
        <path d="M3 18v-6a9 9 0 0 1 18 0v6" />
        <path d="M3 18h18" />
        <path d="M12 3v3" />
        <path d="M8 21l4-3 4 3" />
        <path d="M7 10l10 8" />
        <path d="M17 10l-10 8" />
    </svg>
    <?php
    return ob_get_clean();
}

// -------------------------------------------------------------------
// 4. WIDGET REGISTRATION
// -------------------------------------------------------------------
add_action( 'widgets_init', 'loomaama_register_customizable_widgets' );
function loomaama_register_customizable_widgets() {
    register_widget( 'Loomaama_Hero_widget_new' );
    register_widget( 'Loomaama_Nettle_Journey_widget_new' );
    register_widget( 'Loomaama_Founder_widget_new' );
    register_widget( 'Loomaama_Mountain_widget_new' );
    register_widget( 'Loomaama_Forest_widget_new' );
    register_widget( 'Loomaama_Impact_widget_new' );
    register_widget( 'Loomaama_Artisans_widget_new' );
    register_widget( 'Loomaama_Collection_widget_new' );
}

// ===================================================================
// 5. HERO WIDGET - FULLY CUSTOMIZABLE
// ===================================================================
class Loomaama_Hero_widget_new extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'loomaama_hero_widget_new',
            'Loomaama: Hero Section (Customizable)',
            array(
                'description' => 'Hero section with customizable video, title, subtitle, and eyebrow text.',
                'customize_selective_refresh' => true,
                'show_instance_in_rest' => true,
            )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'From Forest<br />to Form';
        $subtitle = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : 'A scroll-led journey through the mountains, forests, fibres and hands behind every Loomaama piece.';
        $eyebrow = ! empty( $instance['eyebrow'] ) ? $instance['eyebrow'] : 'Wild Himalayan Nettle';
        $video_url = ! empty( $instance['video_url'] ) ? $instance['video_url'] : trailingslashit( get_template_directory_uri() . '/inc' ) . 'videos/Hero%20Video%20Final.mp4';
        $scroll_text = ! empty( $instance['scroll_text'] ) ? $instance['scroll_text'] : 'Scroll to begin ↓';

        echo $args['before_widget'] ?? '';
        ?>
        <section class="hero">
            <video id="heroVideo" class="hero-video" autoplay muted loop playsinline preload="metadata">
                <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
                <h1><?php echo $title; ?></h1>
                <p><?php echo esc_html( $subtitle ); ?></p>
            </div>
            <div class="scroll-cue"><?php echo esc_html( $scroll_text ); ?></div>
        </section>

        <button id="soundToggle" class="sound-toggle" aria-label="Toggle sound">
            <?php echo loomaama_sound_icons(); ?>
            <span class="sound-label"></span>
        </button>
        <?php
        echo $args['after_widget'] ?? '';
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'From Forest<br />to Form';
        $subtitle = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : 'A scroll-led journey through the mountains, forests, fibres and hands behind every Loomaama piece.';
        $eyebrow = ! empty( $instance['eyebrow'] ) ? $instance['eyebrow'] : 'Wild Himalayan Nettle';
        $video_url = ! empty( $instance['video_url'] ) ? $instance['video_url'] : '';
        $scroll_text = ! empty( $instance['scroll_text'] ) ? $instance['scroll_text'] : 'Scroll to begin ↓';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'eyebrow' ); ?>">Eyebrow Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'eyebrow' ); ?>" name="<?php echo $this->get_field_name( 'eyebrow' ); ?>" type="text" value="<?php echo esc_attr( $eyebrow ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title (HTML allowed):</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            <small>Use &lt;br /&gt; for line breaks</small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'subtitle' ); ?>">Subtitle:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" type="text" value="<?php echo esc_attr( $subtitle ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'scroll_text' ); ?>">Scroll Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'scroll_text' ); ?>" name="<?php echo $this->get_field_name( 'scroll_text' ); ?>" type="text" value="<?php echo esc_attr( $scroll_text ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'video_url' ); ?>">Video URL (MP4):</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'video_url' ); ?>" name="<?php echo $this->get_field_name( 'video_url' ); ?>" type="url" value="<?php echo esc_url( $video_url ); ?>" />
            <small>Leave empty to use default video</small>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = wp_kses_post( ($new_instance['title'] ?? '') );
        $instance['subtitle'] = sanitize_text_field( ($new_instance['subtitle'] ?? '') );
        $instance['eyebrow'] = sanitize_text_field( ($new_instance['eyebrow'] ?? '') );
        $instance['video_url'] = esc_url_raw( ($new_instance['video_url'] ?? '') );
        $instance['scroll_text'] = sanitize_text_field( ($new_instance['scroll_text'] ?? '') );
        return $instance;
    }
}

// ===================================================================
// 6. NETTLE JOURNEY WIDGET - CUSTOMIZABLE
// ===================================================================
class Loomaama_Nettle_Journey_widget_new extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'loomaama_nettle_journey_widget_new',
            'Loomaama: Nettle Journey (Customizable)',
            array(
                'description' => 'Nettle journey section with customizable headline, description, and CTA.',
                'customize_selective_refresh' => true,
                'show_instance_in_rest' => true,
            )
        );
    }

    public function widget( $args, $instance ) {
        $eyebrow = ! empty( $instance['eyebrow'] ) ? $instance['eyebrow'] : 'THE NETTLE JOURNEY';
        $headline = ! empty( $instance['headline'] ) ? $instance['headline'] : 'Rare Himalayan nettle, hand-shaped into skin-conscious luxury.';
        $description = ! empty( $instance['description'] ) ? $instance['description'] : 'We create garments that honour the body, respect the earth, and uplift the maker.';
        $cta_text = ! empty( $instance['cta_text'] ) ? $instance['cta_text'] : 'Read the Nettle Journey';
        $cta_url = ! empty( $instance['cta_url'] ) ? $instance['cta_url'] : 'https://loomaama.com.au/the-truth/';
        $tags = ! empty( $instance['tags'] ) ? explode( ',', $instance['tags'] ) : array( '🌿', '✋', '🌍' );

        echo $args['before_widget'] ?? '';
        ?>
        <section class="nettle-journey">
            <div class="tags-background">
                <?php foreach ( $tags as $tag ) : ?>
                    <div class="tag-float"><?php echo esc_html( trim( $tag ) ); ?></div>
                <?php endforeach; ?>
            </div>
            <div class="nettle-container">
                <div class="nettle-content">
                    <div class="nettle-eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
                    <h2 class="nettle-headline"><?php echo esc_html( $headline ); ?></h2>
                    <p class="nettle-description"><?php echo esc_html( $description ); ?></p>
                    <a href="<?php echo esc_url( $cta_url ); ?>" class="nettle-cta">
                        <?php echo esc_html( $cta_text ); ?> <span class="nettle-arrow"></span>
                    </a>
                </div>
            </div>
        </section>
        <?php
        echo $args['after_widget'] ?? '';
    }

    public function form( $instance ) {
        $eyebrow = ! empty( $instance['eyebrow'] ) ? $instance['eyebrow'] : 'THE NETTLE JOURNEY';
        $headline = ! empty( $instance['headline'] ) ? $instance['headline'] : 'Rare Himalayan nettle, hand-shaped into skin-conscious luxury.';
        $description = ! empty( $instance['description'] ) ? $instance['description'] : 'We create garments that honour the body, respect the earth, and uplift the maker.';
        $cta_text = ! empty( $instance['cta_text'] ) ? $instance['cta_text'] : 'Read the Nettle Journey';
        $cta_url = ! empty( $instance['cta_url'] ) ? $instance['cta_url'] : 'https://loomaama.com.au/the-truth/';
        $tags = ! empty( $instance['tags'] ) ? $instance['tags'] : '🌿, ✋, 🌍';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'eyebrow' ); ?>">Eyebrow:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'eyebrow' ); ?>" name="<?php echo $this->get_field_name( 'eyebrow' ); ?>" type="text" value="<?php echo esc_attr( $eyebrow ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'headline' ); ?>">Headline:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'headline' ); ?>" name="<?php echo $this->get_field_name( 'headline' ); ?>" type="text" value="<?php echo esc_attr( $headline ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'description' ); ?>">Description:</label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" rows="3"><?php echo esc_textarea( $description ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_text' ); ?>">CTA Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_text' ); ?>" name="<?php echo $this->get_field_name( 'cta_text' ); ?>" type="text" value="<?php echo esc_attr( $cta_text ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_url' ); ?>">CTA URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_url' ); ?>" name="<?php echo $this->get_field_name( 'cta_url' ); ?>" type="url" value="<?php echo esc_url( $cta_url ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tags' ); ?>">Floating Tags (comma separated):</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" type="text" value="<?php echo esc_attr( $tags ); ?>" />
            <small>e.g. 🌿, ✋, 🌍</small>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['eyebrow'] = sanitize_text_field( ($new_instance['eyebrow'] ?? '') );
        $instance['headline'] = sanitize_text_field( ($new_instance['headline'] ?? '') );
        $instance['description'] = sanitize_textarea_field( ($new_instance['description'] ?? '') );
        $instance['cta_text'] = sanitize_text_field( ($new_instance['cta_text'] ?? '') );
        $instance['cta_url'] = esc_url_raw( ($new_instance['cta_url'] ?? '') );
        $instance['tags'] = sanitize_text_field( ($new_instance['tags'] ?? '') );
        return $instance;
    }
}

// ===================================================================
// 7. FOUNDER STORY WIDGET - CUSTOMIZABLE
// ===================================================================
class Loomaama_Founder_widget_new extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'loomaama_founder_widget_new',
            'Loomaama: Founder Story (Customizable)',
            array(
                'description' => 'Founder story with customizable images, text, and quotes for each step.',
                'customize_selective_refresh' => true,
                'show_instance_in_rest' => true,
            )
        );
    }

    public function widget( $args, $instance ) {
        $steps = array();
        for ( $i = 0; $i < 4; $i++ ) {
            $steps[] = array(
                'number' => ! empty( $instance[ 'step_' . $i . '_number' ] ) ? $instance[ 'step_' . $i . '_number' ] : sprintf( '0%d — The %s', $i + 1, [ 'Beginning', 'Discovery', 'Craft', 'Purpose' ][ $i ] ),
                'title' => ! empty( $instance[ 'step_' . $i . '_title' ] ) ? $instance[ 'step_' . $i . '_title' ] : [ 'A vision shaped by the mountains.', 'Finding beauty in what was already there.', 'Built slowly, with human hands.', 'Creating pieces that carry meaning.' ][ $i ],
                'text' => ! empty( $instance[ 'step_' . $i . '_text' ] ) ? $instance[ 'step_' . $i . '_text' ] : [ 
                    'Loomaama began with a simple belief: materials shaped by nature and knowledge carried through generations deserve a place in the modern world.',
                    'In the Himalayan landscape, wild nettle had long been transformed by local communities into durable natural fibre. The founder saw not a forgotten material, but a story waiting to be carried forward.',
                    'Rather than rushing production, Loomaama works with artisans whose skill, patience and instinct shape every stitch. Each garment preserves the character of the fibre and the individuality of the maker.',
                    'Today, Loomaama continues to connect Himalayan materials, traditional knowledge and contemporary design creating garments intended to be worn, valued and remembered.'
                ][ $i ],
                'quote' => ! empty( $instance[ 'step_' . $i . '_quote' ] ) ? $instance[ 'step_' . $i . '_quote' ] : [ 
                    '"I wanted every piece to feel connected to place, people and purpose."',
                    '',
                    '',
                    ''
                ][ $i ],
                'image' => ! empty( $instance[ 'step_' . $i . '_image' ] ) ? $instance[ 'step_' . $i . '_image' ] : trailingslashit( get_template_directory_uri() . '/inc' ) . 'images/Founder ' . ( $i + 1 ) . '.png',
                'caption' => ! empty( $instance[ 'step_' . $i . '_caption' ] ) ? $instance[ 'step_' . $i . '_caption' ] : [ 
                    'Founder & Creative Director',
                    'Discovering Himalayan Nettle',
                    'Master Artisans at Work',
                    'Purpose & Vision'
                ][ $i ]
            );
        }

        $kicker = ! empty( $instance['kicker'] ) ? $instance['kicker'] : 'From Where it Begins?';
        $cta_text = ! empty( $instance['cta_text'] ) ? $instance['cta_text'] : 'Discover the Vision';
        $cta_url = ! empty( $instance['cta_url'] ) ? $instance['cta_url'] : 'https://loomaama.com.au/the-truth/';

        echo $args['before_widget'] ?? '';
        ?>
        <section class="founder-story" id="founderStory" data-step="0">
            <div class="founder-sticky">
                <div class="founder-layout">
                    <div class="founder-visual">
                        <?php foreach ( $steps as $i => $step ) : ?>
                            <img class="founder-image <?php echo $i === 0 ? 'main' : 'step' . $i; ?>" src="<?php echo esc_url( $step['image'] ); ?>" alt="<?php echo esc_attr( $step['caption'] ); ?>" />
                        <?php endforeach; ?>
                        <div class="founder-grain"></div>
                        <div class="founder-caption"><?php echo esc_html( $steps[0]['caption'] ); ?></div>
                    </div>
                    <div class="founder-panel">
                        <div class="founder-kicker"><?php echo esc_html( $kicker ); ?></div>
                        <div class="founder-progress">
                            <?php for ( $i = 0; $i < 4; $i++ ) : ?>
                                <span class="<?php echo $i === 0 ? 'active' : ''; ?>"></span>
                            <?php endfor; ?>
                        </div>
                        <div class="story-wrap">
                            <?php foreach ( $steps as $i => $step ) : ?>
                                <article class="story-step <?php echo $i === 0 ? 'active' : ''; ?>">
                                    <span class="number"><?php echo esc_html( $step['number'] ); ?></span>
                                    <h2><?php echo esc_html( $step['title'] ); ?></h2>
                                    <p><?php echo esc_html( $step['text'] ); ?></p>
                                    <?php if ( ! empty( $step['quote'] ) ) : ?>
                                        <div class="story-quote"><?php echo esc_html( $step['quote'] ); ?></div>
                                    <?php endif; ?>
                                    <?php if ( $i === 3 ) : ?>
                                        <a href="<?php echo esc_url( $cta_url ); ?>" class="founder-cta"><?php echo esc_html( $cta_text ); ?> <span></span></a>
                                    <?php endif; ?>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        echo $args['after_widget'] ?? '';
    }

    public function form( $instance ) {
        $kicker = ! empty( $instance['kicker'] ) ? $instance['kicker'] : 'From Where it Begins?';
        $cta_text = ! empty( $instance['cta_text'] ) ? $instance['cta_text'] : 'Discover the Vision';
        $cta_url = ! empty( $instance['cta_url'] ) ? $instance['cta_url'] : 'https://loomaama.com.au/the-truth/';

        echo '<h4>Story Steps (4 steps)</h4>';
        for ( $i = 0; $i < 4; $i++ ) {
            $number = ! empty( $instance[ 'step_' . $i . '_number' ] ) ? $instance[ 'step_' . $i . '_number' ] : '';
            $title = ! empty( $instance[ 'step_' . $i . '_title' ] ) ? $instance[ 'step_' . $i . '_title' ] : '';
            $text = ! empty( $instance[ 'step_' . $i . '_text' ] ) ? $instance[ 'step_' . $i . '_text' ] : '';
            $quote = ! empty( $instance[ 'step_' . $i . '_quote' ] ) ? $instance[ 'step_' . $i . '_quote' ] : '';
            $image = ! empty( $instance[ 'step_' . $i . '_image' ] ) ? $instance[ 'step_' . $i . '_image' ] : '';
            $caption = ! empty( $instance[ 'step_' . $i . '_caption' ] ) ? $instance[ 'step_' . $i . '_caption' ] : '';
            ?>
            <div style="background:#f9f9f9; padding:12px; margin:8px 0; border-left:3px solid #9E8574;">
                <h5>Step <?php echo $i + 1; ?></h5>
                <p>
                    <label for="<?php echo $this->get_field_id( 'step_' . $i . '_number' ); ?>">Number Label:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'step_' . $i . '_number' ); ?>" name="<?php echo $this->get_field_name( 'step_' . $i . '_number' ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" placeholder="e.g. 01 — The Beginning" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'step_' . $i . '_title' ); ?>">Title:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'step_' . $i . '_title' ); ?>" name="<?php echo $this->get_field_name( 'step_' . $i . '_title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'step_' . $i . '_text' ); ?>">Text:</label>
                    <textarea class="widefat" id="<?php echo $this->get_field_id( 'step_' . $i . '_text' ); ?>" name="<?php echo $this->get_field_name( 'step_' . $i . '_text' ); ?>" rows="3"><?php echo esc_textarea( $text ); ?></textarea>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'step_' . $i . '_quote' ); ?>">Quote (optional):</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'step_' . $i . '_quote' ); ?>" name="<?php echo $this->get_field_name( 'step_' . $i . '_quote' ); ?>" type="text" value="<?php echo esc_attr( $quote ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'step_' . $i . '_image' ); ?>">Image URL:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'step_' . $i . '_image' ); ?>" name="<?php echo $this->get_field_name( 'step_' . $i . '_image' ); ?>" type="url" value="<?php echo esc_url( $image ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'step_' . $i . '_caption' ); ?>">Image Caption:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'step_' . $i . '_caption' ); ?>" name="<?php echo $this->get_field_name( 'step_' . $i . '_caption' ); ?>" type="text" value="<?php echo esc_attr( $caption ); ?>" />
                </p>
            </div>
            <?php
        }
        ?>
        <hr />
        <p>
            <label for="<?php echo $this->get_field_id( 'kicker' ); ?>">Kicker Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'kicker' ); ?>" name="<?php echo $this->get_field_name( 'kicker' ); ?>" type="text" value="<?php echo esc_attr( $kicker ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_text' ); ?>">CTA Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_text' ); ?>" name="<?php echo $this->get_field_name( 'cta_text' ); ?>" type="text" value="<?php echo esc_attr( $cta_text ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_url' ); ?>">CTA URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_url' ); ?>" name="<?php echo $this->get_field_name( 'cta_url' ); ?>" type="url" value="<?php echo esc_url( $cta_url ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        for ( $i = 0; $i < 4; $i++ ) {
            $instance[ 'step_' . $i . '_number' ] = sanitize_text_field( ($new_instance['step_' . $i . '_number'] ?? '') );
            $instance[ 'step_' . $i . '_title' ] = sanitize_text_field( ($new_instance['step_' . $i . '_title'] ?? '') );
            $instance[ 'step_' . $i . '_text' ] = sanitize_textarea_field( ($new_instance['step_' . $i . '_text'] ?? '') );
            $instance[ 'step_' . $i . '_quote' ] = sanitize_text_field( ($new_instance['step_' . $i . '_quote'] ?? '') );
            $instance[ 'step_' . $i . '_image' ] = esc_url_raw( ($new_instance['step_' . $i . '_image'] ?? '') );
            $instance[ 'step_' . $i . '_caption' ] = sanitize_text_field( ($new_instance['step_' . $i . '_caption'] ?? '') );
        }
        $instance['kicker'] = sanitize_text_field( ($new_instance['kicker'] ?? '') );
        $instance['cta_text'] = sanitize_text_field( ($new_instance['cta_text'] ?? '') );
        $instance['cta_url'] = esc_url_raw( ($new_instance['cta_url'] ?? '') );
        return $instance;
    }
}

// ===================================================================
// 8. MOUNTAIN STORY WIDGET - CUSTOMIZABLE
// ===================================================================
class Loomaama_Mountain_widget_new extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'loomaama_mountain_widget_new',
            'Loomaama: Mountain Story (Customizable)',
            array(
                'description' => 'Mountain story with customizable background images and text for each step.',
                'customize_selective_refresh' => true,
                'show_instance_in_rest' => true,
            )
        );
    }

    public function widget( $args, $instance ) {
        $label = ! empty( $instance['label'] ) ? $instance['label'] : 'The Mountain';
        $steps = array();
        for ( $i = 0; $i < 4; $i++ ) {
            $steps[] = array(
                'title' => ! empty( $instance[ 'm_step_' . $i . '_title' ] ) ? $instance[ 'm_step_' . $i . '_title' ] : [ 'From Fibre to Form', 'Naturally Textured', 'Made Slowly', 'Made to Move' ][ $i ],
                'text' => ! empty( $instance[ 'm_step_' . $i . '_text' ] ) ? $instance[ 'm_step_' . $i . '_text' ] : [ 
                    'Every finished piece carries the landscape, skill and time behind its creation.',
                    'Subtle variations in the fibre reveal the honest character of its handmade origin.',
                    'Each stitch reflects patient craftsmanship and generations of knowledge.',
                    'Breathable, lightweight and shaped to become part of everyday life.'
                ][ $i ],
                'image' => ! empty( $instance[ 'm_step_' . $i . '_image' ] ) ? $instance[ 'm_step_' . $i . '_image' ] : trailingslashit( get_template_directory_uri() . '/inc' ) . 'images/' . [ 'mt everest.png', 'Mt lhotse.png', 'landscape.png', 'landscape 2.png' ][ $i ]
            );
        }

        echo $args['before_widget'] ?? '';
        ?>
        <section class="mountain-story" id="mountainStory" data-step="0">
            <div class="mountain-sticky">
                <div class="mountain-bg-wrapper">
                    <?php foreach ( $steps as $i => $step ) : ?>
                        <div class="mountain-bg <?php echo $i === 0 ? 'main-bg active' : 'alt' . $i . '-bg'; ?>" style="background-image:url('<?php echo esc_url( $step['image'] ); ?>');"></div>
                    <?php endforeach; ?>
                    <div class="mountain-overlay"></div>
                </div>
                <div class="mountain-content-wrapper">
                    <div class="mountain-content">
                        <div class="mountain-chapter-label"><?php echo esc_html( $label ); ?></div>
                        <div class="mountain-copy">
                            <?php foreach ( $steps as $i => $step ) : ?>
                                <div class="step <?php echo $i === 0 ? 'active' : ''; ?>">
                                    <h2><?php echo esc_html( $step['title'] ); ?></h2>
                                    <p><?php echo esc_html( $step['text'] ); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mountain-progress" id="mountainProgress">01 / 04</div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        echo $args['after_widget'] ?? '';
    }

    public function form( $instance ) {
        $label = ! empty( $instance['label'] ) ? $instance['label'] : 'The Mountain';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'label' ); ?>">Chapter Label:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'label' ); ?>" name="<?php echo $this->get_field_name( 'label' ); ?>" type="text" value="<?php echo esc_attr( $label ); ?>" />
        </p>
        <h4>Mountain Steps (4 steps)</h4>
        <?php for ( $i = 0; $i < 4; $i++ ) : 
            $title = ! empty( $instance[ 'm_step_' . $i . '_title' ] ) ? $instance[ 'm_step_' . $i . '_title' ] : '';
            $text = ! empty( $instance[ 'm_step_' . $i . '_text' ] ) ? $instance[ 'm_step_' . $i . '_text' ] : '';
            $image = ! empty( $instance[ 'm_step_' . $i . '_image' ] ) ? $instance[ 'm_step_' . $i . '_image' ] : '';
        ?>
            <div style="background:#f9f9f9; padding:12px; margin:8px 0; border-left:3px solid #9E8574;">
                <h5>Step <?php echo $i + 1; ?></h5>
                <p>
                    <label for="<?php echo $this->get_field_id( 'm_step_' . $i . '_title' ); ?>">Title:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'm_step_' . $i . '_title' ); ?>" name="<?php echo $this->get_field_name( 'm_step_' . $i . '_title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'm_step_' . $i . '_text' ); ?>">Text:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'm_step_' . $i . '_text' ); ?>" name="<?php echo $this->get_field_name( 'm_step_' . $i . '_text' ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'm_step_' . $i . '_image' ); ?>">Background Image URL:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'm_step_' . $i . '_image' ); ?>" name="<?php echo $this->get_field_name( 'm_step_' . $i . '_image' ); ?>" type="url" value="<?php echo esc_url( $image ); ?>" />
                </p>
            </div>
        <?php endfor;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['label'] = sanitize_text_field( ($new_instance['label'] ?? '') );
        for ( $i = 0; $i < 4; $i++ ) {
            $instance[ 'm_step_' . $i . '_title' ] = sanitize_text_field( ($new_instance['m_step_' . $i . '_title'] ?? '') );
            $instance[ 'm_step_' . $i . '_text' ] = sanitize_text_field( ($new_instance['m_step_' . $i . '_text'] ?? '') );
            $instance[ 'm_step_' . $i . '_image' ] = esc_url_raw( ($new_instance['m_step_' . $i . '_image'] ?? '') );
        }
        return $instance;
    }
}

// ===================================================================
// 9. FOREST STORY WIDGET - CUSTOMIZABLE
// ===================================================================
class Loomaama_Forest_widget_new extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'loomaama_forest_widget_new',
            'Loomaama: Forest Story (Customizable)',
            array(
                'description' => 'Forest story with customizable background, text, and floating tags.',
                'customize_selective_refresh' => true,
                'show_instance_in_rest' => true,
            )
        );
    }

    public function widget( $args, $instance ) {
        $label = ! empty( $instance['label'] ) ? $instance['label'] : 'The Forest';
        $background = ! empty( $instance['background'] ) ? $instance['background'] : 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=2000&q=85';
        $steps = array();
        for ( $i = 0; $i < 4; $i++ ) {
            $steps[] = array(
                'title' => ! empty( $instance[ 'f_step_' . $i . '_title' ] ) ? $instance[ 'f_step_' . $i . '_title' ] : [ 
                    'Deep Within the Himalayan Forest', 'Growing Wild', 'Gathered by Hand', 'From Plant to Fibre' 
                ][ $i ],
                'text' => ! empty( $instance[ 'f_step_' . $i . '_text' ] ) ? $instance[ 'f_step_' . $i . '_text' ] : [ 
                    'Wild nettle grows naturally across the mountain landscape of Nepal, untouched by intensive farming.',
                    'No chemical cultivation. No artificial irrigation. Only soil, rain, altitude and time.',
                    'Local communities carefully harvest mature nettle while protecting the surrounding forest and future growth.',
                    'The stems are prepared, softened and transformed into a strong natural fibre ready to be spun by hand.'
                ][ $i ]
            );
        }
        $plant_image = ! empty( $instance['plant_image'] ) ? $instance['plant_image'] : trailingslashit( get_template_directory_uri() . '/inc' ) . 'images/Nettle image.png';
        $tags = ! empty( $instance['tags'] ) ? explode( ',', $instance['tags'] ) : array( 'Naturally grown', 'No chemicals', 'Hand harvested', 'Renewable by nature' );

        echo $args['before_widget'] ?? '';
        ?>
        <section class="forest-story" id="forestStory" data-step="0">
            <div class="sticky forest-sticky">
                <div class="forest-bg" style="background: linear-gradient(90deg, rgba(8, 17, 13, 0.85), rgba(8, 17, 13, 0.3)), url('<?php echo esc_url( $background ); ?>') center/cover;"></div>
                <div class="forest-chapter-label"><?php echo esc_html( $label ); ?></div>
                <div class="forest-content-wrapper">
                    <div class="forest-copy">
                        <?php foreach ( $steps as $i => $step ) : ?>
                            <div class="step <?php echo $i === 0 ? 'active' : ''; ?>">
                                <h2><?php echo esc_html( $step['title'] ); ?></h2>
                                <p><?php echo esc_html( $step['text'] ); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="plant-card">
                    <img src="<?php echo esc_url( $plant_image ); ?>" alt="Wild nettle plant" />
                </div>
                <?php foreach ( $tags as $i => $tag ) : ?>
                    <div class="tag <?php echo [ 'one', 'two', 'three', 'four' ][ $i ]; ?>"><?php echo esc_html( trim( $tag ) ); ?></div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
        echo $args['after_widget'] ?? '';
    }

    public function form( $instance ) {
        $label = ! empty( $instance['label'] ) ? $instance['label'] : 'The Forest';
        $background = ! empty( $instance['background'] ) ? $instance['background'] : '';
        $plant_image = ! empty( $instance['plant_image'] ) ? $instance['plant_image'] : '';
        $tags = ! empty( $instance['tags'] ) ? $instance['tags'] : 'Naturally grown, No chemicals, Hand harvested, Renewable by nature';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'label' ); ?>">Chapter Label:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'label' ); ?>" name="<?php echo $this->get_field_name( 'label' ); ?>" type="text" value="<?php echo esc_attr( $label ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'background' ); ?>">Background Image URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'background' ); ?>" name="<?php echo $this->get_field_name( 'background' ); ?>" type="url" value="<?php echo esc_url( $background ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'plant_image' ); ?>">Plant Image URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'plant_image' ); ?>" name="<?php echo $this->get_field_name( 'plant_image' ); ?>" type="url" value="<?php echo esc_url( $plant_image ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tags' ); ?>">Tags (comma separated):</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" type="text" value="<?php echo esc_attr( $tags ); ?>" />
            <small>Order: 1st tag appears first, 4th tag appears last</small>
        </p>
        <h4>Forest Steps (4 steps)</h4>
        <?php for ( $i = 0; $i < 4; $i++ ) : 
            $title = ! empty( $instance[ 'f_step_' . $i . '_title' ] ) ? $instance[ 'f_step_' . $i . '_title' ] : '';
            $text = ! empty( $instance[ 'f_step_' . $i . '_text' ] ) ? $instance[ 'f_step_' . $i . '_text' ] : '';
        ?>
            <div style="background:#f9f9f9; padding:12px; margin:8px 0; border-left:3px solid #9E8574;">
                <h5>Step <?php echo $i + 1; ?></h5>
                <p>
                    <label for="<?php echo $this->get_field_id( 'f_step_' . $i . '_title' ); ?>">Title:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'f_step_' . $i . '_title' ); ?>" name="<?php echo $this->get_field_name( 'f_step_' . $i . '_title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'f_step_' . $i . '_text' ); ?>">Text:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'f_step_' . $i . '_text' ); ?>" name="<?php echo $this->get_field_name( 'f_step_' . $i . '_text' ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
                </p>
            </div>
        <?php endfor;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['label'] = sanitize_text_field( ($new_instance['label'] ?? '') );
        $instance['background'] = esc_url_raw( ($new_instance['background'] ?? '') );
        $instance['plant_image'] = esc_url_raw( ($new_instance['plant_image'] ?? '') );
        $instance['tags'] = sanitize_text_field( ($new_instance['tags'] ?? '') );
        for ( $i = 0; $i < 4; $i++ ) {
            $instance[ 'f_step_' . $i . '_title' ] = sanitize_text_field( ($new_instance['f_step_' . $i . '_title'] ?? '') );
            $instance[ 'f_step_' . $i . '_text' ] = sanitize_text_field( ($new_instance['f_step_' . $i . '_text'] ?? '') );
        }
        return $instance;
    }
}

// ===================================================================
// 10. IMPACT WIDGET - CUSTOMIZABLE
// ===================================================================
class Loomaama_Impact_widget_new extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'loomaama_impact_widget_new',
            'Loomaama: Impact Section (Customizable)',
            array(
                'description' => 'Impact section with customizable stats and CTA.',
                'customize_selective_refresh' => true,
                'show_instance_in_rest' => true,
            )
        );
    }

    public function widget( $args, $instance ) {
        $eyebrow = ! empty( $instance['eyebrow'] ) ? $instance['eyebrow'] : 'OUR IMPACT';
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Building a future where craft and community thrive together.';
        $cta_text = ! empty( $instance['cta_text'] ) ? $instance['cta_text'] : 'Our Commitment';
        $cta_url = ! empty( $instance['cta_url'] ) ? $instance['cta_url'] : 'https://loomaama.com.au/impact/';

        $stats = array();
        for ( $i = 0; $i < 4; $i++ ) {
            $stats[] = array(
                'number' => ! empty( $instance[ 'stat_' . $i . '_number' ] ) ? $instance[ 'stat_' . $i . '_number' ] : [ '50+', '98%', '10+', '90%' ][ $i ],
                'label' => ! empty( $instance[ 'stat_' . $i . '_label' ] ) ? $instance[ 'stat_' . $i . '_label' ] : [ 'Women Empowered', 'Fair Trade Certified', 'Local Communities', 'Less Water Usage' ][ $i ],
                'desc' => ! empty( $instance[ 'stat_' . $i . '_desc' ] ) ? $instance[ 'stat_' . $i . '_desc' ] : [ 
                    'Through fair wages and skill development programs',
                    'Ensuring ethical practices across all partnerships',
                    'Supporting traditional craft and sustainable livelihoods',
                    'Compared to conventional textile production'
                ][ $i ]
            );
        }

        echo $args['before_widget'] ?? '';
        ?>
        <section class="impact-section">
            <div class="impact-container">
                <div class="impact-header">
                    <div class="impact-eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
                    <h2 class="impact-title"><?php echo esc_html( $title ); ?></h2>
                </div>
                <div class="impact-grid">
                    <?php foreach ( $stats as $stat ) : ?>
                        <div class="impact-card">
                            <div class="impact-number"><?php echo esc_html( $stat['number'] ); ?></div>
                            <div class="impact-label"><?php echo esc_html( $stat['label'] ); ?></div>
                            <div class="impact-description"><?php echo esc_html( $stat['desc'] ); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="impact-footer">
                    <a href="<?php echo esc_url( $cta_url ); ?>" class="impact-cta">
                        <?php echo esc_html( $cta_text ); ?> <span class="impact-arrow"></span>
                    </a>
                </div>
            </div>
        </section>
        <?php
        echo $args['after_widget'] ?? '';
    }

    public function form( $instance ) {
        $eyebrow = ! empty( $instance['eyebrow'] ) ? $instance['eyebrow'] : 'OUR IMPACT';
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Building a future where craft and community thrive together.';
        $cta_text = ! empty( $instance['cta_text'] ) ? $instance['cta_text'] : 'Our Commitment';
        $cta_url = ! empty( $instance['cta_url'] ) ? $instance['cta_url'] : 'https://loomaama.com.au/impact/';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'eyebrow' ); ?>">Eyebrow:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'eyebrow' ); ?>" name="<?php echo $this->get_field_name( 'eyebrow' ); ?>" type="text" value="<?php echo esc_attr( $eyebrow ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_text' ); ?>">CTA Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_text' ); ?>" name="<?php echo $this->get_field_name( 'cta_text' ); ?>" type="text" value="<?php echo esc_attr( $cta_text ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_url' ); ?>">CTA URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_url' ); ?>" name="<?php echo $this->get_field_name( 'cta_url' ); ?>" type="url" value="<?php echo esc_url( $cta_url ); ?>" />
        </p>
        <h4>Impact Stats (4 items)</h4>
        <?php for ( $i = 0; $i < 4; $i++ ) : 
            $number = ! empty( $instance[ 'stat_' . $i . '_number' ] ) ? $instance[ 'stat_' . $i . '_number' ] : '';
            $label = ! empty( $instance[ 'stat_' . $i . '_label' ] ) ? $instance[ 'stat_' . $i . '_label' ] : '';
            $desc = ! empty( $instance[ 'stat_' . $i . '_desc' ] ) ? $instance[ 'stat_' . $i . '_desc' ] : '';
        ?>
            <div style="background:#f9f9f9; padding:12px; margin:8px 0; border-left:3px solid #9E8574;">
                <h5>Stat <?php echo $i + 1; ?></h5>
                <p>
                    <label for="<?php echo $this->get_field_id( 'stat_' . $i . '_number' ); ?>">Number:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'stat_' . $i . '_number' ); ?>" name="<?php echo $this->get_field_name( 'stat_' . $i . '_number' ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'stat_' . $i . '_label' ); ?>">Label:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'stat_' . $i . '_label' ); ?>" name="<?php echo $this->get_field_name( 'stat_' . $i . '_label' ); ?>" type="text" value="<?php echo esc_attr( $label ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'stat_' . $i . '_desc' ); ?>">Description:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'stat_' . $i . '_desc' ); ?>" name="<?php echo $this->get_field_name( 'stat_' . $i . '_desc' ); ?>" type="text" value="<?php echo esc_attr( $desc ); ?>" />
                </p>
            </div>
        <?php endfor;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['eyebrow'] = sanitize_text_field( ($new_instance['eyebrow'] ?? '') );
        $instance['title'] = sanitize_text_field( ($new_instance['title'] ?? '') );
        $instance['cta_text'] = sanitize_text_field( ($new_instance['cta_text'] ?? '') );
        $instance['cta_url'] = esc_url_raw( ($new_instance['cta_url'] ?? '') );
        for ( $i = 0; $i < 4; $i++ ) {
            $instance[ 'stat_' . $i . '_number' ] = sanitize_text_field( ($new_instance['stat_' . $i . '_number'] ?? '') );
            $instance[ 'stat_' . $i . '_label' ] = sanitize_text_field( ($new_instance['stat_' . $i . '_label'] ?? '') );
            $instance[ 'stat_' . $i . '_desc' ] = sanitize_text_field( ($new_instance['stat_' . $i . '_desc'] ?? '') );
        }
        return $instance;
    }
}

// ===================================================================
// 11. ARTISANS WIDGET - CUSTOMIZABLE
// ===================================================================
class Loomaama_Artisans_widget_new extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'loomaama_artisans_widget_new',
            'Loomaama: Artisans Section (Customizable)',
            array(
                'description' => 'Artisans section with customizable artisan profiles.',
                'customize_selective_refresh' => true,
                'show_instance_in_rest' => true,
            )
        );
    }

    public function widget( $args, $instance ) {
        $eyebrow = ! empty( $instance['eyebrow'] ) ? $instance['eyebrow'] : 'THE ARTISANS';
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Every stitch tells a story of patience, skill, and generations of knowledge.';
        $subtitle = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : 'Behind every Loomaama piece is a skilled maker whose hands have shaped natural fibre into lasting form.';
        $cta_text = ! empty( $instance['cta_text'] ) ? $instance['cta_text'] : 'Meet the Makers';
        $cta_url = ! empty( $instance['cta_url'] ) ? $instance['cta_url'] : 'https://loomaama.com.au/the-artisans/';

        $artisans = array();
        for ( $i = 0; $i < 3; $i++ ) {
            $artisans[] = array(
                'name' => ! empty( $instance[ 'artisan_' . $i . '_name' ] ) ? $instance[ 'artisan_' . $i . '_name' ] : [ 'Gita Kulung', 'Ranjita Kulung', 'Sumitra Kulung' ][ $i ],
                'role' => ! empty( $instance[ 'artisan_' . $i . '_role' ] ) ? $instance[ 'artisan_' . $i . '_role' ] : 'Master Weaver',
                'bio' => ! empty( $instance[ 'artisan_' . $i . '_bio' ] ) ? $instance[ 'artisan_' . $i . '_bio' ] : [ 
                    '"The fibre speaks to me. Each thread carries the memory of the mountain and the hands that shaped it."',
                    '"The forest provides everything we need. We take only what we need and give back to the earth."',
                    '"I learned from my grandmother. This craft is not just work, it is our heritage and our future."'
                ][ $i ],
                'image' => ! empty( $instance[ 'artisan_' . $i . '_image' ] ) ? $instance[ 'artisan_' . $i . '_image' ] : trailingslashit( get_template_directory_uri() . '/inc' ) . 'images/' . [ 'Gita Kulung.png', 'Ranjita Kulung.png', 'Sumitra Kulung.jpeg' ][ $i ],
                'badge' => ! empty( $instance[ 'artisan_' . $i . '_badge' ] ) ? $instance[ 'artisan_' . $i . '_badge' ] : [ '25+ Years', '15+ Years', '20+ Years' ][ $i ]
            );
        }

        echo $args['before_widget'] ?? '';
        ?>
        <section class="artisans-section">
            <div class="artisans-container">
                <div class="artisans-header">
                    <div class="artisans-eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
                    <h2 class="artisans-title"><?php echo esc_html( $title ); ?></h2>
                    <p class="artisans-subtitle"><?php echo esc_html( $subtitle ); ?></p>
                </div>
                <div class="artisans-grid">
                    <?php foreach ( $artisans as $artisan ) : ?>
                        <div class="artisan-card">
                            <div class="artisan-image">
                                <img src="<?php echo esc_url( $artisan['image'] ); ?>" alt="<?php echo esc_attr( $artisan['name'] ); ?>" />
                                <div class="artisan-overlay">
                                    <span class="artisan-badge"><?php echo esc_html( $artisan['badge'] ); ?></span>
                                </div>
                            </div>
                            <div class="artisan-info">
                                <h4 class="artisan-name"><?php echo esc_html( $artisan['name'] ); ?></h4>
                                <p class="artisan-role"><?php echo esc_html( $artisan['role'] ); ?></p>
                                <p class="artisan-bio"><?php echo esc_html( $artisan['bio'] ); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="artisans-footer">
                    <a href="<?php echo esc_url( $cta_url ); ?>" class="artisans-cta">
                        <?php echo esc_html( $cta_text ); ?> <span class="artisans-arrow"></span>
                    </a>
                </div>
            </div>
        </section>
        <?php
        echo $args['after_widget'] ?? '';
    }

    public function form( $instance ) {
        $eyebrow = ! empty( $instance['eyebrow'] ) ? $instance['eyebrow'] : 'THE ARTISANS';
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Every stitch tells a story of patience, skill, and generations of knowledge.';
        $subtitle = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : 'Behind every Loomaama piece is a skilled maker whose hands have shaped natural fibre into lasting form.';
        $cta_text = ! empty( $instance['cta_text'] ) ? $instance['cta_text'] : 'Meet the Makers';
        $cta_url = ! empty( $instance['cta_url'] ) ? $instance['cta_url'] : 'https://loomaama.com.au/the-artisans/';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'eyebrow' ); ?>">Eyebrow:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'eyebrow' ); ?>" name="<?php echo $this->get_field_name( 'eyebrow' ); ?>" type="text" value="<?php echo esc_attr( $eyebrow ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'subtitle' ); ?>">Subtitle:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" type="text" value="<?php echo esc_attr( $subtitle ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_text' ); ?>">CTA Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_text' ); ?>" name="<?php echo $this->get_field_name( 'cta_text' ); ?>" type="text" value="<?php echo esc_attr( $cta_text ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_url' ); ?>">CTA URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_url' ); ?>" name="<?php echo $this->get_field_name( 'cta_url' ); ?>" type="url" value="<?php echo esc_url( $cta_url ); ?>" />
        </p>
        <h4>Artisan Profiles (3 artisans)</h4>
        <?php for ( $i = 0; $i < 3; $i++ ) : 
            $name = ! empty( $instance[ 'artisan_' . $i . '_name' ] ) ? $instance[ 'artisan_' . $i . '_name' ] : '';
            $role = ! empty( $instance[ 'artisan_' . $i . '_role' ] ) ? $instance[ 'artisan_' . $i . '_role' ] : '';
            $bio = ! empty( $instance[ 'artisan_' . $i . '_bio' ] ) ? $instance[ 'artisan_' . $i . '_bio' ] : '';
            $image = ! empty( $instance[ 'artisan_' . $i . '_image' ] ) ? $instance[ 'artisan_' . $i . '_image' ] : '';
            $badge = ! empty( $instance[ 'artisan_' . $i . '_badge' ] ) ? $instance[ 'artisan_' . $i . '_badge' ] : '';
        ?>
            <div style="background:#f9f9f9; padding:12px; margin:8px 0; border-left:3px solid #9E8574;">
                <h5>Artisan <?php echo $i + 1; ?></h5>
                <p>
                    <label for="<?php echo $this->get_field_id( 'artisan_' . $i . '_name' ); ?>">Name:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'artisan_' . $i . '_name' ); ?>" name="<?php echo $this->get_field_name( 'artisan_' . $i . '_name' ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'artisan_' . $i . '_role' ); ?>">Role:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'artisan_' . $i . '_role' ); ?>" name="<?php echo $this->get_field_name( 'artisan_' . $i . '_role' ); ?>" type="text" value="<?php echo esc_attr( $role ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'artisan_' . $i . '_bio' ); ?>">Bio/Quote:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'artisan_' . $i . '_bio' ); ?>" name="<?php echo $this->get_field_name( 'artisan_' . $i . '_bio' ); ?>" type="text" value="<?php echo esc_attr( $bio ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'artisan_' . $i . '_image' ); ?>">Image URL:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'artisan_' . $i . '_image' ); ?>" name="<?php echo $this->get_field_name( 'artisan_' . $i . '_image' ); ?>" type="url" value="<?php echo esc_url( $image ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'artisan_' . $i . '_badge' ); ?>">Badge (e.g. 25+ Years):</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'artisan_' . $i . '_badge' ); ?>" name="<?php echo $this->get_field_name( 'artisan_' . $i . '_badge' ); ?>" type="text" value="<?php echo esc_attr( $badge ); ?>" />
                </p>
            </div>
        <?php endfor;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['eyebrow'] = sanitize_text_field( ($new_instance['eyebrow'] ?? '') );
        $instance['title'] = sanitize_text_field( ($new_instance['title'] ?? '') );
        $instance['subtitle'] = sanitize_text_field( ($new_instance['subtitle'] ?? '') );
        $instance['cta_text'] = sanitize_text_field( ($new_instance['cta_text'] ?? '') );
        $instance['cta_url'] = esc_url_raw( ($new_instance['cta_url'] ?? '') );
        for ( $i = 0; $i < 3; $i++ ) {
            $instance[ 'artisan_' . $i . '_name' ] = sanitize_text_field( ($new_instance['artisan_' . $i . '_name'] ?? '') );
            $instance[ 'artisan_' . $i . '_role' ] = sanitize_text_field( ($new_instance['artisan_' . $i . '_role'] ?? '') );
            $instance[ 'artisan_' . $i . '_bio' ] = sanitize_text_field( ($new_instance['artisan_' . $i . '_bio'] ?? '') );
            $instance[ 'artisan_' . $i . '_image' ] = esc_url_raw( ($new_instance['artisan_' . $i . '_image'] ?? '') );
            $instance[ 'artisan_' . $i . '_badge' ] = sanitize_text_field( ($new_instance['artisan_' . $i . '_badge'] ?? '') );
        }
        return $instance;
    }
}

// ===================================================================
// 12. COLLECTION WIDGET - CUSTOMIZABLE
// ===================================================================
class Loomaama_Collection_widget_new extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'loomaama_collection_widget_new',
            'Loomaama: Collection (Customizable)',
            array(
                'description' => 'Collection section with customizable products and CTA.',
                'customize_selective_refresh' => true,
                'show_instance_in_rest' => true,
            )
        );
    }

    public function widget( $args, $instance ) {
        $eyebrow = ! empty( $instance['eyebrow'] ) ? $instance['eyebrow'] : 'The Journey Continues';
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Featured Collection';
        $description = ! empty( $instance['description'] ) ? $instance['description'] : 'Discover pieces shaped by mountain landscapes, natural fibres and the hands of Nepal.';
        $cta_text = ! empty( $instance['cta_text'] ) ? $instance['cta_text'] : 'Explore the Collection';
        $cta_url = ! empty( $instance['cta_url'] ) ? $instance['cta_url'] : 'https://loomaama.com.au/shop';

        $products = array();
        for ( $i = 0; $i < 3; $i++ ) {
            $products[] = array(
                'title' => ! empty( $instance[ 'product_' . $i . '_title' ] ) ? $instance[ 'product_' . $i . '_title' ] : [ 'Nettle Wrap shawl', 'Mixed Pattern Poncho', 'Natural Beanie' ][ $i ],
                'price' => ! empty( $instance[ 'product_' . $i . '_price' ] ) ? $instance[ 'product_' . $i . '_price' ] : [ 'AUD $189', 'AUD $169', 'AUD $249' ][ $i ],
                'image' => ! empty( $instance[ 'product_' . $i . '_image' ] ) ? $instance[ 'product_' . $i . '_image' ] : trailingslashit( get_template_directory_uri() . '/inc' ) . 'images/' . [ 'shawl.jpg', 'Poncho.png', 'Beanie.jpg' ][ $i ]
            );
        }

        echo $args['before_widget'] ?? '';
        ?>
        <section class="collection">
            <div class="collection-head">
                <div>
                    <div class="eyebrow" style="color:#7f694f;"><?php echo esc_html( $eyebrow ); ?></div>
                    <h2><?php echo esc_html( $title ); ?></h2>
                </div>
                <p><?php echo esc_html( $description ); ?></p>
            </div>
            <div class="product-grid">
                <?php foreach ( $products as $product ) : ?>
                    <article class="card">
                        <img src="<?php echo esc_url( $product['image'] ); ?>" alt="<?php echo esc_attr( $product['title'] ); ?>" />
                        <div class="card-info">
                            <div class="card-title"><?php echo esc_html( $product['title'] ); ?></div>
                            <div><?php echo esc_html( $product['price'] ); ?></div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <a class="button" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_text ); ?></a>
        </section>
        <?php
        echo $args['after_widget'] ?? '';
    }

    public function form( $instance ) {
        $eyebrow = ! empty( $instance['eyebrow'] ) ? $instance['eyebrow'] : 'The Journey Continues';
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Featured Collection';
        $description = ! empty( $instance['description'] ) ? $instance['description'] : 'Discover pieces shaped by mountain landscapes, natural fibres and the hands of Nepal.';
        $cta_text = ! empty( $instance['cta_text'] ) ? $instance['cta_text'] : 'Explore the Collection';
        $cta_url = ! empty( $instance['cta_url'] ) ? $instance['cta_url'] : 'https://loomaama.com.au/shop';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'eyebrow' ); ?>">Eyebrow:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'eyebrow' ); ?>" name="<?php echo $this->get_field_name( 'eyebrow' ); ?>" type="text" value="<?php echo esc_attr( $eyebrow ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'description' ); ?>">Description:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" value="<?php echo esc_attr( $description ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_text' ); ?>">CTA Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_text' ); ?>" name="<?php echo $this->get_field_name( 'cta_text' ); ?>" type="text" value="<?php echo esc_attr( $cta_text ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_url' ); ?>">CTA URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_url' ); ?>" name="<?php echo $this->get_field_name( 'cta_url' ); ?>" type="url" value="<?php echo esc_url( $cta_url ); ?>" />
        </p>
        <h4>Products (3 items)</h4>
        <?php for ( $i = 0; $i < 3; $i++ ) : 
            $product_title = ! empty( $instance[ 'product_' . $i . '_title' ] ) ? $instance[ 'product_' . $i . '_title' ] : '';
            $price = ! empty( $instance[ 'product_' . $i . '_price' ] ) ? $instance[ 'product_' . $i . '_price' ] : '';
            $image = ! empty( $instance[ 'product_' . $i . '_image' ] ) ? $instance[ 'product_' . $i . '_image' ] : '';
        ?>
            <div style="background:#f9f9f9; padding:12px; margin:8px 0; border-left:3px solid #9E8574;">
                <h5>Product <?php echo $i + 1; ?></h5>
                <p>
                    <label for="<?php echo $this->get_field_id( 'product_' . $i . '_title' ); ?>">Product Title:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'product_' . $i . '_title' ); ?>" name="<?php echo $this->get_field_name( 'product_' . $i . '_title' ); ?>" type="text" value="<?php echo esc_attr( $product_title ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'product_' . $i . '_price' ); ?>">Price:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'product_' . $i . '_price' ); ?>" name="<?php echo $this->get_field_name( 'product_' . $i . '_price' ); ?>" type="text" value="<?php echo esc_attr( $price ); ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'product_' . $i . '_image' ); ?>">Image URL:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'product_' . $i . '_image' ); ?>" name="<?php echo $this->get_field_name( 'product_' . $i . '_image' ); ?>" type="url" value="<?php echo esc_url( $image ); ?>" />
                </p>
            </div>
        <?php endfor;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['eyebrow'] = sanitize_text_field( ($new_instance['eyebrow'] ?? '') );
        $instance['title'] = sanitize_text_field( ($new_instance['title'] ?? '') );
        $instance['description'] = sanitize_text_field( ($new_instance['description'] ?? '') );
        $instance['cta_text'] = sanitize_text_field( ($new_instance['cta_text'] ?? '') );
        $instance['cta_url'] = esc_url_raw( ($new_instance['cta_url'] ?? '') );
        for ( $i = 0; $i < 3; $i++ ) {
            $instance[ 'product_' . $i . '_title' ] = sanitize_text_field( ($new_instance['product_' . $i . '_title'] ?? '') );
            $instance[ 'product_' . $i . '_price' ] = sanitize_text_field( ($new_instance['product_' . $i . '_price'] ?? '') );
            $instance[ 'product_' . $i . '_image' ] = esc_url_raw( ($new_instance['product_' . $i . '_image'] ?? '') );
        }
        return $instance;
    }
}