<?php
/**
 * SCF Field Group: Shipping & Packaging
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
            'default_value' => 'The Mountain Pays the Passage',
            'instructions' => 'Small eyebrow text above the hero title',
        ),
        array(
            'key' => 'field_shipping_hero_title',
            'label' => 'Hero Title',
            'name' => 'shipping_hero_title',
            'type' => 'text',
            'default_value' => 'The Final Journey.',
            'instructions' => 'Main title for the hero section',
        ),
        array(
            'key' => 'field_shipping_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'shipping_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'How your garment travels from the Himalayas to your home. Zero plastic. Zero rush. Just quiet intention.',
            'instructions' => 'Subtitle text for the hero section',
        ),
        array(
            'key' => 'field_shipping_hero_image',
            'label' => 'Hero Background Image',
            'name' => 'shipping_hero_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Background image for the hero section',
        ),

        // TAB: Packaging
        array(
            'key' => 'field_shipping_tab_packaging',
            'label' => 'Packaging',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_packaging_eyebrow',
            'label' => 'Packaging Eyebrow Text',
            'name' => 'packaging_eyebrow',
            'type' => 'text',
            'default_value' => 'Earth-Bound Packaging',
            'instructions' => 'Eyebrow text above the packaging title',
        ),
        array(
            'key' => 'field_packaging_title',
            'label' => 'Packaging Title',
            'name' => 'packaging_title',
            'type' => 'text',
            'default_value' => 'Wrapped in the Forest.',
            'instructions' => 'Main title for the packaging section',
        ),
        array(
            'key' => 'field_packaging_description',
            'label' => 'Packaging Description',
            'name' => 'packaging_description',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'default_value' => '<p>We do not wrap natural masterpieces in synthetic plastic. Your garment arrives in a breathable sanctuary.</p><p>We use handmade Nepalese Lokta paper, secured with raw hemp twine, inside a fully compostable mailer. When you open your parcel, you will feel the texture of the mountains before you even touch the garment.</p>',
            'instructions' => 'Main content for the packaging section',
        ),
        array(
            'key' => 'field_packaging_image',
            'label' => 'Packaging Image',
            'name' => 'packaging_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Image showing eco-friendly packaging',
        ),
        array(
            'key' => 'field_packaging_image_alt',
            'label' => 'Packaging Image Alt Text',
            'name' => 'packaging_image_alt',
            'type' => 'text',
            'default_value' => 'Eco-friendly parcel wrapped in string',
            'instructions' => 'Alt text for the packaging image',
        ),

        // TAB: Shipping
        array(
            'key' => 'field_shipping_tab_shipping',
            'label' => 'Global Shipping',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_shipping_eyebrow',
            'label' => 'Shipping Eyebrow Text',
            'name' => 'shipping_eyebrow',
            'type' => 'text',
            'default_value' => 'Borderless Patronage',
            'instructions' => 'Eyebrow text above the shipping title',
        ),
        array(
            'key' => 'field_shipping_title',
            'label' => 'Shipping Title',
            'name' => 'shipping_title',
            'type' => 'text',
            'default_value' => 'Delivered with Grace.',
            'instructions' => 'Main title for the shipping section',
        ),
        array(
            'key' => 'field_shipping_description',
            'label' => 'Shipping Description',
            'name' => 'shipping_description',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'default_value' => '<p>True luxury does not penalize distance. We offer <strong class="text-white">complimentary global shipping</strong> on all orders.</p><p>Your investment in our artisans covers the entire journey. You will receive full tracking details to follow its path from Kathmandu directly to your door.</p>',
            'instructions' => 'Main content for the shipping section',
        ),

        // TAB: Timeline
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
            'instructions' => 'Eyebrow text above the timeline title',
        ),
        array(
            'key' => 'field_timeline_title',
            'label' => 'Timeline Title',
            'name' => 'timeline_title',
            'type' => 'text',
            'default_value' => 'The Anticipation of Craft.',
            'instructions' => 'Main title for the timeline section',
        ),
        array(
            'key' => 'field_timeline_description',
            'label' => 'Timeline Description',
            'name' => 'timeline_description',
            'type' => 'textarea',
            'default_value' => 'LOOMAAMA operates on the rhythm of the loom, not the speed of an algorithm. True craft takes patience.',
            'instructions' => 'Descriptive text below the timeline title',
        ),
        array(
            'key' => 'field_ready_harvest_icon',
            'label' => 'Ready Harvest Icon',
            'name' => 'ready_harvest_icon',
            'type' => 'text',
            'default_value' => 'fa-solid fa-box-open',
            'instructions' => 'Font Awesome icon class for Ready Harvest card',
        ),
        array(
            'key' => 'field_ready_harvest_title',
            'label' => 'Ready Harvest Title',
            'name' => 'ready_harvest_title',
            'type' => 'text',
            'default_value' => 'Ready Harvest',
            'instructions' => 'Title for the Ready Harvest card',
        ),
        array(
            'key' => 'field_ready_harvest_text',
            'label' => 'Ready Harvest Text',
            'name' => 'ready_harvest_text',
            'type' => 'textarea',
            'default_value' => 'Items currently in our studio, crafted from recent harvests, will depart Kathmandu within <strong class="text-dark">48 hours</strong> of your purchase.',
            'instructions' => 'Description for the Ready Harvest card',
        ),
        array(
            'key' => 'field_made_to_order_icon',
            'label' => 'Made to Order Icon',
            'name' => 'made_to_order_icon',
            'type' => 'text',
            'default_value' => 'fa-solid fa-hourglass-half',
            'instructions' => 'Font Awesome icon class for Made to Order card',
        ),
        array(
            'key' => 'field_made_to_order_title',
            'label' => 'Made to Order Title',
            'name' => 'made_to_order_title',
            'type' => 'text',
            'default_value' => 'Made to Order',
            'instructions' => 'Title for the Made to Order card',
        ),
        array(
            'key' => 'field_made_to_order_text',
            'label' => 'Made to Order Text',
            'name' => 'made_to_order_text',
            'type' => 'textarea',
            'default_value' => 'For pieces that require dedicated weaving upon request, we will communicate the <strong class="text-dark">exact timeline</strong> to you immediately following your purchase.',
            'instructions' => 'Description for the Made to Order card',
        ),

        // TAB: Returns
        array(
            'key' => 'field_shipping_tab_returns',
            'label' => 'Returns',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_returns_eyebrow',
            'label' => 'Returns Eyebrow Text',
            'name' => 'returns_eyebrow',
            'type' => 'text',
            'default_value' => 'Returning to the Source',
            'instructions' => 'Eyebrow text above the returns title',
        ),
        array(
            'key' => 'field_returns_title',
            'label' => 'Returns Title',
            'name' => 'returns_title',
            'type' => 'text',
            'default_value' => 'Honest Returns.',
            'instructions' => 'Main title for the returns section',
        ),
        array(
            'key' => 'field_returns_description',
            'label' => 'Returns Description',
            'name' => 'returns_description',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'default_value' => '<p>We want your LOOMAAMA piece to feel completely right. We accept unworn, unwashed returns within <strong class="text-dark">14 days</strong> of arrival.</p><p>Because we do not rely on automated warehouses, we handle every return personally. We simply ask that you fold and return the item with the same care and respect in which it was sent.</p>',
            'instructions' => 'Main content for the returns section',
        ),
        array(
            'key' => 'field_returns_button_text',
            'label' => 'Returns Button Text',
            'name' => 'returns_button_text',
            'type' => 'text',
            'default_value' => 'Initiate a Return',
            'instructions' => 'Text for the return button',
        ),
        array(
            'key' => 'field_returns_button_link',
            'label' => 'Returns Button Link',
            'name' => 'returns_button_link',
            'type' => 'text',
            'default_value' => '/my-account',
            'instructions' => 'URL for the return button',
        ),
        array(
            'key' => 'field_returns_image',
            'label' => 'Returns Image',
            'name' => 'returns_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Image for the returns section',
        ),
        array(
            'key' => 'field_returns_image_alt',
            'label' => 'Returns Image Alt Text',
            'name' => 'returns_image_alt',
            'type' => 'text',
            'default_value' => 'Folded natural fabric',
            'instructions' => 'Alt text for the returns image',
        ),

        // TAB: Closing CTA
        array(
            'key' => 'field_shipping_tab_closing',
            'label' => 'Closing CTA',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_closing_eyebrow',
            'label' => 'Closing Eyebrow Text',
            'name' => 'closing_eyebrow',
            'type' => 'text',
            'default_value' => 'Your Journey Awaits',
            'instructions' => 'Eyebrow text above the closing title (optional)',
        ),
        array(
            'key' => 'field_closing_title',
            'label' => 'Closing CTA Title',
            'name' => 'closing_title',
            'type' => 'text',
            'default_value' => 'Ready to Bring the Wild Home?',
            'instructions' => 'Main title for the closing CTA section',
        ),
        array(
            'key' => 'field_closing_text',
            'label' => 'Closing CTA Text',
            'name' => 'closing_text',
            'type' => 'textarea',
            'default_value' => 'Experience the tactile truth of pure Himalayan fiber. Delivered globally, with grace.',
            'instructions' => 'Descriptive text for the closing CTA section',
        ),
        array(
            'key' => 'field_closing_button_text',
            'label' => 'Closing Button Text',
            'name' => 'closing_button_text',
            'type' => 'text',
            'default_value' => 'Shop the Collection',
            'instructions' => 'Text for the closing CTA button',
        ),
        array(
            'key' => 'field_closing_button_link',
            'label' => 'Closing Button Link',
            'name' => 'closing_button_link',
            'type' => 'text',
            'default_value' => '/shop',
            'instructions' => 'URL for the closing CTA button',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-shipping-packaging.php',
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