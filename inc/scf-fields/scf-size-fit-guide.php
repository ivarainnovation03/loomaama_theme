<?php
/**
 * SCF Field Group: Size & Fit Guide
 *
 * @package Loomaama
 */

if (!function_exists('register_field_group')) {
    return;
}

register_field_group(array(
    'id' => 'acf_size_fit_guide',
    'title' => 'Size & Fit Guide Settings',
    'fields' => array(
        // TAB: Announcement
        array(
            'key' => 'field_size_tab_announcement',
            'label' => 'Announcement Bar',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_announcement_text',
            'label' => 'Announcement Text',
            'name' => 'size_announcement_text',
            'type' => 'text',
            'default_value' => 'LOOMAAMA Size & Fit Guide',
        ),

        // TAB: Hero
        array(
            'key' => 'field_size_tab_hero',
            'label' => 'Hero Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_hero_eyebrow',
            'label' => 'Hero Eyebrow',
            'name' => 'size_hero_eyebrow',
            'type' => 'text',
            'default_value' => 'Find Your Size',
        ),
        array(
            'key' => 'field_size_hero_title',
            'label' => 'Hero Title',
            'name' => 'size_hero_title',
            'type' => 'text',
            'default_value' => 'Find Your Natural Fit',
        ),
        array(
            'key' => 'field_size_hero_text',
            'label' => 'Hero Text',
            'name' => 'size_hero_text',
            'type' => 'textarea',
            'default_value' => 'Choose the size that lets your LOOMAAMA piece sit comfortably, move easily, and fall naturally.',
        ),
        array(
            'key' => 'field_size_hero_image',
            'label' => 'Hero Image',
            'name' => 'size_hero_image',
            'type' => 'image',
            'save_format' => 'url',
        ),

        // TAB: Categories
        array(
            'key' => 'field_size_tab_categories',
            'label' => 'Category Grid',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_categories',
            'label' => 'Category Items',
            'name' => 'size_categories',
            'type' => 'repeater',
            'instructions' => 'Add up to 5 category items',
            'sub_fields' => array(
                array(
                    'key' => 'field_size_category_name',
                    'label' => 'Category Name',
                    'name' => 'category_name',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_size_category_link',
                    'label' => 'Category Link',
                    'name' => 'category_link',
                    'type' => 'text',
                    'default_value' => '#',
                ),
                array(
                    'key' => 'field_size_category_image',
                    'label' => 'Category Image',
                    'name' => 'category_image',
                    'type' => 'image',
                    'save_format' => 'url',
                ),
            ),
        ),

        // TAB: Before You Choose
        array(
            'key' => 'field_size_tab_before',
            'label' => 'Before You Choose',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_before_eyebrow',
            'label' => 'Eyebrow Text',
            'name' => 'size_before_eyebrow',
            'type' => 'text',
            'default_value' => 'Before You Choose',
        ),
        array(
            'key' => 'field_size_before_title',
            'label' => 'Title',
            'name' => 'size_before_title',
            'type' => 'text',
            'default_value' => 'LOOMAAMA garments are made from Himalayan nettle cloth.',
        ),
        array(
            'key' => 'field_size_before_text',
            'label' => 'Description Text',
            'name' => 'size_before_text',
            'type' => 'textarea',
            'default_value' => 'The fabric has a natural texture, structure, and soft drape. Choose your usual size for a balanced fit. Size up if you prefer more room and a relaxed look. Choose a closer fit only for pieces made to sit near the body.',
        ),

        // TAB: Size Charts Header
        array(
            'key' => 'field_size_tab_charts_header',
            'label' => 'Size Charts Header',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_charts_eyebrow',
            'label' => 'Eyebrow Text',
            'name' => 'size_charts_eyebrow',
            'type' => 'text',
            'default_value' => 'Size Charts',
        ),
        array(
            'key' => 'field_size_charts_title',
            'label' => 'Title',
            'name' => 'size_charts_title',
            'type' => 'text',
            'default_value' => 'Use the size chart as a starting point.',
        ),
        array(
            'key' => 'field_size_charts_subtitle',
            'label' => 'Subtitle',
            'name' => 'size_charts_subtitle',
            'type' => 'text',
            'default_value' => 'For the best fit, also check the product measurements on each product page.',
        ),

        // TAB: Tops Chart
        array(
            'key' => 'field_size_tab_tops',
            'label' => 'Tops Chart',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_tops_title',
            'label' => 'Chart Title',
            'name' => 'size_tops_title',
            'type' => 'text',
            'default_value' => 'Tops, Shirts, Vests & Sleeveless Pieces',
        ),
        array(
            'key' => 'field_size_tops_note',
            'label' => 'Chart Note',
            'name' => 'size_tops_note',
            'type' => 'text',
            'default_value' => 'A good fit should sit comfortably on the shoulders and rest across the chest without pulling.',
        ),
        array(
            'key' => 'field_size_tops_chart',
            'label' => 'Tops Size Chart',
            'name' => 'size_tops_chart',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_size_tops_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_size_tops_bust', 'name' => 'bust', 'type' => 'text'),
                array('key' => 'field_size_tops_waist', 'name' => 'waist', 'type' => 'text'),
                array('key' => 'field_size_tops_shoulder', 'name' => 'shoulder', 'type' => 'text'),
                array('key' => 'field_size_tops_fit_feel', 'name' => 'fit_feel', 'type' => 'text'),
            ),
        ),

        // TAB: Bralettes Chart
        array(
            'key' => 'field_size_tab_bralettes',
            'label' => 'Bralettes Chart',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_bralettes_title',
            'label' => 'Chart Title',
            'name' => 'size_bralettes_title',
            'type' => 'text',
            'default_value' => 'Bralettes & Close-Fit Tops',
        ),
        array(
            'key' => 'field_size_bralettes_note',
            'label' => 'Chart Note',
            'name' => 'size_bralettes_note',
            'type' => 'text',
            'default_value' => 'These pieces should feel secure but not tight. The fabric should sit gently on the body without pressure.',
        ),
        array(
            'key' => 'field_size_bralettes_chart',
            'label' => 'Bralettes Size Chart',
            'name' => 'size_bralettes_chart',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_size_bralettes_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_size_bralettes_bust', 'name' => 'bust', 'type' => 'text'),
                array('key' => 'field_size_bralettes_underbust', 'name' => 'underbust', 'type' => 'text'),
                array('key' => 'field_size_bralettes_fit_feel', 'name' => 'fit_feel', 'type' => 'text'),
                array('key' => 'field_size_bralettes_best_for', 'name' => 'best_for', 'type' => 'text'),
            ),
        ),

        // TAB: Pants Chart
        array(
            'key' => 'field_size_tab_pants',
            'label' => 'Pants Chart',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_pants_title',
            'label' => 'Chart Title',
            'name' => 'size_pants_title',
            'type' => 'text',
            'default_value' => 'Pants, Trousers & Shorts',
        ),
        array(
            'key' => 'field_size_pants_note',
            'label' => 'Chart Note',
            'name' => 'size_pants_note',
            'type' => 'text',
            'default_value' => 'Choose your size mainly by hip measurement. The garment should feel easy when you sit, walk, and move.',
        ),
        array(
            'key' => 'field_size_pants_chart',
            'label' => 'Pants Size Chart',
            'name' => 'size_pants_chart',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_size_pants_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_size_pants_waist', 'name' => 'waist', 'type' => 'text'),
                array('key' => 'field_size_pants_hip', 'name' => 'hip', 'type' => 'text'),
                array('key' => 'field_size_pants_inseam', 'name' => 'inseam', 'type' => 'text'),
                array('key' => 'field_size_pants_fit_feel', 'name' => 'fit_feel', 'type' => 'text'),
            ),
        ),

        // TAB: Dresses Chart
        array(
            'key' => 'field_size_tab_dresses',
            'label' => 'Dresses Chart',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_dresses_title',
            'label' => 'Chart Title',
            'name' => 'size_dresses_title',
            'type' => 'text',
            'default_value' => 'Dresses, Skirts & One-Piece Garments',
        ),
        array(
            'key' => 'field_size_dresses_note',
            'label' => 'Chart Note',
            'name' => 'size_dresses_note',
            'type' => 'text',
            'default_value' => 'Follow the measurement that needs the most room: bust, waist, or hip. The garment should fall naturally without pulling.',
        ),
        array(
            'key' => 'field_size_dresses_chart',
            'label' => 'Dresses Size Chart',
            'name' => 'size_dresses_chart',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_size_dresses_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_size_dresses_bust', 'name' => 'bust', 'type' => 'text'),
                array('key' => 'field_size_dresses_waist', 'name' => 'waist', 'type' => 'text'),
                array('key' => 'field_size_dresses_hip', 'name' => 'hip', 'type' => 'text'),
                array('key' => 'field_size_dresses_fit_feel', 'name' => 'fit_feel', 'type' => 'text'),
            ),
        ),

        // TAB: Outer Layers Chart
        array(
            'key' => 'field_size_tab_outer',
            'label' => 'Outer Layers Chart',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_outer_title',
            'label' => 'Chart Title',
            'name' => 'size_outer_title',
            'type' => 'text',
            'default_value' => 'Outer Layers, Ponchos & Wraps',
        ),
        array(
            'key' => 'field_size_outer_note',
            'label' => 'Chart Note',
            'name' => 'size_outer_note',
            'type' => 'text',
            'default_value' => 'Outer layers are made for comfort and movement. Size up if you want to layer over another garment.',
        ),
        array(
            'key' => 'field_size_outer_chart',
            'label' => 'Outer Layers Size Chart',
            'name' => 'size_outer_chart',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_size_outer_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_size_outer_bust', 'name' => 'bust', 'type' => 'text'),
                array('key' => 'field_size_outer_shoulder', 'name' => 'shoulder', 'type' => 'text'),
                array('key' => 'field_size_outer_best_use', 'name' => 'best_use', 'type' => 'text'),
                array('key' => 'field_size_outer_fit_feel', 'name' => 'fit_feel', 'type' => 'text'),
            ),
        ),

        // TAB: Accessories Chart
        array(
            'key' => 'field_size_tab_accessories',
            'label' => 'Accessories Chart',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_accessories_title',
            'label' => 'Chart Title',
            'name' => 'size_accessories_title',
            'type' => 'text',
            'default_value' => 'Shawls, Scarves, Bags, Towels & Slippers',
        ),
        array(
            'key' => 'field_size_accessories_note',
            'label' => 'Chart Note',
            'name' => 'size_accessories_note',
            'type' => 'text',
            'default_value' => 'For accessories, check the product dimensions. For shawls and scarves, choose by coverage. For bags, check strap length and size. For slippers, choose by foot length and size up if between sizes.',
        ),
        array(
            'key' => 'field_size_accessories_chart',
            'label' => 'Accessories Size Chart',
            'name' => 'size_accessories_chart',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_size_accessories_product', 'name' => 'product', 'type' => 'text'),
                array('key' => 'field_size_accessories_guide', 'name' => 'guide', 'type' => 'text'),
                array('key' => 'field_size_accessories_best_for', 'name' => 'best_for', 'type' => 'text'),
                array('key' => 'field_size_accessories_fit_note', 'name' => 'fit_note', 'type' => 'text'),
            ),
        ),

        // TAB: Gallery
        array(
            'key' => 'field_size_tab_gallery',
            'label' => 'Gallery',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_gallery',
            'label' => 'Gallery Images',
            'name' => 'size_gallery',
            'type' => 'repeater',
            'sub_fields' => array(
                array(
                    'key' => 'field_size_gallery_image',
                    'label' => 'Image',
                    'name' => 'image',
                    'type' => 'image',
                    'save_format' => 'url',
                ),
            ),
        ),

        // TAB: Measurement Guide
        array(
            'key' => 'field_size_tab_measurement',
            'label' => 'Measurement Guide',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_measure_eyebrow',
            'label' => 'Eyebrow Text',
            'name' => 'size_measure_eyebrow',
            'type' => 'text',
            'default_value' => 'Measurement Guide',
        ),
        array(
            'key' => 'field_size_measure_title',
            'label' => 'Title',
            'name' => 'size_measure_title',
            'type' => 'text',
            'default_value' => 'Measurement Guide',
        ),
        array(
            'key' => 'field_size_measure_image',
            'label' => 'Measurement Image',
            'name' => 'size_measure_image',
            'type' => 'image',
            'save_format' => 'url',
        ),
        array(
            'key' => 'field_size_measurements',
            'label' => 'Measurement Items',
            'name' => 'size_measurements',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_size_measure_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_size_measure_description', 'name' => 'description', 'type' => 'text'),
            ),
        ),

        // TAB: Fit Feeling
        array(
            'key' => 'field_size_tab_fit',
            'label' => 'Fit Feeling',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_fit_eyebrow',
            'label' => 'Eyebrow Text',
            'name' => 'size_fit_eyebrow',
            'type' => 'text',
            'default_value' => 'Fit Feeling',
        ),
        array(
            'key' => 'field_size_fit_title',
            'label' => 'Title',
            'name' => 'size_fit_title',
            'type' => 'text',
            'default_value' => 'Fit Feeling',
        ),
        array(
            'key' => 'field_size_fit_types',
            'label' => 'Fit Types',
            'name' => 'size_fit_types',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_size_fit_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_size_fit_description', 'name' => 'description', 'type' => 'textarea'),
            ),
        ),

        // TAB: Handmade Variation
        array(
            'key' => 'field_size_tab_handmade',
            'label' => 'Handmade Variation',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_handmade_eyebrow',
            'label' => 'Eyebrow Text',
            'name' => 'size_handmade_eyebrow',
            'type' => 'text',
            'default_value' => 'Handmade Variation',
        ),
        array(
            'key' => 'field_size_handmade_title',
            'label' => 'Title',
            'name' => 'size_handmade_title',
            'type' => 'text',
            'default_value' => 'Every LOOMAAMA piece is made through hand-guided work.',
        ),
        array(
            'key' => 'field_size_handmade_text',
            'label' => 'Description Text',
            'name' => 'size_handmade_text',
            'type' => 'textarea',
            'default_value' => 'Small differences in size, texture, and fall are natural. These are not flaws. They are part of handmade nettle cloth.',
        ),
        array(
            'key' => 'field_size_handmade_image',
            'label' => 'Handmade Image',
            'name' => 'size_handmade_image',
            'type' => 'image',
            'save_format' => 'url',
        ),

        // TAB: Help Section
        array(
            'key' => 'field_size_tab_help',
            'label' => 'Help Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_size_help_eyebrow',
            'label' => 'Eyebrow Text',
            'name' => 'size_help_eyebrow',
            'type' => 'text',
            'default_value' => 'Personal Fit Help',
        ),
        array(
            'key' => 'field_size_help_title',
            'label' => 'Title',
            'name' => 'size_help_title',
            'type' => 'text',
            'default_value' => 'Still unsure?',
        ),
        array(
            'key' => 'field_size_help_text',
            'label' => 'Description Text',
            'name' => 'size_help_text',
            'type' => 'textarea',
            'default_value' => 'Send us your measurements and the product name. We will help you choose the best size for your body and the garment.',
        ),
        array(
            'key' => 'field_size_help_cards',
            'label' => 'Help Cards',
            'name' => 'size_help_cards',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_size_help_card_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_size_help_card_description', 'name' => 'description', 'type' => 'text'),
                array('key' => 'field_size_help_card_button_text', 'name' => 'button_text', 'type' => 'text'),
                array('key' => 'field_size_help_card_button_link', 'name' => 'button_link', 'type' => 'text'),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-size-fit-guide.php',
            ),
        ),
    ),
    'options' => array(
        'position' => 'normal',
        'layout' => 'no_box',
        'hide_on_screen' => array(),
    ),
));