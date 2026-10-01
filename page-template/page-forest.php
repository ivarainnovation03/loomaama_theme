<?php
/**
 * Template Name: Forest (Materials)
 *
 * @package Loomaama
 */

get_header();
?>
    <main>
<?php 
$hero_image = get_field('forest_hero_image');
$hero_video = get_field('forest_hero_video');

// Handle different ACF field return formats
if (is_array($hero_video)) {
    $hero_video = !empty($hero_video['url']) ? $hero_video['url'] : (!empty($hero_video[0]) ? $hero_video[0] : '');
} elseif (is_object($hero_video) && isset($hero_video->url)) {
    $hero_video = $hero_video->url;
}

$hero_video = esc_url($hero_video);
$has_video = !empty($hero_video);
?>

<section class="hero-editorial jarallax" <?php echo $has_video ? 'data-video-src="mp4:' . $hero_video . '"' : ''; ?> data-jarallax data-speed="0.5">
    <?php if (!$has_video && $hero_image): ?>
        <div class="jarallax-img" style="background-image: url('<?php echo esc_url($hero_image); ?>');"></div>
    <?php endif; ?>
    
        <div class="hero-editorial-content reveal">
           <span class="eyebrow mb-4 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('forest_hero_eyebrow') ?: ''); ?></span>
            <h1 class="headline display-3 mb-4 fw-bold"><?php echo esc_html(get_field('forest_hero_title') ?: 'The Forest is our CEO.'); ?></h1>
           <h5 class="headline fw-normal mb-5" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('forest_hero_subheading') ?: 'If it cannot return to the soil, it does not belong on our skin.'); ?></h5>
            <p class=" mx-auto" style="max-width: 800px; font-weight: 300; line-height: 1.8;" data-aos="fade-up" data-aos-delay="300">
                <?php echo esc_html(get_field('forest_hero_subtitle') ?: 'Born from the earth. Made for your skin. Destined to return.<br>Every fibre we use is grown with patience, harvested with care, and designed to leave nothing behind. No plastic. No chemicals. Just nature doing what it has always done best, and real people honoured every step of the way.'); ?>
            </p>
        </div>     
