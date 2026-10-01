<?php
/**
 * SCF Field Group: About (The Truth)
 * 
 * @package Loomaama
 */

if (!function_exists('register_field_group')) {
    return;
}

register_field_group(array(
    'id' => 'acf_about',
    'title' => 'About Page Settings',
    'fields' => array(
        // TAB: Hero
        array(
            'key' => 'field_about_tab_hero',
            'label' => 'Hero Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_about_hero_title',
            'label' => 'Hero Title',
            'name' => 'about_hero_title',
            'type' => 'text',
            'default_value' => 'Profit is the byproduct.<br>Prosperity is the product.',
            'instructions' => 'Main headline for the hero section. Supports HTML line breaks.',
        ),
        array(
            'key' => 'field_about_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'about_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'Nature is the most advanced lab on Earth. Why would we use a factory?',
            'instructions' => 'Subtitle text below the main headline.',
        ),
        array(
            'key' => 'field_about_hero_image',
            'label' => 'Hero Background Image',
            'name' => 'about_hero_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Background image for the hero section.',
        ),

        // TAB: Introduction
        array(
            'key' => 'field_about_tab_intro',
            'label' => 'Introduction',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_about_intro_eyebrow',
            'label' => 'Introduction Eyebrow Text',
            'name' => 'about_intro_eyebrow',
            'type' => 'text',
            'default_value' => 'Everything we need is already in the forest',
            'instructions' => 'Small eyebrow text above the introduction title.',
        ),
        array(
            'key' => 'field_about_intro_title',
            'label' => 'Introduction Title',
            'name' => 'about_intro_title',
            'type' => 'text',
            'default_value' => 'Designed by Forest,<br>edited by humans.',
            'instructions' => 'Main title for the introduction section.',
        ),
        array(
            'key' => 'field_about_intro_text',
            'label' => 'Introduction Text',
            'name' => 'about_intro_text',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'instructions' => 'Main content text for the introduction section.',
        ),

        // TAB: Manifesto
        array(
            'key' => 'field_about_tab_manifesto',
            'label' => 'Manifesto',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_about_manifesto_eyebrow',
            'label' => 'Manifesto Eyebrow Text',
            'name' => 'about_manifesto_eyebrow',
            'type' => 'text',
            'default_value' => 'Mountain is the maker',
            'instructions' => 'Eyebrow text above the manifesto title.',
        ),
        array(
            'key' => 'field_about_manifesto_image',
            'label' => 'Manifesto Image',
            'name' => 'about_manifesto_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Image for the manifesto section.',
        ),
        array(
            'key' => 'field_about_manifesto_title',
            'label' => 'Manifesto Title',
            'name' => 'about_manifesto_title',
            'type' => 'text',
            'default_value' => 'We Believe Luxury<br>Should Be Quiet.',
            'instructions' => 'Main title for the manifesto section.',
        ),
        array(
            'key' => 'field_about_manifesto_subtitle',
            'label' => 'Manifesto Subtitle',
            'name' => 'about_manifesto_subtitle',
            'type' => 'text',
            'default_value' => 'It should be felt, not announced.',
            'instructions' => 'Subtitle text below the manifesto title.',
        ),
        array(
            'key' => 'field_about_manifesto_text',
            'label' => 'Manifesto Text',
            'name' => 'about_manifesto_text',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'instructions' => 'Main content text for the manifesto section.',
        ),

        // TAB: Principles
        array(
            'key' => 'field_about_tab_principles',
            'label' => 'Principles',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_about_principles_title',
            'label' => 'Principles Section Title',
            'name' => 'about_principles_title',
            'type' => 'text',
            'default_value' => 'Our Principles',
            'instructions' => 'Title for the principles section.',
        ),
        array(
            'key' => 'field_about_principles',
            'label' => 'Principles Items',
            'name' => 'about_principles',
            'type' => 'repeater',
            'instructions' => 'Add your principles here.',
            'sub_fields' => array(
                array(
                    'key' => 'field_principle_icon',
                    'label' => 'Icon Class',
                    'name' => 'icon',
                    'type' => 'text',
                    'instructions' => 'Font Awesome icon class (e.g., fa-solid fa-leaf)',
                    'default_value' => 'fa-solid fa-leaf',
                ),
                array(
                    'key' => 'field_principle_title',
                    'label' => 'Title',
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => 'Principle title',
                ),
                array(
                    'key' => 'field_principle_text',
                    'label' => 'Description',
                    'name' => 'text',
                    'type' => 'textarea',
                    'instructions' => 'Principle description text',
                ),
            ),
        ),

        // TAB: Patronage
        array(
            'key' => 'field_about_tab_patronage',
            'label' => 'Patronage',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_patronage_eyebrow',
            'label' => 'Patronage Eyebrow Text',
            'name' => 'patronage_eyebrow',
            'type' => 'text',
            'default_value' => 'We share the profit because we share the planet',
            'instructions' => 'Eyebrow text above the patronage title.',
        ),
        array(
            'key' => 'field_patronage_title',
            'label' => 'Patronage Section Title',
            'name' => 'patronage_title',
            'type' => 'text',
            'default_value' => 'You\'re Not a Consumer.<br>You\'re a Patron.',
            'instructions' => 'Main title for the patronage section.',
        ),
         array(
            'key' => 'field_patronage_subtitle',
            'label' => 'Patronage Section Subtitle',
            'name' => 'patronage_subtitle',
            'type' => 'text',
            'default_value' => 'You\'re Not a Consumer.<br>You\'re a Patron.',
            'instructions' => 'Subtitle for the patronage section.',
        ),
        array(
            'key' => 'field_patronage_text',
            'label' => 'Patronage Section Content',
            'name' => 'patronage_text',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'instructions' => 'Main content text for the patronage section.',
        ),
        array(
            'key' => 'field_patronage_families',
            'label' => 'Families Supported Text',
            'name' => 'patronage_families',
            'type' => 'text',
            'default_value' => '"Every purchase supports 3-5 artisan families"',
            'instructions' => 'Text displayed in the diagram footer showing families supported.',
        ),

        // TAB: Story
        array(
            'key' => 'field_about_tab_story',
            'label' => 'Story',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_story_title',
            'label' => 'Story Section Title',
            'name' => 'story_title',
            'type' => 'text',
            'default_value' => 'How LOOMAAMA Began',
            'instructions' => 'Main title for the story section.',
        ),
        array(
            'key' => 'field_story_subtitle',
            'label' => 'Story Subtitle',
            'name' => 'story_subtitle',
            'type' => 'text',
            'default_value' => 'We are not made of plastic. Why are our clothes?',
            'instructions' => 'Subtitle text below the story title.',
        ),
        array(
            'key' => 'field_story_image',
            'label' => 'Story Image',
            'name' => 'story_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Image for the story section.',
        ),
        array(
            'key' => 'field_story_text',
            'label' => 'Story Content',
            'name' => 'story_text',
            'type' => 'wysiwyg',
            'toolbar' => 'full',
            'media_upload' => true,
            'instructions' => 'Main content text for the story section.',
        ),

        // TAB: Founder
        array(
            'key' => 'field_about_tab_founder',
            'label' => 'Founder',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_founder_eyebrow',
            'label' => 'Founder Eyebrow Text',
            'name' => 'founder_eyebrow',
            'type' => 'text',
            'default_value' => 'The Vision',
            'instructions' => 'Eyebrow text above the founder section.',
        ),
        array(
            'key' => 'field_founder_name',
            'label' => 'Founder Name',
            'name' => 'founder_name',
            'type' => 'text',
            'default_value' => 'Bini Karki',
            'instructions' => 'Full name of the founder.',
        ),
        array(
            'key' => 'field_founder_title',
            'label' => 'Founder Title',
            'name' => 'founder_title',
            'type' => 'text',
            'default_value' => 'Founder',
            'instructions' => 'Professional title of the founder.',
        ),
        array(
            'key' => 'field_founder_image',
            'label' => 'Founder Image',
            'name' => 'founder_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Portrait image of the founder.',
        ),
        array(
            'key' => 'field_founder_quote',
            'label' => 'Founder Quote / Message',
            'name' => 'founder_quote',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'instructions' => 'Quote or personal message from the founder.',
        ),

        // TAB: Non-Negotiables
        array(
            'key' => 'field_about_tab_non_negotiables',
            'label' => 'Non-Negotiables',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_non_negotiables_title',
            'label' => 'Non-Negotiables Title',
            'name' => 'non_negotiables_title',
            'type' => 'text',
            'default_value' => 'Our Non-Negotiables',
            'instructions' => 'Title for the non-negotiables section.',
        ),
        array(
            'key' => 'field_non_negotiables_about',
            'label' => 'Non-Negotiables List',
            'name' => 'non_negotiables_about',
            'type' => 'repeater',
            'instructions' => 'Add items that LOOMAAMA never compromises on.',
            'sub_fields' => array(
                array(
                    'key' => 'field_non_negotiable_about_item',
                    'label' => 'Non-Negotiable Item',
                    'name' => 'item',
                    'type' => 'text',
                    'instructions' => 'Text describing a non-negotiable value or practice.',
                ),
            ),
        ),

        // TAB: Closing & CTA
        array(
            'key' => 'field_about_tab_closing',
            'label' => 'Closing & CTA',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_closing_statement',
            'label' => 'Closing Statement',
            'name' => 'closing_statement',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'instructions' => 'Closing statement text at the bottom of the page.',
            'default_value' => '<h2 class="headline display-4 mb-4">This Feels Right.<br>You Already Know.</h2><p>If you have read this far, you have already felt it. That quiet recognition. That sense that this is what clothing was always supposed to be. Welcome to LOOMAAMA.</p>',
        ),
        array(
            'key' => 'field_cta_button_text',
            'label' => 'CTA Button Text',
            'name' => 'cta_button_text',
            'type' => 'text',
            'default_value' => 'Explore the Collection',
            'instructions' => 'Text for the final call-to-action button.',
        ),
        array(
            'key' => 'field_cta_button_url',
            'label' => 'CTA Button URL',
            'name' => 'cta_button_url',
            'type' => 'text',
            'default_value' => '/shop',
            'instructions' => 'URL for the final call-to-action button.',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-about.php',
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