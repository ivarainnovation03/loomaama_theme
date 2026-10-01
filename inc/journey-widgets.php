<?php
/**
 * Loomaama Journey Section Widgets
 *
 * Fully editable, independent widgets for the Loomaama journey sections.
 * This module intentionally outputs no theme header or footer.
 *
 * @package Loomaama
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Return a Journey asset URL.
 *
 * @param string $relative_path Relative path below /assets/.
 * @return string
 */
function loomaama_journey_asset_url( $relative_path ) {
    return LOOMAAMA_URI . '/assets/' . ltrim( $relative_path, '/' );
}

/**
 * Convert a hexadecimal colour and percentage opacity to rgba().
 *
 * @param string     $hex     Hexadecimal colour.
 * @param int|float  $opacity Opacity from 0 to 100.
 * @return string
 */
function loomaama_journey_rgba( $hex, $opacity ) {
    $hex = sanitize_hex_color( $hex ) ?: '#000000';
    $hex = ltrim( $hex, '#' );

    $red   = hexdec( substr( $hex, 0, 2 ) );
    $green = hexdec( substr( $hex, 2, 2 ) );
    $blue  = hexdec( substr( $hex, 4, 2 ) );
    $alpha = min( 1, max( 0, (float) $opacity / 100 ) );

    return sprintf( 'rgba(%d,%d,%d,%s)', $red, $green, $blue, rtrim( rtrim( number_format( $alpha, 3, '.', '' ), '0' ), '.' ) );
}

/**
 * Load Media Library, colour picker and grouped-field behaviour in widget admin.
 *
 * @param string $hook_suffix Current admin page.
 */
function loomaama_journey_widgets_admin_assets( $hook_suffix ) {
    if ( ! in_array( $hook_suffix, array( 'widgets.php', 'customize.php' ), true ) ) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );

    wp_enqueue_style(
        'loomaama-journey-widgets-admin',
        LOOMAAMA_URI . '/assets/css/journey-widgets-admin.css',
        array( 'wp-color-picker' ),
        LOOMAAMA_VERSION
    );

    wp_enqueue_script(
        'loomaama-journey-widgets-admin',
        LOOMAAMA_URI . '/assets/js/journey-widgets-admin.js',
        array( 'jquery', 'wp-color-picker' ),
        LOOMAAMA_VERSION,
        true
    );
}
add_action( 'admin_enqueue_scripts', 'loomaama_journey_widgets_admin_assets' );

/**
 * Shared base class.
 */
abstract class Loomaama_Journey_Widget_Base extends WP_Widget {

    /** @var array<string,array<string,mixed>> */
    protected $field_schema = array();

    /** @var array<string,mixed> */
    protected $default_values = array();

    /** @var string */
    protected $section_modifier = '';

    /**
     * Enqueue the shared public assets.
     */
    protected function enqueue_assets() {
        wp_enqueue_style( 'loomaama-journey-widgets' );
        wp_enqueue_script( 'loomaama-journey-widgets' );
    }

    /**
     * Shared defaults available to every section.
     * Blank colour values preserve the original stylesheet design.
     *
     * @return array<string,mixed>
     */
    protected function common_defaults() {
        return array(
            'background_color' => '',
            'accent_color'     => '',
            'heading_color'    => '',
            'text_color'       => '',
            'button_bg_color'  => '',
            'button_text_color'=> '',
            'padding_top'      => '',
            'padding_bottom'   => '',
            'content_max_width'=> '',
            'border_radius'    => 0,
            'text_align'       => 'inherit',
            'section_id'       => '',
            'custom_class'     => '',
            'show_desktop'     => 1,
            'show_tablet'      => 1,
            'show_mobile'      => 1,
        );
    }

    /**
     * Shared design and advanced fields.
     *
     * @return array<string,array<string,mixed>>
     */
    protected function common_fields() {
        return array(
            '_design_start' => array(
                'type'  => 'group_start',
                'label' => __( 'Design', 'loomaama' ),
            ),
            'background_color' => array(
                'type'        => 'color',
                'label'       => __( 'Section background colour', 'loomaama' ),
                'description' => __( 'Leave blank to use the original design.', 'loomaama' ),
            ),
            'accent_color' => array(
                'type'        => 'color',
                'label'       => __( 'Accent colour', 'loomaama' ),
                'description' => __( 'Controls eyebrows, labels, numbers and decorative accents.', 'loomaama' ),
            ),
            'heading_color' => array(
                'type'        => 'color',
                'label'       => __( 'Heading colour', 'loomaama' ),
                'description' => __( 'Leave blank to keep the section default.', 'loomaama' ),
            ),
            'text_color' => array(
                'type'        => 'color',
                'label'       => __( 'Body text colour', 'loomaama' ),
                'description' => __( 'Leave blank to keep the section default.', 'loomaama' ),
            ),
            'button_bg_color' => array(
                'type'        => 'color',
                'label'       => __( 'Button background colour', 'loomaama' ),
                'description' => __( 'Used where the section has a button.', 'loomaama' ),
            ),
            'button_text_color' => array(
                'type'        => 'color',
                'label'       => __( 'Button text colour', 'loomaama' ),
            ),
            'padding_top' => array(
                'type'        => 'number',
                'label'       => __( 'Top padding (px)', 'loomaama' ),
                'min'         => 0,
                'max'         => 500,
                'step'        => 1,
                'allow_blank' => true,
                'description' => __( 'Most useful for standard sections. Leave blank for the original value.', 'loomaama' ),
            ),
            'padding_bottom' => array(
                'type'        => 'number',
                'label'       => __( 'Bottom padding (px)', 'loomaama' ),
                'min'         => 0,
                'max'         => 500,
                'step'        => 1,
                'allow_blank' => true,
            ),
            'content_max_width' => array(
                'type'        => 'number',
                'label'       => __( 'Content maximum width (px)', 'loomaama' ),
                'min'         => 280,
                'max'         => 2400,
                'step'        => 1,
                'allow_blank' => true,
            ),
            'border_radius' => array(
                'type'  => 'number',
                'label' => __( 'Section corner radius (px)', 'loomaama' ),
                'min'   => 0,
                'max'   => 160,
                'step'  => 1,
            ),
            'text_align' => array(
                'type'    => 'select',
                'label'   => __( 'Text alignment', 'loomaama' ),
                'options' => array(
                    'inherit' => __( 'Original layout', 'loomaama' ),
                    'left'    => __( 'Left', 'loomaama' ),
                    'center'  => __( 'Centre', 'loomaama' ),
                    'right'   => __( 'Right', 'loomaama' ),
                ),
            ),
            '_design_end' => array( 'type' => 'group_end' ),

            '_advanced_start' => array(
                'type'  => 'group_start',
                'label' => __( 'Advanced and visibility', 'loomaama' ),
            ),
            'section_id' => array(
                'label'       => __( 'HTML anchor ID', 'loomaama' ),
                'description' => __( 'Example: mountain-story. Do not include #.', 'loomaama' ),
            ),
            'custom_class' => array(
                'label'       => __( 'Additional CSS class(es)', 'loomaama' ),
                'description' => __( 'Separate multiple class names with spaces.', 'loomaama' ),
            ),
            'show_desktop' => array(
                'type'  => 'checkbox',
                'label' => __( 'Show on desktop', 'loomaama' ),
            ),
            'show_tablet' => array(
                'type'  => 'checkbox',
                'label' => __( 'Show on tablet', 'loomaama' ),
            ),
            'show_mobile' => array(
                'type'  => 'checkbox',
                'label' => __( 'Show on mobile', 'loomaama' ),
            ),
            '_advanced_end' => array( 'type' => 'group_end' ),
        );
    }

    /**
     * Configure a widget while automatically adding common controls.
     *
     * @param array<string,mixed>              $defaults Widget defaults.
     * @param array<string,array<string,mixed>> $fields   Widget fields.
     */
    protected function configure( $defaults, $fields ) {
        $this->default_values = array_merge( $this->common_defaults(), $defaults );
        $this->field_schema   = array_merge( $fields, $this->common_fields() );
    }

    /**
     * Merge saved values with defaults.
     *
     * @param mixed $instance Saved widget instance.
     * @return array<string,mixed>
     */
    protected function values( $instance ) {
        return wp_parse_args( is_array( $instance ) ? $instance : array(), $this->default_values );
    }

    /**
     * Determine whether a checkbox value is on.
     *
     * @param array<string,mixed> $values Values.
     * @param string              $key    Key.
     * @return bool
     */
    protected function is_on( $values, $key ) {
        return ! empty( $values[ $key ] );
    }

    /**
     * Convert a title/textarea into safe text with line breaks.
     *
     * @param string $text Text.
     * @return string
     */
    protected function multiline( $text ) {
        return nl2br( esc_html( (string) $text ) );
    }

    /**
     * Return target attributes.
     *
     * @param bool $new_tab Open in new tab.
     * @return string
     */
    protected function target_attributes( $new_tab ) {
        return $new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';
    }

    /**
     * Build CSS custom properties for a widget wrapper.
     *
     * @param array<string,mixed> $values Widget values.
     * @param array<string,string> $extra  Additional CSS variables.
     * @return string
     */
    protected function wrapper_style( $values, $extra = array() ) {
        $variables = array();
        $color_map = array(
            'background_color'  => '--lm-custom-bg',
            'accent_color'      => '--lm-custom-accent',
            'heading_color'     => '--lm-custom-heading',
            'text_color'        => '--lm-custom-text',
            'button_bg_color'   => '--lm-custom-button-bg',
            'button_text_color' => '--lm-custom-button-text',
        );

        foreach ( $color_map as $key => $css_variable ) {
            $colour = isset( $values[ $key ] ) ? sanitize_hex_color( $values[ $key ] ) : '';
            if ( $colour ) {
                $variables[ $css_variable ] = $colour;
            }
        }

        if ( '' !== (string) ( $values['padding_top'] ?? '' ) ) {
            $variables['--lm-custom-padding-top'] = max( 0, (float) $values['padding_top'] ) . 'px';
        }
        if ( '' !== (string) ( $values['padding_bottom'] ?? '' ) ) {
            $variables['--lm-custom-padding-bottom'] = max( 0, (float) $values['padding_bottom'] ) . 'px';
        }
        if ( '' !== (string) ( $values['content_max_width'] ?? '' ) ) {
            $variables['--lm-custom-content-width'] = max( 280, (float) $values['content_max_width'] ) . 'px';
        }

        $variables['--lm-custom-radius'] = max( 0, (float) ( $values['border_radius'] ?? 0 ) ) . 'px';

        if ( in_array( $values['text_align'] ?? 'inherit', array( 'left', 'center', 'right' ), true ) ) {
            $variables['--lm-custom-align'] = $values['text_align'];
        }

        foreach ( $extra as $name => $value ) {
            if ( '' !== (string) $value ) {
                $variables[ $name ] = $value;
            }
        }

        $style = array();
        foreach ( $variables as $name => $value ) {
            $style[] = $name . ':' . $value;
        }

        return implode( ';', $style );
    }

    /**
     * Open the widget wrapper.
     *
     * @param array<string,mixed> $args          WordPress widget arguments.
     * @param array<string,mixed> $values        Widget values.
     * @param array<string,string> $extra_vars   Additional CSS variables.
     * @param array<int,string>    $extra_classes Additional classes.
     */
    protected function open_widget( $args, $values, $extra_vars = array(), $extra_classes = array() ) {
        $before_widget = $args['before_widget'] ?? '';
        if ( $before_widget ) {
            // Long sticky sections should not inherit AOS transforms from the theme's widget wrapper.
            $before_widget = preg_replace( '/\sdata-aos(?:-[\w-]+)?=(["\']).*?\1/i', '', $before_widget );
            $before_widget = preg_replace( '/class=(["\'])(.*?)\1/i', 'class=$1$2 loomaama-journey-host$1', $before_widget, 1 );
        }
        echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        $classes = array(
            'loomaama-section-widget',
            'loomaama-section-widget--' . sanitize_html_class( $this->section_modifier ),
        );

        if ( ! $this->is_on( $values, 'show_desktop' ) ) {
            $classes[] = 'lm-hide-desktop';
        }
        if ( ! $this->is_on( $values, 'show_tablet' ) ) {
            $classes[] = 'lm-hide-tablet';
        }
        if ( ! $this->is_on( $values, 'show_mobile' ) ) {
            $classes[] = 'lm-hide-mobile';
        }

        if ( ! empty( $values['custom_class'] ) ) {
            $custom_classes = preg_split( '/\s+/', (string) $values['custom_class'] );
            foreach ( $custom_classes as $custom_class ) {
                $custom_class = sanitize_html_class( $custom_class );
                if ( $custom_class ) {
                    $classes[] = $custom_class;
                }
            }
        }

        foreach ( $extra_classes as $extra_class ) {
            $extra_class = sanitize_html_class( $extra_class );
            if ( $extra_class ) {
                $classes[] = $extra_class;
            }
        }

        $section_id = ! empty( $values['section_id'] ) ? sanitize_title( $values['section_id'] ) : '';
        $style      = $this->wrapper_style( $values, $extra_vars );

        echo '<div class="' . esc_attr( implode( ' ', array_unique( $classes ) ) ) . '"';
        if ( $section_id ) {
            echo ' id="' . esc_attr( $section_id ) . '"';
        }
        if ( $style ) {
            echo ' style="' . esc_attr( $style ) . '"';
        }
        echo '>';
    }

