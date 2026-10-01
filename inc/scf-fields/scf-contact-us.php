<?php
/**
 * SCF Field Group: Contact Us Page
 * Premium Design with Floating Elements
 *
 * @package Loomaama
 */

if (!function_exists('register_field_group')) {
    return;
}

register_field_group(array(
    'id' => 'acf_contact_us',
    'title' => 'Contact Us Page Settings',
    'fields' => array(
        // TAB: Hero
        array(
            'key' => 'field_contact_tab_hero',
            'label' => 'Hero Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_contact_hero_eyebrow',
            'label' => 'Hero Eyebrow',
            'name' => 'contact_hero_eyebrow',
            'type' => 'text',
            'default_value' => 'Loomaama Client Care',
        ),
        array(
            'key' => 'field_contact_hero_title',
            'label' => 'Hero Title',
            'name' => 'contact_hero_title',
            'type' => 'text',
            'default_value' => 'Contact Us',
        ),
        array(
            'key' => 'field_contact_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'contact_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'Every garment begins with a conversation. If you need guidance, support, or answers, we are here to assist.',
        ),
        array(
            'key' => 'field_hero_main_image',
            'label' => 'Hero Main Image',
            'name' => 'hero_main_image',
            'type' => 'image',
            'save_format' => 'url',
        ),
        array(
            'key' => 'field_hero_secondary_image',
            'label' => 'Hero Secondary Image',
            'name' => 'hero_secondary_image',
            'type' => 'image',
            'save_format' => 'url',
        ),
        array(
            'key' => 'field_hero_note_eyebrow',
            'label' => 'Hero Note Eyebrow',
            'name' => 'hero_note_eyebrow',
            'type' => 'text',
            'default_value' => 'Care Note',
        ),
        array(
            'key' => 'field_hero_note_text',
            'label' => 'Hero Note Text',
            'name' => 'hero_note_text',
            'type' => 'textarea',
            'default_value' => 'For fit help, include your measurements and the piece you are considering.',
        ),

        // TAB: Path Cards
        array(
            'key' => 'field_contact_tab_paths',
            'label' => 'Path Cards',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_path_cards',
            'label' => 'Path Cards',
            'name' => 'path_cards',
            'type' => 'repeater',
            'max' => 4,
            'sub_fields' => array(
                array('key' => 'field_path_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_path_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_path_description', 'name' => 'description', 'type' => 'textarea'),
            ),
        ),

        // TAB: Support Cards
        array(
            'key' => 'field_contact_tab_support',
            'label' => 'Support Cards',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_support_eyebrow',
            'label' => 'Support Eyebrow',
            'name' => 'support_eyebrow',
            'type' => 'text',
            'default_value' => 'What Do You Need?',
        ),
        array(
            'key' => 'field_support_title',
            'label' => 'Support Title',
            'name' => 'support_title',
            'type' => 'text',
            'default_value' => 'Support by moment, not by menu.',
        ),
        array(
            'key' => 'field_support_subtitle',
            'label' => 'Support Subtitle',
            'name' => 'support_subtitle',
            'type' => 'textarea',
            'default_value' => 'Every inquiry is guided to the right place, so your question receives the attention it deserves.',
        ),
        array(
            'key' => 'field_support_cards',
            'label' => 'Support Cards',
            'name' => 'support_cards',
            'type' => 'repeater',
            'max' => 3,
            'sub_fields' => array(
                array('key' => 'field_support_card_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_support_card_description', 'name' => 'description', 'type' => 'textarea'),
            ),
        ),

        // TAB: Contact Form
        array(
            'key' => 'field_contact_tab_form',
            'label' => 'Contact Form',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_form_eyebrow',
            'label' => 'Form Eyebrow',
            'name' => 'form_eyebrow',
            'type' => 'text',
            'default_value' => 'Contact Form',
        ),
        array(
            'key' => 'field_form_title',
            'label' => 'Form Title',
            'name' => 'form_title',
            'type' => 'text',
            'default_value' => 'Share the details with us.',
        ),
        array(
            'key' => 'field_form_subtitle',
            'label' => 'Form Subtitle',
            'name' => 'form_subtitle',
            'type' => 'textarea',
            'default_value' => 'The more we understand your garment, order, or question, the better we can guide you.',
        ),
        array(
            'key' => 'field_form_first_name_label',
            'label' => 'First Name Label',
            'name' => 'form_first_name_label',
            'type' => 'text',
            'default_value' => 'First Name',
        ),
        array(
            'key' => 'field_form_last_name_label',
            'label' => 'Last Name Label',
            'name' => 'form_last_name_label',
            'type' => 'text',
            'default_value' => 'Last Name',
        ),
        array(
            'key' => 'field_form_email_label',
            'label' => 'Email Label',
            'name' => 'form_email_label',
            'type' => 'text',
            'default_value' => 'Email',
        ),
        array(
            'key' => 'field_form_inquiry_label',
            'label' => 'Inquiry Type Label',
            'name' => 'form_inquiry_label',
            'type' => 'text',
            'default_value' => 'Inquiry Type',
        ),
        array(
            'key' => 'field_inquiry_types',
            'label' => 'Inquiry Type Options',
            'name' => 'inquiry_types',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_inquiry_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_inquiry_value', 'name' => 'value', 'type' => 'text'),
            ),
        ),
        array(
            'key' => 'field_form_order_label',
            'label' => 'Order Number Label',
            'name' => 'form_order_label',
            'type' => 'text',
            'default_value' => 'Order Number, if applicable',
        ),
        array(
            'key' => 'field_form_message_label',
            'label' => 'Message Label',
            'name' => 'form_message_label',
            'type' => 'text',
            'default_value' => 'Message',
        ),
        array(
            'key' => 'field_form_button_text',
            'label' => 'Submit Button Text',
            'name' => 'form_button_text',
            'type' => 'text',
            'default_value' => 'Submit Inquiry',
        ),

        // TAB: Image Ribbon
        array(
            'key' => 'field_contact_tab_ribbon',
            'label' => 'Image Ribbon',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_ribbon_image',
            'label' => 'Ribbon Background Image',
            'name' => 'ribbon_image',
            'type' => 'image',
            'save_format' => 'url',
        ),
        array(
            'key' => 'field_ribbon_text',
            'label' => 'Ribbon Text',
            'name' => 'ribbon_text',
            'type' => 'text',
            'default_value' => 'The right answer should feel as considered as the garment itself.',
        ),

        // TAB: Detail Items
        array(
            'key' => 'field_contact_tab_details',
            'label' => 'Detail Items',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_details_eyebrow',
            'label' => 'Details Eyebrow',
            'name' => 'details_eyebrow',
            'type' => 'text',
            'default_value' => 'Before You Write',
        ),
        array(
            'key' => 'field_details_title',
            'label' => 'Details Title',
            'name' => 'details_title',
            'type' => 'text',
            'default_value' => 'Useful details help us guide you better.',
        ),
        array(
            'key' => 'field_detail_items',
            'label' => 'Detail Items',
            'name' => 'detail_items',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_detail_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_detail_description', 'name' => 'description', 'type' => 'textarea'),
            ),
        ),

        // TAB: Final CTA
        array(
            'key' => 'field_contact_tab_final',
            'label' => 'Final CTA Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_final_eyebrow',
            'label' => 'Final Eyebrow',
            'name' => 'final_eyebrow',
            'type' => 'text',
            'default_value' => 'Still Unsure?',
        ),
        array(
            'key' => 'field_final_title',
            'label' => 'Final Title',
            'name' => 'final_title',
            'type' => 'text',
            'default_value' => 'Every question deserves a considered answer.',
        ),
        array(
            'key' => 'field_final_text',
            'label' => 'Final Text',
            'name' => 'final_text',
            'type' => 'textarea',
            'default_value' => 'Send us what you know. We will help with the rest.',
        ),
        array(
            'key' => 'field_final_button_text',
            'label' => 'Final Button Text',
            'name' => 'final_button_text',
            'type' => 'text',
            'default_value' => 'Contact Loomaama',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-contact-us.php',
            ),
        ),
    ),
    'options' => array(
        'position' => 'normal',
        'layout' => 'no_box',
        'hide_on_screen' => array(),
    ),
));