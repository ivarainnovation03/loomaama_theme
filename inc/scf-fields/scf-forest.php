<?php
/**
 * SCF Field Group: Forest (Materials)
 * 
 * @package Loomaama
 */

if (!function_exists('register_field_group')) {
    return;
}

register_field_group(array(
    'id' => 'acf_forest',
    'title' => 'Forest (Materials) Page Settings',
    'fields' => array(
        // TAB: Hero
        array(
            'key' => 'field_forest_tab_hero',
            'label' => 'Hero Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_forest_hero_eyebrow',
            'label' => 'Hero Eyebrow Text',
            'name' => 'forest_hero_eyebrow',
            'type' => 'text',
            'default_value' => 'Nature Doesn\'t Do Synthetic. Neither Do We.',
            'instructions' => 'Small eyebrow text above the hero title',
        ),
        array(
            'key' => 'field_forest_hero_title',
            'label' => 'Hero Title',
            'name' => 'forest_hero_title',
            'type' => 'text',
            'default_value' => 'The Forest is our CEO.',
            'instructions' => 'Main title for the hero section',
        ),
        array(
            'key' => 'field_forest_hero_subheading',
            'label' => 'Hero Subheading',
            'name' => 'forest_hero_subheading',
            'type' => 'text',
            'default_value' => 'If it cannot return to the soil, it does not belong on our skin.',
            'instructions' => 'Subheading line below the main title',
        ),
        array(
            'key' => 'field_forest_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'forest_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'Born from the earth. Made for your skin. Destined to return.<br>Every fibre we use is grown with patience, harvested with care, and designed to leave nothing behind. No plastic. No chemicals. Just nature doing what it has always done best, and real people honoured every step of the way.',
            'instructions' => 'Paragraph text for the hero section',
        ),
        array(
            'key' => 'field_forest_hero_image',
            'label' => 'Hero Background Image',
            'name' => 'forest_hero_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Background image for the hero section',
        ),
         array(
            'key' => 'field_forest_hero_video',
            'label' => 'Hero Background Video',
            'name' => 'forest_hero_video',
            'type' => 'file',
            'save_format' => 'url',
            'instructions' => 'Background video for the hero section',
        ),

        // TAB: Introduction
        array(
            'key' => 'field_forest_tab_intro',
            'label' => 'Introduction',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_materials_intro_eyebrow',
            'label' => 'Materials Intro Eyebrow',
            'name' => 'materials_intro_eyebrow',
            'type' => 'text',
            'default_value' => 'Nature Made Your Skin. It Made Your Clothing Too.',
            'instructions' => 'Eyebrow text above the introduction title',
        ),
        array(
            'key' => 'field_materials_intro_title',
            'label' => 'Materials Intro Title',
            'name' => 'materials_intro_title',
            'type' => 'text',
            'default_value' => 'What Touches Your Skin Matters.',
            'instructions' => 'Main title for the introduction section',
        ),
        array(
            'key' => 'field_materials_intro_subtitle',
            'label' => 'Materials Intro Subtitle',
            'name' => 'materials_intro_subtitle',
            'type' => 'text',
            'default_value' => 'Your Skin Has Been Waiting for This.',
            'instructions' => 'Subtitle for the introduction section',
        ),
        array(
            'key' => 'field_materials_intro_text',
            'label' => 'Materials Intro Text',
            'name' => 'materials_intro_text',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'default_value' => '<p>Natural fibres carry an intelligence that nature spent thousands of years perfecting. They breathe when your body needs air, warm when it needs comfort, and rest against your skin without leaving anything harmful behind. No toxins. No resistance. Just a quiet, effortless harmony your body has always been waiting for.</p><p class="fw-medium">Wearing LOOMAAMA is not a style switch. It is your body finally getting what it always deserved.</p>',
            'instructions' => 'Main content for the introduction section',
        ),
        array(
            'key' => 'field_stat_callout',
            'label' => 'Stat Callout Text',
            'name' => 'stat_callout',
            'type' => 'textarea',
            'default_value' => '"Synthetic clothing sheds 700,000 microplastic fibers per wash."',
            'instructions' => 'Statistical fact displayed in the callout box',
        ),

        // TAB: Materials
        array(
            'key' => 'field_forest_tab_materials',
            'label' => 'Materials',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_materials',
            'label' => 'Materials',
            'name' => 'materials',
            'type' => 'repeater',
            'instructions' => 'Add materials featured on this page (e.g., Nettle, Hemp, Cotton, Wool)',
            'sub_fields' => array(
                array(
                    'key' => 'field_material_name',
                    'label' => 'Material Name',
                    'name' => 'name',
                    'type' => 'text',
                    'instructions' => 'Name of the material (e.g., Wild Nettle, Hemp)',
                ),
                array(
                    'key' => 'field_material_eyebrow',
                    'label' => 'Eyebrow Text',
                    'name' => 'eyebrow',
                    'type' => 'text',
                    'instructions' => 'Eyebrow text above the heading',
                ),
                array(
                    'key' => 'field_material_heading',
                    'label' => 'Heading',
                    'name' => 'heading',
                    'type' => 'text',
                    'instructions' => 'Main heading for this material section',
                ),
                array(
                    'key' => 'field_material_bold_text',
                    'label' => 'Bold Text',
                    'name' => 'bold_text',
                    'type' => 'textarea',
                    'instructions' => 'Bold emphasis text displayed after the heading',
                ),
                array(
                    'key' => 'field_material_description',
                    'label' => 'Description',
                    'name' => 'description',
                    'type' => 'wysiwyg',
                    'toolbar' => 'basic',
                    'instructions' => 'Main descriptive text for this material',
                ),
                array(
                    'key' => 'field_material_benefits',
                    'label' => 'Benefits List',
                    'name' => 'benefits',
                    'type' => 'repeater',
                    'instructions' => 'List of benefits for this material',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_benefit_item',
                            'label' => 'Benefit Item',
                            'name' => 'item',
                            'type' => 'text',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_material_sensory',
                    'label' => 'Sensory Note',
                    'name' => 'sensory',
                    'type' => 'textarea',
                    'instructions' => 'Sensory description (how it feels, behaves over time)',
                ),
                array(
                    'key' => 'field_material_image_1',
                    'label' => 'Main Image',
                    'name' => 'image_1',
                    'type' => 'image',
                    'save_format' => 'url',
                    'instructions' => 'Primary image for this material (large)',
                ),
                array(
                    'key' => 'field_material_image_2',
                    'label' => 'Image 2',
                    'name' => 'image_2',
                    'type' => 'image',
                    'save_format' => 'url',
                    'instructions' => 'Secondary image (small)',
                ),
                array(
                    'key' => 'field_material_image_3',
                    'label' => 'Image 3',
                    'name' => 'image_3',
                    'type' => 'image',
                    'save_format' => 'url',
                    'instructions' => 'Tertiary image (small)',
                ),
                array(
                    'key' => 'field_material_button_text',
                    'label' => 'Button Text',
                    'name' => 'button_text',
                    'type' => 'text',
                    'instructions' => 'Text for the call-to-action button',
                ),
                array(
                    'key' => 'field_material_button_style',
                    'label' => 'Button Style',
                    'name' => 'button_style',
                    'type' => 'select',
                    'choices' => array(
                        'dark' => 'Dark',
                        'outline' => 'Outline',
                    ),
                    'default_value' => 'dark',
                ),
                array(
                    'key' => 'field_material_button_link',
                    'label' => 'Button Link',
                    'name' => 'button_link',
                    'type' => 'text',
                    'instructions' => 'URL for the button',
                ),
            ),
        ),

        // TAB: What We Avoid
        array(
            'key' => 'field_forest_tab_avoid',
            'label' => 'What We Avoid',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_avoid_title',
            'label' => 'Avoid Section Title',
            'name' => 'avoid_title',
            'type' => 'text',
            'default_value' => 'What You Won\'t Find in LOOMAAMA',
            'instructions' => 'Title for the "what we avoid" section',
        ),
        array(
            'key' => 'field_avoid_list',
            'label' => 'What We Avoid - List',
            'name' => 'avoid_list',
            'type' => 'repeater',
            'instructions' => 'Add categories of things LOOMAAMA avoids',
            'sub_fields' => array(
                array(
                    'key' => 'field_avoid_category',
                    'label' => 'Category Title',
                    'name' => 'category',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_avoid_items',
                    'label' => 'Items',
                    'name' => 'items',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_avoid_item',
                            'label' => 'Item',
                            'name' => 'item',
                            'type' => 'text',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_avoid_reason',
                    'label' => 'Reason',
                    'name' => 'reason',
                    'type' => 'textarea',
                ),
            ),
        ),

        // TAB: Biodegradability
        array(
            'key' => 'field_forest_tab_biodegradable',
            'label' => 'Biodegradability',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_biodegradable_eyebrow',
            'label' => 'Biodegradable Eyebrow',
            'name' => 'biodegradable_eyebrow',
            'type' => 'text',
            'default_value' => 'From Earth to Earth',
            'instructions' => 'Eyebrow text above the biodegradable title',
        ),
        array(
            'key' => 'field_biodegradable_title',
            'label' => 'Biodegradable Section Title',
            'name' => 'biodegradable_title',
            'type' => 'text',
            'default_value' => 'Nature Completes What It Begins.',
            'instructions' => 'Main title for the biodegradable section',
        ),
        array(
            'key' => 'field_biodegradable_subtitle',
            'label' => 'Biodegradable Section Subtitle',
            'name' => 'biodegradable_subtitle',
            'type' => 'text',
            'default_value' => 'It Came from the Soil. It Will Return to It. Made to Last. Made to Return.',
            'instructions' => 'Subtitle for the biodegradable section',
        ),
        array(
            'key' => 'field_biodegradable_text',
            'label' => 'Biodegradable Section Text',
            'name' => 'biodegradable_text',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'default_value' => '<p>Every LOOMAAMA garment is designed with its whole life in mind, not just the years you wear it. When the time finally comes, years from now, it will return to the earth the same way it arrived. Quietly. Completely. Without leaving anything behind that does not belong.</p><p class="fw-medium">No microplastics lingering in soil. No synthetic waste sitting in landfill for centuries. Just fibre completing the most natural cycle there is. This is what it means to make clothing honestly.</p>',
            'instructions' => 'Main content for the biodegradable section',
        ),
        array(
            'key' => 'field_lifecycle_steps',
            'label' => 'Lifecycle Steps',
            'name' => 'lifecycle_steps',
            'type' => 'repeater',
            'instructions' => 'Add steps for the circular lifecycle diagram',
            'sub_fields' => array(
                array(
                    'key' => 'field_lifecycle_icon',
                    'label' => 'Icon Class',
                    'name' => 'icon',
                    'type' => 'text',
                    'instructions' => 'Font Awesome icon class (e.g., fa-solid fa-seedling)',
                ),
                array(
                    'key' => 'field_lifecycle_label',
                    'label' => 'Label',
                    'name' => 'label',
                    'type' => 'text',
                    'instructions' => 'Step label (e.g., Plant, Harvest, Garment)',
                ),
            ),
        ),

        // TAB: Closing CTA
        array(
            'key' => 'field_forest_tab_closing',
            'label' => 'Closing CTA',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_closing_eyebrow',
            'label' => 'Closing Eyebrow',
            'name' => 'closing_eyebrow',
            'type' => 'text',
            'default_value' => 'The Earth is Your Closet',
            'instructions' => 'Eyebrow text above the closing title',
        ),
        array(
            'key' => 'field_closing_title',
            'label' => 'Closing Title',
            'name' => 'closing_title',
            'type' => 'text',
            'default_value' => 'Ready to Feel the Difference?',
            'instructions' => 'Main title for the closing section',
        ),
        array(
            'key' => 'field_closing_text',
            'label' => 'Closing Text',
            'name' => 'closing_text',
            'type' => 'textarea',
            'default_value' => 'Explore our collection of natural fiber garments, each handcrafted to last.',
            'instructions' => 'Descriptive text for the closing section',
        ),
        array(
            'key' => 'field_closing_button_text',
            'label' => 'Closing Button Text',
            'name' => 'closing_button_text',
            'type' => 'text',
            'default_value' => 'Explore the Collection',
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
                'value' => 'page-template/page-forest.php',
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