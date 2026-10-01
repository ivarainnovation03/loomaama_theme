<?php
/**
 * SCF Field Group: FAQ Page
 *
 * @package Loomaama
 */

if (!function_exists('register_field_group')) {
    return;
}

register_field_group(array(
    'id' => 'acf_faq_page',
    'title' => 'FAQ Page Settings',
    'fields' => array(
        // TAB: Hero
        array(
            'key' => 'field_faq_tab_hero',
            'label' => 'Hero Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_faq_hero_eyebrow',
            'label' => 'Hero Eyebrow',
            'name' => 'faq_hero_eyebrow',
            'type' => 'text',
            'default_value' => 'FAQ',
        ),
        array(
            'key' => 'field_faq_hero_title',
            'label' => 'Hero Title',
            'name' => 'faq_hero_title',
            'type' => 'text',
            'default_value' => 'The Quiet Answers Behind Every Piece.',
        ),
        array(
            'key' => 'field_faq_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'faq_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'A quiet guide to the material, making, care, and service behind LOOMAAMA. Written for those who look closely before they choose.',
        ),
        array(
            'key' => 'field_faq_editorial_note',
            'label' => 'Editorial Note',
            'name' => 'faq_editorial_note',
            'type' => 'text',
            'default_value' => 'Material. Craft. Care. Service.',
        ),
        array(
            'key' => 'field_faq_support_items',
            'label' => 'Support Box Items',
            'name' => 'faq_support_items',
            'type' => 'repeater',
            'max' => 3,
            'sub_fields' => array(
                array('key' => 'field_faq_support_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_faq_support_value', 'name' => 'value', 'type' => 'text'),
            ),
        ),

        // TAB: Service Cards
        array(
            'key' => 'field_faq_tab_service',
            'label' => 'Service Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_faq_service_title',
            'label' => 'Service Title',
            'name' => 'faq_service_title',
            'type' => 'text',
            'default_value' => 'The LOOMAAMA Service',
        ),
        array(
            'key' => 'field_faq_service_subtitle',
            'label' => 'Service Subtitle',
            'name' => 'faq_service_subtitle',
            'type' => 'textarea',
            'default_value' => 'A refined path through the essentials: material, craft, fit, care, orders, shipping, and returns.',
        ),
        array(
            'key' => 'field_faq_service_cards',
            'label' => 'Service Cards',
            'name' => 'faq_service_cards',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_faq_card_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_faq_card_description', 'name' => 'description', 'type' => 'textarea'),
            ),
        ),

        // TAB: Story Images
        array(
            'key' => 'field_faq_tab_story',
            'label' => 'Story Images',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_faq_story_images',
            'label' => 'Story Images (2 images)',
            'name' => 'faq_story_images',
            'type' => 'repeater',
            'max' => 2,
            'sub_fields' => array(
                array('key' => 'field_faq_story_image', 'name' => 'image', 'type' => 'image', 'save_format' => 'url'),
                array('key' => 'field_faq_story_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_faq_story_caption', 'name' => 'caption', 'type' => 'text'),
            ),
        ),

        // TAB: FAQ Accordion
        array(
            'key' => 'field_faq_tab_accordion',
            'label' => 'FAQ Accordion',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_faq_accordion_title',
            'label' => 'Accordion Section Title',
            'name' => 'faq_accordion_title',
            'type' => 'text',
            'default_value' => 'Questions We Are Asked Most',
        ),
        array(
            'key' => 'field_faq_accordion_subtitle',
            'label' => 'Accordion Section Subtitle',
            'name' => 'faq_accordion_subtitle',
            'type' => 'textarea',
            'default_value' => 'Answers shaped around the questions customers ask before choosing, wearing, and caring for LOOMAAMA.',
        ),
        array(
            'key' => 'field_faq_accordion_items',
            'label' => 'FAQ Items',
            'name' => 'faq_accordion_items',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_faq_question', 'name' => 'question', 'type' => 'text'),
                array('key' => 'field_faq_answer', 'name' => 'answer', 'type' => 'wysiwyg'),
            ),
        ),

        // TAB: Visual Items
        array(
            'key' => 'field_faq_tab_visual',
            'label' => 'Visual Items',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_faq_visual_items',
            'label' => 'Visual Grid Items (3 images)',
            'name' => 'faq_visual_items',
            'type' => 'repeater',
            'max' => 3,
            'sub_fields' => array(
                array('key' => 'field_faq_visual_image', 'name' => 'image', 'type' => 'image', 'save_format' => 'url'),
                array('key' => 'field_faq_visual_label', 'name' => 'label', 'type' => 'text'),
            ),
        ),

        // TAB: Contact
        array(
            'key' => 'field_faq_tab_contact',
            'label' => 'Contact Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_faq_contact_title',
            'label' => 'Contact Title',
            'name' => 'faq_contact_title',
            'type' => 'text',
            'default_value' => 'Not every answer lives here.',
        ),
        array(
            'key' => 'field_faq_contact_text',
            'label' => 'Contact Text',
            'name' => 'faq_contact_text',
            'type' => 'textarea',
            'default_value' => 'For material, fit, order, or return guidance, our team will respond with care and clarity.',
        ),
        array(
            'key' => 'field_faq_contact_button_text',
            'label' => 'Button Text',
            'name' => 'faq_contact_button_text',
            'type' => 'text',
            'default_value' => 'Contact Our Team',
        ),
        array(
            'key' => 'field_faq_contact_button_link',
            'label' => 'Button Link',
            'name' => 'faq_contact_button_link',
            'type' => 'text',
            'default_value' => 'mailto:support@loomaama.com.au',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-faq.php',
            ),
        ),
    ),
    'options' => array(
        'position' => 'normal',
        'layout' => 'no_box',
        'hide_on_screen' => array(),
    ),
));