<?php
/**
 * Template Name: About
 *
 * @package Loomaama
 */

get_header();
?>
  <?php $hero_image = get_field('about_hero_image'); ?>
<main>
    <!-- HERO SECTION -->
    <section class="hero-editorial" data-aos="fade-up" <?php if ($hero_image): ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
        <div class="hero-editorial-content reveal">
            <h1 class="headline display-4 mb-4 fw-bold" data-aos="fade-up" data-aos-delay="100">
                <?php echo wp_kses_post(get_field('about_hero_title') ?: 'Profit is the byproduct.<br>Prosperity is the product.'); ?>
            </h1>
            <p class="fs-5 mx-auto" style="max-width: 700px; font-weight: 300;" data-aos="fade-up" data-aos-delay="200">
                <?php echo wp_kses_post(get_field('about_hero_subtitle') ?: 'Nature is the most advanced lab on Earth. Why would we use a factory?'); ?>
            </p>
        </div>
      
    </section>

    <!-- SECTION 1: ABOUT US -->
    <section class=" my-md-5" data-aos="fade-up">
        <div class="container ">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center reveal">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100">
                        <?php echo esc_html(get_field('about_intro_eyebrow') ?: ''); ?>
                    </span>
                    <h2 class="headline display-6 mb-5" data-aos="fade-up" data-aos-delay="200">
                        <?php echo wp_kses_post(get_field('about_intro_title') ?: 'Designed by Forest,<br>edited by humans.'); ?>
                    </h2>
                    <div class="text-secondary fs-5 mb-4 text-start drop-cap lh-lg" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('about_intro_text') ?: '<p>We believe the earth already holds everything we need to live beautifully. Deep in the forests of the mystic Himalayas, nature quietly grows something extraordinary: a wild fiber nourished only by rain, warmed only by sun. For generations, mountain artisans have gathered this untamed gift and transformed it, with patient hands and ancient knowledge, into cloth of remarkable grace.</p><p>Their craft is timeless. Their story, long deserving of the light. LOOMAAMA aims to bring this artistry to the world.</p><p>We bring the world\'s most authentic, chemical-free, and life-giving fabrics to our skin. When we wear LOOMAAMA, we aren\'t just wearing a garment; we are wearing a story of survival, a mountain\'s breath, and a promise of a better life for the person who made it. Each thread holds a life. Each piece finds its purpose.</p>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: THE MANIFESTO -->
    <section class="bg-card" data-aos="fade-up">
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0 reveal" data-aos="fade-up" data-aos-delay="100">
                    <div class="overflow-hidden rounded-4">
                    <?php $manifesto_image = get_field('about_manifesto_image'); ?>
                    <img src="<?php echo esc_url($manifesto_image ?: 'https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=1000'); ?>" class="img-fluid w-100 object-fit-cover"  alt="Natural Fabric Texture">
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1 reveal" data-aos="fade-up" data-aos-delay="200">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100">
                        <?php echo wp_kses_post(get_field('about_manifesto_eyebrow') ?: ''); ?>
                    </span>
                    <h2 class="headline display-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('about_manifesto_title') ?: 'We Believe Luxury<br>Should Be Quiet.'); ?>
                    </h2>
                    <h6 class="headline mb-4 display-7 text-secondary" data-aos="fade-up" data-aos-delay="400">
                        <?php echo wp_kses_post(get_field('about_manifesto_subtitle') ?: 'It should be felt, not announced.'); ?>
                    </h6>
                    <div class="text-secondary lh-lg " data-aos="fade-up" data-aos-delay="400">
                        <?php echo wp_kses_post(get_field('about_manifesto_text') ?: '<p>We do not farm the earth; we listen to it. Our fibers are wild, our dyes are drawn from living plants, and our hearts beat alongside the artisans of the Himalayas who shape every thread with care. We believe what you wear should be gentle on your skin and kind to the soul who made it.</p><p>This is not just a garment. It is a piece of the mountain, warm with the hands that touched it. It is a promise of a more beautiful world. LOOMAAMA exists beyond trends, beyond seasons, beyond urgency. We design with time, allowing garments to settle, soften, and become part of the wearer\'s life.</p>'); ?>
                    </div>
                   
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: OUR PRINCIPLES -->
    <section class=" my-md-5" data-aos="fade-up">
        <div class="container ">
            <div class="row text-center mb-5 reveal">
                <div class="col-12">
                    <h2 class="headline display-6" data-aos="fade-up" data-aos-delay="100">Our Principles</h2>
                </div>
            </div>
            <div class="row">
                <?php 
                $principles = get_field('about_principles');
                if ($principles):
                    foreach ($principles as $key =>$principle):
                        $delay = ($key + 1) * 100;
                ?>
                <div class="col-md-4 text-center reveal" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
                    <i class="<?php echo esc_attr($principle['icon']); ?> principle-icon"></i>
                    <h4 class="headline mb-3"><?php echo wp_kses_post($principle['title']); ?></h4>
                    <p class="text-secondary lh-lg px-xl-3 principle-text">
                        <?php echo wp_kses_post($principle['text']); ?>
                    </p>
                </div>
                <?php 
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- SECTION 4: THE PATRONAGE MODEL -->
    <section class="" style="border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 reveal" data-aos="fade-up" data-aos-delay="100">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100">
                        <?php echo esc_html(get_field('patronage_eyebrow') ?: ''); ?>
                    </span>
                    <h2 class="headline mb-4" data-aos="fade-up" data-aos-delay="200">
                        <?php echo wp_kses_post(get_field('patronage_title') ?: 'You\'re Not a Consumer.<br>You\'re a Patron.'); ?>
                    </h2>
                    <h6 class="headline display-7 text-secondary mb-4" data-aos="fade-up" data-aos-delay="200">
                        <?php echo wp_kses_post(get_field('patronage_subtitle') ?: 'You\'re Not a Consumer.<br>You\'re a Patron.'); ?>
                    </h6>
                    <div class="text-secondary lh-lg mb-5" data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('patronage_text') ?: '<p>When you bring a LOOMAAMA piece into your life, something quietly remarkable happens. A weaver in the Himalayas receives fair pay for work that took days of patient skill. A family is a little more stable. A craft that has lived for generations gets to live a little longer.</p><p>This is what we mean by patronage. Not charity. Not transaction. A relationship built on mutual respect, where your choice genuinely matters to a real human being on the other side of the world. <strong>You are part of something larger.</strong></p>'); ?>
                    </div>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('social-contract'))); ?>" class="btn btn-outline-dark px-4 py-3 btn-lux headline" data-aos="fade-up" data-aos-delay="400">
                        See Where Money Goes
                    </a>
                </div>
                
                <div class="col-lg-6 reveal" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-4 p-md-3 px-5 bg-editorial">
                        <div class="flow-diagram mb-4">
                            <div class="diagram-step" data-aos="fade-up" data-aos-delay="300">
                                <i class="fa-solid fa-bag-shopping mb-2 text-secondary fs-4"></i>
                                <h6 class="headline mb-0 ">Your Purchase</h6>
                            </div>
                            <div class="diagram-arrow" data-aos="fade-up" data-aos-delay="400"><i class="fa-solid fa-arrow-down"></i></div>
                            <div class="diagram-step" data-aos="fade-up" data-aos-delay="500">
                                <i class="fa-solid fa-hand-holding-dollar mb-2 text-secondary fs-4"></i>
                                <h6 class="headline mb-0" data-aos="fade-up" data-aos-delay="600">Artisan Wage</h6>
                            </div>
                            <div class="diagram-arrow" data-aos="fade-up" data-aos-delay="700"><i class="fa-solid fa-arrow-down"></i></div>
                            <div class="diagram-step" style="border-color: var(--text-main); background: var(--text-main); color: #3a3939;" data-aos="fade-up" data-aos-delay="800">
                                <i class="fa-solid fa-house mb-2 fs-4"></i>
                                <h6 class="headline mb-0" data-aos="fade-up" data-aos-delay="900">Community Growth</h6>
                            </div>
                        </div>
                        <div class="text-center pt-3 border-top border-dark-subtle" data-aos="fade-up" data-aos-delay="1000">
                            <p class="headline mb-0 fw-bold" style="letter-spacing: 0.05em;"><?php echo esc_html(get_field('patronage_families') ?: '"Every purchase supports 3-5 artisan families"'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: OUR STORY -->
    <section class=" my-md-5" data-aos="fade-up">
        <div class="container ">
            <div class="row justify-content-center mb-5 reveal" data-aos="fade-up" data-aos-delay="100">
                <div class="col-lg-8 text-center">
                    <h2 class="headline display-5 mb-4" data-aos="fade-up" data-aos-delay="200"><?php echo wp_kses_post(get_field('story_title') ?: 'How LOOMAAMA Began'); ?></h2>
                    <h5 class="headline text-secondary" data-aos="fade-up" data-aos-delay="300"><?php echo wp_kses_post(get_field('story_subtitle') ?: 'We are not made of plastic. Why are our clothes?'); ?></h5>
                </div>
            </div>
            
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0 reveal" data-aos="fade-up" data-aos-delay="100">
                    <div  class="overflow-hidden rounded-4">
                        <?php $story_image = get_field('story_image'); ?>
                        <img src="<?php echo esc_url($story_image ?: 'https://images.unsplash.com/photo-1523381235312-3a1574da123f?q=80&w=1000'); ?>" class="img-fluid w-100 object-fit-cover" alt="Artisan weaving">
                    </div>
                </div>
                <div class="col-md-6 px-lg-5 reveal" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-secondary lh-lg">
                        <?php echo wp_kses_post(get_field('story_text') ?: '<p>For too long, fashion drifted far from what it was always meant to be. Fabrics that suffocate the skin rather than let it breathe. Chemicals that sit against our bodies all day without us ever questioning them. And somewhere at the beginning of it all, skilled human hands are being paid the least for contributing the most.</p><p>We felt that something needed to come back. We began with one material and one community. Wild Himalayan nettle, growing freely in mountain air, touched by nothing but rain and sun. And a small, gifted group of artisans in rural Nepal, whose knowledge had been passed down through generations and whose craft was long overdue for the dignity it deserved.</p><p>Our intention was simple and has never changed: to create clothing that feels right against the body, honours the earth it came from, and ensures the person who made it can live with genuine security and pride.</p><p>Today, LOOMAAMA works with artisans across Nepal, weaving garments from nettle, hemp, cotton, and wool. Every piece is shaped by human hands. Every purchase travels back to the people who made it possible. This is slow fashion. This is clothing made with love, for the body, for the maker, and for the world they both share.</p>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 6: MEET THE FOUNDER -->
    <section class="bg-card" >
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-lg-4 offset-lg-1 mb-4 mb-lg-0 reveal" data-aos="fade-up" data-aos-delay="100">
                    <div style="aspect-ratio:3/4; overflow:hidden; border-radius:20px;">
                    <?php $founder_image = get_field('founder_image'); ?>
                    <img src="<?php echo esc_url($founder_image ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=800'); ?>" class="img-fluid w-100" style="aspect-ratio: 3/4; object-fit: cover;" alt="Founder Portrait">
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 reveal" data-aos="fade-up" data-aos-delay="200">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="300"></span>
                    <h2 class="headline display-5 mb-4" data-aos="fade-up" data-aos-delay="400">A Note from the Founder</h2>
                    <div class="text-secondary lh-lg mb-5" data-aos="fade-up" data-aos-delay="500">
                        <?php echo wp_kses_post(get_field('founder_quote') ?: '<p>"My journey began on a quiet trek through the Himalayas. Along those winding trails, I met local artisans who have worked with natural fibres for generations. In their hands, I saw patience. In their craft, pride. And in their work, a deep reverence for the earth.</p><p>Witnessing fashion produced without regard for the people who make it or the land that provides its fibres made it impossible for me to look away. Loomaama was born out of respect for the earth and for these skilled hands. We create slowly and intentionally. Each piece is designed to endure, and to carry a story you can cherish."</p>'); ?>
                    </div>
                    <div class="mt-4" data-aos="fade-up" data-aos-delay="600">
                        <p class="headline mb-0 fs-5"><?php echo wp_kses_post(get_field('founder_name') ?: 'Bini Karki'); ?></p>
                        <p class="text-secondary small text-uppercase letter-spacing-1"><?php echo wp_kses_post(get_field('founder_title') ?: 'Founder'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 7: WHAT WE DON'T DO -->
    <section class=" my-md-5">
        <div class="container  text-center reveal" data-aos="fade-up" data-aos-delay="100">
            <h2 class="headline display-6 mb-5" data-aos="fade-up" data-aos-delay="200">Our Non-Negotiables</h2>
            <ul class="non-negotiables-list">
                <?php 
                $delay='';
                $non_negotiables = get_field('non_negotiables_about');
                if ($non_negotiables):
                    foreach ($non_negotiables as $key => $item):
                        $delay = ($key + 1) * 100;
                ?>
                <li data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>"><i class="fa-solid fa-xmark"></i> <?php echo wp_kses_post($item['item']); ?></li>
                <?php 
                    endforeach;
                endif;
                $delay = ($delay + 100);
                ?>
                <li class="justify-content-center" style="color: var(--brand); font-size: 2.5rem; margin-top: 40px; display: block;" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">No Compromise.</li>
            </ul>
        </div>
    </section>

    <!-- CLOSING STATEMENT -->
    <section class=" text-center bg-card" data-aos="fade-up" data-aos-delay="100">
        <div class="container reveal">
            <div class="text-secondary  mx-auto mb-5" style="max-width: 700px;" data-aos="fade-up" data-aos-delay="200">
                <?php echo wp_kses_post(get_field('closing_statement') ?: '<h2 class="headline display-5 mb-4">This Feels Right.<br>You Already Know.</h2><p>If you have read this far, you have already felt it. That quiet recognition. That sense that this is what clothing was always supposed to be. Welcome to LOOMAAMA.</p>'); ?>
            </div>
            <a href="<?php echo esc_url(get_field('cta_button_url') ?: wc_get_page_permalink('shop')); ?>" class="cta-button btn btn-dark px-5 py-3 btn-lux headline fs-6" data-aos="fade-up" data-aos-delay="300"><?php echo esc_html(get_field('cta_button_text') ?: 'Explore the Collection'); ?></a>
        </div>
    </section>

</main>

<?php get_footer(); ?>