</section>


    <!-- SECTION 1: WHY MATERIAL MATTERS -->
    <section class=" my-md-5" data-aos="fade-up">
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-2 mb-lg-0 reveal">
                  <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('materials_intro_eyebrow') ?: ''); ?></span>
                    <h2 class="headline display-6 mb-4" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('materials_intro_title') ?: 'What Touches Your Skin Matters.'); ?></h2>
                    <h5 class="headline mb-4 text-secondary" data-aos="fade-up" data-aos-delay="300"><?php echo esc_html(get_field('materials_intro_subtitle') ?: 'Your Skin Has Been Waiting for This.'); ?></h5>
                    <div class="text-secondary  mb-4 lh-lg" data-aos="fade-up" data-aos-delay="400">
                        <?php echo wp_kses_post(get_field('materials_intro_text') ?: '<p>Natural fibres carry an intelligence that nature spent thousands of years perfecting. They breathe when your body needs air, warm when it needs comfort, and rest against your skin without leaving anything harmful behind. No toxins. No resistance. Just a quiet, effortless harmony your body has always been waiting for.</p><p class="fw-medium">Wearing LOOMAAMA is not a style switch. It is your body finally getting what it always deserved.</p>'); ?>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 reveal">
                    <div class="stat-callout">
                        <p class="headline" data-aos="fade-up" data-aos-delay="500"><?php echo esc_html(get_field('stat_callout') ?: '"Synthetic clothing sheds 700,000 microplastic fibers per wash."'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: OUR MATERIALS -->
    <?php 
    $materials = get_field('materials');
    if ($materials):
        $material_count = count($materials);
        foreach ($materials as $index => $material):
            $bg_class = ($index % 2 == 0) ? 'bg-editorial' : 'bg-white';
            $row_class = ($index % 2 == 1) ? 'flex-lg-row-reverse' : '';
    ?>
    <section class=" <?php echo $bg_class; ?>" data-aos="fade-up">
        <div class="container ">
            <div class="row align-items-center <?php echo $row_class; ?>">
                <div class="col-lg-5 mb-5 mb-lg-0 reveal">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($material['eyebrow']); ?></span>
                    <h2 class="headline mb-4 display-6" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html($material['heading']); ?></h2>
                    <?php if ($material['bold_text']): ?>
                    <p class="fw-bold mb-4" data-aos="fade-up" data-aos-delay="300"><?php echo esc_html($material['bold_text']); ?></p>
                    <?php endif; ?>
                    <div class="text-secondary lh-lg mb-4" data-aos="fade-up" data-aos-delay="400">
                        <?php echo wp_kses_post($material['description']); ?>
                    </div>
                    
                    <?php if ($material['benefits']): ?>
                    <ul class="benefits-list headline fs-6">
                        <?php foreach ($material['benefits'] as $benefit): ?>
                        <li data-aos="fade-up" data-aos-delay="500"><?php echo esc_html($benefit['item']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>

                    <?php if ($material['sensory']): ?>
                    <div class="mt-4 p-4 border" data-aos="fade-up" data-aos-delay="600" style="background: #fff; border-color: var(--border) !important;">
                        <p class="mb-0 text-secondary font-italic small">
                            <strong class="text-dark">SENSORY:</strong> <?php echo esc_html($material['sensory']); ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($material['button_text']): ?>
                    <a href="<?php echo esc_url($material['button_link']); ?>" data-aos="fade-up" data-aos-delay="700" class="btn <?php echo ($material['button_style'] == 'dark') ? 'btn-dark' : 'btn-outline-dark'; ?> px-4 py-3 btn-lux headline mt-5"><?php echo esc_html($material['button_text']); ?></a>
                    <?php endif; ?>
                </div>

                <div class="col-lg-6 offset-lg-1 reveal" data-aos="fade-up" data-aos-delay="100">
                    <div class="collage-grid">
                        <?php if ($material['image_1']): ?>
                        <div class="collage-main" data-aos="fade-up" data-aos-delay="200">
                            <img src="<?php echo esc_url($material['image_1']); ?>" class="collage-img" alt="<?php echo esc_attr($material['name']); ?>">
                        </div>
                        <?php endif; ?>
                        <?php if ($material['image_2']): ?>
                        <div class="collage-sub" data-aos="fade-up" data-aos-delay="300">
                            <img src="<?php echo esc_url($material['image_2']); ?>" class="collage-img" alt="<?php echo esc_attr($material['name']); ?>">
                        </div>
                        <?php endif; ?>
                        <?php if ($material['image_3']): ?>
                        <div class="collage-sub" data-aos="fade-up" data-aos-delay="400">
                            <img src="<?php echo esc_url($material['image_3']); ?>" class="collage-img" alt="<?php echo esc_attr($material['name']); ?>">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php 
        endforeach;
    else:
    ?>
    <!-- Default Nettle Section -->
    <section class="" style="background: var(--bg-card);">
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0 reveal">
                    <span class="eyebrow text-secondary mb-3 d-block">The Masterpiece(ALLO): The Silk of Forest</span>
                    <h2 class="headline mb-4 display-6">Wild Nettle: The Mountain's Gift</h2>
                    <p class="fw-bold mb-4">Himalayan Nettle: 10,000 years of testing. 0 recalls. The most advanced textile in the world grows wild. We just found it.</p>
                    <div class="text-secondary lh-lg mb-4">
                        <p>Once wild and untamed, nettle now finds new life in fabric that is both enduring and graceful. Clothing that celebrates nature's resilience with effortless style. Allo is fully biodegradable, and it returns to the earth as naturally as it came from it.</p>
                        <p>For centuries, nettle has been a quiet companion to Himalayan life. It asks for nothing. No irrigation, no pesticides, no fertiliser. It simply rises with the rain, the way it always has, the way it always will. If it is not harvested, it withers back into the soil.</p>
                    </div>
                    
                    <ul class="benefits-list headline fs-6">
                        <li>Naturally antibacterial & hypoallergenic</li>
                        <li>Stronger than cotton</li>
                        <li>Requires zero water for cultivation</li>
                        <li>Softens beautifully with age</li>
                        <li>Biodegradable</li>
                    </ul>

                    <div class="mt-4 p-4 border" style="background: #fff; border-color: var(--border) !important;">
                        <p class="mb-0 text-secondary font-italic small">
                            <strong class="text-dark">SENSORY:</strong> Nettle feels crisp when new, like raw linen. After a few washes, it softens into something familiar; structured but gentle, breathable but warm.
                        </p>
                    </div>
                    
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-dark px-4 py-3 btn-lux headline mt-5">Shop Nettle Collection</a>
                </div>

                <div class="col-lg-6 offset-lg-1 reveal">
                    <div class="collage-grid">
                        <div class="collage-main">
                            <img src="https://images.unsplash.com/photo-1542385151-efd9000785a0?q=80&w=1000&auto=format&fit=crop" class="collage-img" alt="Wild Nettle Forest">
                        </div>
                        <div class="collage-sub">
                            <img src="https://images.unsplash.com/photo-1584447128309-b66b7a4d1b63?q=80&w=800&auto=format&fit=crop" class="collage-img" alt="Nettle Fibre Texture">
                        </div>
                        <div class="collage-sub">
                            <img src="https://images.unsplash.com/photo-1523381235312-3a1574da123f?q=80&w=800&auto=format&fit=crop" class="collage-img" alt="Artisan Processing Nettle">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php endif; ?>

    <!-- SECTION 3: WHAT WE AVOID -->
    <section class="border-brand" data-aos="fade-up">
        <div class="container ">
            <div class="row text-center mb-5 reveal">
                <div class="col-12">
                    <h2 class="headline display-6"><?php echo esc_html(get_field('avoid_title') ?: 'What You Won\'t Find in LOOMAAMA'); ?></h2>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <?php 
                $avoid_list = get_field('avoid_list');
                if ($avoid_list):
                    foreach ($avoid_list as $key => $avoid):
                        $delay = 200 + ($key * 200);
                ?>
                <div class="col-md-5 reveal" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
                    <div class="p-5 h-100" style="background: var(--bg-editorial);">
                        <h4 class="headline mb-4"><?php echo esc_html($avoid['category']); ?></h4>
                        <ul class="list-unstyled text-secondary lh-lg mb-4 fs-5 font-family-base">
                            <?php foreach ($avoid['items'] as $key => $item):
                                $xdelay = 300 + ($key * 100);
                                ?>
                            <li data-aos="fade-up" data-aos-delay="<?php echo esc_attr($xdelay); ?>">
                                <i class="fa-solid fa-xmark me-2" style="color:#b2b2b2;"></i> <?php echo esc_html($item['item']); ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="pt-4 border-top border-dark-subtle">
                            <p class="headline fw-bold mb-1">WHY:</p>
                            <p class="small text-secondary mb-0"><?php echo esc_html($avoid['reason']); ?></p>
                        </div>
                    </div>
                </div>
                <?php 
                    endforeach;
                else:
                ?>
                <div class="col-md-5 reveal">
                    <div class="p-5 h-100" style="background: var(--bg-editorial);">
                        <h4 class="headline mb-4">Synthetic Fibers</h4>
                        <ul class="list-unstyled text-secondary lh-lg mb-4 fs-5 font-family-base">
                            <li data-aos="fade-up" data-aos-delay="300">
                                <i class="fa-solid fa-xmark me-2" style="color:#b2b2b2;"></i> Polyester
                            </li>
                            <li data-aos="fade-up" data-aos-delay="400">
                                <i class="fa-solid fa-xmark me-2" style="color:#b2b2b2;"></i> Nylon
                            </li>
                            <li data-aos="fade-up" data-aos-delay="500">
                                <i class="fa-solid fa-xmark me-2" style="color:#b2b2b2;"></i> Acrylic
                            </li>
                            <li data-aos="fade-up" data-aos-delay="600">
                                <i class="fa-solid fa-xmark me-2" style="color:#b2b2b2;"></i> Spandex/Elastane
                            </li>
                            <li data-aos="fade-up" data-aos-delay="700">
                                <i class="fa-solid fa-xmark me-2" style="color:#b2b2b2;"></i> Rayon (semi-synthetic)
                            </li>
                        </ul>
                        <div class="pt-4 border-top border-dark-subtle">
                            <p class="headline fw-bold mb-1">WHY:</p>
                            <p class="small text-secondary mb-0">Derived from petroleum, shed microplastics, trap heat, and don't biodegrade.</p>
                        </div>
                    </div>
                </div>
                
              
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- SECTION 4: THE BIODEGRADABILITY PROMISE -->
    <section class=" my-md-5" data-aos="fade-up" style="background: var(--bg-card);">
        <div class="container  text-center reveal">
            <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('biodegradable_eyebrow') ?: ''); ?></span>
            <h2 class="headline display-5 mb-4" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('biodegradable_title') ?: 'Nature Completes What It Begins.'); ?></h2>
            <h5 class="headline mb-5 text-secondary" data-aos="fade-up" data-aos-delay="300"><?php echo esc_html(get_field('biodegradable_subtitle') ?: 'It Came from the Soil. It Will Return to It. Made to Last. Made to Return.'); ?></h5>

            <div class="text-secondary fs-5 mx-auto mb-5 lh-lg" style="max-width: 800px;" data-aos="fade-up" data-aos-delay="400">
                <?php echo wp_kses_post(get_field('biodegradable_text') ?: '<p>Every LOOMAAMA garment is designed with its whole life in mind, not just the years you wear it. When the time finally comes, years from now, it will return to the earth the same way it arrived. Quietly. Completely. Without leaving anything behind that does not belong.</p><p class="fw-medium">No microplastics lingering in soil. No synthetic waste sitting in landfill for centuries. Just fibre completing the most natural cycle there is. This is what it means to make clothing honestly.</p>'); ?>
            </div>

            <!-- Circular/Cycle Diagram -->
            <div class="lifecycle-container mt-5" data-aos="fade-up" data-aos-delay="500">
                <?php 
                $lifecycle_steps = get_field('lifecycle_steps');
                if ($lifecycle_steps):
                    $step_count = count($lifecycle_steps);
                    foreach ($lifecycle_steps as $index => $step):
                        $delay = 600 + ($index * 200);
                ?>
                <div class="lifecycle-step" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
                    <div class="lifecycle-icon"><i class="<?php echo esc_attr($step['icon']); ?>"></i></div>
                    <span class="headline small fw-bold text-uppercase"><?php echo esc_html($step['label']); ?></span>
                </div>
                <?php if ($index < $step_count - 1): ?>
                <div class="lifecycle-arrow" ><i class="fa-solid fa-arrow-right"></i></div>
                <?php endif; ?>
                <?php 
                    endforeach;
                else:
                ?>
                <div class="lifecycle-step">
                    <div class="lifecycle-icon"><i class="fa-solid fa-seedling"></i></div>
                    <span class="headline small fw-bold text-uppercase">1. Plant</span>
                </div>
                <div class="lifecycle-arrow" ><i class="fa-solid fa-arrow-right"></i></div>
                <div class="lifecycle-step">
                    <div class="lifecycle-icon"><i class="fa-solid fa-hands"></i></div>
                    <span class="headline small fw-bold text-uppercase">2. Harvest</span>
                </div>
                <div class="lifecycle-arrow" ><i class="fa-solid fa-arrow-right"></i></div>
                <div class="lifecycle-step">
                    <div class="lifecycle-icon"><i class="fa-solid fa-shirt"></i></div>
                    <span class="headline small fw-bold text-uppercase">3. Garment</span>
                </div>
                <div class="lifecycle-arrow" ><i class="fa-solid fa-arrow-right"></i></div>
                <div class="lifecycle-step">
                    <div class="lifecycle-icon"><i class="fa-solid fa-leaf"></i></div>
                    <span class="headline small fw-bold text-uppercase">4. Compost</span>
                </div>
                <div class="lifecycle-arrow" ><i class="fa-solid fa-arrow-right"></i></div>
                <div class="lifecycle-step">
                    <div class="lifecycle-icon" style="background: var(--text-main); color: #fff; border-color: var(--text-main);"><i class="fa-solid fa-earth-americas"></i></div>
                    <span class="headline small fw-bold text-uppercase">5. Soil</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CLOSING CTA -->
