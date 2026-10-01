<?php
/**
 * Hero Section Template
 *
 * @package Loomaama
 */

$hero_type = get_theme_mod('hero_type', 'video');
$hero_title = get_theme_mod('hero_title', __('The Nettle Journey', 'loomaama'));
$hero_subtitle = get_theme_mod('hero_subtitle', __('Once wild and untamed, nettle now finds new life in fabric that is both enduring and graceful. Clothing that celebrates nature\'s resilience with effortless style', 'loomaama'));
$hero_button_text = get_theme_mod('hero_button_text', __('Discover Collection →', 'loomaama'));
$hero_button_url = get_theme_mod('hero_button_url', function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : '#');
$overlay_opacity = get_theme_mod('hero_overlay_opacity', '0.25');
?>

<section class="hero-video <?php echo esc_attr($hero_type); ?>-hero">
    <?php if ($hero_type === 'video') : 
        $video_url = get_theme_mod('hero_video_mp4');
       
        if ($video_url) : ?>
            <video autoplay muted loop playsinline>
                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
            </video>
        <?php else : ?>
            <video autoplay muted loop playsinline>
                <source src="<?php echo esc_url(LOOMAAMA_URI . '/assets/videos/intro3.mp4'); ?>" type="video/mp4">
            </video>
        <?php endif; ?>
    <?php elseif ($hero_type === 'image' || $hero_type === 'parallax') : 
        $image_url = get_theme_mod('hero_image');
        if (!$image_url) {
            $image_url = LOOMAAMA_URI . '/assets/images/hero-image.jpg';
        }
    ?>
        <div class="hero-image" style="background-image: url('<?php echo esc_url($image_url); ?>'); <?php echo $hero_type === 'parallax' ? 'background-attachment: fixed;' : ''; ?>"></div>
    <?php endif; ?>

    <div class="hero-overlay" style="opacity: <?php echo esc_attr($overlay_opacity); ?>;"></div>

    <div class="hero-text">
        <h2 class="headline mb-4">
            <?php echo esc_html($hero_title); ?>
        </h2>
        <p class="text-secondary mb-4 text-white">
            <?php echo esc_html($hero_subtitle); ?>
        </p>
        <a href="<?php echo esc_url($hero_button_url); ?>" class="btn btn-dark px-4 py-3 btn-lux headline">
            <?php echo esc_html($hero_button_text); ?>
        </a>
    </div>
</section>

<style>
    .hero-video .hero-overlay {
        background-color: rgba(0, 0, 0, <?php echo esc_attr($overlay_opacity); ?>);
    }
</style>