<?php
/**
 * SCF Field Group: Shipping & Returns Page
 *
 * @package Loomaama
 */

if (!function_exists('register_field_group')) {
    return;
}

register_field_group(array(
    'id' => 'acf_shipping_returns',
    'title' => 'Shipping & Returns Settings',
    'fields' => array(
        // TAB: Hero
        array(
            'key' => 'field_sr_tab_hero',
            'label' => 'Hero Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_sr_hero_eyebrow',
            'label' => 'Hero Eyebrow Text',
            'name' => 'shipping_returns_hero_eyebrow',
            'type' => 'text',
            'default_value' => 'Client Care',
        ),
        array(
            'key' => 'field_sr_hero_title',
            'label' => 'Hero Title',
            'name' => 'shipping_returns_hero_title',
            'type' => 'text',
            'default_value' => 'Shipping & Returns',
        ),
        array(
            'key' => 'field_sr_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'shipping_returns_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'A considered experience from order to arrival. Every LOOMAAMA piece is prepared with care, checked by hand, and sent with the same attention that shaped the garment itself.',
        ),
        array(
            'key' => 'field_sr_hero_image',
            'label' => 'Hero Background Image',
            'name' => 'shipping_returns_hero_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Background image for hero section (used if no video)',
        ),
        array(
            'key' => 'field_sr_hero_video',
            'label' => 'Hero Video (MP4)',
            'name' => 'shipping_returns_hero_video',
            'type' => 'file',
            'save_format' => 'url',
            'instructions' => 'Upload an MP4 video for parallax background',
        ),
        array(
            'key' => 'field_sr_hero_card_title',
            'label' => 'Hero Card Title',
            'name' => 'hero_card_title',
            'type' => 'text',
            'default_value' => 'How do I return or exchange my online purchase?',
        ),
        array(
            'key' => 'field_sr_hero_card_text_1',
            'label' => 'Hero Card Text 1',
            'name' => 'hero_card_text_1',
            'type' => 'text',
            'default_value' => 'LOOMAAMA accepts eligible returns and exchanges within 30 days of purchase.',
        ),
        array(
            'key' => 'field_sr_hero_card_text_2',
            'label' => 'Hero Card Text 2',
            'name' => 'hero_card_text_2',
            'type' => 'text',
            'default_value' => 'Returned items must be unworn, unused, unwashed, and returned in original condition with tags and packaging intact.',
        ),

        // TAB: Quick Info
        array(
            'key' => 'field_sr_tab_quick',
            'label' => 'Quick Info Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_sr_quick_items',
            'label' => 'Quick Info Items',
            'name' => 'quick_info_items',
            'type' => 'repeater',
            'max' => 3,
            'sub_fields' => array(
                array('key' => 'field_sr_quick_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_sr_quick_text', 'name' => 'text', 'type' => 'text'),
            ),
        ),

        // TAB: Returns
        array(
            'key' => 'field_sr_tab_returns',
            'label' => 'Returns Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_sr_returns_eyebrow',
            'label' => 'Returns Eyebrow',
            'name' => 'returns_eyebrow',
            'type' => 'text',
            'default_value' => 'Returns & Exchanges',
        ),
        array(
            'key' => 'field_sr_returns_title',
            'label' => 'Returns Title',
            'name' => 'returns_title',
            'type' => 'text',
            'default_value' => 'A simple process, handled with care.',
        ),
        array(
            'key' => 'field_sr_returns_text_1',
            'label' => 'Returns Text 1',
            'name' => 'returns_text_1',
            'type' => 'textarea',
            'default_value' => 'LOOMAAMA accepts eligible returns and exchanges within 30 days of purchase. Items must be unused, unworn, unwashed, and returned with all original tags, packaging, and order documentation.',
        ),
        array(
            'key' => 'field_sr_returns_text_2',
            'label' => 'Returns Text 2',
            'name' => 'returns_text_2',
            'type' => 'textarea',
            'default_value' => 'Natural texture, fibre slubs, handwoven irregularities, and tonal variations are part of wild Himalayan nettle cloth. These qualities are not considered faults.',
        ),
        array(
            'key' => 'field_sr_eligible_title',
            'label' => 'Eligible Title',
            'name' => 'eligible_title',
            'type' => 'text',
            'default_value' => 'Eligible for return',
        ),
        array(
            'key' => 'field_sr_eligible_items',
            'label' => 'Eligible Items',
            'name' => 'eligible_items',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_sr_eligible_text', 'name' => 'text', 'type' => 'text'),
            ),
        ),
        array(
            'key' => 'field_sr_not_eligible_title',
            'label' => 'Not Eligible Title',
            'name' => 'not_eligible_title',
            'type' => 'text',
            'default_value' => 'Not eligible for return',
        ),
        array(
            'key' => 'field_sr_not_eligible_items',
            'label' => 'Not Eligible Items',
            'name' => 'not_eligible_items',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_sr_not_eligible_text', 'name' => 'text', 'type' => 'text'),
            ),
        ),

        // TAB: Process
        array(
            'key' => 'field_sr_tab_process',
            'label' => 'Return Process',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_sr_process_eyebrow',
            'label' => 'Process Eyebrow',
            'name' => 'process_eyebrow',
            'type' => 'text',
            'default_value' => 'The Return Process',
        ),
        array(
            'key' => 'field_sr_process_title',
            'label' => 'Process Title',
            'name' => 'process_title',
            'type' => 'text',
            'default_value' => 'Four steps from request to resolution.',
        ),
        array(
            'key' => 'field_sr_process_steps',
            'label' => 'Process Steps',
            'name' => 'process_steps',
            'type' => 'repeater',
            'max' => 4,
            'sub_fields' => array(
                array('key' => 'field_sr_step_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_sr_step_description', 'name' => 'description', 'type' => 'textarea'),
            ),
        ),

        // TAB: Natural Variation
        array(
            'key' => 'field_sr_tab_variation',
            'label' => 'Natural Variation',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_sr_variation_eyebrow',
            'label' => 'Variation Eyebrow',
            'name' => 'variation_eyebrow',
            'type' => 'text',
            'default_value' => 'Natural Variation',
        ),
        array(
            'key' => 'field_sr_variation_title',
            'label' => 'Variation Title',
            'name' => 'variation_title',
            'type' => 'text',
            'default_value' => 'Made by nature. Finished by hand.',
        ),
        array(
            'key' => 'field_sr_variation_text_1',
            'label' => 'Variation Text 1',
            'name' => 'variation_text_1',
            'type' => 'textarea',
            'default_value' => 'Wild Himalayan nettle is a naturally expressive fibre. Subtle variation in weave, texture, tone, and surface character is part of its authenticity.',
        ),
        array(
            'key' => 'field_sr_variation_text_2',
            'label' => 'Variation Text 2',
            'name' => 'variation_text_2',
            'type' => 'textarea',
            'default_value' => 'These details are not imperfections. They are the visible record of origin, material, and human hands.',
        ),
        array(
            'key' => 'field_sr_variation_image',
            'label' => 'Variation Image',
            'name' => 'variation_image',
            'type' => 'image',
            'save_format' => 'url',
        ),

        // TAB: FAQ
        array(
            'key' => 'field_sr_tab_faq',
            'label' => 'FAQ Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_sr_faq_eyebrow',
            'label' => 'FAQ Eyebrow',
            'name' => 'faq_eyebrow',
            'type' => 'text',
            'default_value' => 'Questions',
        ),
        array(
            'key' => 'field_sr_faq_title',
            'label' => 'FAQ Title',
            'name' => 'faq_title',
            'type' => 'text',
            'default_value' => 'Shipping & return details',
        ),
        array(
            'key' => 'field_sr_faq_items',
            'label' => 'FAQ Items',
            'name' => 'faq_items',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_sr_faq_question', 'name' => 'question', 'type' => 'text'),
                array('key' => 'field_sr_faq_answer', 'name' => 'answer', 'type' => 'textarea'),
            ),
        ),

        // TAB: Contact
        array(
            'key' => 'field_sr_tab_contact',
            'label' => 'Contact Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_sr_contact_eyebrow',
            'label' => 'Contact Eyebrow',
            'name' => 'contact_eyebrow',
            'type' => 'text',
            'default_value' => 'Need Assistance?',
        ),
        array(
            'key' => 'field_sr_contact_title',
            'label' => 'Contact Title',
            'name' => 'contact_title',
            'type' => 'text',
            'default_value' => 'LOOMAAMA Client Care',
        ),
        array(
            'key' => 'field_sr_contact_text',
            'label' => 'Contact Text',
            'name' => 'contact_text',
            'type' => 'textarea',
            'default_value' => 'For returns, exchanges, shipping enquiries, or order support, our Client Care team is here to help.',
        ),
        array(
            'key' => 'field_sr_contact_button_text',
            'label' => 'Contact Button Text',
            'name' => 'contact_button_text',
            'type' => 'text',
            'default_value' => 'Contact Client Care',
        ),
        array(
            'key' => 'field_sr_contact_button_link',
            'label' => 'Contact Button Link',
            'name' => 'contact_button_link',
            'type' => 'text',
            'default_value' => 'mailto:support@loomaama.com.au',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-shipping-returns.php',
            ),
        ),
    ),
    'options' => array(
        'position' => 'normal',
        'layout' => 'no_box',
        'hide_on_screen' => array(),
    ),
));