<?php
/**
 * SCF Field Group: Archive (The Process)
 * 
 * @package Loomaama
 */

if (!function_exists('register_field_group')) {
    return;
}

register_field_group(array(
    'id' => 'acf_archive',
    'title' => 'Archive Page Settings',
    'fields' => array(
        // TAB: Hero
        array(
            'key' => 'field_archive_tab_hero',
            'label' => 'Hero Section',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_archive_hero_eyebrow',
            'label' => 'Hero Eyebrow Text',
            'name' => 'archive_hero_eyebrow',
            'type' => 'text',
            'default_value' => 'From Wild Forest to Finished Piece',
            'instructions' => 'Small eyebrow text above the hero title.',
        ),
        array(
            'key' => 'field_archive_hero_title',
            'label' => 'Hero Title',
            'name' => 'archive_hero_title',
            'type' => 'text',
            'default_value' => 'From Fiber to Fabric:<br>The Journey of a Garment',
            'instructions' => 'Main headline for the hero section. Supports HTML line breaks.',
        ),
        array(
            'key' => 'field_archive_hero_subtitle',
            'label' => 'Hero Subtitle',
            'name' => 'archive_hero_subtitle',
            'type' => 'textarea',
            'default_value' => 'Every LOOMAAMA piece passes through many hands and takes weeks to complete. This is what that process looks like, from the first harvest to the final stitch.',
            'instructions' => 'Subtitle text below the main headline.',
        ),
        array(
            'key' => 'field_archive_left_image',
            'label' => 'Hero Left Image',
            'name' => 'archive_left_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Background image for the left split pane.',
        ),
        array(
            'key' => 'field_archive_right_image',
            'label' => 'Hero Right Image',
            'name' => 'archive_right_image',
            'type' => 'image',
            'save_format' => 'url',
            'instructions' => 'Background image for the right split pane.',
        ),

        // TAB: Process Overview
        array(
            'key' => 'field_archive_tab_process',
            'label' => 'Process Overview',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_process_eyebrow',
            'label' => 'Process Overview Eyebrow Text',
            'name' => 'process_eyebrow',
            'type' => 'text',
            'default_value' => 'Made With Time. Made With Care.',
            'instructions' => 'Eyebrow text above the process title.',
        ),
        array(
            'key' => 'field_process_title',
            'label' => 'Process Section Title',
            'name' => 'process_title',
            'type' => 'text',
            'default_value' => 'How a LOOMAAMA Garment is Made',
            'instructions' => 'Main title for the process overview section.',
        ),
        array(
            'key' => 'field_process_subtitle',
            'label' => 'Process Section Subtitle',
            'name' => 'process_subtitle',
            'type' => 'textarea',
            'default_value' => 'From harvest to finished piece, a single LOOMAAMA garment passes through 8-12 artisans and takes 3-6 weeks to complete. Here\'s how it happens.',
            'instructions' => 'Subtitle text below the process title.',
        ),
        array(
            'key' => 'field_process_steps',
            'label' => 'Process Timeline Steps',
            'name' => 'process_steps',
            'type' => 'repeater',
            'instructions' => 'Add the main process steps shown in the timeline.',
            'sub_fields' => array(
                array(
                    'key' => 'field_step_number',
                    'label' => 'Step Number',
                    'name' => 'step_number',
                    'type' => 'number',
                    'instructions' => 'Step number (e.g., 1, 2, 3)',
                ),
                array(
                    'key' => 'field_step_title',
                    'label' => 'Step Title',
                    'name' => 'step_title',
                    'type' => 'text',
                    'instructions' => 'Short title for this step (e.g., Harvest, Spinning)',
                ),
                array(
                    'key' => 'field_step_icon',
                    'label' => 'Step Icon',
                    'name' => 'step_icon',
                    'type' => 'text',
                    'instructions' => 'Font Awesome icon class (e.g., fa-solid fa-leaf)',
                    'default_value' => 'fa-solid fa-leaf',
                ),
            ),
        ),

        // TAB: Detailed Steps
        array(
            'key' => 'field_archive_tab_detailed',
            'label' => 'Detailed Steps',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_detailed_steps',
            'label' => 'Detailed Steps Breakdown',
            'name' => 'detailed_steps',
            'type' => 'repeater',
            'instructions' => 'Add detailed steps with images and descriptions.',
            'sub_fields' => array(
                array(
                    'key' => 'field_step_badge',
                    'label' => 'Step Badge Number',
                    'name' => 'step_badge',
                    'type' => 'number',
                    'instructions' => 'Number displayed on the badge (e.g., 01, 02)',
                ),
                array(
                    'key' => 'field_step_eyebrow',
                    'label' => 'Step Eyebrow Text',
                    'name' => 'step_eyebrow',
                    'type' => 'text',
                    'instructions' => 'Small eyebrow text above the step heading.',
                ),
                array(
                    'key' => 'field_step_heading',
                    'label' => 'Step Heading',
                    'name' => 'step_heading',
                    'type' => 'text',
                    'instructions' => 'Main heading for this step.',
                ),
                array(
                    'key' => 'field_step_subheading',
                    'label' => 'Step Subheading',
                    'name' => 'step_subheading',
                    'type' => 'text',
                    'instructions' => 'Optional subheading below the main heading.',
                ),
                array(
                    'key' => 'field_step_description',
                    'label' => 'Step Description',
                    'name' => 'step_description',
                    'type' => 'wysiwyg',
                    'toolbar' => 'basic',
                    'media_upload' => false,
                    'instructions' => 'Detailed description of this step in the process.',
                ),
                array(
                    'key' => 'field_step_image_1',
                    'label' => 'Step Image 1',
                    'name' => 'step_image_1',
                    'type' => 'image',
                    'save_format' => 'url',
                    'instructions' => 'Primary image for this step.',
                ),
                array(
                    'key' => 'field_step_image_2',
                    'label' => 'Step Image 2',
                    'name' => 'step_image_2',
                    'type' => 'image',
                    'save_format' => 'url',
                    'instructions' => 'Secondary image for this step (displayed offset).',
                ),
                array(
                    'key' => 'field_step_bg_style',
                    'label' => 'Background Style',
                    'name' => 'step_bg_style',
                    'type' => 'select',
                    'choices' => array(
                        'white' => 'White',
                        'editorial' => 'Editorial (Gray)',
                    ),
                    'default_value' => 'white',
                    'instructions' => 'Choose the background color for this section.',
                ),
            ),
        ),

        // TAB: Handmade
        array(
            'key' => 'field_archive_tab_handmade',
            'label' => 'Handmade',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_handmade_eyebrow',
            'label' => 'Handmade Section Eyebrow',
            'name' => 'handmade_eyebrow',
            'type' => 'text',
            'default_value' => 'No Factory Ever Made Anything This Honest.',
            'instructions' => 'Eyebrow text above the handmade section title.',
        ),
        array(
            'key' => 'field_handmade_title',
            'label' => 'Handmade Section Title',
            'name' => 'handmade_title',
            'type' => 'text',
            'default_value' => 'What Machines Can\'t Replicate',
            'instructions' => 'Main title for the handmade section.',
        ),
        array(
            'key' => 'field_handmade_text',
            'label' => 'Handmade Section Content',
            'name' => 'handmade_text',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'instructions' => 'Main content text for the handmade section.',
            'default_value' => '<p>Handmade fabric carries something machine-made never will. Slight variations in thickness, texture, and tension. A drape that moves differently. A surface that tells the truth about how it was made.</p><p class="text-main fs-5 lh-lg mb-4 fw-bold">These are not flaws. They are fingerprints.</p><p>Machine-made fabric is uniform, predictable, and silent. LOOMAAMA fabric breathes, ages beautifully, and carries the quiet presence of the person who made it.</p><p class="fw-medium">That is the difference. And once you feel it, you will not go back.</p>',
        ),

        // TAB: Time
        array(
            'key' => 'field_archive_tab_time',
            'label' => 'Time',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_time_eyebrow',
            'label' => 'Time Section Eyebrow',
            'name' => 'time_eyebrow',
            'type' => 'text',
            'default_value' => 'Good Things Take Time',
            'instructions' => 'Eyebrow text above the time section title.',
        ),
        array(
            'key' => 'field_time_title',
            'label' => 'Time Section Title',
            'name' => 'time_title',
            'type' => 'text',
            'default_value' => 'Why It Takes Weeks,<br>Not Hours.',
            'instructions' => 'Main title for the time section.',
        ),
        array(
            'key' => 'field_time_stat',
            'label' => 'Time Stat Number',
            'name' => 'time_stat',
            'type' => 'text',
            'default_value' => '3-6 Weeks',
            'instructions' => 'The statistic number displayed in the stat box.',
        ),
        array(
            'key' => 'field_time_text',
            'label' => 'Time Stat Label',
            'name' => 'time_text',
            'type' => 'text',
            'default_value' => 'Average time from fiber to finished garment',
            'instructions' => 'Label text below the statistic number.',
        ),
        array(
            'key' => 'field_time_description',
            'label' => 'Time Section Description',
            'name' => 'time_description',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'instructions' => 'Detailed description for the time section.',
            'default_value' => '<p>A single LOOMAAMA garment represents 40 to 60 hours of skilled human labor. Not because the process is inefficient. Because it cannot be shortened without losing what makes it worth wearing.</p><p>Fast fashion prioritises speed. We prioritise the garment. The result is clothing that does not fall apart after a season, because it was meant to stay longer.</p>',
        ),

        // TAB: Tools
        array(
            'key' => 'field_archive_tab_tools',
            'label' => 'Tools',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_tools_eyebrow',
            'label' => 'Tools Section Eyebrow',
            'name' => 'tools_eyebrow',
            'type' => 'text',
            'default_value' => 'Ancient Tools. Honest Craft.',
            'instructions' => 'Eyebrow text above the tools section title.',
        ),
        array(
            'key' => 'field_tools_title',
            'label' => 'Tools Section Title',
            'name' => 'tools_title',
            'type' => 'text',
            'default_value' => 'The Tools of the Trade',
            'instructions' => 'Main title for the tools section.',
        ),
        array(
            'key' => 'field_tools_text',
            'label' => 'Tools Section Content',
            'name' => 'tools_text',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false,
            'instructions' => 'Description text for the tools section.',
            'default_value' => '<p>LOOMAAMA artisans work with hand spindles, wooden looms, and simple sewing machines. Tools that have been passed down through generations alongside the skill. No industrial machinery. No automation. No shortcuts.</p><p class="fw-medium">These tools are not outdated. They are precise. And in the right hands, they produce something no modern machine has ever been able to match.</p>',
        ),
        array(
            'key' => 'field_tool_images',
            'label' => 'Tool Gallery Images',
            'name' => 'tool_images',
            'type' => 'gallery',
            'save_format' => 'url',
            'instructions' => 'Upload images of traditional tools used by artisans.',
        ),

        // TAB: Closing CTA
        array(
            'key' => 'field_archive_tab_closing',
            'label' => 'Closing CTA',
            'type' => 'tab',
        ),
        array(
            'key' => 'field_closing_title',
            'label' => 'Closing Section Title',
            'name' => 'closing_title',
            'type' => 'text',
            'default_value' => 'See the Process in Action',
            'instructions' => 'Main title for the closing CTA section.',
        ),
        array(
            'key' => 'field_closing_text',
            'label' => 'Closing Section Text',
            'name' => 'closing_text',
            'type' => 'textarea',
            'default_value' => 'Follow our journal for behind-the-scenes stories, artisan profiles, and deep dives into traditional craft.',
            'instructions' => 'Descriptive text for the closing CTA section.',
        ),
        array(
            'key' => 'field_closing_button_text',
            'label' => 'Closing Button Text',
            'name' => 'closing_button_text',
            'type' => 'text',
            'default_value' => 'Read the Journal',
            'instructions' => 'Text displayed on the CTA button.',
        ),
        array(
            'key' => 'field_closing_button_link',
            'label' => 'Closing Button Link',
            'name' => 'closing_button_link',
            'type' => 'text',
            'instructions' => 'URL for the CTA button (e.g., /journal or #)',
            'default_value' => '#',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-template/page-archive.php',
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