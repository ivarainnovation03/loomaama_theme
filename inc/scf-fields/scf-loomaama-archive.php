<?php
/**
 * SCF Field Group: LOOMAAMA Archive Page
 *
 * @package Loomaama
 */

if (!function_exists('register_field_group')) {
    return;
}

register_field_group(array(
    'id' => 'acf_loomaama_archive',
    'title' => 'LOOMAAMA Archive Settings',
    'fields' => array(
        // TAB: Hero
        array(
            'key' => 'field_archive_tab_hero',
            'label' => 'Hero Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_archive_hero_eyebrow',
            'label' => 'Hero Eyebrow',
            'name' => 'archive_hero_eyebrow',
            'type' => 'text',
            'default_value' => 'LOOMAAMA Archive',
        ),
        array(
            'key' => 'field_archive_hero_title',
            'label' => 'Hero Title',
            'name' => 'archive_hero_title',
            'type' => 'text',
            'default_value' => 'Company History',
        ),
        array(
            'key' => 'field_archive_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'archive_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'A record of wild nettle, handwoven cloth, people, place, and the quiet decisions that shaped LOOMAAMA.',
        ),
        array(
            'key' => 'field_archive_hero_caption',
            'label' => 'Hero Caption',
            'name' => 'archive_hero_caption',
            'type' => 'text',
            'default_value' => 'Archive image: early nettle cloth study, recorded as part of LOOMAAMA\'s material language.',
        ),
        array(
            'key' => 'field_archive_hero_image',
            'label' => 'Hero Background Image',
            'name' => 'archive_hero_image',
            'type' => 'image',
            'save_format' => 'url',
        ),

        // TAB: Commitments
        array(
            'key' => 'field_archive_tab_commitments',
            'label' => 'Commitments Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_archive_commitments',
            'label' => 'Commitment Items',
            'name' => 'archive_commitments',
            'type' => 'repeater',
            'max' => 5,
            'sub_fields' => array(
                array('key' => 'field_commitment_icon_class', 'name' => 'icon_class', 'type' => 'text', 'instructions' => 'Font Awesome icon class (e.g., fa-solid fa-leaf)'),
                array('key' => 'field_commitment_text', 'name' => 'text', 'type' => 'text'),
            ),
        ),

        // TAB: Intro
        array(
            'key' => 'field_archive_tab_intro',
            'label' => 'Intro Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_archive_intro_eyebrow',
            'label' => 'Intro Eyebrow',
            'name' => 'archive_intro_eyebrow',
            'type' => 'text',
            'default_value' => 'Our Archive',
        ),
        array(
            'key' => 'field_archive_intro_title',
            'label' => 'Intro Title',
            'name' => 'archive_intro_title',
            'type' => 'text',
            'default_value' => 'Not a catalogue. A record of becoming.',
        ),
        array(
            'key' => 'field_archive_intro_text',
            'label' => 'Intro Text',
            'name' => 'archive_intro_text',
            'type' => 'textarea',
            'default_value' => 'This page follows the material story behind LOOMAAMA: the plant before fibre, the fibre before thread, the thread before cloth, and the hands behind each finished piece.',
        ),

        // TAB: Timeline
        array(
            'key' => 'field_archive_tab_timeline',
            'label' => 'Timeline Items',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_archive_timeline',
            'label' => 'Timeline Items',
            'name' => 'archive_timeline',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_timeline_category', 'name' => 'category', 'type' => 'text'),
                array('key' => 'field_timeline_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_timeline_description', 'name' => 'description', 'type' => 'textarea'),
                array('key' => 'field_timeline_image', 'name' => 'image', 'type' => 'image', 'save_format' => 'url'),
                array('key' => 'field_timeline_image_position', 'name' => 'image_position', 'type' => 'select', 'choices' => array('right' => 'Right', 'left' => 'Left'), 'default_value' => 'right'),
            ),
        ),

        // TAB: Full Image
        array(
            'key' => 'field_archive_tab_fullimage',
            'label' => 'Full Image Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_archive_full_image',
            'label' => 'Full Width Image',
            'name' => 'archive_full_image',
            'type' => 'image',
            'save_format' => 'url',
        ),

        // TAB: Quote
        array(
            'key' => 'field_archive_tab_quote',
            'label' => 'Quote Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_archive_quote_eyebrow',
            'label' => 'Quote Eyebrow',
            'name' => 'archive_quote_eyebrow',
            'type' => 'text',
            'default_value' => 'Archive Statement',
        ),
        array(
            'key' => 'field_archive_quote_text',
            'label' => 'Quote Text',
            'name' => 'archive_quote_text',
            'type' => 'text',
            'default_value' => 'The cloth remembers the hand, the plant, and the place it came from.',
        ),
        array(
            'key' => 'field_archive_quote_subtext',
            'label' => 'Quote Subtext',
            'name' => 'archive_quote_subtext',
            'type' => 'text',
            'default_value' => 'LOOMAAMA\'s archive exists to keep that memory visible.',
        ),

        // TAB: Archive Index
        array(
            'key' => 'field_archive_tab_index',
            'label' => 'Archive Index',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_archive_index_eyebrow',
            'label' => 'Index Eyebrow',
            'name' => 'archive_index_eyebrow',
            'type' => 'text',
            'default_value' => 'Archive Index',
        ),
        array(
            'key' => 'field_archive_index_title',
            'label' => 'Index Title',
            'name' => 'archive_index_title',
            'type' => 'text',
            'default_value' => 'Records by Category',
        ),
        array(
            'key' => 'field_archive_index_items',
            'label' => 'Index Items',
            'name' => 'archive_index_items',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_index_category', 'name' => 'category', 'type' => 'text'),
                array('key' => 'field_index_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_index_description', 'name' => 'description', 'type' => 'text'),
            ),
        ),

        // TAB: CTA
        array(
            'key' => 'field_archive_tab_cta',
            'label' => 'CTA Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_archive_cta_eyebrow',
            'label' => 'CTA Eyebrow',
            'name' => 'archive_cta_eyebrow',
            'type' => 'text',
            'default_value' => 'The Archive Will Continue',
        ),
        array(
            'key' => 'field_archive_cta_title',
            'label' => 'CTA Title',
            'name' => 'archive_cta_title',
            'type' => 'text',
            'default_value' => 'Each garment adds another record.',
        ),
        array(
            'key' => 'field_archive_cta_text',
            'label' => 'CTA Text',
            'name' => 'archive_cta_text',
            'type' => 'textarea',
            'default_value' => 'LOOMAAMA is built through material, place, and people. The archive keeps that story visible.',
        ),
        array(
            'key' => 'field_archive_cta_button_text',
            'label' => 'Button Text',
            'name' => 'archive_cta_button_text',
            'type' => 'text',
            'default_value' => 'Become a Witness',
        ),
        array(
            'key' => 'field_archive_cta_button_link',
            'label' => 'Button Link',
            'name' => 'archive_cta_button_link',
            'type' => 'text',
            'default_value' => '/shop',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-loomaama-archive.php',
            ),
        ),
    ),
    'options' => array(
        'position' => 'normal',
        'layout' => 'no_box',
        'hide_on_screen' => array(),
    ),
));