<section class=" text-center" style="background: var(--bg-card);" data-aos="fade-up" data-aos-delay="200">
    <div class="container  reveal">
        <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="300"><?php echo esc_html(get_field('closing_eyebrow') ?: ''); ?></span>
        <h2 class="headline display-4 mb-4" data-aos="fade-up" data-aos-delay="400"><?php echo esc_html(get_field('closing_title') ?: 'Ready to Feel the Difference?'); ?></h2>
        <p class="text-secondary fs-5 mx-auto mb-5" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="500">
            <?php echo esc_html(get_field('closing_text') ?: 'Explore our collection of natural fiber garments, each handcrafted to last.'); ?>
        </p>
        <a href="<?php echo esc_url(get_field('closing_button_link') ?: wc_get_page_permalink('shop')); ?>" class="cta-button btn btn-dark px-5 py-3 btn-lux headline fs-6" data-aos="fade-up" data-aos-delay="600"><?php echo esc_html(get_field('closing_button_text') ?: 'Explore the Collection'); ?></a>
    </div>
</section>

</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure jarallax and video plugin are loaded
    if (typeof jarallax !== 'undefined') {
        // Re-initialize jarallax with video settings
        var elements = document.querySelectorAll('.jarallax');
        
        elements.forEach(function(element) {
            // Destroy existing instance if any
            if (element.jarallax) {
                element.jarallax.destroy();
            }
            
            // Initialize with video settings
            jarallax(element, {
                speed: 0.5,
                videoVolume: 0,
                videoLoop: true,
                videoStartTime: 0,
                videoEndTime: 0,
                imgPosition: '50% 50%',
                imgSize: 'cover',
                imgRepeat: 'no-repeat',              
                videoSrc: `mp4:<?php echo $hero_video; ?>`

            });
        });
    }
    
    // Fallback: Force video to play after a delay
    setTimeout(function() {
        var videoSection = document.querySelector('.jarallax[data-jarallax-video]');
        if (videoSection) {
            var video = videoSection.querySelector('video');
            if (video) {
                video.muted = true;
                video.play().catch(function(e) {
                    console.log('Video play error:', e);
                });
            }
        }
    }, 500);
});
</script>
<?php get_footer(); ?>