    /**
     * Close the widget wrapper.
     *
     * @param array<string,mixed> $args WordPress widget arguments.
     */
    protected function close_widget( $args ) {
        echo '</div>';
        echo $args['after_widget'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Render the widget form.
     *
     * @param array<string,mixed> $instance Saved instance.
     */
    public function form( $instance ) {
        $values     = $this->values( $instance );
        $group_open = false;

        echo '<div class="lm-journey-widget-form">';

        foreach ( $this->field_schema as $key => $field ) {
            $type = $field['type'] ?? 'text';

            if ( 'group_start' === $type ) {
                if ( $group_open ) {
                    echo '</div></details>';
                }
                $group_open = true;
                echo '<details class="lm-widget-group"' . ( ! empty( $field['open'] ) ? ' open' : '' ) . '>';
                echo '<summary>' . esc_html( $field['label'] ?? __( 'Settings', 'loomaama' ) ) . '</summary>';
                echo '<div class="lm-widget-group__body">';
                continue;
            }

            if ( 'group_end' === $type ) {
                if ( $group_open ) {
                    echo '</div></details>';
                    $group_open = false;
                }
                continue;
            }

            $label       = $field['label'] ?? $key;
            $description = $field['description'] ?? '';
            $value       = $values[ $key ] ?? '';
            $field_id    = $this->get_field_id( $key );
            $field_name  = $this->get_field_name( $key );
            $placeholder = $field['placeholder'] ?? '';

            echo '<div class="lm-widget-field lm-widget-field--' . esc_attr( $type ) . '">';

            if ( 'checkbox' === $type ) {
                ?>
                <label class="lm-widget-checkbox" for="<?php echo esc_attr( $field_id ); ?>">
                    <input
                        type="checkbox"
                        id="<?php echo esc_attr( $field_id ); ?>"
                        name="<?php echo esc_attr( $field_name ); ?>"
                        value="1"
                        <?php checked( ! empty( $value ) ); ?>
                    >
                    <span><?php echo esc_html( $label ); ?></span>
                </label>
                <?php
            } else {
                echo '<label for="' . esc_attr( $field_id ) . '">' . esc_html( $label ) . '</label>';

                if ( 'textarea' === $type ) {
                    ?>
                    <textarea
                        class="widefat"
                        rows="<?php echo esc_attr( $field['rows'] ?? 3 ); ?>"
                        id="<?php echo esc_attr( $field_id ); ?>"
                        name="<?php echo esc_attr( $field_name ); ?>"
                        placeholder="<?php echo esc_attr( $placeholder ); ?>"
                    ><?php echo esc_textarea( $value ); ?></textarea>
                    <?php
                } elseif ( 'number' === $type ) {
                    ?>
                    <input
                        class="widefat"
                        type="number"
                        id="<?php echo esc_attr( $field_id ); ?>"
                        name="<?php echo esc_attr( $field_name ); ?>"
                        value="<?php echo esc_attr( $value ); ?>"
                        min="<?php echo esc_attr( $field['min'] ?? 0 ); ?>"
                        max="<?php echo esc_attr( $field['max'] ?? 9999 ); ?>"
                        step="<?php echo esc_attr( $field['step'] ?? 1 ); ?>"
                        placeholder="<?php echo esc_attr( $placeholder ); ?>"
                    >
                    <?php
                } elseif ( 'select' === $type ) {
                    ?>
                    <select class="widefat" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_name ); ?>">
                        <?php foreach ( (array) ( $field['options'] ?? array() ) as $option_value => $option_label ) : ?>
                            <option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( (string) $value, (string) $option_value ); ?>>
                                <?php echo esc_html( $option_label ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php
                } elseif ( 'color' === $type ) {
                    ?>
                    <input
                        class="widefat lm-color-field"
                        type="text"
                        id="<?php echo esc_attr( $field_id ); ?>"
                        name="<?php echo esc_attr( $field_name ); ?>"
                        value="<?php echo esc_attr( $value ); ?>"
                        data-default-color="<?php echo esc_attr( $field['default_color'] ?? '' ); ?>"
                        placeholder="#000000"
                    >
                    <?php
                } elseif ( 'media' === $type ) {
                    ?>
                    <div class="lm-media-control">
                        <input
                            class="widefat lm-media-url"
                            type="url"
                            id="<?php echo esc_attr( $field_id ); ?>"
                            name="<?php echo esc_attr( $field_name ); ?>"
                            value="<?php echo esc_attr( $value ); ?>"
                            placeholder="<?php echo esc_attr( $placeholder ); ?>"
                        >
                        <div class="lm-media-actions">
                            <button
                                type="button"
                                class="button lm-media-select"
                                data-target="<?php echo esc_attr( $field_id ); ?>"
                                data-media-type="<?php echo esc_attr( $field['media_type'] ?? 'image' ); ?>"
                            ><?php esc_html_e( 'Choose media', 'loomaama' ); ?></button>
                            <button
                                type="button"
                                class="button-link-delete lm-media-clear"
                                data-target="<?php echo esc_attr( $field_id ); ?>"
                            ><?php esc_html_e( 'Clear', 'loomaama' ); ?></button>
                        </div>
                    </div>
                    <?php
                } else {
                    $input_type = 'url' === $type ? 'url' : 'text';
                    ?>
                    <input
                        class="widefat"
                        type="<?php echo esc_attr( $input_type ); ?>"
                        id="<?php echo esc_attr( $field_id ); ?>"
                        name="<?php echo esc_attr( $field_name ); ?>"
                        value="<?php echo esc_attr( $value ); ?>"
                        placeholder="<?php echo esc_attr( $placeholder ); ?>"
                    >
                    <?php
                }
            }

            if ( $description ) {
                echo '<small class="lm-widget-field__description">' . esc_html( $description ) . '</small>';
            }

            echo '</div>';
        }

        if ( $group_open ) {
            echo '</div></details>';
        }

        echo '</div>';
    }

    /**
     * Sanitize widget values.
     *
     * @param array<string,mixed> $new_instance New values.
     * @param array<string,mixed> $old_instance Old values.
     * @return array<string,mixed>
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        foreach ( $this->field_schema as $key => $field ) {
            $type = $field['type'] ?? 'text';

            if ( in_array( $type, array( 'group_start', 'group_end' ), true ) ) {
                continue;
            }

            $value = $new_instance[ $key ] ?? '';

            switch ( $type ) {
                case 'checkbox':
                    $instance[ $key ] = ! empty( $value ) ? 1 : 0;
                    break;

                case 'url':
                case 'media':
                    $instance[ $key ] = esc_url_raw( $value );
                    break;

                case 'textarea':
                    $instance[ $key ] = sanitize_textarea_field( $value );
                    break;

                case 'number':
                    if ( '' === (string) $value && ! empty( $field['allow_blank'] ) ) {
                        $instance[ $key ] = '';
                        break;
                    }
                    $number = (float) $value;
                    if ( isset( $field['min'] ) ) {
                        $number = max( (float) $field['min'], $number );
                    }
                    if ( isset( $field['max'] ) ) {
                        $number = min( (float) $field['max'], $number );
                    }
                    $instance[ $key ] = $number;
                    break;

                case 'color':
                    $instance[ $key ] = sanitize_hex_color( $value ) ?: '';
                    break;

                case 'select':
                    $options = array_keys( (array) ( $field['options'] ?? array() ) );
                    $instance[ $key ] = in_array( (string) $value, array_map( 'strval', $options ), true )
                        ? sanitize_text_field( $value )
                        : (string) reset( $options );
                    break;

                default:
                    $instance[ $key ] = sanitize_text_field( $value );
                    break;
            }
        }

        return $instance;
    }
}

/**
 * Hero widget.
 */
class Loomaama_Journey_Hero_Widget extends Loomaama_Journey_Widget_Base {
    public function __construct() {
        $this->section_modifier = 'hero';

        $this->configure(
            array(
                'eyebrow'            => 'Wild Himalayan Nettle',
                'title'              => "From Forest\nto Form",
                'description'        => 'A scroll-led journey through the mountains, forests, fibres and hands behind every Loomaama piece.',
                'scroll_text'        => 'Scroll to begin ↓',
                'media_type'         => 'video',
                'video_url'          => '',
                'image_url'          => '',
                'poster_url'         => '',
                'autoplay'           => 1,
                'loop'               => 1,
                'show_sound'         => 1,
                'show_scroll'        => 1,
                'sound_on_text'      => 'Mute',
                'sound_off_text'     => 'Unmute',
                'height_desktop'     => 100,
                'height_mobile'      => 100,
                'overlay_type'       => 'gradient',
                'overlay_color'      => '#08120e',
                'overlay_opacity'    => 55,
                'overlay_start_opacity' => 35,
                'overlay_end_opacity'   => 65,
                'heading_size'       => 110,
                'heading_size_mobile'=> 38,
                'description_size'   => 18,
                'content_position'   => 'center',
                'text_align'         => 'center',
            ),
            array(
                '_content_start' => array( 'type' => 'group_start', 'label' => __( 'Content', 'loomaama' ), 'open' => true ),
                'eyebrow'        => array( 'label' => __( 'Eyebrow', 'loomaama' ) ),
                'title'          => array( 'type' => 'textarea', 'rows' => 2, 'label' => __( 'Main heading', 'loomaama' ), 'description' => __( 'Use a new line where the heading should break.', 'loomaama' ) ),
                'description'    => array( 'type' => 'textarea', 'rows' => 4, 'label' => __( 'Description', 'loomaama' ) ),
                'scroll_text'    => array( 'label' => __( 'Scroll cue text', 'loomaama' ) ),
                'show_scroll'    => array( 'type' => 'checkbox', 'label' => __( 'Show scroll cue', 'loomaama' ) ),
                '_content_end'   => array( 'type' => 'group_end' ),

                '_media_start' => array( 'type' => 'group_start', 'label' => __( 'Background media', 'loomaama' ) ),
                'media_type'   => array(
                    'type'    => 'select',
                    'label'   => __( 'Media type', 'loomaama' ),
                    'options' => array(
                        'video' => __( 'Video', 'loomaama' ),
                        'image' => __( 'Image', 'loomaama' ),
                    ),
                ),
                'video_url'      => array( 'type' => 'media', 'media_type' => 'video', 'label' => __( 'Background video', 'loomaama' ) ),
                'image_url'      => array( 'type' => 'media', 'media_type' => 'image', 'label' => __( 'Background or fallback image', 'loomaama' ) ),
                'poster_url'     => array( 'type' => 'media', 'media_type' => 'image', 'label' => __( 'Video poster image', 'loomaama' ) ),
                'autoplay'       => array( 'type' => 'checkbox', 'label' => __( 'Autoplay video', 'loomaama' ) ),
                'loop'           => array( 'type' => 'checkbox', 'label' => __( 'Loop video', 'loomaama' ) ),
                'show_sound'     => array( 'type' => 'checkbox', 'label' => __( 'Show sound control', 'loomaama' ) ),
                'sound_on_text'  => array( 'label' => __( 'Sound-on button text', 'loomaama' ) ),
                'sound_off_text' => array( 'label' => __( 'Muted button text', 'loomaama' ) ),
                '_media_end'     => array( 'type' => 'group_end' ),

                '_layout_start' => array( 'type' => 'group_start', 'label' => __( 'Hero layout', 'loomaama' ) ),
                'height_desktop' => array( 'type' => 'number', 'label' => __( 'Desktop height (vh)', 'loomaama' ), 'min' => 30, 'max' => 160 ),
                'height_mobile'  => array( 'type' => 'number', 'label' => __( 'Mobile height (vh)', 'loomaama' ), 'min' => 30, 'max' => 160 ),
                'content_position'=> array(
                    'type'    => 'select',
                    'label'   => __( 'Vertical content position', 'loomaama' ),
                    'options' => array(
                        'top'    => __( 'Top', 'loomaama' ),
                        'center' => __( 'Centre', 'loomaama' ),
                        'bottom' => __( 'Bottom', 'loomaama' ),
                    ),
                ),
                'overlay_type'    => array(
                    'type'    => 'select',
                    'label'   => __( 'Overlay style', 'loomaama' ),
                    'options' => array(
                        'gradient' => __( 'Reference gradient', 'loomaama' ),
                        'solid'    => __( 'Solid colour', 'loomaama' ),
                    ),
                ),
                'overlay_color'   => array( 'type' => 'color', 'label' => __( 'Overlay colour', 'loomaama' ) ),
                'overlay_start_opacity' => array( 'type' => 'number', 'label' => __( 'Gradient top opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
                'overlay_end_opacity'   => array( 'type' => 'number', 'label' => __( 'Gradient bottom opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
                'overlay_opacity' => array( 'type' => 'number', 'label' => __( 'Solid overlay opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
                'heading_size'    => array( 'type' => 'number', 'label' => __( 'Desktop heading maximum size (px)', 'loomaama' ), 'min' => 24, 'max' => 220 ),
                'heading_size_mobile' => array( 'type' => 'number', 'label' => __( 'Mobile heading size (px)', 'loomaama' ), 'min' => 20, 'max' => 100 ),
                'description_size'=> array( 'type' => 'number', 'label' => __( 'Description size (px)', 'loomaama' ), 'min' => 10, 'max' => 40 ),
                '_layout_end'      => array( 'type' => 'group_end' ),
            )
        );

        parent::__construct(
            'loomaama_journey_hero',
            __( 'Loomaama Journey: Hero', 'loomaama' ),
            array( 'description' => __( 'Fully editable journey hero with video or image.', 'loomaama' ) )
        );
    }

    public function widget( $args, $instance ) {
        $this->enqueue_assets();
        $v = $this->values( $instance );

        $overlay_colour = sanitize_hex_color( $v['overlay_color'] ) ?: '#08120e';
        $overlay_bg = 'solid' === ( $v['overlay_type'] ?? 'gradient' )
            ? loomaama_journey_rgba( $overlay_colour, $v['overlay_opacity'] )
            : 'linear-gradient(' . loomaama_journey_rgba( $overlay_colour, $v['overlay_start_opacity'] ) . ',' . loomaama_journey_rgba( $overlay_colour, $v['overlay_end_opacity'] ) . ')';

        $extra = array(
            '--lm-hero-bg'                  => ! empty( $v['image_url'] ) ? 'url("' . esc_url( $v['image_url'] ) . '")' : '',
            '--lm-hero-height'              => max( 30, (float) $v['height_desktop'] ) . 'vh',
            '--lm-hero-height-mobile'       => max( 30, (float) $v['height_mobile'] ) . 'vh',
            '--lm-hero-overlay-bg'          => $overlay_bg,
            '--lm-hero-heading-size'        => max( 24, (float) $v['heading_size'] ) . 'px',
            '--lm-hero-heading-size-mobile' => max( 20, (float) $v['heading_size_mobile'] ) . 'px',
            '--lm-hero-description-size'    => max( 10, (float) $v['description_size'] ) . 'px',
        );

        $this->open_widget( $args, $v, $extra, array( 'lm-hero-position-' . $v['content_position'] ) );
        ?>
        <section class="hero">
            <?php if ( 'video' === $v['media_type'] && ! empty( $v['video_url'] ) ) : ?>
                <video
                    class="hero-video"
                    data-lm-role="hero-video"
                    <?php echo $this->is_on( $v, 'autoplay' ) ? 'autoplay' : ''; ?>
                    <?php echo $this->is_on( $v, 'loop' ) ? 'loop' : ''; ?>
                    muted
                    playsinline
                    preload="metadata"
                    <?php echo ! empty( $v['poster_url'] ) ? 'poster="' . esc_url( $v['poster_url'] ) . '"' : ''; ?>
                >
                    <source src="<?php echo esc_url( $v['video_url'] ); ?>" type="video/mp4">
                    <?php esc_html_e( 'Your browser does not support the video tag.', 'loomaama' ); ?>
                </video>
            <?php endif; ?>

            <div class="hero-overlay"></div>
            <div class="hero-content">
                <?php if ( $v['eyebrow'] ) : ?><div class="eyebrow"><?php echo esc_html( $v['eyebrow'] ); ?></div><?php endif; ?>
                <?php if ( $v['title'] ) : ?><h1><?php echo $this->multiline( $v['title'] ); ?></h1><?php endif; ?>
                <?php if ( $v['description'] ) : ?><p><?php echo $this->multiline( $v['description'] ); ?></p><?php endif; ?>
            </div>

            <?php if ( $this->is_on( $v, 'show_scroll' ) && $v['scroll_text'] ) : ?>
                <div class="scroll-cue"><?php echo esc_html( $v['scroll_text'] ); ?></div>
            <?php endif; ?>
        </section>

        <?php if ( 'video' === $v['media_type'] && $v['video_url'] && $this->is_on( $v, 'show_sound' ) ) : ?>
            <button
                type="button"
                class="sound-toggle"
                data-lm-role="sound-toggle"
                data-sound-on-label="<?php echo esc_attr( $v['sound_on_text'] ); ?>"
                data-sound-off-label="<?php echo esc_attr( $v['sound_off_text'] ); ?>"
                aria-label="<?php esc_attr_e( 'Toggle sound', 'loomaama' ); ?>"
            >
                <svg class="sound-icon sound-off" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M3 18h18"></path><path d="M12 3v3"></path><path d="M8 21l4-3 4 3"></path></svg>
                <svg class="sound-icon sound-on" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="display:none" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M3 18h18"></path><path d="M12 3v3"></path><path d="M8 21l4-3 4 3"></path><path d="M7 10l10 8"></path><path d="M17 10l-10 8"></path></svg>
                <span class="sound-label"><?php echo esc_html( $v['sound_off_text'] ); ?></span>
            </button>
        <?php endif; ?>
        <?php
        $this->close_widget( $args );
    }
}

/**
 * Nettle intro widget.
 */
class Loomaama_Journey_Nettle_Intro_Widget extends Loomaama_Journey_Widget_Base {
    public function __construct() {
        $this->section_modifier = 'nettle-journey';

        $this->configure(
            array(
                'eyebrow'         => 'THE NETTLE JOURNEY',
                'title'           => 'Rare Himalayan nettle, hand-shaped into skin-conscious luxury.',
                'description'     => 'We create garments that honour the body, respect the earth, and uplift the maker.',
                'button_text'     => 'Read the Nettle Journey',
                'button_url'      => home_url( '/the-truth/' ),
                'button_new_tab'  => 0,
                'show_decorations'=> 1,
                'decoration_1'    => '🌿',
                'decoration_2'    => '✋',
                'decoration_3'    => '🌍',
                'heading_size'    => 58,
                'description_size'=> 18,
                'background_color'=> '#F4EEE4',
                'text_align'      => 'center',
            ),
            array(
                '_content_start' => array( 'type' => 'group_start', 'label' => __( 'Content', 'loomaama' ), 'open' => true ),
                'eyebrow'        => array( 'label' => __( 'Eyebrow', 'loomaama' ) ),
                'title'          => array( 'type' => 'textarea', 'rows' => 3, 'label' => __( 'Heading', 'loomaama' ) ),
                'description'    => array( 'type' => 'textarea', 'rows' => 4, 'label' => __( 'Description', 'loomaama' ) ),
                'button_text'    => array( 'label' => __( 'Button text', 'loomaama' ) ),
                'button_url'     => array( 'type' => 'url', 'label' => __( 'Button URL', 'loomaama' ) ),
                'button_new_tab' => array( 'type' => 'checkbox', 'label' => __( 'Open button in a new tab', 'loomaama' ) ),
                '_content_end'   => array( 'type' => 'group_end' ),

                '_decor_start'    => array( 'type' => 'group_start', 'label' => __( 'Decorations', 'loomaama' ) ),
                'show_decorations'=> array( 'type' => 'checkbox', 'label' => __( 'Show floating decorations', 'loomaama' ) ),
                'decoration_1'    => array( 'label' => __( 'Decoration 1', 'loomaama' ) ),
                'decoration_2'    => array( 'label' => __( 'Decoration 2', 'loomaama' ) ),
                'decoration_3'    => array( 'label' => __( 'Decoration 3', 'loomaama' ) ),
                '_decor_end'      => array( 'type' => 'group_end' ),

                '_type_start'    => array( 'type' => 'group_start', 'label' => __( 'Typography', 'loomaama' ) ),
                'heading_size'   => array( 'type' => 'number', 'label' => __( 'Heading maximum size (px)', 'loomaama' ), 'min' => 20, 'max' => 120 ),
                'description_size'=> array( 'type' => 'number', 'label' => __( 'Description size (px)', 'loomaama' ), 'min' => 10, 'max' => 40 ),
                '_type_end'      => array( 'type' => 'group_end' ),
            )
        );

        parent::__construct(
            'loomaama_journey_nettle_intro',
            __( 'Loomaama Journey: Nettle Intro', 'loomaama' ),
            array( 'description' => __( 'Editable Nettle Journey introduction and call to action.', 'loomaama' ) )
        );
    }

    public function widget( $args, $instance ) {
        $this->enqueue_assets();
        $v = $this->values( $instance );

        $this->open_widget(
            $args,
            $v,
            array(
                '--lm-nettle-heading-size'     => max( 20, (float) $v['heading_size'] ) . 'px',
                '--lm-nettle-description-size' => max( 10, (float) $v['description_size'] ) . 'px',
            )
        );
        ?>
        <section class="nettle-journey">
            <?php if ( $this->is_on( $v, 'show_decorations' ) ) : ?>
                <div class="tags-background" aria-hidden="true">
                    <div class="tag-float"><?php echo esc_html( $v['decoration_1'] ); ?></div>
                    <div class="tag-float"><?php echo esc_html( $v['decoration_2'] ); ?></div>
                    <div class="tag-float"><?php echo esc_html( $v['decoration_3'] ); ?></div>
                </div>
            <?php endif; ?>
            <div class="nettle-container">
                <div class="nettle-content">
                    <?php if ( $v['eyebrow'] ) : ?><div class="nettle-eyebrow"><?php echo esc_html( $v['eyebrow'] ); ?></div><?php endif; ?>
                    <?php if ( $v['title'] ) : ?><h2 class="nettle-headline"><?php echo $this->multiline( $v['title'] ); ?></h2><?php endif; ?>
                    <?php if ( $v['description'] ) : ?><p class="nettle-description"><?php echo $this->multiline( $v['description'] ); ?></p><?php endif; ?>
                    <?php if ( $v['button_text'] && $v['button_url'] ) : ?>
                        <a class="nettle-cta" href="<?php echo esc_url( $v['button_url'] ); ?>"<?php echo $this->target_attributes( $this->is_on( $v, 'button_new_tab' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                            <?php echo esc_html( $v['button_text'] ); ?> <span class="nettle-arrow"></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
        $this->close_widget( $args );
    }
}

/**
 * Founder story widget.
 */
class Loomaama_Journey_Founder_Story_Widget extends Loomaama_Journey_Widget_Base {
    public function __construct() {
        $this->section_modifier = 'founder-story';

        $defaults = array(
            'kicker'           => 'From Where it Begins?',
            'show_progress'    => 1,
            'scroll_height'        => 420,
            'scroll_height_tablet' => 360,
            'scroll_height_mobile' => 320,
            'image_width'      => 56,
            'panel_start_color'=> '#202820',
            'panel_end_color'  => '#FAF9F7',
        );

        $steps = array(
            1 => array(
                'image'   => loomaama_journey_asset_url( 'images/journey/Founder 1.png' ),
                'alt'     => 'Founder portrait',
                'caption' => 'Founder & Creative Director',
                'number'  => '01 — The Beginning',
                'title'   => 'A vision shaped by the mountains.',
                'text'    => 'Loomaama began with a simple belief: materials shaped by nature and knowledge carried through generations deserve a place in the modern world.',
                'quote'   => '"I wanted every piece to feel connected to place, people and purpose."',
                'button_text' => '',
                'button_url'  => '',
            ),
            2 => array(
                'image'   => loomaama_journey_asset_url( 'images/journey/Founder 2.png' ),
                'alt'     => 'Discovering Himalayan nettle',
                'caption' => 'Discovering Himalayan Nettle',
                'number'  => '02 — The Discovery',
                'title'   => 'Finding beauty in what was already there.',
                'text'    => 'In the Himalayan landscape, wild nettle had long been transformed by local communities into durable natural fibre. The founder saw not a forgotten material, but a story waiting to be carried forward.',
                'quote'   => '',
                'button_text' => '',
                'button_url'  => '',
            ),
            3 => array(
                'image'   => loomaama_journey_asset_url( 'images/journey/Founder 3.png' ),
                'alt'     => 'Master artisans at work',
                'caption' => 'Master Artisans at Work',
                'number'  => '03 — The Craft',
                'title'   => 'Built slowly, with human hands.',
                'text'    => 'Rather than rushing production, Loomaama works with artisans whose skill, patience and instinct shape every stitch. Each garment preserves the character of the fibre and the individuality of the maker.',
                'quote'   => '',
                'button_text' => '',
                'button_url'  => '',
            ),
            4 => array(
                'image'   => loomaama_journey_asset_url( 'images/journey/Founder 4.png' ),
                'alt'     => 'Purpose and vision',
                'caption' => 'Purpose & Vision',
                'number'  => '04 — The Purpose',
                'title'   => 'Creating pieces that carry meaning.',
                'text'    => 'Today, Loomaama continues to connect Himalayan materials, traditional knowledge and contemporary design creating garments intended to be worn, valued and remembered.',
                'quote'   => '',
                'button_text' => 'Discover the Vision',
                'button_url'  => home_url( '/the-truth/' ),
            ),
        );

        $fields = array(
            '_general_start' => array( 'type' => 'group_start', 'label' => __( 'General', 'loomaama' ), 'open' => true ),
            'kicker'         => array( 'label' => __( 'Section kicker', 'loomaama' ) ),
            'show_progress'  => array( 'type' => 'checkbox', 'label' => __( 'Show progress bars', 'loomaama' ) ),
            'scroll_height'  => array( 'type' => 'number', 'label' => __( 'Desktop scroll height (vh)', 'loomaama' ), 'min' => 120, 'max' => 900 ),
            'scroll_height_tablet' => array( 'type' => 'number', 'label' => __( 'Tablet scroll height (vh)', 'loomaama' ), 'min' => 120, 'max' => 900 ),
            'scroll_height_mobile' => array( 'type' => 'number', 'label' => __( 'Mobile scroll height (vh)', 'loomaama' ), 'min' => 120, 'max' => 900 ),
            'image_width'    => array( 'type' => 'number', 'label' => __( 'Image column width (%)', 'loomaama' ), 'min' => 30, 'max' => 75 ),
            'panel_start_color' => array( 'type' => 'color', 'label' => __( 'Panel gradient start', 'loomaama' ) ),
            'panel_end_color'   => array( 'type' => 'color', 'label' => __( 'Panel gradient end', 'loomaama' ) ),
            '_general_end'   => array( 'type' => 'group_end' ),
        );

        foreach ( $steps as $i => $step ) {
            $defaults[ 'enabled_' . $i ]        = 1;
            $defaults[ 'image_' . $i ]          = $step['image'];
            $defaults[ 'image_alt_' . $i ]      = $step['alt'];
            $defaults[ 'caption_' . $i ]        = $step['caption'];
            $defaults[ 'number_' . $i ]         = $step['number'];
            $defaults[ 'title_' . $i ]          = $step['title'];
            $defaults[ 'text_' . $i ]           = $step['text'];
            $defaults[ 'quote_' . $i ]          = $step['quote'];
            $defaults[ 'button_text_' . $i ]    = $step['button_text'];
            $defaults[ 'button_url_' . $i ]     = $step['button_url'];
            $defaults[ 'button_new_tab_' . $i ] = 0;

            $fields[ '_step_' . $i . '_start' ] = array( 'type' => 'group_start', 'label' => sprintf( __( 'Story step %d', 'loomaama' ), $i ) );
            $fields[ 'enabled_' . $i ]           = array( 'type' => 'checkbox', 'label' => sprintf( __( 'Enable step %d', 'loomaama' ), $i ) );
            $fields[ 'image_' . $i ]             = array( 'type' => 'media', 'media_type' => 'image', 'label' => __( 'Image', 'loomaama' ) );
            $fields[ 'image_alt_' . $i ]         = array( 'label' => __( 'Image alt text', 'loomaama' ) );
            $fields[ 'caption_' . $i ]           = array( 'label' => __( 'Image caption', 'loomaama' ) );
            $fields[ 'number_' . $i ]            = array( 'label' => __( 'Step label or number', 'loomaama' ) );
            $fields[ 'title_' . $i ]             = array( 'type' => 'textarea', 'rows' => 2, 'label' => __( 'Heading', 'loomaama' ) );
            $fields[ 'text_' . $i ]              = array( 'type' => 'textarea', 'rows' => 5, 'label' => __( 'Body text', 'loomaama' ) );
            $fields[ 'quote_' . $i ]             = array( 'type' => 'textarea', 'rows' => 3, 'label' => __( 'Quote (optional)', 'loomaama' ) );
            $fields[ 'button_text_' . $i ]       = array( 'label' => __( 'Button text (optional)', 'loomaama' ) );
            $fields[ 'button_url_' . $i ]        = array( 'type' => 'url', 'label' => __( 'Button URL', 'loomaama' ) );
            $fields[ 'button_new_tab_' . $i ]    = array( 'type' => 'checkbox', 'label' => __( 'Open button in a new tab', 'loomaama' ) );
            $fields[ '_step_' . $i . '_end' ]    = array( 'type' => 'group_end' );
        }

        $this->configure( $defaults, $fields );

        parent::__construct(
            'loomaama_journey_founder_story',
            __( 'Loomaama Journey: Founder Story', 'loomaama' ),
            array( 'description' => __( 'Four fully editable scroll-led story steps.', 'loomaama' ) )
        );
    }

    public function widget( $args, $instance ) {
        $this->enqueue_assets();
        $v = $this->values( $instance );

        $enabled = array();
        for ( $i = 1; $i <= 4; $i++ ) {
            if ( $this->is_on( $v, 'enabled_' . $i ) ) {
                $enabled[] = $i;
            }
        }
        if ( empty( $enabled ) ) {
            return;
        }

        $extra = array(
            '--lm-founder-height'        => max( 120, (float) $v['scroll_height'] ) . 'vh',
            '--lm-founder-height-tablet' => max( 120, (float) $v['scroll_height_tablet'] ) . 'vh',
            '--lm-founder-height-mobile' => max( 120, (float) $v['scroll_height_mobile'] ) . 'vh',
            '--lm-founder-image-width'   => min( 75, max( 30, (float) $v['image_width'] ) ) . '%',
            '--lm-founder-panel-start' => sanitize_hex_color( $v['panel_start_color'] ) ?: '#202820',
            '--lm-founder-panel-end'   => sanitize_hex_color( $v['panel_end_color'] ) ?: '#FAF9F7',
        );

        $this->open_widget( $args, $v, $extra );
        ?>
        <section class="founder-story" data-step="0">
            <div class="founder-sticky">
                <div class="founder-layout">
                    <div class="founder-visual">
                        <?php foreach ( $enabled as $position => $i ) : ?>
                            <img
                                class="founder-image <?php echo esc_attr( 0 === $position ? 'main active' : 'step' . $position ); ?>"
                                src="<?php echo esc_url( $v[ 'image_' . $i ] ); ?>"
                                alt="<?php echo esc_attr( $v[ 'image_alt_' . $i ] ); ?>"
                                data-caption="<?php echo esc_attr( $v[ 'caption_' . $i ] ); ?>"
                            >
                        <?php endforeach; ?>
                        <div class="founder-grain"></div>
                        <div class="founder-caption"><?php echo esc_html( $v[ 'caption_' . $enabled[0] ] ); ?></div>
                    </div>

                    <div class="founder-panel">
                        <?php if ( $v['kicker'] ) : ?><div class="founder-kicker"><?php echo esc_html( $v['kicker'] ); ?></div><?php endif; ?>

                        <?php if ( $this->is_on( $v, 'show_progress' ) ) : ?>
                            <div class="founder-progress" aria-hidden="true">
                                <?php foreach ( $enabled as $position => $unused ) : ?><span class="<?php echo 0 === $position ? 'active' : ''; ?>"></span><?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="story-wrap">
                            <?php foreach ( $enabled as $position => $i ) : ?>
                                <article class="story-step<?php echo 0 === $position ? ' active' : ''; ?>">
                                    <?php if ( $v[ 'number_' . $i ] ) : ?><span class="number"><?php echo esc_html( $v[ 'number_' . $i ] ); ?></span><?php endif; ?>
                                    <?php if ( $v[ 'title_' . $i ] ) : ?><h2><?php echo $this->multiline( $v[ 'title_' . $i ] ); ?></h2><?php endif; ?>
                                    <?php if ( $v[ 'text_' . $i ] ) : ?><p><?php echo $this->multiline( $v[ 'text_' . $i ] ); ?></p><?php endif; ?>
                                    <?php if ( $v[ 'quote_' . $i ] ) : ?><div class="story-quote"><?php echo $this->multiline( $v[ 'quote_' . $i ] ); ?></div><?php endif; ?>
                                    <?php if ( $v[ 'button_text_' . $i ] && $v[ 'button_url_' . $i ] ) : ?>
                                        <a class="founder-cta" href="<?php echo esc_url( $v[ 'button_url_' . $i ] ); ?>"<?php echo $this->target_attributes( $this->is_on( $v, 'button_new_tab_' . $i ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                                            <?php echo esc_html( $v[ 'button_text_' . $i ] ); ?> <span></span>
                                        </a>
                                    <?php endif; ?>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        $this->close_widget( $args );
    }
}

/**
 * Mountain story widget.
 */
class Loomaama_Journey_Mountain_Story_Widget extends Loomaama_Journey_Widget_Base {
    public function __construct() {
        $this->section_modifier = 'mountain-story';

        $defaults = array(
            'label'           => 'The Mountain',
            'show_progress'   => 1,
            'scroll_height'        => 420,
            'scroll_height_tablet' => 360,
            'scroll_height_mobile' => 320,
            'scroll_height_small'  => 300,
            'overlay_type'         => 'gradient',
            'overlay_color'        => '#000000',
            'overlay_opacity'      => 52,
            'overlay_start_opacity'=> 20,
            'overlay_mid_opacity'  => 50,
            'overlay_end_opacity'  => 70,
            'heading_size'         => 72,
            'heading_size_tablet'  => 38,
            'heading_size_mobile'  => 30,
            'heading_size_small'   => 26,
            'description_size'     => 22,
            'description_size_tablet' => 16,
            'description_size_mobile' => 14,
            'description_size_small'  => 13,
            'text_align'      => 'center',
        );

        $steps = array(
            1 => array( loomaama_journey_asset_url( 'images/journey/mt everest.png' ), 'center center', 'From Fibre to Form', 'Every finished piece carries the landscape, skill and time behind its creation.' ),
            2 => array( loomaama_journey_asset_url( 'images/journey/Mt lhotse.png' ), 'center center', 'Naturally Textured', 'Subtle variations in the fibre reveal the honest character of its handmade origin.' ),
            3 => array( loomaama_journey_asset_url( 'images/journey/landscape.png' ), 'center center', 'Made Slowly', 'Each stitch reflects patient craftsmanship and generations of knowledge.' ),
            4 => array( loomaama_journey_asset_url( 'images/journey/landscape 2.png' ), 'center center', 'Made to Move', 'Breathable, lightweight and shaped to become part of everyday life.' ),
        );

        $fields = array(
            '_general_start' => array( 'type' => 'group_start', 'label' => __( 'General', 'loomaama' ), 'open' => true ),
            'label'          => array( 'label' => __( 'Chapter label', 'loomaama' ) ),
            'show_progress'  => array( 'type' => 'checkbox', 'label' => __( 'Show step counter', 'loomaama' ) ),
            'scroll_height'  => array( 'type' => 'number', 'label' => __( 'Desktop scroll height (vh)', 'loomaama' ), 'min' => 120, 'max' => 900 ),
            'scroll_height_tablet' => array( 'type' => 'number', 'label' => __( 'Tablet scroll height (vh)', 'loomaama' ), 'min' => 120, 'max' => 900 ),
            'scroll_height_mobile' => array( 'type' => 'number', 'label' => __( 'Mobile scroll height (vh)', 'loomaama' ), 'min' => 120, 'max' => 900 ),
            'scroll_height_small'  => array( 'type' => 'number', 'label' => __( 'Small mobile scroll height (vh)', 'loomaama' ), 'min' => 120, 'max' => 900 ),
            'overlay_type' => array(
                'type' => 'select',
                'label' => __( 'Overlay style', 'loomaama' ),
                'options' => array( 'gradient' => __( 'Reference gradient', 'loomaama' ), 'solid' => __( 'Solid colour', 'loomaama' ) ),
            ),
            'overlay_color'  => array( 'type' => 'color', 'label' => __( 'Image overlay colour', 'loomaama' ) ),
            'overlay_start_opacity'=> array( 'type' => 'number', 'label' => __( 'Gradient top opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
            'overlay_mid_opacity'  => array( 'type' => 'number', 'label' => __( 'Gradient middle opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
            'overlay_end_opacity'  => array( 'type' => 'number', 'label' => __( 'Gradient bottom opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
            'overlay_opacity'=> array( 'type' => 'number', 'label' => __( 'Solid overlay opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
            'heading_size'   => array( 'type' => 'number', 'label' => __( 'Desktop heading maximum size (px)', 'loomaama' ), 'min' => 20, 'max' => 140 ),
            'heading_size_tablet' => array( 'type' => 'number', 'label' => __( 'Tablet heading size (px)', 'loomaama' ), 'min' => 20, 'max' => 140 ),
            'heading_size_mobile' => array( 'type' => 'number', 'label' => __( 'Mobile heading size (px)', 'loomaama' ), 'min' => 20, 'max' => 140 ),
            'heading_size_small'  => array( 'type' => 'number', 'label' => __( 'Small mobile heading size (px)', 'loomaama' ), 'min' => 20, 'max' => 140 ),
            'description_size'=> array( 'type' => 'number', 'label' => __( 'Desktop description maximum size (px)', 'loomaama' ), 'min' => 10, 'max' => 50 ),
            'description_size_tablet'=> array( 'type' => 'number', 'label' => __( 'Tablet description size (px)', 'loomaama' ), 'min' => 10, 'max' => 50 ),
            'description_size_mobile'=> array( 'type' => 'number', 'label' => __( 'Mobile description size (px)', 'loomaama' ), 'min' => 10, 'max' => 50 ),
            'description_size_small'=> array( 'type' => 'number', 'label' => __( 'Small mobile description size (px)', 'loomaama' ), 'min' => 10, 'max' => 50 ),
            '_general_end'   => array( 'type' => 'group_end' ),
        );

        foreach ( $steps as $i => $step ) {
            $defaults[ 'enabled_' . $i ]     = 1;
            $defaults[ 'background_' . $i ]  = $step[0];
            $defaults[ 'position_' . $i ]    = $step[1];
            $defaults[ 'title_' . $i ]       = $step[2];
            $defaults[ 'text_' . $i ]        = $step[3];

            $fields[ '_step_' . $i . '_start' ] = array( 'type' => 'group_start', 'label' => sprintf( __( 'Mountain step %d', 'loomaama' ), $i ) );
            $fields[ 'enabled_' . $i ]          = array( 'type' => 'checkbox', 'label' => sprintf( __( 'Enable step %d', 'loomaama' ), $i ) );
            $fields[ 'background_' . $i ]       = array( 'type' => 'media', 'media_type' => 'image', 'label' => __( 'Background image', 'loomaama' ) );
            $fields[ 'position_' . $i ]         = array( 'label' => __( 'Background position', 'loomaama' ), 'description' => __( 'Examples: center center, 50% 30%, left top.', 'loomaama' ) );
            $fields[ 'title_' . $i ]            = array( 'type' => 'textarea', 'rows' => 2, 'label' => __( 'Heading', 'loomaama' ) );
            $fields[ 'text_' . $i ]             = array( 'type' => 'textarea', 'rows' => 4, 'label' => __( 'Description', 'loomaama' ) );
            $fields[ '_step_' . $i . '_end' ]   = array( 'type' => 'group_end' );
        }

        $this->configure( $defaults, $fields );

        parent::__construct(
            'loomaama_journey_mountain_story',
            __( 'Loomaama Journey: Mountain Story', 'loomaama' ),
            array( 'description' => __( 'Fully editable image-led mountain story.', 'loomaama' ) )
        );
    }

    public function widget( $args, $instance ) {
        $this->enqueue_assets();
        $v = $this->values( $instance );

        $enabled = array();
        for ( $i = 1; $i <= 4; $i++ ) {
            if ( $this->is_on( $v, 'enabled_' . $i ) ) {
                $enabled[] = $i;
            }
        }
        if ( empty( $enabled ) ) {
            return;
        }

        $mountain_overlay_colour = sanitize_hex_color( $v['overlay_color'] ) ?: '#000000';
        $mountain_overlay_bg = 'solid' === ( $v['overlay_type'] ?? 'gradient' )
            ? loomaama_journey_rgba( $mountain_overlay_colour, $v['overlay_opacity'] )
            : 'linear-gradient(180deg,' . loomaama_journey_rgba( $mountain_overlay_colour, $v['overlay_start_opacity'] ) . ' 0%,' . loomaama_journey_rgba( $mountain_overlay_colour, $v['overlay_mid_opacity'] ) . ' 40%,' . loomaama_journey_rgba( $mountain_overlay_colour, $v['overlay_end_opacity'] ) . ' 100%)';

        $this->open_widget(
            $args,
            $v,
            array(
                '--lm-mountain-height'              => max( 120, (float) $v['scroll_height'] ) . 'vh',
                '--lm-mountain-height-tablet'       => max( 120, (float) $v['scroll_height_tablet'] ) . 'vh',
                '--lm-mountain-height-mobile'       => max( 120, (float) $v['scroll_height_mobile'] ) . 'vh',
                '--lm-mountain-height-small'        => max( 120, (float) $v['scroll_height_small'] ) . 'vh',
                '--lm-mountain-overlay-bg'          => $mountain_overlay_bg,
                '--lm-mountain-heading-size'        => max( 20, (float) $v['heading_size'] ) . 'px',
                '--lm-mountain-heading-tablet'      => max( 20, (float) $v['heading_size_tablet'] ) . 'px',
                '--lm-mountain-heading-mobile'      => max( 20, (float) $v['heading_size_mobile'] ) . 'px',
                '--lm-mountain-heading-small'       => max( 20, (float) $v['heading_size_small'] ) . 'px',
                '--lm-mountain-description-size'    => max( 10, (float) $v['description_size'] ) . 'px',
                '--lm-mountain-description-tablet'  => max( 10, (float) $v['description_size_tablet'] ) . 'px',
                '--lm-mountain-description-mobile'  => max( 10, (float) $v['description_size_mobile'] ) . 'px',
                '--lm-mountain-description-small'   => max( 10, (float) $v['description_size_small'] ) . 'px',
            )
        );
        ?>
        <section class="mountain-story" data-step="0">
            <div class="mountain-sticky">
                <div class="mountain-bg-wrapper">
                    <?php foreach ( $enabled as $position => $i ) : ?>
                        <div
                            class="mountain-bg<?php echo 0 === $position ? ' active' : ''; ?>"
                            style="background-image:url('<?php echo esc_url( $v[ 'background_' . $i ] ); ?>');background-position:<?php echo esc_attr( $v[ 'position_' . $i ] ); ?>"
                        ></div>
                    <?php endforeach; ?>
                    <div class="mountain-overlay"></div>
                </div>

                <div class="mountain-content-wrapper">
                    <div class="mountain-content">
                        <?php if ( $v['label'] ) : ?><div class="mountain-chapter-label"><?php echo esc_html( $v['label'] ); ?></div><?php endif; ?>
                        <div class="mountain-copy">
                            <?php foreach ( $enabled as $position => $i ) : ?>
                                <div class="step<?php echo 0 === $position ? ' active' : ''; ?>">
                                    <?php if ( $v[ 'title_' . $i ] ) : ?><h2><?php echo $this->multiline( $v[ 'title_' . $i ] ); ?></h2><?php endif; ?>
                                    <?php if ( $v[ 'text_' . $i ] ) : ?><p><?php echo $this->multiline( $v[ 'text_' . $i ] ); ?></p><?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
						
                    </div>
                </div>
            </div>
        </section>
        <?php
        $this->close_widget( $args );
    }
}

/**
 * Impact widget.
 */
class Loomaama_Journey_Impact_Widget extends Loomaama_Journey_Widget_Base {
    public function __construct() {
        $this->section_modifier = 'impact';

        $defaults = array(
            'eyebrow'         => 'OUR IMPACT',
            'title'           => 'Building a future where craft and community thrive together.',
            'button_text'     => 'Our Commitment',
            'button_url'      => home_url( '/impact/' ),
            'button_new_tab'  => 0,
            'columns'         => '4',
            'columns_tablet'  => '2',
            'columns_mobile'  => '1',
            'card_background' => '#FFFFFF',
            'card_border'     => '#DED7CD',
            'card_radius'     => 20,
            'number_size'     => 56,
            'number_size_tablet' => 40,
            'number_size_mobile' => 32,
        );

        $cards = array(
            1 => array( '50+', 'Women Empowered', 'Through fair wages and skill development programs' ),
            2 => array( '98%', 'Fair Trade Certified', 'Ensuring ethical practices across all partnerships' ),
            3 => array( '10+', 'Local Communities', 'Supporting traditional craft and sustainable livelihoods' ),
            4 => array( '90%', 'Less Water Usage', 'Compared to conventional textile production' ),
        );

        $fields = array(
            '_content_start' => array( 'type' => 'group_start', 'label' => __( 'Content and layout', 'loomaama' ), 'open' => true ),
            'eyebrow'        => array( 'label' => __( 'Eyebrow', 'loomaama' ) ),
            'title'          => array( 'type' => 'textarea', 'rows' => 3, 'label' => __( 'Heading', 'loomaama' ) ),
            'button_text'    => array( 'label' => __( 'Button text', 'loomaama' ) ),
            'button_url'     => array( 'type' => 'url', 'label' => __( 'Button URL', 'loomaama' ) ),
            'button_new_tab' => array( 'type' => 'checkbox', 'label' => __( 'Open button in a new tab', 'loomaama' ) ),
            'columns'        => array(
                'type'    => 'select',
                'label'   => __( 'Desktop columns', 'loomaama' ),
                'options' => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
            ),
            'card_background'=> array( 'type' => 'color', 'label' => __( 'Card background', 'loomaama' ) ),
            'card_border'    => array( 'type' => 'color', 'label' => __( 'Card border', 'loomaama' ) ),
            'card_radius'    => array( 'type' => 'number', 'label' => __( 'Card corner radius (px)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
            'number_size'    => array( 'type' => 'number', 'label' => __( 'Statistic size (px)', 'loomaama' ), 'min' => 20, 'max' => 120 ),
            '_content_end'   => array( 'type' => 'group_end' ),
        );

        foreach ( $cards as $i => $card ) {
            $defaults[ 'enabled_' . $i ]      = 1;
            $defaults[ 'number_' . $i ]       = $card[0];
            $defaults[ 'label_' . $i ]        = $card[1];
            $defaults[ 'text_' . $i ]         = $card[2];
            $defaults[ 'url_' . $i ]          = '';
            $defaults[ 'new_tab_' . $i ]      = 0;

            $fields[ '_card_' . $i . '_start' ] = array( 'type' => 'group_start', 'label' => sprintf( __( 'Impact card %d', 'loomaama' ), $i ) );
            $fields[ 'enabled_' . $i ]          = array( 'type' => 'checkbox', 'label' => sprintf( __( 'Enable card %d', 'loomaama' ), $i ) );
            $fields[ 'number_' . $i ]           = array( 'label' => __( 'Statistic', 'loomaama' ) );
            $fields[ 'label_' . $i ]            = array( 'label' => __( 'Label', 'loomaama' ) );
            $fields[ 'text_' . $i ]             = array( 'type' => 'textarea', 'rows' => 3, 'label' => __( 'Description', 'loomaama' ) );
            $fields[ 'url_' . $i ]              = array( 'type' => 'url', 'label' => __( 'Optional card link', 'loomaama' ) );
            $fields[ 'new_tab_' . $i ]          = array( 'type' => 'checkbox', 'label' => __( 'Open card link in a new tab', 'loomaama' ) );
            $fields[ '_card_' . $i . '_end' ]   = array( 'type' => 'group_end' );
        }

        $this->configure( $defaults, $fields );

        parent::__construct(
            'loomaama_journey_impact',
            __( 'Loomaama Journey: Impact', 'loomaama' ),
            array( 'description' => __( 'Editable impact heading and statistic cards.', 'loomaama' ) )
        );
    }

    public function widget( $args, $instance ) {
        $this->enqueue_assets();
        $v = $this->values( $instance );

        $this->open_widget(
            $args,
            $v,
            array(
                '--lm-impact-columns'   => (string) max( 1, min( 4, (int) $v['columns'] ) ),
                '--lm-card-bg'          => sanitize_hex_color( $v['card_background'] ) ?: '#FFFFFF',
                '--lm-card-border'      => sanitize_hex_color( $v['card_border'] ) ?: '#DED7CD',
                '--lm-card-radius'      => max( 0, (float) $v['card_radius'] ) . 'px',
                '--lm-impact-number-size'        => max( 20, (float) $v['number_size'] ) . 'px',
                '--lm-impact-number-tablet'      => max( 20, (float) $v['number_size_tablet'] ) . 'px',
                '--lm-impact-number-mobile'      => max( 20, (float) $v['number_size_mobile'] ) . 'px',
            )
        );
        ?>
        <section class="impact-section">
            <div class="impact-container">
                <div class="impact-header">
                    <?php if ( $v['eyebrow'] ) : ?><div class="impact-eyebrow"><?php echo esc_html( $v['eyebrow'] ); ?></div><?php endif; ?>
                    <?php if ( $v['title'] ) : ?><h2 class="impact-title"><?php echo $this->multiline( $v['title'] ); ?></h2><?php endif; ?>
                </div>

                <div class="impact-grid">
                    <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
                        <?php if ( ! $this->is_on( $v, 'enabled_' . $i ) ) { continue; } ?>
                        <?php $has_link = ! empty( $v[ 'url_' . $i ] ); ?>
                        <?php if ( $has_link ) : ?><a class="impact-card impact-card--linked" href="<?php echo esc_url( $v[ 'url_' . $i ] ); ?>"<?php echo $this->target_attributes( $this->is_on( $v, 'new_tab_' . $i ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php else : ?><div class="impact-card"><?php endif; ?>
                            <?php if ( $v[ 'number_' . $i ] ) : ?><div class="impact-number"><?php echo esc_html( $v[ 'number_' . $i ] ); ?></div><?php endif; ?>
                            <?php if ( $v[ 'label_' . $i ] ) : ?><div class="impact-label"><?php echo esc_html( $v[ 'label_' . $i ] ); ?></div><?php endif; ?>
                            <?php if ( $v[ 'text_' . $i ] ) : ?><div class="impact-description"><?php echo $this->multiline( $v[ 'text_' . $i ] ); ?></div><?php endif; ?>
                        <?php if ( $has_link ) : ?></a><?php else : ?></div><?php endif; ?>
                    <?php endfor; ?>
                </div>

                <?php if ( $v['button_text'] && $v['button_url'] ) : ?>
                    <div class="impact-footer">
                        <a class="impact-cta" href="<?php echo esc_url( $v['button_url'] ); ?>"<?php echo $this->target_attributes( $this->is_on( $v, 'button_new_tab' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                            <?php echo esc_html( $v['button_text'] ); ?> <span class="impact-arrow"></span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
        $this->close_widget( $args );
    }
}

/**
 * Forest story widget.
 */
class Loomaama_Journey_Forest_Story_Widget extends Loomaama_Journey_Widget_Base {
    public function __construct() {
        $this->section_modifier = 'forest-story';

        $defaults = array(
            'label'             => 'The Forest',
            'background_url'    => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=2000&q=85',
            'background_position'=> 'center center',
            'overlay_type'      => 'gradient',
            'overlay_color'     => '#08110d',
            'overlay_opacity'   => 52,
            'overlay_direction' => 90,
            'overlay_start_opacity' => 85,
            'overlay_end_opacity'   => 30,
            'plant_image'       => loomaama_journey_asset_url( 'images/journey/Nettle image.png' ),
            'plant_alt'         => 'Wild nettle plant',
            'show_plant'        => 1,
            'plant_shape'       => 'arch',
            'show_tags'         => 1,
            'scroll_height'     => 420,
            'tag_background'    => '#14221c',
            'tag_text_color'    => '#FFFFFF',
        );

        $steps = array(
            1 => array( 'Deep Within the Himalayan Forest', 'Wild nettle grows naturally across the mountain landscape of Nepal, untouched by intensive farming.', 'Naturally grown' ),
            2 => array( 'Growing Wild', 'No chemical cultivation. No artificial irrigation. Only soil, rain, altitude and time.', 'No chemicals' ),
            3 => array( 'Gathered by Hand', 'Local communities carefully harvest mature nettle while protecting the surrounding forest and future growth.', 'Hand harvested' ),
            4 => array( 'From Plant to Fibre', 'The stems are prepared, softened and transformed into a strong natural fibre ready to be spun by hand.', 'Renewable by nature' ),
        );

        $fields = array(
            '_general_start' => array( 'type' => 'group_start', 'label' => __( 'General and media', 'loomaama' ), 'open' => true ),
            'label'          => array( 'label' => __( 'Chapter label', 'loomaama' ) ),
            'background_url' => array( 'type' => 'media', 'media_type' => 'image', 'label' => __( 'Background image', 'loomaama' ) ),
            'background_position' => array( 'label' => __( 'Background position', 'loomaama' ), 'description' => __( 'Examples: center center, 50% 30%, right top.', 'loomaama' ) ),
            'overlay_type' => array(
                'type' => 'select',
                'label' => __( 'Overlay style', 'loomaama' ),
                'options' => array( 'gradient' => __( 'Reference side gradient', 'loomaama' ), 'solid' => __( 'Solid colour', 'loomaama' ) ),
            ),
            'overlay_color'  => array( 'type' => 'color', 'label' => __( 'Overlay colour', 'loomaama' ) ),
            'overlay_direction' => array( 'type' => 'number', 'label' => __( 'Gradient direction (degrees)', 'loomaama' ), 'min' => 0, 'max' => 360 ),
            'overlay_start_opacity'=> array( 'type' => 'number', 'label' => __( 'Gradient start opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
            'overlay_end_opacity'=> array( 'type' => 'number', 'label' => __( 'Gradient end opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
            'overlay_opacity'=> array( 'type' => 'number', 'label' => __( 'Solid overlay opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
            'scroll_height'  => array( 'type' => 'number', 'label' => __( 'Scroll section height (vh)', 'loomaama' ), 'min' => 120, 'max' => 900 ),
            'show_plant'     => array( 'type' => 'checkbox', 'label' => __( 'Show plant image card', 'loomaama' ) ),
            'plant_image'    => array( 'type' => 'media', 'media_type' => 'image', 'label' => __( 'Plant image', 'loomaama' ) ),
            'plant_alt'      => array( 'label' => __( 'Plant image alt text', 'loomaama' ) ),
            'plant_shape'    => array(
                'type'    => 'select',
                'label'   => __( 'Plant image shape', 'loomaama' ),
                'options' => array(
                    'arch'    => __( 'Arch', 'loomaama' ),
                    'rounded' => __( 'Rounded rectangle', 'loomaama' ),
                    'square'  => __( 'Square', 'loomaama' ),
                    'circle'  => __( 'Circle', 'loomaama' ),
                ),
            ),
            'show_tags'      => array( 'type' => 'checkbox', 'label' => __( 'Show progressive tags', 'loomaama' ) ),
            'tag_background' => array( 'type' => 'color', 'label' => __( 'Tag background', 'loomaama' ) ),
            'tag_text_color' => array( 'type' => 'color', 'label' => __( 'Tag text colour', 'loomaama' ) ),
            '_general_end'   => array( 'type' => 'group_end' ),
        );

        foreach ( $steps as $i => $step ) {
            $defaults[ 'enabled_' . $i ] = 1;
            $defaults[ 'title_' . $i ]   = $step[0];
            $defaults[ 'text_' . $i ]    = $step[1];
            $defaults[ 'tag_' . $i ]     = $step[2];

            $fields[ '_step_' . $i . '_start' ] = array( 'type' => 'group_start', 'label' => sprintf( __( 'Forest step %d', 'loomaama' ), $i ) );
            $fields[ 'enabled_' . $i ]          = array( 'type' => 'checkbox', 'label' => sprintf( __( 'Enable step %d', 'loomaama' ), $i ) );
            $fields[ 'title_' . $i ]            = array( 'type' => 'textarea', 'rows' => 2, 'label' => __( 'Heading', 'loomaama' ) );
            $fields[ 'text_' . $i ]             = array( 'type' => 'textarea', 'rows' => 4, 'label' => __( 'Description', 'loomaama' ) );
            $fields[ 'tag_' . $i ]              = array( 'label' => __( 'Progressive tag', 'loomaama' ) );
            $fields[ '_step_' . $i . '_end' ]   = array( 'type' => 'group_end' );
        }

        $this->configure( $defaults, $fields );

        parent::__construct(
            'loomaama_journey_forest_story',
            __( 'Loomaama Journey: Forest Story', 'loomaama' ),
            array( 'description' => __( 'Editable forest story with plant card and progressive tags.', 'loomaama' ) )
        );
    }

    public function widget( $args, $instance ) {
        $this->enqueue_assets();
        $v = $this->values( $instance );

        $enabled = array();
        for ( $i = 1; $i <= 4; $i++ ) {
            if ( $this->is_on( $v, 'enabled_' . $i ) ) {
                $enabled[] = $i;
            }
        }
        if ( empty( $enabled ) ) {
            return;
        }

        $forest_overlay_colour = sanitize_hex_color( $v['overlay_color'] ) ?: '#08110d';
        $forest_overlay_bg = 'solid' === ( $v['overlay_type'] ?? 'gradient' )
            ? loomaama_journey_rgba( $forest_overlay_colour, $v['overlay_opacity'] )
            : 'linear-gradient(' . max( 0, min( 360, (float) $v['overlay_direction'] ) ) . 'deg,' . loomaama_journey_rgba( $forest_overlay_colour, $v['overlay_start_opacity'] ) . ',' . loomaama_journey_rgba( $forest_overlay_colour, $v['overlay_end_opacity'] ) . ')';

        $this->open_widget(
            $args,
            $v,
            array(
                '--lm-forest-height' => max( 120, (float) $v['scroll_height'] ) . 'vh',
                '--lm-forest-bg'     => ! empty( $v['background_url'] ) ? 'url("' . esc_url( $v['background_url'] ) . '")' : '',
                '--lm-forest-position'=> sanitize_text_field( $v['background_position'] ),
                '--lm-forest-overlay-bg' => $forest_overlay_bg,
                '--lm-tag-bg'        => sanitize_hex_color( $v['tag_background'] ) ?: '#14221c',
                '--lm-tag-text'      => sanitize_hex_color( $v['tag_text_color'] ) ?: '#FFFFFF',
            ),
            array( 'lm-plant-shape-' . $v['plant_shape'] )
        );
        ?>
        <section class="forest-story" data-step="0">
            <div class="sticky forest-sticky">
                <div class="forest-bg"></div>
                <div class="forest-custom-overlay"></div>
                <?php if ( $v['label'] ) : ?><div class="forest-chapter-label"><?php echo esc_html( $v['label'] ); ?></div><?php endif; ?>

                <div class="forest-content-wrapper">
                    <div class="forest-copy">
                        <?php foreach ( $enabled as $position => $i ) : ?>
                            <div class="step<?php echo 0 === $position ? ' active' : ''; ?>">
                                <?php if ( $v[ 'title_' . $i ] ) : ?><h2><?php echo $this->multiline( $v[ 'title_' . $i ] ); ?></h2><?php endif; ?>
                                <?php if ( $v[ 'text_' . $i ] ) : ?><p><?php echo $this->multiline( $v[ 'text_' . $i ] ); ?></p><?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ( $this->is_on( $v, 'show_plant' ) && $v['plant_image'] ) : ?>
                    <div class="plant-card">
                        <img src="<?php echo esc_url( $v['plant_image'] ); ?>" alt="<?php echo esc_attr( $v['plant_alt'] ); ?>">
                    </div>
                <?php endif; ?>

                <?php if ( $this->is_on( $v, 'show_tags' ) ) : ?>
                    <?php foreach ( $enabled as $position => $i ) : ?>
                        <?php if ( $v[ 'tag_' . $i ] ) : ?><div class="tag" data-tag-index="<?php echo esc_attr( $position ); ?>"><?php echo esc_html( $v[ 'tag_' . $i ] ); ?></div><?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <?php
        $this->close_widget( $args );
    }
}

/**
 * Artisans widget.
 */
class Loomaama_Journey_Artisans_Widget extends Loomaama_Journey_Widget_Base {
    public function __construct() {
        $this->section_modifier = 'artisans';

        $this->configure(
            array(
                'eyebrow'                  => 'MEET THE MAKERS',
                'title'                    => "Hands That\nWeave Heritage",
                'highlight_text'           => 'Hands',
                'subtitle'                 => '',
                'button_text'              => 'Meet all artisans',
                'button_url'               => home_url( '/the-artisans/' ),
                'button_new_tab'           => 0,
                'show_button'              => 0,
                'artisan_orderby'          => 'menu_order_title',
                'artisan_order'            => 'ASC',
                'minimal_cards'            => 1,
                'show_badges'              => 0,
                'show_type'                => 0,
                'show_location'            => 0,
                'show_quote'               => 0,
                'quote_word_limit'         => 18,
                'autoplay'                 => 1,
                'autoplay_delay'           => 2000,
                'pause_on_hover'           => 1,
                'show_arrows'              => 1,
                'show_dots'                => 1,
                'slides_desktop'           => 3,
                'slides_tablet'            => 2,
                'slides_mobile'            => 1,
                'card_gap'                 => 30,
                'card_height'              => 420,
                'card_height_tablet'       => 380,
                'card_height_mobile'       => 360,
                'image_position'           => 'center top',
                'section_background'       => '#F6F4F0',
                'panel_background'         => '#FFFFFF',
                'panel_border'             => '#E8E4DE',
                'accent'                   => '#9E8574',
                'title_color'              => '#1C1C1A',
                'name_color'               => '#FFFFFF',
                'overlay_color'            => '#0A0F0C',
                'overlay_opacity'          => 70,
                'panel_padding_x'          => 60,
                'panel_padding_y'          => 50,
                'panel_shadow'             => 1,
                'container_width'          => 1200,
            ),
            array(
                '_content_start' => array(
                    'type'  => 'group_start',
                    'label' => __( 'Heading and artisan source', 'loomaama' ),
                    'open'  => true,
                ),
                'eyebrow' => array(
                    'label' => __( 'Eyebrow', 'loomaama' ),
                ),
                'title' => array(
                    'type'        => 'textarea',
                    'rows'        => 3,
                    'label'       => __( 'Heading', 'loomaama' ),
                    'description' => __( 'Use a new line to control the editorial title break.', 'loomaama' ),
                ),
                'highlight_text' => array(
                    'label'       => __( 'Highlighted heading text', 'loomaama' ),
                    'description' => __( 'The first matching word or phrase uses the accent colour.', 'loomaama' ),
                ),
                'subtitle' => array(
                    'type'  => 'textarea',
                    'rows'  => 3,
                    'label' => __( 'Optional subtitle', 'loomaama' ),
                ),
                'artisan_orderby' => array(
                    'type'        => 'select',
                    'label'       => __( 'Artisan order', 'loomaama' ),
                    'options'     => array(
                        'menu_order_title' => __( 'Manual order, then name', 'loomaama' ),
                        'title'            => __( 'Name', 'loomaama' ),
                        'date'             => __( 'Publish date', 'loomaama' ),
                        'rand'             => __( 'Random', 'loomaama' ),
                    ),
                    'description' => __( 'All published artisan posts are loaded automatically. Manual order uses the post Order value when available.', 'loomaama' ),
                ),
                'artisan_order' => array(
                    'type'    => 'select',
                    'label'   => __( 'Order direction', 'loomaama' ),
                    'options' => array(
                        'ASC'  => __( 'Ascending', 'loomaama' ),
                        'DESC' => __( 'Descending', 'loomaama' ),
                    ),
                ),
                '_content_end' => array( 'type' => 'group_end' ),

                '_card_start' => array(
                    'type'  => 'group_start',
                    'label' => __( 'Artisan slide content', 'loomaama' ),
                ),
                'minimal_cards' => array(
                    'type'        => 'checkbox',
                    'label'       => __( 'Use minimal reference design', 'loomaama' ),
                    'description' => __( 'Shows only the artisan name over the portrait, matching the supplied design.', 'loomaama' ),
                ),
                'show_badges' => array(
                    'type'        => 'checkbox',
                    'label'       => __( 'Show years-crafting badge', 'loomaama' ),
                    'description' => __( 'Loaded from the years_crafting field.', 'loomaama' ),
                ),
                'show_type' => array(
                    'type'        => 'checkbox',
                    'label'       => __( 'Show artisan type', 'loomaama' ),
                    'description' => __( 'Loaded from the artisan-type taxonomy.', 'loomaama' ),
                ),
                'show_location' => array(
                    'type'        => 'checkbox',
                    'label'       => __( 'Show artisan location', 'loomaama' ),
                    'description' => __( 'Loaded from the artisan-location taxonomy.', 'loomaama' ),
                ),
                'show_quote' => array(
                    'type'        => 'checkbox',
                    'label'       => __( 'Show quote', 'loomaama' ),
                    'description' => __( 'Uses the quote field first and falls back to the excerpt.', 'loomaama' ),
                ),
                'quote_word_limit' => array(
                    'type'  => 'number',
                    'label' => __( 'Quote maximum words', 'loomaama' ),
                    'min'   => 5,
                    'max'   => 60,
                ),
                'image_position' => array(
                    'label'       => __( 'Portrait position', 'loomaama' ),
                    'description' => __( 'Examples: center top, 50% 25%, center center.', 'loomaama' ),
                ),
                '_card_end' => array( 'type' => 'group_end' ),

                '_slider_start' => array(
                    'type'  => 'group_start',
                    'label' => __( 'Slider behaviour', 'loomaama' ),
                ),
                'autoplay' => array(
                    'type'  => 'checkbox',
                    'label' => __( 'Enable autoplay', 'loomaama' ),
                ),
                'autoplay_delay' => array(
                    'type'  => 'number',
                    'label' => __( 'Autoplay delay (milliseconds)', 'loomaama' ),
                    'min'   => 1000,
                    'max'   => 15000,
                    'step'  => 250,
                ),
                'pause_on_hover' => array(
                    'type'  => 'checkbox',
                    'label' => __( 'Pause autoplay on hover', 'loomaama' ),
                ),
                'show_arrows' => array(
                    'type'  => 'checkbox',
                    'label' => __( 'Show previous and next buttons', 'loomaama' ),
                ),
                'show_dots' => array(
                    'type'  => 'checkbox',
                    'label' => __( 'Show navigation dots', 'loomaama' ),
                ),
                'slides_desktop' => array(
                    'type'    => 'select',
                    'label'   => __( 'Slides visible on desktop', 'loomaama' ),
                    'options' => array( '1' => '1', '2' => '2', '3' => '3' ),
                ),
                'slides_tablet' => array(
                    'type'    => 'select',
                    'label'   => __( 'Slides visible on tablet', 'loomaama' ),
                    'options' => array( '1' => '1', '2' => '2', '3' => '3' ),
                ),
                'slides_mobile' => array(
                    'type'    => 'select',
                    'label'   => __( 'Slides visible on mobile', 'loomaama' ),
                    'options' => array( '1' => '1', '2' => '2' ),
                ),
                'card_gap' => array(
                    'type'  => 'number',
                    'label' => __( 'Gap between slides (px)', 'loomaama' ),
                    'min'   => 0,
                    'max'   => 80,
                ),
                'card_height' => array(
                    'type'  => 'number',
                    'label' => __( 'Desktop slide height (px)', 'loomaama' ),
                    'min'   => 240,
                    'max'   => 800,
                ),
                'card_height_tablet' => array(
                    'type'  => 'number',
                    'label' => __( 'Tablet slide height (px)', 'loomaama' ),
                    'min'   => 240,
                    'max'   => 800,
                ),
                'card_height_mobile' => array(
                    'type'  => 'number',
                    'label' => __( 'Mobile slide height (px)', 'loomaama' ),
                    'min'   => 240,
                    'max'   => 800,
                ),
                '_slider_end' => array( 'type' => 'group_end' ),

                '_style_start' => array(
                    'type'  => 'group_start',
                    'label' => __( 'Reference design styling', 'loomaama' ),
                ),
                'section_background' => array(
                    'type'  => 'color',
                    'label' => __( 'Outer background', 'loomaama' ),
                ),
                'panel_background' => array(
                    'type'  => 'color',
                    'label' => __( 'Panel background', 'loomaama' ),
                ),
                'panel_border' => array(
                    'type'  => 'color',
                    'label' => __( 'Panel border', 'loomaama' ),
                ),
                'accent' => array(
                    'type'  => 'color',
                    'label' => __( 'Accent colour', 'loomaama' ),
                ),
                'title_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Title colour', 'loomaama' ),
                ),
                'name_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Artisan name colour', 'loomaama' ),
                ),
                'overlay_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Portrait overlay colour', 'loomaama' ),
                ),
                'overlay_opacity' => array(
                    'type'  => 'number',
                    'label' => __( 'Overlay strength', 'loomaama' ),
                    'min'   => 0,
                    'max'   => 100,
                    'step'  => 1,
                ),
                'panel_padding_x' => array(
                    'type'  => 'number',
                    'label' => __( 'Desktop panel side padding (px)', 'loomaama' ),
                    'min'   => 0,
                    'max'   => 160,
                ),
                'panel_padding_y' => array(
                    'type'  => 'number',
                    'label' => __( 'Desktop panel vertical padding (px)', 'loomaama' ),
                    'min'   => 0,
                    'max'   => 160,
                ),
                'panel_shadow' => array(
                    'type'  => 'checkbox',
                    'label' => __( 'Show soft panel shadow', 'loomaama' ),
                ),
                'container_width' => array(
                    'type'  => 'number',
                    'label' => __( 'Panel maximum width (px)', 'loomaama' ),
                    'min'   => 680,
                    'max'   => 2000,
                ),
                '_style_end' => array( 'type' => 'group_end' ),

                '_button_start' => array(
                    'type'  => 'group_start',
                    'label' => __( 'Optional section link', 'loomaama' ),
                ),
                'show_button' => array(
                    'type'  => 'checkbox',
                    'label' => __( 'Show link below slider', 'loomaama' ),
                ),
                'button_text' => array(
                    'label' => __( 'Link text', 'loomaama' ),
                ),
                'button_url' => array(
                    'type'  => 'url',
                    'label' => __( 'Link URL', 'loomaama' ),
                ),
                'button_new_tab' => array(
                    'type'  => 'checkbox',
                    'label' => __( 'Open link in a new tab', 'loomaama' ),
                ),
                '_button_end' => array( 'type' => 'group_end' ),
            )
        );

        parent::__construct(
            'loomaama_journey_artisans',
            __( 'Loomaama Journey: Artisans', 'loomaama' ),
            array(
                'description' => __( 'Automatically display every published artisan post in the editorial artisan slider.', 'loomaama' ),
            )
        );
    }


    /**
     * Format the years-crafting value.
     *
     * @param string $years Saved years value.
     * @return string
     */
    private function format_years_badge( $years ) {
        $years = trim( wp_strip_all_tags( (string) $years ) );

        if ( '' === $years ) {
            return '';
        }

        if ( false !== stripos( $years, 'year' ) ) {
            return $years;
        }

        return sprintf( __( '%s Years', 'loomaama' ), $years );
    }

    /**
     * Build slide data from an artisan custom post.
     *
     * @param int $artisan_id Artisan post ID.
     * @param int $word_limit Maximum words for quote/excerpt.
     * @return array<string,string>|false
     */
    private function get_artisan_card_data( $artisan_id, $word_limit ) {
        $artisan_id = absint( $artisan_id );
        $post       = get_post( $artisan_id );

        if ( ! $post || 'artisan' !== $post->post_type || 'publish' !== $post->post_status ) {
            return false;
        }

        $image = get_the_post_thumbnail_url( $artisan_id, 'loomaama-product-medium' );
        if ( ! $image ) {
            $image = get_the_post_thumbnail_url( $artisan_id, 'full' );
        }

        $type_terms     = get_the_terms( $artisan_id, 'artisan-type' );
        $location_terms = get_the_terms( $artisan_id, 'artisan-location' );

        $type = ( $type_terms && ! is_wp_error( $type_terms ) ) ? $type_terms[0]->name : '';
        $location = ( $location_terms && ! is_wp_error( $location_terms ) ) ? $location_terms[0]->name : '';

        $quote = trim( (string) get_post_meta( $artisan_id, 'quote', true ) );
        if ( '' === $quote ) {
            $quote = trim( (string) get_the_excerpt( $artisan_id ) );
        }
        if ( '' === $quote ) {
            $quote = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
        }

        return array(
            'name'     => get_the_title( $artisan_id ),
            'image'    => $image ? $image : '',
            'alt'      => get_the_title( $artisan_id ),
            'badge'    => $this->format_years_badge( get_post_meta( $artisan_id, 'years_crafting', true ) ),
            'type'     => $type,
            'location' => $location,
            'quote'    => wp_trim_words( $quote, max( 5, absint( $word_limit ) ), '…' ),
            'url'      => get_permalink( $artisan_id ),
        );
    }

    /**
     * Render the heading while highlighting the first matching phrase.
     *
     * @param string $title     Heading text.
     * @param string $highlight Phrase to highlight.
     * @return string
     */
    private function highlighted_title( $title, $highlight ) {
        $escaped_title = esc_html( (string) $title );
        $highlight     = trim( (string) $highlight );

        if ( '' !== $highlight ) {
            $escaped_highlight = esc_html( $highlight );
            $pattern           = '/' . preg_quote( $escaped_highlight, '/' ) . '/iu';
            $escaped_title     = preg_replace( $pattern, '<span class="lm-artisan-title-highlight">$0</span>', $escaped_title, 1 );
        }

        return nl2br( $escaped_title );
    }

    public function widget( $args, $instance ) {
        $this->enqueue_assets();
        $v = $this->values( $instance );

        $cards = array();

        $orderby_map = array(
            'menu_order_title' => 'menu_order title',
            'title'            => 'title',
            'date'             => 'date',
            'rand'             => 'rand',
        );

        $orderby = isset( $orderby_map[ $v['artisan_orderby'] ] )
            ? $orderby_map[ $v['artisan_orderby'] ]
            : 'menu_order title';

        $order = 'DESC' === strtoupper( (string) $v['artisan_order'] ) ? 'DESC' : 'ASC';

        $artisan_posts = get_posts(
            array(
                'post_type'              => 'artisan',
                'post_status'            => 'publish',
                'posts_per_page'         => -1,
                'orderby'                => $orderby,
                'order'                  => $order,
                'no_found_rows'          => true,
                'suppress_filters'       => false,
                'update_post_meta_cache' => true,
                'update_post_term_cache' => true,
            )
        );

        foreach ( $artisan_posts as $artisan_post ) {
            $card = $this->get_artisan_card_data( $artisan_post->ID, $v['quote_word_limit'] );

            if ( $card ) {
                $cards[] = $card;
            }
        }

        $overlay_colour = sanitize_hex_color( $v['overlay_color'] ) ?: '#0A0F0C';
        $overlay_alpha  = min( 1, max( 0, (float) $v['overlay_opacity'] / 100 ) );

        $this->open_widget(
            $args,
            $v,
            array(
                '--lm-artisan-section-bg'          => sanitize_hex_color( $v['section_background'] ) ?: '#F6F4F0',
                '--lm-artisan-panel-bg'            => sanitize_hex_color( $v['panel_background'] ) ?: '#FFFFFF',
                '--lm-artisan-panel-border'        => sanitize_hex_color( $v['panel_border'] ) ?: '#E8E4DE',
                '--lm-artisan-accent'              => sanitize_hex_color( $v['accent'] ) ?: '#9E8574',
                '--lm-artisan-title-color'         => sanitize_hex_color( $v['title_color'] ) ?: '#1C1C1A',
                '--lm-artisan-name-color'          => sanitize_hex_color( $v['name_color'] ) ?: '#FFFFFF',
                '--lm-artisan-overlay'             => sprintf( 'rgba(%d,%d,%d,%s)', hexdec( substr( ltrim( $overlay_colour, '#' ), 0, 2 ) ), hexdec( substr( ltrim( $overlay_colour, '#' ), 2, 2 ) ), hexdec( substr( ltrim( $overlay_colour, '#' ), 4, 2 ) ), rtrim( rtrim( number_format( $overlay_alpha, 2, '.', '' ), '0' ), '.' ) ),
                '--lm-artisan-panel-padding-x'     => max( 0, (float) $v['panel_padding_x'] ) . 'px',
                '--lm-artisan-panel-padding-y'     => max( 0, (float) $v['panel_padding_y'] ) . 'px',
                '--lm-artisan-container-width'     => max( 680, (float) $v['container_width'] ) . 'px',
                '--lm-artisan-gap'                 => max( 0, (float) $v['card_gap'] ) . 'px',
                '--lm-artisan-height'              => max( 240, (float) $v['card_height'] ) . 'px',
                '--lm-artisan-height-tablet'       => max( 240, (float) $v['card_height_tablet'] ) . 'px',
                '--lm-artisan-height-mobile'       => max( 240, (float) $v['card_height_mobile'] ) . 'px',
                '--lm-artisan-slides-desktop'      => (string) max( 1, min( 3, (int) $v['slides_desktop'] ) ),
                '--lm-artisan-slides-tablet'       => (string) max( 1, min( 3, (int) $v['slides_tablet'] ) ),
                '--lm-artisan-slides-mobile'       => (string) max( 1, min( 2, (int) $v['slides_mobile'] ) ),
                '--lm-artisan-image-position'      => sanitize_text_field( $v['image_position'] ),
                '--lm-artisan-panel-shadow'        => $this->is_on( $v, 'panel_shadow' ) ? '0 20px 60px rgba(0,0,0,.04)' : 'none',
            ),
            array( $this->is_on( $v, 'minimal_cards' ) ? 'lm-artisan-minimal' : 'lm-artisan-detailed' )
        );
        ?>
        <section class="lm-artisan-story">
            <div class="lm-artisan-panel">
                <header class="lm-artisan-header">
                    <?php if ( $v['eyebrow'] ) : ?><div class="lm-artisan-eyebrow"><?php echo esc_html( $v['eyebrow'] ); ?></div><?php endif; ?>
                    <?php if ( $v['title'] ) : ?>
                        <h2 class="lm-artisan-title"><?php echo $this->highlighted_title( $v['title'], $v['highlight_text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
                    <?php endif; ?>
                    <?php if ( $v['subtitle'] ) : ?><p class="lm-artisan-subtitle"><?php echo $this->multiline( $v['subtitle'] ); ?></p><?php endif; ?>
                </header>

                <?php if ( $cards ) : ?>
                    <div
                        class="lm-artisan-slider"
                        data-lm-artisan-slider
                        data-autoplay="<?php echo $this->is_on( $v, 'autoplay' ) ? '1' : '0'; ?>"
                        data-autoplay-delay="<?php echo esc_attr( max( 1000, absint( $v['autoplay_delay'] ) ) ); ?>"
                        data-pause-hover="<?php echo $this->is_on( $v, 'pause_on_hover' ) ? '1' : '0'; ?>"
                        data-slides-desktop="<?php echo esc_attr( max( 1, min( 3, (int) $v['slides_desktop'] ) ) ); ?>"
                        data-slides-tablet="<?php echo esc_attr( max( 1, min( 3, (int) $v['slides_tablet'] ) ) ); ?>"
                        data-slides-mobile="<?php echo esc_attr( max( 1, min( 2, (int) $v['slides_mobile'] ) ) ); ?>"
                    >
                        <div class="lm-artisan-viewport" data-lm-artisan-viewport tabindex="0" aria-label="<?php esc_attr_e( 'Artisan profiles', 'loomaama' ); ?>">
                            <div class="lm-artisan-track" data-lm-artisan-track>
                                <?php foreach ( $cards as $index => $card ) : ?>
                                    <article class="lm-artisan-slide" data-lm-artisan-slide>
                                        <a class="lm-artisan-slide-link" href="<?php echo esc_url( $card['url'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View artisan %s', 'loomaama' ), $card['name'] ) ); ?>">
                                            <?php if ( $card['image'] ) : ?>
                                                <img class="lm-artisan-image" src="<?php echo esc_url( $card['image'] ); ?>" alt="<?php echo esc_attr( $card['alt'] ); ?>" loading="lazy">
                                            <?php else : ?>
                                                <span class="lm-artisan-image-placeholder" aria-hidden="true"></span>
                                            <?php endif; ?>
                                            <span class="lm-artisan-overlay" aria-hidden="true"></span>
                                            <span class="lm-artisan-slide-content">
                                                <?php if ( ! $this->is_on( $v, 'minimal_cards' ) ) : ?>
                                                    <span class="lm-artisan-slide-number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
                                                    <?php if ( $this->is_on( $v, 'show_type' ) && $card['type'] ) : ?><span class="lm-artisan-role"><?php echo esc_html( $card['type'] ); ?></span><?php endif; ?>
                                                    <?php if ( $this->is_on( $v, 'show_location' ) && $card['location'] ) : ?><span class="lm-artisan-location"><?php echo esc_html( $card['location'] ); ?></span><?php endif; ?>
                                                <?php endif; ?>
                                                <strong class="lm-artisan-name"><?php echo esc_html( $card['name'] ); ?></strong>
                                                <?php if ( ! $this->is_on( $v, 'minimal_cards' ) && $this->is_on( $v, 'show_badges' ) && $card['badge'] ) : ?><span class="lm-artisan-badge"><?php echo esc_html( $card['badge'] ); ?></span><?php endif; ?>
                                                <?php if ( ! $this->is_on( $v, 'minimal_cards' ) && $this->is_on( $v, 'show_quote' ) && $card['quote'] ) : ?><span class="lm-artisan-quote">“<?php echo esc_html( trim( $card['quote'], "\"'“”‘’" ) ); ?>”</span><?php endif; ?>
                                            </span>
                                        </a>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?php if ( $this->is_on( $v, 'show_arrows' ) || $this->is_on( $v, 'show_dots' ) ) : ?>
                            <div class="lm-artisan-controls">
                                <?php if ( $this->is_on( $v, 'show_arrows' ) ) : ?>
                                    <button class="lm-artisan-nav lm-artisan-prev" type="button" data-lm-artisan-prev aria-label="<?php esc_attr_e( 'Previous artisan', 'loomaama' ); ?>">‹</button>
                                <?php endif; ?>

                                <?php if ( $this->is_on( $v, 'show_dots' ) ) : ?><div class="lm-artisan-dots" data-lm-artisan-dots></div><?php endif; ?>

                                <?php if ( $this->is_on( $v, 'show_arrows' ) ) : ?>
                                    <button class="lm-artisan-nav lm-artisan-next" type="button" data-lm-artisan-next aria-label="<?php esc_attr_e( 'Next artisan', 'loomaama' ); ?>">›</button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
                    <p class="lm-artisan-empty"><?php esc_html_e( 'No published artisan profiles were found.', 'loomaama' ); ?></p>
                <?php endif; ?>

                <?php if ( $this->is_on( $v, 'show_button' ) && $v['button_text'] && $v['button_url'] ) : ?>
                    <div class="lm-artisan-footer">
                        <a class="lm-artisan-footer-link" href="<?php echo esc_url( $v['button_url'] ); ?>"<?php echo $this->target_attributes( $this->is_on( $v, 'button_new_tab' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                            <?php echo esc_html( $v['button_text'] ); ?> <span aria-hidden="true">→</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
        $this->close_widget( $args );
    }
}
/**
 * Thread transition widget.
 */
class Loomaama_Journey_Thread_Transition_Widget extends Loomaama_Journey_Widget_Base {
    public function __construct() {
        $this->section_modifier = 'thread-transition';

        $this->configure(
            array(
                'title'       => 'From the forest, a fibre begins its journey.',
                'show_thread' => 1,
                'thread_width'=> 120,
                'thread_width_unit' => 'vw',
                'thread_color'=> '#FFFFFF',
                'thread_opacity' => 55,
                'heading_size'=> 92,
                'text_align'  => 'center',
            ),
            array(
                '_content_start' => array( 'type' => 'group_start', 'label' => __( 'Content and design', 'loomaama' ), 'open' => true ),
                'title'           => array( 'type' => 'textarea', 'rows' => 3, 'label' => __( 'Heading', 'loomaama' ) ),
                'show_thread'     => array( 'type' => 'checkbox', 'label' => __( 'Show decorative thread', 'loomaama' ) ),
                'thread_width'    => array( 'type' => 'number', 'label' => __( 'Thread width', 'loomaama' ), 'min' => 20, 'max' => 1000 ),
                'thread_width_unit' => array( 'type' => 'select', 'label' => __( 'Thread width unit', 'loomaama' ), 'options' => array( 'vw' => 'vw', 'px' => 'px', '%' => '%' ) ),
                'thread_color'    => array( 'type' => 'color', 'label' => __( 'Thread colour', 'loomaama' ) ),
                'thread_opacity'  => array( 'type' => 'number', 'label' => __( 'Thread opacity (%)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
                'heading_size'    => array( 'type' => 'number', 'label' => __( 'Heading maximum size (px)', 'loomaama' ), 'min' => 18, 'max' => 120 ),
                '_content_end'    => array( 'type' => 'group_end' ),
            )
        );

        parent::__construct(
            'loomaama_journey_thread_transition',
            __( 'Loomaama Journey: Thread Transition', 'loomaama' ),
            array( 'description' => __( 'Editable transition heading and decorative thread.', 'loomaama' ) )
        );
    }

    public function widget( $args, $instance ) {
        $this->enqueue_assets();
        $v = $this->values( $instance );

        $this->open_widget(
            $args,
            $v,
            array(
                '--lm-thread-width'  => max( 20, (float) $v['thread_width'] ) . ( in_array( $v['thread_width_unit'], array( 'vw', 'px', '%' ), true ) ? $v['thread_width_unit'] : 'vw' ),
                '--lm-thread-color'  => loomaama_journey_rgba( $v['thread_color'], $v['thread_opacity'] ),
                '--lm-thread-heading'=> max( 18, (float) $v['heading_size'] ) . 'px',
            )
        );
        ?>
        <section class="thread-transition">
            <?php if ( $this->is_on( $v, 'show_thread' ) ) : ?><div class="thread"></div><?php endif; ?>
            <?php if ( $v['title'] ) : ?><h2><?php echo $this->multiline( $v['title'] ); ?></h2><?php endif; ?>
        </section>
        <?php
        $this->close_widget( $args );
    }
}

/**
 * Featured collection widget.
 */
class Loomaama_Journey_Collection_Widget extends Loomaama_Journey_Widget_Base {
    public function __construct() {
        $this->section_modifier = 'collection';

        $defaults = array(
            'eyebrow'          => 'The Journey Continues',
            'title'            => 'Featured Collection',
            'description'      => 'Discover pieces shaped by mountain landscapes, natural fibres and the hands of Nepal.',
            'button_text'      => 'Explore the Collection',
            'button_url'       => home_url( '/shop/' ),
            'button_new_tab'   => 0,
            'columns'          => '3',
            'columns_tablet'   => '1',
            'columns_mobile'   => '1',
            'card_background'  => '#F7F3EE',
            'card_radius'      => 0,
            'image_ratio'      => '4/5',
        );

        $products = array(
            1 => array( loomaama_journey_asset_url( 'images/journey/shawl.jpg' ), 'Nettle Wrap shawl', 'Nettle Wrap shawl', 'AUD $189', '' ),
            2 => array( loomaama_journey_asset_url( 'images/journey/Poncho.png' ), 'Mixed Pattern Poncho', 'Mixed Pattern Poncho', 'AUD $169', '' ),
            3 => array( loomaama_journey_asset_url( 'images/journey/Beanie.jpg' ), 'Natural Beanie', 'Natural Beanie', 'AUD $249', '' ),
        );

        $fields = array(
            '_content_start' => array( 'type' => 'group_start', 'label' => __( 'Content and layout', 'loomaama' ), 'open' => true ),
            'eyebrow'        => array( 'label' => __( 'Eyebrow', 'loomaama' ) ),
            'title'          => array( 'type' => 'textarea', 'rows' => 2, 'label' => __( 'Heading', 'loomaama' ) ),
            'description'    => array( 'type' => 'textarea', 'rows' => 4, 'label' => __( 'Description', 'loomaama' ) ),
            'button_text'    => array( 'label' => __( 'Main button text', 'loomaama' ) ),
            'button_url'     => array( 'type' => 'url', 'label' => __( 'Main button URL', 'loomaama' ) ),
            'button_new_tab' => array( 'type' => 'checkbox', 'label' => __( 'Open main button in a new tab', 'loomaama' ) ),
            'columns'        => array( 'type' => 'select', 'label' => __( 'Desktop columns', 'loomaama' ), 'options' => array( '1' => '1', '2' => '2', '3' => '3' ) ),
            'columns_tablet' => array( 'type' => 'select', 'label' => __( 'Tablet columns', 'loomaama' ), 'options' => array( '1' => '1', '2' => '2', '3' => '3' ) ),
            'columns_mobile' => array( 'type' => 'select', 'label' => __( 'Mobile columns', 'loomaama' ), 'options' => array( '1' => '1', '2' => '2' ) ),
            'card_background'=> array( 'type' => 'color', 'label' => __( 'Card background', 'loomaama' ) ),
            'card_radius'    => array( 'type' => 'number', 'label' => __( 'Card corner radius (px)', 'loomaama' ), 'min' => 0, 'max' => 100 ),
            'image_ratio'    => array(
                'type'    => 'select',
                'label'   => __( 'Product image ratio', 'loomaama' ),
                'options' => array(
                    '1/1' => __( 'Square 1:1', 'loomaama' ),
                    '3/4' => __( 'Portrait 3:4', 'loomaama' ),
                    '2/3' => __( 'Portrait 2:3', 'loomaama' ),
                    '3/2' => __( 'Landscape 3:2', 'loomaama' ),
                    '4/5' => __( 'Portrait 4:5', 'loomaama' ),
                ),
            ),
            '_content_end' => array( 'type' => 'group_end' ),
        );

        foreach ( $products as $i => $product ) {
            $defaults[ 'enabled_' . $i ]    = 1;
            $defaults[ 'product_id_' . $i ] = 0;
            $defaults[ 'image_' . $i ]      = $product[0];
            $defaults[ 'image_alt_' . $i ]  = $product[1];
            $defaults[ 'name_' . $i ]       = $product[2];
            $defaults[ 'price_' . $i ]      = $product[3];
            $defaults[ 'url_' . $i ]        = $product[4];
            $defaults[ 'new_tab_' . $i ]    = 0;

            $fields[ '_product_' . $i . '_start' ] = array( 'type' => 'group_start', 'label' => sprintf( __( 'Product card %d', 'loomaama' ), $i ) );
            $fields[ 'enabled_' . $i ]             = array( 'type' => 'checkbox', 'label' => sprintf( __( 'Enable product card %d', 'loomaama' ), $i ) );
            $fields[ 'product_id_' . $i ]          = array( 'type' => 'number', 'label' => __( 'WooCommerce product ID (optional)', 'loomaama' ), 'min' => 0, 'max' => 999999, 'description' => __( 'When valid, the image, name, price and URL are loaded from WooCommerce automatically.', 'loomaama' ) );
            $fields[ 'image_' . $i ]               = array( 'type' => 'media', 'media_type' => 'image', 'label' => __( 'Manual image', 'loomaama' ) );
            $fields[ 'image_alt_' . $i ]           = array( 'label' => __( 'Manual image alt text', 'loomaama' ) );
            $fields[ 'name_' . $i ]                = array( 'label' => __( 'Manual product name', 'loomaama' ) );
            $fields[ 'price_' . $i ]               = array( 'label' => __( 'Manual price text', 'loomaama' ) );
            $fields[ 'url_' . $i ]                 = array( 'type' => 'url', 'label' => __( 'Manual product URL', 'loomaama' ) );
            $fields[ 'new_tab_' . $i ]             = array( 'type' => 'checkbox', 'label' => __( 'Open product in a new tab', 'loomaama' ) );
            $fields[ '_product_' . $i . '_end' ]   = array( 'type' => 'group_end' );
        }

        $this->configure( $defaults, $fields );

        parent::__construct(
            'loomaama_journey_collection',
            __( 'Loomaama Journey: Featured Collection', 'loomaama' ),
            array( 'description' => __( 'Manual or WooCommerce-powered editorial product cards.', 'loomaama' ) )
        );
    }

    /**
     * Resolve a manual card or WooCommerce product.
     *
     * @param array<string,mixed> $values Widget values.
     * @param int                 $index  Card number.
     * @return array<string,string>
     */
    private function product_card_data( $values, $index ) {
        $data = array(
            'image' => (string) $values[ 'image_' . $index ],
            'alt'   => (string) $values[ 'image_alt_' . $index ],
            'name'  => (string) $values[ 'name_' . $index ],
            'price' => (string) $values[ 'price_' . $index ],
            'url'   => (string) $values[ 'url_' . $index ],
        );

        $product_id = absint( $values[ 'product_id_' . $index ] );
        if ( $product_id && function_exists( 'wc_get_product' ) ) {
            $product = wc_get_product( $product_id );
            if ( $product ) {
                $image_id = $product->get_image_id();
                $data['image'] = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : wc_placeholder_img_src( 'large' );
                $data['alt']   = get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: $product->get_name();
                $data['name']  = $product->get_name();
                $data['price'] = wp_strip_all_tags( $product->get_price_html() );
                $data['url']   = $product->get_permalink();
            }
        }

        return $data;
    }

    public function widget( $args, $instance ) {
        $this->enqueue_assets();
        $v = $this->values( $instance );

        $this->open_widget(
            $args,
            $v,
            array(
                '--lm-product-columns'     => (string) max( 1, min( 3, (int) $v['columns'] ) ),
                '--lm-product-card-bg'     => sanitize_hex_color( $v['card_background'] ) ?: '#FFFFFF',
                '--lm-product-card-radius' => max( 0, (float) $v['card_radius'] ) . 'px',
                '--lm-product-image-ratio' => str_replace( '/', ' / ', sanitize_text_field( $v['image_ratio'] ) ),
            )
        );
        ?>
        <section class="collection">
            <div class="collection-head">
                <div>
                    <?php if ( $v['eyebrow'] ) : ?><div class="eyebrow"><?php echo esc_html( $v['eyebrow'] ); ?></div><?php endif; ?>
                    <?php if ( $v['title'] ) : ?><h2><?php echo $this->multiline( $v['title'] ); ?></h2><?php endif; ?>
                </div>
                <?php if ( $v['description'] ) : ?><p><?php echo $this->multiline( $v['description'] ); ?></p><?php endif; ?>
            </div>

            <div class="product-grid">
                <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
                    <?php if ( ! $this->is_on( $v, 'enabled_' . $i ) ) { continue; } ?>
                    <?php $product = $this->product_card_data( $v, $i ); ?>
                    <article class="card">
                        <?php if ( $product['url'] ) : ?><a href="<?php echo esc_url( $product['url'] ); ?>"<?php echo $this->target_attributes( $this->is_on( $v, 'new_tab_' . $i ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php endif; ?>
                            <?php if ( $product['image'] ) : ?><img src="<?php echo esc_url( $product['image'] ); ?>" alt="<?php echo esc_attr( $product['alt'] ); ?>"><?php endif; ?>
                        <?php if ( $product['url'] ) : ?></a><?php endif; ?>
                        <div class="card-info">
                            <div class="card-title"><?php echo esc_html( $product['name'] ); ?></div>
                            <div><?php echo esc_html( $product['price'] ); ?></div>
                        </div>
                    </article>
                <?php endfor; ?>
            </div>

            <?php if ( $v['button_text'] && $v['button_url'] ) : ?>
                <a class="button" href="<?php echo esc_url( $v['button_url'] ); ?>"<?php echo $this->target_attributes( $this->is_on( $v, 'button_new_tab' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                    <?php echo esc_html( $v['button_text'] ); ?>
                </a>
            <?php endif; ?>
        </section>
        <?php
        $this->close_widget( $args );
    }
}


/**
 * Interactive garment-tag and impact story widget.
 */
class Loomaama_Journey_Tag_Impact_Widget extends Loomaama_Journey_Widget_Base {

    public function __construct() {
        $this->section_modifier = 'journey-tag-impact';

        $defaults = array(
            'kicker'                    => 'The story you can hold',
            'title'                     => 'A garment should carry the truth of where it began.',
            'intro'                     => 'Every Loomaama piece begins with wild Himalayan nettle and a story worth keeping. The tag makes its origin visible. The impact keeps every claim accountable.',

            'tag_kicker'                => '01 / The tag',
            'leaf_image'                => loomaama_journey_asset_url( 'images/material/chemical-dye.jpg' ),
 			'earth_image'                => loomaama_journey_asset_url( 'images/earth_tag.jpg' ),
            'tag_front_image'           => loomaama_journey_asset_url( 'images/material/terry-cotton.jpg' ),
            'tag_front_alt'             => 'Hand-shaped Loomaama garment tag showing the maker, place and days of hand knitting',
            'tag_back_image'            => loomaama_journey_asset_url( 'images/material/nylong fabric.jpg' ),
            'tag_back_alt'              => 'Reverse of the hand-shaped Loomaama garment tag showing garment care guidance',
            'tag_aria_label'            => 'Turn the garment tag',
            'tag_title'                 => 'More than a label.',
            'tag_text'                  => 'The maker, the place, the fibre and the time remain visible long after the piece leaves the hands that shaped it.',
            'tag_hint'                  => '',

            'impact_image'              => loomaama_journey_asset_url( 'images/founder/founder-img.jpg' ),
            'impact_image_alt'          => 'Wild Himalayan nettle held by a hand shaped like a wooden hanger',
            'impact_kicker'             => '02 / The impact',
            'impact_title'              => 'Proof, not promises.',
            'impact_link_text'          => 'Explore our impact',
            'impact_link_url'           => home_url( '/impact/' ),
            'impact_link_new_tab'       => 0,

            'footer_text'               => 'Wild fibre. Visible origin. A lighter trace forward.',
            'footer_link_text'          => 'Become a witness',
            'footer_link_url'           => home_url( '/shop/' ),
            'footer_link_new_tab'       => 0,

            'paper_color'               => '#F4F0E8',
            'paper_deep_color'          => '#EAE3D7',
            'ink_color'                 => '#1C1C19',
            'sage_color'                => '#59634F',
            'white_color'               => '#FFFDF8',
            'line_color'                => '#1C1C19',
            'line_opacity'              => 18,
            'impact_text_color'         => '#FFFFFF',
            'impact_overlay_color'      => '#0D100C',
            'overlay_bottom_opacity'    => 90,
            'overlay_middle_opacity'    => 42,
            'overlay_top_opacity'       => 4,

            'stage_gap'                 => 34,
            'card_min_height'           => 760,
            'card_min_height_tablet'    => 690,
            'card_min_height_mobile'    => 620,
            'tag_card_min_height_mobile'=> 710,
            'tag_card_padding'          => 48,
            'tag_width'                 => 84,
            'leaf_width'                => 69,
            'leaf_opacity'              => 18,
            'leaf_rotation'             => -14,
            'tag_contrast'              => 120,
            'flip_duration'             => 82,
            'impact_image_x'            => 63,
            'impact_image_y'            => 'center',

            'title_size'                => 102,
            'intro_size'                => 29,
            'card_title_size'           => 68,
            'tag_copy_title_size'       => 49,
        );

        $proof_defaults = array(
            1 => array( '01', 'Material with origin', 1 ),
            2 => array( '02', 'The maker made visible', 1 ),
            3 => array( '03', 'Knowledge carried forward', 1 ),
            4 => array( '04', '', 0 ),
            5 => array( '05', '', 0 ),
        );

        $fields = array(
            '_intro_start' => array(
                'type'  => 'group_start',
                'label' => __( 'Section introduction', 'loomaama' ),
                'open'  => true,
            ),
            'kicker' => array(
                'label' => __( 'Eyebrow', 'loomaama' ),
            ),
            'title' => array(
                'type'  => 'textarea',
                'rows'  => 3,
                'label' => __( 'Main heading', 'loomaama' ),
            ),
            'intro' => array(
                'type'  => 'textarea',
                'rows'  => 5,
                'label' => __( 'Introductory text', 'loomaama' ),
            ),
            '_intro_end' => array( 'type' => 'group_end' ),

            '_tag_start' => array(
                'type'  => 'group_start',
                'label' => __( 'Tag card', 'loomaama' ),
            ),
            'tag_kicker' => array(
                'label' => __( 'Tag card label', 'loomaama' ),
            ),
            'leaf_image' => array(
                'type'       => 'media',
                'media_type' => 'image',
                'label'      => __( 'Decorative background image', 'loomaama' ),
            ),
  			'earth_image' => array(
                'type'       => 'media',
                'media_type' => 'image',
                'label'      => __( 'Decorative Earth image', 'loomaama' ),
            ),
            'tag_front_image' => array(
                'type'       => 'media',
                'media_type' => 'image',
                'label'      => __( 'Tag front image', 'loomaama' ),
            ),
            'tag_front_alt' => array(
                'label' => __( 'Tag front alternative text', 'loomaama' ),
            ),
            'tag_back_image' => array(
                'type'       => 'media',
                'media_type' => 'image',
                'label'      => __( 'Tag back image', 'loomaama' ),
            ),
            'tag_back_alt' => array(
                'label' => __( 'Tag back alternative text', 'loomaama' ),
            ),
            'tag_aria_label' => array(
                'label'       => __( 'Flip-button accessibility label', 'loomaama' ),
                'description' => __( 'Used by screen readers.', 'loomaama' ),
            ),
            'tag_title' => array(
                'label' => __( 'Tag card heading', 'loomaama' ),
            ),
            'tag_text' => array(
                'type'  => 'textarea',
                'rows'  => 4,
                'label' => __( 'Tag card description', 'loomaama' ),
            ),
            'tag_hint' => array(
                'label' => __( 'Flip instruction', 'loomaama' ),
            ),
            '_tag_end' => array( 'type' => 'group_end' ),

            '_impact_start' => array(
                'type'  => 'group_start',
                'label' => __( 'Impact card', 'loomaama' ),
            ),
            'impact_image' => array(
                'type'       => 'media',
                'media_type' => 'image',
                'label'      => __( 'Impact background image', 'loomaama' ),
            ),
            'impact_image_alt' => array(
                'label' => __( 'Impact image alternative text', 'loomaama' ),
            ),
            'impact_kicker' => array(
                'label' => __( 'Impact card label', 'loomaama' ),
            ),
            'impact_title' => array(
                'label' => __( 'Impact card heading', 'loomaama' ),
            ),
            'impact_link_text' => array(
                'label' => __( 'Impact link text', 'loomaama' ),
            ),
            'impact_link_url' => array(
                'type'  => 'url',
                'label' => __( 'Impact link URL', 'loomaama' ),
            ),
            'impact_link_new_tab' => array(
                'type'  => 'checkbox',
                'label' => __( 'Open impact link in a new tab', 'loomaama' ),
            ),
            'impact_image_x' => array(
                'type'  => 'number',
                'label' => __( 'Image horizontal position (%)', 'loomaama' ),
                'min'   => 0,
                'max'   => 100,
            ),
            'impact_image_y' => array(
                'type'    => 'select',
                'label'   => __( 'Image vertical position', 'loomaama' ),
                'options' => array(
                    'top'    => __( 'Top', 'loomaama' ),
                    'center' => __( 'Centre', 'loomaama' ),
                    'bottom' => __( 'Bottom', 'loomaama' ),
                ),
            ),
            '_impact_end' => array( 'type' => 'group_end' ),
        );

        foreach ( $proof_defaults as $index => $proof ) {
            $defaults[ 'proof_enabled_' . $index ] = $proof[2];
            $defaults[ 'proof_number_' . $index ]  = $proof[0];
            $defaults[ 'proof_text_' . $index ]    = $proof[1];

            $fields[ '_proof_' . $index . '_start' ] = array(
                'type'  => 'group_start',
                'label' => sprintf( __( 'Impact point %d', 'loomaama' ), $index ),
            );
            $fields[ 'proof_enabled_' . $index ] = array(
                'type'  => 'checkbox',
                'label' => sprintf( __( 'Enable impact point %d', 'loomaama' ), $index ),
            );
            $fields[ 'proof_number_' . $index ] = array(
                'label' => __( 'Number', 'loomaama' ),
            );
            $fields[ 'proof_text_' . $index ] = array(
                'label' => __( 'Statement', 'loomaama' ),
            );
            $fields[ '_proof_' . $index . '_end' ] = array( 'type' => 'group_end' );
        }

        $fields = array_merge(
            $fields,
            array(
                '_footer_start' => array(
                    'type'  => 'group_start',
                    'label' => __( 'Section closing', 'loomaama' ),
                ),
                'footer_text' => array(
                    'type'  => 'textarea',
                    'rows'  => 3,
                    'label' => __( 'Closing statement', 'loomaama' ),
                ),
                'footer_link_text' => array(
                    'label' => __( 'Closing link text', 'loomaama' ),
                ),
                'footer_link_url' => array(
                    'type'  => 'url',
                    'label' => __( 'Closing link URL', 'loomaama' ),
                ),
                'footer_link_new_tab' => array(
                    'type'  => 'checkbox',
                    'label' => __( 'Open closing link in a new tab', 'loomaama' ),
                ),
                '_footer_end' => array( 'type' => 'group_end' ),

                '_layout_start' => array(
                    'type'  => 'group_start',
                    'label' => __( 'Layout and flip effect', 'loomaama' ),
                ),
                'stage_gap' => array(
                    'type'  => 'number',
                    'label' => __( 'Gap between cards (px)', 'loomaama' ),
                    'min'   => 0,
                    'max'   => 120,
                ),
                'card_min_height' => array(
                    'type'  => 'number',
                    'label' => __( 'Desktop card minimum height (px)', 'loomaama' ),
                    'min'   => 300,
                    'max'   => 1400,
                ),
                'card_min_height_tablet' => array(
                    'type'  => 'number',
                    'label' => __( 'Tablet card minimum height (px)', 'loomaama' ),
                    'min'   => 300,
                    'max'   => 1400,
                ),
                'card_min_height_mobile' => array(
                    'type'  => 'number',
                    'label' => __( 'Mobile card minimum height (px)', 'loomaama' ),
                    'min'   => 300,
                    'max'   => 1400,
                ),
                'tag_card_min_height_mobile' => array(
                    'type'  => 'number',
                    'label' => __( 'Mobile tag-card minimum height (px)', 'loomaama' ),
                    'min'   => 300,
                    'max'   => 1600,
                ),
                'tag_card_padding' => array(
                    'type'  => 'number',
                    'label' => __( 'Tag card maximum inner padding (px)', 'loomaama' ),
                    'min'   => 12,
                    'max'   => 120,
                ),
                'tag_width' => array(
                    'type'  => 'number',
                    'label' => __( 'Tag width (%)', 'loomaama' ),
                    'min'   => 20,
                    'max'   => 100,
                ),
                'leaf_width' => array(
                    'type'  => 'number',
                    'label' => __( 'Decorative image width (%)', 'loomaama' ),
                    'min'   => 10,
                    'max'   => 120,
                ),
				'earth_width' => array(
                    'type'  => 'number',
                    'label' => __( 'Decorative Earth image width (%)', 'loomaama' ),
                    'min'   => 10,
                    'max'   => 120,
                ),
                'leaf_opacity' => array(
                    'type'  => 'number',
                    'label' => __( 'Decorative image opacity (%)', 'loomaama' ),
                    'min'   => 0,
                    'max'   => 100,
                ),
                'leaf_rotation' => array(
                    'type'  => 'number',
                    'label' => __( 'Decorative image rotation (degrees)', 'loomaama' ),
                    'min'   => -180,
                    'max'   => 180,
                ),
                'tag_contrast' => array(
                    'type'  => 'number',
                    'label' => __( 'Tag image contrast (%)', 'loomaama' ),
                    'min'   => 50,
                    'max'   => 200,
                ),
                'flip_duration' => array(
                    'type'        => 'number',
                    'label'       => __( 'Flip duration (hundredths of a second)', 'loomaama' ),
                    'description' => __( '82 equals 0.82 seconds.', 'loomaama' ),
                    'min'         => 10,
                    'max'         => 300,
                ),
                '_layout_end' => array( 'type' => 'group_end' ),

                '_typography_start' => array(
                    'type'  => 'group_start',
                    'label' => __( 'Typography sizes', 'loomaama' ),
                ),
                'title_size' => array(
                    'type'  => 'number',
                    'label' => __( 'Main heading maximum size (px)', 'loomaama' ),
                    'min'   => 30,
                    'max'   => 180,
                ),
                'intro_size' => array(
                    'type'  => 'number',
                    'label' => __( 'Intro maximum size (px)', 'loomaama' ),
                    'min'   => 14,
                    'max'   => 80,
                ),
                'card_title_size' => array(
                    'type'  => 'number',
                    'label' => __( 'Impact heading maximum size (px)', 'loomaama' ),
                    'min'   => 24,
                    'max'   => 140,
                ),
                'tag_copy_title_size' => array(
                    'type'  => 'number',
                    'label' => __( 'Tag heading maximum size (px)', 'loomaama' ),
                    'min'   => 20,
                    'max'   => 100,
                ),
                '_typography_end' => array( 'type' => 'group_end' ),

                '_colours_start' => array(
                    'type'  => 'group_start',
                    'label' => __( 'Tag Impact colours', 'loomaama' ),
                ),
                'paper_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Paper background', 'loomaama' ),
                ),
                'paper_deep_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Deeper paper tone', 'loomaama' ),
                ),
                'ink_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Ink colour', 'loomaama' ),
                ),
                'sage_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Sage accent', 'loomaama' ),
                ),
                'white_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Warm white', 'loomaama' ),
                ),
                'line_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Divider colour', 'loomaama' ),
                ),
                'line_opacity' => array(
                    'type'  => 'number',
                    'label' => __( 'Divider opacity (%)', 'loomaama' ),
                    'min'   => 0,
                    'max'   => 100,
                ),
                'impact_text_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Impact card text', 'loomaama' ),
                ),
                'impact_overlay_color' => array(
                    'type'  => 'color',
                    'label' => __( 'Impact overlay colour', 'loomaama' ),
                ),
                'overlay_bottom_opacity' => array(
                    'type'  => 'number',
                    'label' => __( 'Overlay bottom opacity (%)', 'loomaama' ),
                    'min'   => 0,
                    'max'   => 100,
                ),
                'overlay_middle_opacity' => array(
                    'type'  => 'number',
                    'label' => __( 'Overlay middle opacity (%)', 'loomaama' ),
                    'min'   => 0,
                    'max'   => 100,
                ),
                'overlay_top_opacity' => array(
                    'type'  => 'number',
                    'label' => __( 'Overlay top opacity (%)', 'loomaama' ),
                    'min'   => 0,
                    'max'   => 100,
                ),
                '_colours_end' => array( 'type' => 'group_end' ),
            )
        );

        $this->configure( $defaults, $fields );

        parent::__construct(
            'loomaama_journey_tag_impact',
            __( 'journey-tag-impact', 'loomaama' ),
            array(
                'classname'                   => 'loomaama_journey_tag_impact',
                'description'                 => __( 'Interactive garment tag and impact story section.', 'loomaama' ),
                'customize_selective_refresh' => true,
            )
        );
    }

    public function widget( $args, $instance ) {
        $this->enqueue_assets();
        $v = $this->values( $instance );

        $paper        = sanitize_hex_color( $v['paper_color'] ) ?: '#F4F0E8';
        $paper_deep   = sanitize_hex_color( $v['paper_deep_color'] ) ?: '#EAE3D7';
        $ink          = sanitize_hex_color( $v['ink_color'] ) ?: '#1C1C19';
        $sage         = sanitize_hex_color( $v['sage_color'] ) ?: '#59634F';
        $white        = sanitize_hex_color( $v['white_color'] ) ?: '#FFFDF8';
        $line         = sanitize_hex_color( $v['line_color'] ) ?: '#1C1C19';
        $impact_text  = sanitize_hex_color( $v['impact_text_color'] ) ?: '#FFFFFF';
        $overlay      = sanitize_hex_color( $v['impact_overlay_color'] ) ?: '#0D100C';
        $overlay_bg   = sprintf(
            'linear-gradient(to top,%1$s 0%%,%2$s 43%%,%3$s 72%%)',
            loomaama_journey_rgba( $overlay, $v['overlay_bottom_opacity'] ),
            loomaama_journey_rgba( $overlay, $v['overlay_middle_opacity'] ),
            loomaama_journey_rgba( $overlay, $v['overlay_top_opacity'] )
        );
        $title_id = 'lm-tag-impact-title-' . sanitize_html_class( $this->id );

        $this->open_widget(
            $args,
            $v,
            array(
                '--lm-ti-paper'                   => $paper,
                '--lm-ti-paper-deep'              => $paper_deep,
                '--lm-ti-ink'                     => $ink,
                '--lm-ti-sage'                    => $sage,
                '--lm-ti-white'                   => $white,
                '--lm-ti-line'                    => loomaama_journey_rgba( $line, $v['line_opacity'] ),
                '--lm-ti-grid-line'               => loomaama_journey_rgba( $ink, 3.5 ),
                '--lm-ti-impact-text'             => $impact_text,
                '--lm-ti-ink-76'                  => loomaama_journey_rgba( $ink, 76 ),
                '--lm-ti-ink-68'                  => loomaama_journey_rgba( $ink, 68 ),
                '--lm-ti-ink-52'                  => loomaama_journey_rgba( $ink, 52 ),
                '--lm-ti-impact-text-90'          => loomaama_journey_rgba( $impact_text, 90 ),
                '--lm-ti-impact-text-82'          => loomaama_journey_rgba( $impact_text, 82 ),
                '--lm-ti-impact-line-32'          => loomaama_journey_rgba( $impact_text, 32 ),
                '--lm-ti-impact-line-22'          => loomaama_journey_rgba( $impact_text, 22 ),
                '--lm-ti-impact-text-62'          => loomaama_journey_rgba( $impact_text, 62 ),
                '--lm-ti-impact-overlay'          => $overlay_bg,
                '--lm-ti-stage-gap'               => max( 0, (float) $v['stage_gap'] ) . 'px',
                '--lm-ti-card-height'             => max( 300, (float) $v['card_min_height'] ) . 'px',
                '--lm-ti-card-height-tablet'      => max( 300, (float) $v['card_min_height_tablet'] ) . 'px',
                '--lm-ti-card-height-mobile'      => max( 300, (float) $v['card_min_height_mobile'] ) . 'px',
                '--lm-ti-tag-height-mobile'       => max( 300, (float) $v['tag_card_min_height_mobile'] ) . 'px',
                '--lm-ti-tag-padding'             => max( 12, (float) $v['tag_card_padding'] ) . 'px',
                '--lm-ti-tag-width'               => max( 20, min( 100, (float) $v['tag_width'] ) ) . '%',
                '--lm-ti-leaf-width'              => max( 10, min( 120, (float) $v['leaf_width'] ) ) . '%',
                '--lm-ti-leaf-opacity'            => max( 0, min( 1, (float) $v['leaf_opacity'] / 100 ) ),
                '--lm-ti-leaf-rotation'           => max( -180, min( 180, (float) $v['leaf_rotation'] ) ) . 'deg',
                '--lm-ti-tag-contrast'            => max( 0.5, min( 2, (float) $v['tag_contrast'] / 100 ) ),
                '--lm-ti-flip-duration'           => max( 0.1, min( 3, (float) $v['flip_duration'] / 100 ) ) . 's',
                '--lm-ti-impact-position'         => max( 0, min( 100, (float) $v['impact_image_x'] ) ) . '% ' . $v['impact_image_y'],
                '--lm-ti-title-size'              => max( 30, (float) $v['title_size'] ) . 'px',
                '--lm-ti-intro-size'              => max( 14, (float) $v['intro_size'] ) . 'px',
                '--lm-ti-card-title-size'         => max( 24, (float) $v['card_title_size'] ) . 'px',
                '--lm-ti-tag-copy-title-size'     => max( 20, (float) $v['tag_copy_title_size'] ) . 'px',
            )
        );
        ?>
        <section class="lm-story" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
            <div class="lm-story__inner">
                <div class="lm-story__header">
                    <div>
                        <?php if ( $v['kicker'] ) : ?>
                            <p class="lm-kicker"><?php echo esc_html( $v['kicker'] ); ?></p>
                        <?php endif; ?>
                        <?php if ( $v['title'] ) : ?>
                            <h2 class="lm-story__title" id="<?php echo esc_attr( $title_id ); ?>"><?php echo $this->multiline( $v['title'] ); ?></h2>
                        <?php endif; ?>
                    </div>
                    <?php if ( $v['intro'] ) : ?>
                        <p class="lm-story__intro"><?php echo $this->multiline( $v['intro'] ); ?></p>
                    <?php endif; ?>
                </div>

                <div class="lm-story__stage">
                    <article class="lm-card lm-card--tag">
                        <?php if ( $v['tag_kicker'] ) : ?>
                            <p class="lm-card__kicker"><?php echo esc_html( $v['tag_kicker'] ); ?></p>
                        <?php endif; ?>

                        <div class="lm-tag-stage">
                            <?php if ( $v['leaf_image'] ) : ?>
                                <img class="lm-tag-leaf" src="<?php echo esc_url( $v['leaf_image'] ); ?>" alt="" aria-hidden="true" loading="lazy">
                            <?php endif; ?>
							<?php if ( $v['earth_image'] ) : ?>
                                <img class="lm-tag-earth" src="<?php echo esc_url( $v['earth_image'] ); ?>" alt="" aria-hidden="true" loading="lazy">
                            <?php endif; ?>

                            <button
                                class="lm-tag-flip"
                                type="button"
                                aria-label="<?php echo esc_attr( $v['tag_aria_label'] ?: __( 'Turn the garment tag', 'loomaama' ) ); ?>"
                                aria-pressed="false"
                            >
                                <?php if ( $v['tag_front_image'] ) : ?>
                                    <img class="lm-tag-face lm-tag-face--front" src="<?php echo esc_url( $v['tag_front_image'] ); ?>" alt="<?php echo esc_attr( $v['tag_front_alt'] ); ?>">
                                <?php endif; ?>
                                <?php if ( $v['tag_back_image'] ) : ?>
                                    <img class="lm-tag-face lm-tag-face--back" src="<?php echo esc_url( $v['tag_back_image'] ); ?>" alt="<?php echo esc_attr( $v['tag_back_alt'] ); ?>">
                                <?php endif; ?>
                            </button>
                        </div>

                        <div class="lm-tag-copy">
                            <?php if ( $v['tag_title'] ) : ?><h3><?php echo esc_html( $v['tag_title'] ); ?></h3><?php endif; ?>
                            <?php if ( $v['tag_text'] ) : ?><p><?php echo $this->multiline( $v['tag_text'] ); ?></p><?php endif; ?>
                            
                        </div>
                    </article>

                    <article class="lm-card lm-card--impact">
                        <?php if ( $v['impact_image'] ) : ?>
                            <img class="lm-card__media" src="<?php echo esc_url( $v['impact_image'] ); ?>" alt="<?php echo esc_attr( $v['impact_image_alt'] ); ?>" loading="lazy">
                        <?php endif; ?>
                        <div class="lm-card__content">
                            <?php if ( $v['impact_kicker'] ) : ?><p class="lm-card__kicker"><?php echo esc_html( $v['impact_kicker'] ); ?></p><?php endif; ?>
                            <?php if ( $v['impact_title'] ) : ?><h3 class="lm-card__title"><?php echo esc_html( $v['impact_title'] ); ?></h3><?php endif; ?>

                            <ul class="lm-impact-list">
                                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                    <?php if ( ! $this->is_on( $v, 'proof_enabled_' . $i ) || '' === trim( (string) $v[ 'proof_text_' . $i ] ) ) { continue; } ?>
                                    <li>
                                        <span><?php echo esc_html( $v[ 'proof_number_' . $i ] ); ?></span>
                                        <?php echo esc_html( $v[ 'proof_text_' . $i ] ); ?>
                                    </li>
                                <?php endfor; ?>
                            </ul>

                            <?php if ( $v['impact_link_text'] && $v['impact_link_url'] ) : ?>
                                <a class="lm-link" href="<?php echo esc_url( $v['impact_link_url'] ); ?>"<?php echo $this->target_attributes( $this->is_on( $v, 'impact_link_new_tab' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                                    <?php echo esc_html( $v['impact_link_text'] ); ?> <span aria-hidden="true"></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                </div>

                <?php if ( $v['footer_text'] || ( $v['footer_link_text'] && $v['footer_link_url'] ) ) : ?>
                    <div class="lm-story__footer">
                        <?php if ( $v['footer_text'] ) : ?><p><?php echo $this->multiline( $v['footer_text'] ); ?></p><?php endif; ?>
                        <?php if ( $v['footer_link_text'] && $v['footer_link_url'] ) : ?>
                            <a class="lm-link" href="<?php echo esc_url( $v['footer_link_url'] ); ?>"<?php echo $this->target_attributes( $this->is_on( $v, 'footer_link_new_tab' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                                <?php echo esc_html( $v['footer_link_text'] ); ?> <span aria-hidden="true"></span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
        $this->close_widget( $args );
    }
}

/**
 * Register all Journey widgets beside existing Loomaama widgets.
 */
function loomaama_register_journey_widgets() {
    register_widget( 'Loomaama_Journey_Hero_Widget' );
    register_widget( 'Loomaama_Journey_Nettle_Intro_Widget' );
    register_widget( 'Loomaama_Journey_Founder_Story_Widget' );
    register_widget( 'Loomaama_Journey_Mountain_Story_Widget' );
    register_widget( 'Loomaama_Journey_Impact_Widget' );
    register_widget( 'Loomaama_Journey_Forest_Story_Widget' );
    register_widget( 'Loomaama_Journey_Artisans_Widget' );
    register_widget( 'Loomaama_Journey_Thread_Transition_Widget' );
    register_widget( 'Loomaama_Journey_Collection_Widget' );
    register_widget( 'Loomaama_Journey_Tag_Impact_Widget' );
}
add_action( 'widgets_init', 'loomaama_register_journey_widgets' );
