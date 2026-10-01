<?php
/**
 * SCF Field Group: Care Guide
 * 
 * @package Loomaama
 */

if (!function_exists('register_field_group')) {
    return;
}

register_field_group(array(
    'id' => 'acf_care_guide',
    'title' => 'Care Guide Settings',
    'fields' => array(
        // TAB: Hero
        array(
            'key' => 'field_care_tab_hero',
            'label' => 'Hero Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_hero_title',
            'label' => 'Hero Title',
            'name' => 'care_hero_title',
            'type' => 'text',
            'default_value' => 'Respect the Memory of Hands.',
            'instructions' => 'Main title for the hero section',
        ),
        array(
            'key' => 'field_care_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'care_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'Your LOOMAAMA garment was made with care. Here\'s how to care for it in return.',
            'instructions' => 'Subtitle text for the hero section',
        ),
        array(
            'key' => 'field_care_hero_image',
            'label' => 'Hero Background Image',
            'name' => 'care_hero_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Upload background image for hero section',
        ),

        // TAB: Why Care Matters
        array(
            'key' => 'field_care_tab_why',
            'label' => 'Why Care Matters',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_section_title',
            'label' => 'Why Care Matters Eyebrow',
            'name' => 'care_section_title',
            'type' => 'text',
            'default_value' => 'The Art of Aging Gracefully',
            'instructions' => 'Eyebrow text above the section heading',
        ),
        array(
            'key' => 'field_care_section_heading',
            'label' => 'Why Care Matters Heading',
            'name' => 'care_section_heading',
            'type' => 'text',
            'default_value' => 'Natural Fibers Age Beautifully; If You Let Them.',
            'instructions' => 'Main heading for the why care matters section',
        ),
        array(
            'key' => 'field_care_section_text',
            'label' => 'Why Care Matters Text',
            'name' => 'care_section_text',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'default_value' => '<p>Synthetic fabrics are built for quick, repetitive washing. Natural fibers require a gentler touch. When properly maintained, they soften with wear, develop a unique character, and can last for decades.</p><p>LOOMAAMA garments are crafted to be worn for years instead of just a single season. A little mindful care guarantees they reach their full, beautiful potential.</p>',
            'instructions' => 'Main content text for the why care matters section',
        ),
        array(
            'key' => 'field_care_section_image',
            'label' => 'Why Care Matters Image',
            'name' => 'care_section_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Image for the why care matters section',
        ),
        array(
            'key' => 'field_care_section_image_alt',
            'label' => 'Why Care Matters Image Alt Text',
            'name' => 'care_section_image_alt',
            'type' => 'text',
            'default_value' => 'Soft natural fibers',
            'instructions' => 'Alt text for the section image',
        ),

        // TAB: Golden Rules
        array(
            'key' => 'field_care_tab_rules',
            'label' => 'Golden Rules',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_rules_eyebrow',
            'label' => 'Golden Rules Eyebrow',
            'name' => 'care_rules_eyebrow',
            'type' => 'text',
            'default_value' => 'The Basics of Natural Care',
            'instructions' => 'Eyebrow text above the golden rules title',
        ),
        array(
            'key' => 'field_care_rules_title',
            'label' => 'Golden Rules Title',
            'name' => 'care_rules_title',
            'type' => 'text',
            'default_value' => 'The Golden Rules',
            'instructions' => 'Main title for the golden rules section',
        ),
        array(
            'key' => 'field_care_rules',
            'label' => 'Golden Rules',
            'name' => 'care_rules',
            'type' => 'repeater',
            'instructions' => 'Add the golden rules for caring for natural fibers',
            'sub_fields' => array(
                array(
                    'key' => 'field_care_rule_number',
                    'label' => 'Rule Number',
                    'name' => 'rule_number',
                    'type' => 'number',
                    'default_value' => 1,
                    'instructions' => 'The rule number (1, 2, 3, etc.)',
                ),
                array(
                    'key' => 'field_care_rule_title',
                    'label' => 'Rule Title',
                    'name' => 'rule_title',
                    'type' => 'text',
                    'instructions' => 'Short title for the rule',
                ),
                array(
                    'key' => 'field_care_rule_description',
                    'label' => 'Rule Description',
                    'name' => 'rule_description',
                    'type' => 'textarea',
                    'instructions' => 'Detailed description of the rule',
                ),
            ),
        ),

        // TAB: Fiber Care
        array(
            'key' => 'field_care_tab_fibers',
            'label' => 'Fiber Care Guides',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_fibers_eyebrow',
            'label' => 'Fiber Care Eyebrow',
            'name' => 'care_fibers_eyebrow',
            'type' => 'text',
            'default_value' => 'Every Fiber Has Its Own Language',
            'instructions' => 'Eyebrow text above the fiber care tabs section',
        ),
        array(
            'key' => 'field_care_fibers_title',
            'label' => 'Fiber Care Title',
            'name' => 'care_fibers_title',
            'type' => 'text',
            'default_value' => 'Fiber-Specific Care',
            'instructions' => 'Main title for the fiber care tabs section',
        ),
        array(
            'key' => 'field_care_fibers_description',
            'label' => 'Fiber Care Description',
            'name' => 'care_fibers_description',
            'type' => 'textarea',
            'default_value' => 'Different fibers have different needs. Here\'s how to care for each type of LOOMAAMA garment.',
            'instructions' => 'Description text above the fiber tabs',
        ),
        array(
            'key' => 'field_care_fibers',
            'label' => 'Fiber Care Guides',
            'name' => 'care_fibers',
            'type' => 'repeater',
            'instructions' => 'Add care guides for different fiber types (Nettle, Hemp, Cotton, Wool)',
            'sub_fields' => array(
                array(
                    'key' => 'field_care_fiber_name',
                    'label' => 'Fiber Name',
                    'name' => 'fiber_name',
                    'type' => 'text',
                    'instructions' => 'Name of the fiber (e.g., Nettle, Hemp, Cotton)',
                ),
                array(
                    'key' => 'field_care_fiber_title',
                    'label' => 'Fiber Title',
                    'name' => 'fiber_title',
                    'type' => 'text',
                    'instructions' => 'Descriptive title for this fiber section',
                ),
                array(
                    'key' => 'field_care_fiber_description',
                    'label' => 'Fiber Description',
                    'name' => 'fiber_description',
                    'type' => 'wysiwyg',
                    'toolbar' => 'basic',
                    'instructions' => 'Description of this fiber type and its characteristics',
                ),
                array(
                    'key' => 'field_care_washing',
                    'label' => 'Washing Instructions',
                    'name' => 'washing',
                    'type' => 'textarea',
                    'instructions' => 'Each line will become a bullet point. Example: "Hand wash cold with mild detergent"',
                ),
                array(
                    'key' => 'field_care_drying',
                    'label' => 'Drying Instructions',
                    'name' => 'drying',
                    'type' => 'textarea',
                    'instructions' => 'Each line will become a bullet point. Example: "Lay flat to dry away from direct sunlight"',
                ),
                array(
                    'key' => 'field_care_ironing',
                    'label' => 'Ironing Instructions',
                    'name' => 'ironing',
                    'type' => 'textarea',
                    'instructions' => 'Each line will become a bullet point. Example: "Iron on low heat with a cloth barrier"',
                ),
                array(
                    'key' => 'field_care_storage',
                    'label' => 'Storage Instructions',
                    'name' => 'storage',
                    'type' => 'textarea',
                    'instructions' => 'Each line will become a bullet point. Example: "Store folded in a cool, dry place"',
                ),
            ),
        ),

        // TAB: Closing CTA
        array(
            'key' => 'field_care_tab_closing',
            'label' => 'Closing CTA',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_closing_title',
            'label' => 'Closing Section Title',
            'name' => 'care_closing_title',
            'type' => 'text',
            'default_value' => 'Wear Well. Care Well.',
            'instructions' => 'Title for the closing section (if used)',
        ),
        array(
            'key' => 'field_care_closing_text',
            'label' => 'Closing Section Text',
            'name' => 'care_closing_text',
            'type' => 'textarea',
            'default_value' => 'A well-cared-for garment rewards you with years of comfort and beauty. Thank you for choosing to wear something made with intention.',
            'instructions' => 'Text for the closing section (if used)',
        ),
        array(
            'key' => 'field_care_closing_button_text',
            'label' => 'Closing Button Text',
            'name' => 'care_closing_button_text',
            'type' => 'text',
            'default_value' => 'Shop the Collection',
            'instructions' => 'Text for the closing CTA button (if used)',
        ),
        array(
            'key' => 'field_care_closing_button_link',
            'label' => 'Closing Button Link',
            'name' => 'care_closing_button_link',
            'type' => 'text',
            'default_value' => '/shop',
            'instructions' => 'URL for the closing CTA button (if used)',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-care-guide.php',
                'order_no' => 0,
                'group_no' => 0,
            ),
        ),
    ),
    'options' => array(
        'position' => 'normal',
        'layout' => 'no_box',
        'hide_on_screen' => array(),
    ),
));