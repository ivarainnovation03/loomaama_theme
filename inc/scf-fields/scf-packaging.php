<?php
/**
 * SCF Field Group: Shipping & Packaging
 * Updated with packaging.html design fields
 *
 * @package Loomaama
 */

if (!function_exists('register_field_group')) {
    return;
}

register_field_group(array(
    'id' => 'acf_shipping_packaging',
    'title' => 'Shipping & Packaging Page Settings',
    'fields' => array(
        // TAB: Hero
        array(
            'key' => 'field_shipping_tab_hero',
            'label' => 'Hero Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_shipping_hero_eyebrow',
            'label' => 'Hero Eyebrow Text',
            'name' => 'shipping_hero_eyebrow',
            'type' => 'text',
            'default_value' => 'Packaging',
        ),
        array(
            'key' => 'field_shipping_hero_title',
            'label' => 'Hero Title',
            'name' => 'shipping_hero_title',
            'type' => 'text',
            'default_value' => 'The Last Hand',
        ),
        array(
            'key' => 'field_shipping_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'shipping_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'Nothing is rushed. Nothing is excessive. Only the final care a rare cloth deserves.',
        ),
        array(
            'key' => 'field_shipping_hero_layer_a',
            'label' => 'Hero Layer A Image',
            'name' => 'shipping_hero_layer_a',
            'type' => 'image',
            'save_format' => 'url',
        ),
        array(
            'key' => 'field_shipping_hero_layer_b',
            'label' => 'Hero Layer B Image',
            'name' => 'shipping_hero_layer_b',
            'type' => 'image',
            'save_format' => 'url',
        ),
         array(
            'key' => 'field_shipping_hero_layer_c',
            'label' => 'Hero Layer C Image',
            'name' => 'shipping_hero_layer_c',
            'type' => 'image',
            'save_format' => 'url',
        ),

        // TAB: Manifesto
        array(
            'key' => 'field_shipping_tab_manifesto',
            'label' => 'Manifesto Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_manifesto_eyebrow',
            'label' => 'Manifesto Eyebrow',
            'name' => 'manifesto_eyebrow',
            'type' => 'text',
            'default_value' => 'Packaging Philosophy',
        ),
        array(
            'key' => 'field_manifesto_text',
            'label' => 'Manifesto Text',
            'name' => 'manifesto_text',
            'type' => 'textarea',
            'default_value' => 'The wrapping is quiet because the garment carries the story.',
        ),
        array(
            'key' => 'field_manifesto_image',
            'label' => 'Manifesto Image',
            'name' => 'manifesto_image',
            'type' => 'image',
            'save_format' => 'url',
        ),

        // TAB: Packaging (Original - kept)
        array(
            'key' => 'field_shipping_tab_packaging_original',
            'label' => 'Packaging Section (Original)',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_packaging_eyebrow',
            'label' => 'Packaging Eyebrow Text',
            'name' => 'packaging_eyebrow',
            'type' => 'text',
            'default_value' => 'Earth-Bound Packaging',
        ),
        array(
            'key' => 'field_packaging_title',
            'label' => 'Packaging Title',
            'name' => 'packaging_title',
            'type' => 'text',
            'default_value' => 'Wrapped in the Forest.',
        ),
        array(
            'key' => 'field_packaging_description',
            'label' => 'Packaging Description',
            'name' => 'packaging_description',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'default_value' => '<p>We do not wrap natural masterpieces in synthetic plastic. Your garment arrives in a breathable sanctuary.</p><p>We use handmade Nepalese Lokta paper, secured with raw hemp twine, inside a fully compostable mailer.</p>',
        ),
        array(
            'key' => 'field_packaging_image',
            'label' => 'Packaging Image',
            'name' => 'packaging_image',
            'type' => 'image',
            'save_format' => 'url',
        ),
        array(
            'key' => 'field_packaging_image_alt',
            'label' => 'Packaging Image Alt Text',
            'name' => 'packaging_image_alt',
            'type' => 'text',
            'default_value' => 'Eco-friendly parcel wrapped in string',
        ),

        // TAB: Anatomy
        array(
            'key' => 'field_shipping_tab_anatomy',
            'label' => 'Anatomy Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_anatomy_eyebrow',
            'label' => 'Anatomy Eyebrow',
            'name' => 'anatomy_eyebrow',
            'type' => 'text',
            'default_value' => 'Anatomy of Arrival',
        ),
        array(
            'key' => 'field_anatomy_title',
            'label' => 'Anatomy Title',
            'name' => 'anatomy_title',
            'type' => 'text',
            'default_value' => 'Every layer has a job.',
        ),
        array(
            'key' => 'field_anatomy_callout_1',
            'label' => 'Callout 1',
            'name' => 'anatomy_callout_1',
            'type' => 'text',
            'default_value' => 'Outer Protection',
        ),
        array(
            'key' => 'field_anatomy_callout_2',
            'label' => 'Callout 2',
            'name' => 'anatomy_callout_2',
            'type' => 'text',
            'default_value' => 'Soft Inner Layer',
        ),
        array(
            'key' => 'field_anatomy_callout_3',
            'label' => 'Callout 3',
            'name' => 'anatomy_callout_3',
            'type' => 'text',
            'default_value' => 'Garment Resting Space',
        ),
        array(
            'key' => 'field_anatomy_items',
            'label' => 'Anatomy Items',
            'name' => 'anatomy_items',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_anatomy_item_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_anatomy_item_description', 'name' => 'description', 'type' => 'textarea'),
            ),
        ),

        // TAB: Tactile Grid
        array(
            'key' => 'field_shipping_tab_tactile',
            'label' => 'Tactile Grid',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_tactile_items',
            'label' => 'Tactile Items',
            'name' => 'tactile_items',
            'type' => 'repeater',
            'max' => 4,
            'sub_fields' => array(
                array('key' => 'field_tactile_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_tactile_image', 'name' => 'image', 'type' => 'image', 'save_format' => 'url'),
            ),
        ),

        // TAB: Receipt / What We Choose
        array(
            'key' => 'field_shipping_tab_receipt',
            'label' => 'What We Choose Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_receipt_eyebrow',
            'label' => 'Receipt Eyebrow',
            'name' => 'receipt_eyebrow',
            'type' => 'text',
            'default_value' => 'What We Choose',
        ),
        array(
            'key' => 'field_receipt_title',
            'label' => 'Receipt Title',
            'name' => 'receipt_title',
            'type' => 'text',
            'default_value' => 'A quieter kind of luxury.',
        ),
        array(
            'key' => 'field_receipt_text',
            'label' => 'Receipt Text',
            'name' => 'receipt_text',
            'type' => 'textarea',
            'default_value' => 'Each layer is chosen with care, so the garment arrives protected, composed, and ready to be lived in.',
        ),
        array(
            'key' => 'field_receipt_items',
            'label' => 'Receipt Items',
            'name' => 'receipt_items',
            'type' => 'repeater',
            'sub_fields' => array(
                array('key' => 'field_receipt_label', 'name' => 'label', 'type' => 'text'),
                array('key' => 'field_receipt_value', 'name' => 'value', 'type' => 'text'),
            ),
        ),

        // TAB: Large Text Quote
        array(
            'key' => 'field_shipping_tab_largetext',
            'label' => 'Large Text Quote',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_large_text_quote',
            'label' => 'Quote Text',
            'name' => 'large_text_quote',
            'type' => 'textarea',
            'default_value' => 'The first touch should feel as considered as the last stitch.',
        ),

        // TAB: Global Shipping (Original - kept)
        array(
            'key' => 'field_shipping_tab_global',
            'label' => 'Global Shipping',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_shipping_eyebrow',
            'label' => 'Shipping Eyebrow Text',
            'name' => 'shipping_eyebrow',
            'type' => 'text',
            'default_value' => 'Borderless Patronage',
        ),
        array(
            'key' => 'field_shipping_title',
            'label' => 'Shipping Title',
            'name' => 'shipping_title',
            'type' => 'text',
            'default_value' => 'Delivered with Grace.',
        ),
        array(
            'key' => 'field_shipping_description',
            'label' => 'Shipping Description',
            'name' => 'shipping_description',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'default_value' => '<p>True luxury does not penalize distance. We offer <strong class="text-white">complimentary global shipping</strong> on all orders.</p><p>You will receive full tracking details to follow its path from Kathmandu directly to your door.</p>',
        ),

        // TAB: Timeline (Original - kept)
        array(
            'key' => 'field_shipping_tab_timeline',
            'label' => 'Timeline',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_timeline_eyebrow',
            'label' => 'Timeline Eyebrow Text',
            'name' => 'timeline_eyebrow',
            'type' => 'text',
            'default_value' => 'The Pace of the Forest',
        ),
        array(
            'key' => 'field_timeline_title',
            'label' => 'Timeline Title',
            'name' => 'timeline_title',
            'type' => 'text',
            'default_value' => 'The Anticipation of Craft.',
        ),
        array(
            'key' => 'field_timeline_description',
            'label' => 'Timeline Description',
            'name' => 'timeline_description',
            'type' => 'textarea',
            'default_value' => 'LOOMAAMA operates on the rhythm of the loom, not the speed of an algorithm. True craft takes patience.',
        ),
        array(
            'key' => 'field_ready_harvest_icon',
            'label' => 'Ready Harvest Icon',
            'name' => 'ready_harvest_icon',
            'type' => 'text',
            'default_value' => 'fa-solid fa-box-open',
        ),
        array(
            'key' => 'field_ready_harvest_title',
            'label' => 'Ready Harvest Title',
            'name' => 'ready_harvest_title',
            'type' => 'text',
            'default_value' => 'Ready Harvest',
        ),
        array(
            'key' => 'field_ready_harvest_text',
            'label' => 'Ready Harvest Text',
            'name' => 'ready_harvest_text',
            'type' => 'textarea',
            'default_value' => 'Items currently in our studio, crafted from recent harvests, will depart Kathmandu within <strong class="text-dark">48 hours</strong> of your purchase.',
        ),
        array(
            'key' => 'field_made_to_order_icon',
            'label' => 'Made to Order Icon',
            'name' => 'made_to_order_icon',
            'type' => 'text',
            'default_value' => 'fa-solid fa-hourglass-half',
        ),
        array(
            'key' => 'field_made_to_order_title',
            'label' => 'Made to Order Title',
            'name' => 'made_to_order_title',
            'type' => 'text',
            'default_value' => 'Made to Order',
        ),
        array(
            'key' => 'field_made_to_order_text',
            'label' => 'Made to Order Text',
            'name' => 'made_to_order_text',
            'type' => 'textarea',
            'default_value' => 'For pieces that require dedicated weaving upon request, we will communicate the <strong class="text-dark">exact timeline</strong> to you immediately following your purchase.',
        ),

        // TAB: Path / Journey
        array(
            'key' => 'field_shipping_tab_path',
            'label' => 'Path / Journey Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_path_steps',
            'label' => 'Path Steps',
            'name' => 'path_steps',
            'type' => 'repeater',
            'max' => 5,
            'sub_fields' => array(
                array('key' => 'field_path_number', 'name' => 'number', 'type' => 'text'),
                array('key' => 'field_path_title', 'name' => 'title', 'type' => 'text'),
                array('key' => 'field_path_description', 'name' => 'description', 'type' => 'textarea'),
            ),
        ),

        // TAB: Final Section
        array(
            'key' => 'field_shipping_tab_final',
            'label' => 'Final Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_final_eyebrow',
            'label' => 'Final Eyebrow',
            'name' => 'final_eyebrow',
            'type' => 'text',
            'default_value' => 'Packaging Statement',
        ),
        array(
            'key' => 'field_final_title',
            'label' => 'Final Title',
            'name' => 'final_title',
            'type' => 'text',
            'default_value' => 'The garment is the centre.',
        ),
        array(
            'key' => 'field_final_text',
            'label' => 'Final Text',
            'name' => 'final_text',
            'type' => 'textarea',
            'default_value' => 'The garment remains the focus. Everything around it exists only to protect, preserve, and honour what has already been made.',
        ),
        array(
            'key' => 'field_final_button_text',
            'label' => 'Final Button Text',
            'name' => 'final_button_text',
            'type' => 'text',
            'default_value' => 'Become a Witness',
        ),
        array(
            'key' => 'field_final_button_link',
            'label' => 'Final Button Link',
            'name' => 'final_button_link',
            'type' => 'text',
            'default_value' => '/shop',
        ),
        array(
            'key' => 'field_final_image',
            'label' => 'Final Image',
            'name' => 'final_image',
            'type' => 'image',
            'save_format' => 'url',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-packaging.php',
            ),
        ),
    ),
    'options' => array(
        'position' => 'normal',
        'layout' => 'no_box',
        'hide_on_screen' => array(),
    ),
));