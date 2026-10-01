<?php
/**
 * SCF Field Group: Care Guide
 * Updated to match the new design structure from care page preview
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
            'key' => 'field_care_hero_eyebrow',
            'label' => 'Hero Eyebrow',
            'name' => 'care_hero_eyebrow',
            'type' => 'text',
            'default_value' => 'Care Guide',
            'instructions' => 'Small uppercase text above the main title',
        ),
        array(
            'key' => 'field_care_hero_title',
            'label' => 'Hero Title',
            'name' => 'care_hero_title',
            'type' => 'text',
            'default_value' => 'Make It Last Beautifully',
            'instructions' => 'Main title for the hero section',
        ),
        array(
            'key' => 'field_care_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'care_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'Care is the final part of making. Your garment was made slowly. Care for it with the same attention.',
            'instructions' => 'Subtitle text for the hero section',
        ),
        array(
            'key' => 'field_care_hero_image',
            'label' => 'Hero Background Image',
            'name' => 'care_hero_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Upload background image for hero section (right side)',
        ),

        // TAB: Machine Wash
        array(
            'key' => 'field_care_tab_machine',
            'label' => 'Machine Wash Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_machine_eyebrow',
            'label' => 'Machine Wash Eyebrow',
            'name' => 'care_machine_eyebrow',
            'type' => 'text',
            'default_value' => 'Machine Wash',
        ),
        array(
            'key' => 'field_care_machine_title',
            'label' => 'Machine Wash Title',
            'name' => 'care_machine_title',
            'type' => 'text',
            'default_value' => 'When Needed, Wash Gently',
        ),
        array(
            'key' => 'field_care_machine_lead',
            'label' => 'Machine Wash Lead Text',
            'name' => 'care_machine_lead',
            'type' => 'textarea',
            'default_value' => 'Use the gentlest setting to protect the natural structure of Himalayan nettle cloth.',
        ),
        array(
            'key' => 'field_care_machine_steps',
            'label' => 'Machine Wash Steps',
            'name' => 'care_machine_steps',
            'type' => 'repeater',
            'instructions' => 'Add step-by-step instructions for machine washing',
            'sub_fields' => array(
                array(
                    'key' => 'field_care_machine_step_text',
                    'label' => 'Step Text',
                    'name' => 'step_text',
                    'type' => 'text',
                ),
            ),
        ),
        array(
            'key' => 'field_care_machine_image',
            'label' => 'Machine Wash Image',
            'name' => 'care_machine_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Image for the machine wash section',
        ),

        // TAB: Hand Wash
        array(
            'key' => 'field_care_tab_hand',
            'label' => 'Hand Wash Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_hand_eyebrow',
            'label' => 'Hand Wash Eyebrow',
            'name' => 'care_hand_eyebrow',
            'type' => 'text',
            'default_value' => 'Hand Wash',
        ),
        array(
            'key' => 'field_care_hand_title',
            'label' => 'Hand Wash Title',
            'name' => 'care_hand_title',
            'type' => 'text',
            'default_value' => 'The Most Respectful Care',
        ),
        array(
            'key' => 'field_care_hand_lead',
            'label' => 'Hand Wash Lead Text',
            'name' => 'care_hand_lead',
            'type' => 'textarea',
            'default_value' => 'Hand washing is preferred when your garment needs a slower, softer ritual of care.',
        ),
        array(
            'key' => 'field_care_hand_steps',
            'label' => 'Hand Wash Steps',
            'name' => 'care_hand_steps',
            'type' => 'repeater',
            'instructions' => 'Add step-by-step instructions for hand washing',
            'sub_fields' => array(
                array(
                    'key' => 'field_care_hand_step_text',
                    'label' => 'Step Text',
                    'name' => 'step_text',
                    'type' => 'text',
                ),
            ),
        ),
        array(
            'key' => 'field_care_hand_image',
            'label' => 'Hand Wash Image',
            'name' => 'care_hand_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Image for the hand wash section',
        ),

        // TAB: Dry Naturally
        array(
            'key' => 'field_care_tab_dry',
            'label' => 'Dry Naturally Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_dry_eyebrow',
            'label' => 'Dry Naturally Eyebrow',
            'name' => 'care_dry_eyebrow',
            'type' => 'text',
            'default_value' => 'Dry Naturally',
        ),
        array(
            'key' => 'field_care_dry_title',
            'label' => 'Dry Naturally Title',
            'name' => 'care_dry_title',
            'type' => 'text',
            'default_value' => 'Let Air Finish the Process',
        ),
        array(
            'key' => 'field_care_dry_lead',
            'label' => 'Dry Naturally Lead Text',
            'name' => 'care_dry_lead',
            'type' => 'textarea',
            'default_value' => 'Lay flat, reshape gently, and dry in shade with natural airflow. Avoid tumble drying and strong direct sunlight.',
        ),
        array(
            'key' => 'field_care_dry_image',
            'label' => 'Dry Naturally Image',
            'name' => 'care_dry_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Full width image for the drying section',
        ),

        // TAB: Refresh & Storage Cards
        array(
            'key' => 'field_care_tab_cards',
            'label' => 'Refresh & Storage Cards',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_refresh_eyebrow',
            'label' => 'Refresh Card Eyebrow',
            'name' => 'care_refresh_eyebrow',
            'type' => 'text',
            'default_value' => 'Refresh Between Wears',
        ),
        array(
            'key' => 'field_care_refresh_title',
            'label' => 'Refresh Card Title',
            'name' => 'care_refresh_title',
            'type' => 'text',
            'default_value' => 'Not Every Wear Needs a Wash',
        ),
        array(
            'key' => 'field_care_refresh_text',
            'label' => 'Refresh Card Text',
            'name' => 'care_refresh_text',
            'type' => 'textarea',
            'default_value' => 'Hang your garment in fresh air, away from harsh dust. Nettle cloth benefits from storage, breath, and time.',
        ),
        array(
            'key' => 'field_care_storage_eyebrow',
            'label' => 'Storage Card Eyebrow',
            'name' => 'care_storage_eyebrow',
            'type' => 'text',
            'default_value' => 'Storage',
        ),
        array(
            'key' => 'field_care_storage_title',
            'label' => 'Storage Card Title',
            'name' => 'care_storage_title',
            'type' => 'text',
            'default_value' => 'Store With Care',
        ),
        array(
            'key' => 'field_care_storage_text',
            'label' => 'Storage Card Text',
            'name' => 'care_storage_text',
            'type' => 'textarea',
            'default_value' => 'Make sure the garment is completely dry. Fold heavier pieces instead of hanging them. Keep in a cool, dry place and use breathable cotton or fabric storage bags. Avoid plastic covers for long-term storage.',
        ),

        // TAB: Do / Do Not Notes
        array(
            'key' => 'field_care_tab_notes',
            'label' => 'Do / Do Not Notes',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_do_title',
            'label' => 'Do Section Title',
            'name' => 'care_do_title',
            'type' => 'text',
            'default_value' => 'Do',
        ),
        array(
            'key' => 'field_care_do_items',
            'label' => 'Do Items',
            'name' => 'care_do_items',
            'type' => 'repeater',
            'sub_fields' => array(
                array(
                    'key' => 'field_care_do_item_text',
                    'label' => 'Item Text',
                    'name' => 'item_text',
                    'type' => 'text',
                ),
            ),
        ),
        array(
            'key' => 'field_care_dont_title',
            'label' => 'Do Not Section Title',
            'name' => 'care_dont_title',
            'type' => 'text',
            'default_value' => 'Do Not',
        ),
        array(
            'key' => 'field_care_dont_items',
            'label' => 'Do Not Items',
            'name' => 'care_dont_items',
            'type' => 'repeater',
            'sub_fields' => array(
                array(
                    'key' => 'field_care_dont_item_text',
                    'label' => 'Item Text',
                    'name' => 'item_text',
                    'type' => 'text',
                ),
            ),
        ),

        // TAB: Closing CTA
        array(
            'key' => 'field_care_tab_cta',
            'label' => 'Closing CTA',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_care_cta_eyebrow',
            'label' => 'CTA Eyebrow',
            'name' => 'care_cta_eyebrow',
            'type' => 'text',
            'default_value' => 'Made to Last Beyond Seasons',
        ),
        array(
            'key' => 'field_care_cta_title',
            'label' => 'CTA Title',
            'name' => 'care_cta_title',
            'type' => 'text',
            'default_value' => 'Wear Well. Care Well.',
        ),
        array(
            'key' => 'field_care_cta_text',
            'label' => 'CTA Main Text',
            'name' => 'care_cta_text',
            'type' => 'text',
            'default_value' => 'A well-cared-for garment rewards you with years of comfort and beauty.',
        ),
        array(
            'key' => 'field_care_cta_subtext',
            'label' => 'CTA Subtext',
            'name' => 'care_cta_subtext',
            'type' => 'textarea',
            'default_value' => 'Need help caring for your garment?<br>Contact LOOMAAMA for personal garment care guidance.',
        ),
        array(
            'key' => 'field_care_cta_button_text',
            'label' => 'Button Text',
            'name' => 'care_cta_button_text',
            'type' => 'text',
            'default_value' => 'Become a Witness',
        ),
        array(
            'key' => 'field_care_cta_button_link',
            'label' => 'Button Link',
            'name' => 'care_cta_button_link',
            'type' => 'text',
            'default_value' => '#',
            'instructions' => 'URL for the CTA button',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-care-guide.php',
            ),
        ),
    ),
    'options' => array(
        'position' => 'normal',
        'layout' => 'no_box',
        'hide_on_screen' => array(),
    ),
));