<?php
/**
 * Template Name: Shipping & Returns
 *
 * @package Loomaama
 */

get_header();
?>

<main>
    <!-- HERO SECTION WITH PARALLAX VIDEO/IMAGE -->
    <?php 
    $hero_image = get_field('shipping_returns_hero_image');
    $hero_video = get_field('shipping_returns_hero_video');

    // Handle different ACF field return formats
    if (is_array($hero_video)) {
        $hero_video = !empty($hero_video['url']) ? $hero_video['url'] : (!empty($hero_video[0]) ? $hero_video[0] : '');
    } elseif (is_object($hero_video) && isset($hero_video->url)) {
        $hero_video = $hero_video->url;
    }

    $hero_video = esc_url($hero_video);
    $has_video = !empty($hero_video);
    ?>

    <section class="hero-shipping-returns jarallax">
        <?php if (!$has_video && $hero_image): ?>
            <div class="jarallax-img" style="background-image: url('<?php echo esc_url($hero_image); ?>');"></div>
        <?php endif; ?>
        
        <div class="hero-shipping-returns-content reveal" data-aos="fade">
            <div class="hero-shipping-returns-grid">
                <div>
                    <div class="eyebrow" data-aos="fade-up" data-aos-delay="100">
                        <?php echo esc_html(get_field('shipping_returns_hero_eyebrow') ?: 'Client Care'); ?>
                    </div>
                    <h1 data-aos="fade-up" data-aos-delay="200">
                        <?php echo wp_kses_post(get_field('shipping_returns_hero_title') ?: 'Shipping & Returns'); ?>
                    </h1>
                    <p data-aos="fade-up" data-aos-delay="300">
                        <?php echo wp_kses_post(get_field('shipping_returns_hero_subtitle') ?: 'A considered experience from order to arrival. Every LOOMAAMA piece is prepared with care, checked by hand, and sent with the same attention that shaped the garment itself.'); ?>
                    </p>
                </div>

                <aside class="hero-shipping-returns-card" data-aos="fade-up" data-aos-delay="400">
                    <h3><?php echo esc_html(get_field('hero_card_title') ?: 'How do I return or exchange my online purchase?'); ?></h3>
                    <p><?php echo esc_html(get_field('hero_card_text_1') ?: 'LOOMAAMA accepts eligible returns and exchanges within 30 days of purchase.'); ?></p>
                    <p><?php echo esc_html(get_field('hero_card_text_2') ?: 'Returned items must be unworn, unused, unwashed, and returned in original condition with tags and packaging intact.'); ?></p>
                </aside>
            </div>
        </div>
    </section>

    <!-- QUICK INFO SECTION -->
    <section class="quick-info-shipping">
        <?php 
        $quick_items = get_field('quick_info_items');
        if ($quick_items):
            foreach ($quick_items as $item):
        ?>
        <div class="quick-item">
            <span><?php echo esc_html($item['label']); ?></span>
            <p><?php echo esc_html($item['text']); ?></p>
        </div>
        <?php 
            endforeach;
        else:
        ?>
        <div class="quick-item">
            <span>Shipping</span>
            <p>Australia and selected international destinations.</p>
        </div>
        <div class="quick-item">
            <span>Returns</span>
            <p>Eligible returns accepted within 30 days.</p>
        </div>
        <div class="quick-item">
            <span>Support</span>
            <p>Client Care assistance for every order.</p>
        </div>
        <?php endif; ?>
    </section>

    <!-- RETURNS & EXCHANGES SECTION -->
    <section class="section-shipping">
        <div class="narrow-shipping">
            <div class="eyebrow"><?php echo esc_html(get_field('returns_eyebrow') ?: 'Returns & Exchanges'); ?></div>
            <h2><?php echo wp_kses_post(get_field('returns_title') ?: 'A simple process, handled with care.'); ?></h2>
            <p>
                <?php echo wp_kses_post(get_field('returns_text_1') ?: 'LOOMAAMA accepts eligible returns and exchanges within 30 days of purchase. Items must be unused, unworn, unwashed, and returned with all original tags, packaging, and order documentation.'); ?>
            </p>
            <p>
                <?php echo wp_kses_post(get_field('returns_text_2') ?: 'Natural texture, fibre slubs, handwoven irregularities, and tonal variations are part of wild Himalayan nettle cloth. These qualities are not considered faults.'); ?>
            </p>

            <div class="policy-grid-shipping">
                <div class="policy-card">
                    <h3><?php echo esc_html(get_field('eligible_title') ?: 'Eligible for return'); ?></h3>
                    <ul class="policy-list">
                        <?php 
                        $eligible_items = get_field('eligible_items');
                        if ($eligible_items):
                            foreach ($eligible_items as $item):
                        ?>
                        <li><?php echo esc_html($item['text']); ?></li>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <li>Unworn and unused</li>
                        <li>Unwashed and unaltered</li>
                        <li>Original tags attached</li>
                        <li>Original packaging included</li>
                        <li>Returned within 30 days</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="policy-card">
                    <h3><?php echo esc_html(get_field('not_eligible_title') ?: 'Not eligible for return'); ?></h3>
                    <ul class="policy-list">
                        <?php 
                        $not_eligible_items = get_field('not_eligible_items');
                        if ($not_eligible_items):
                            foreach ($not_eligible_items as $item):
                        ?>
                        <li><?php echo esc_html($item['text']); ?></li>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <li>Intimate garments</li>
                        <li>Personalised or made-to-order pieces</li>
                        <li>Final sale items</li>
                        <li>Worn, washed, or altered items</li>
                        <li>Items without original tags or packaging</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- RETURN PROCESS SECTION -->
    <section class="process-shipping">
        <div class="eyebrow"><?php echo esc_html(get_field('process_eyebrow') ?: 'The Return Process'); ?></div>
        <h2><?php echo wp_kses_post(get_field('process_title') ?: 'Four steps from request to resolution.'); ?></h2>

        <div class="steps-shipping">
            <?php 
            $process_steps = get_field('process_steps');
            if ($process_steps):
                foreach ($process_steps as $index => $step):
                    $step_num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            ?>
            <div class="step">
                <span>Step <?php echo esc_html($step_num); ?></span>
                <h3><?php echo esc_html($step['title']); ?></h3>
                <p><?php echo esc_html($step['description']); ?></p>
            </div>
            <?php 
                endforeach;
            else:
            ?>
            <div class="step">
                <span>Step 01</span>
                <h3>Request Your Return</h3>
                <p>Contact Client Care with your order number and reason for return.</p>
            </div>
            <div class="step">
                <span>Step 02</span>
                <h3>Prepare Your Package</h3>
                <p>Place the item securely in its original packaging with all tags and documents.</p>
            </div>
            <div class="step">
                <span>Step 03</span>
                <h3>Send Your Return</h3>
                <p>Ship your parcel using a tracked shipping service to the address provided.</p>
            </div>
            <div class="step">
                <span>Step 04</span>
                <h3>Review & Refund</h3>
                <p>Once received and approved, your refund will be processed to the original payment method.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- NATURAL VARIATION SECTION -->
    <section class="section-shipping">
        <div class="image-text-shipping">
            <div class="image-box" style="background-image: url('<?php echo esc_url(get_field('variation_image') ?: 'https://images.unsplash.com/photo-1604014237800-1c9102c219da?auto=format&fit=crop&w=1200&q=80'); ?>');"></div>
            <div>
                <div class="eyebrow"><?php echo esc_html(get_field('variation_eyebrow') ?: 'Natural Variation'); ?></div>
                <h2><?php echo wp_kses_post(get_field('variation_title') ?: 'Made by nature. Finished by hand.'); ?></h2>
                <p>
                    <?php echo wp_kses_post(get_field('variation_text_1') ?: 'Wild Himalayan nettle is a naturally expressive fibre. Subtle variation in weave, texture, tone, and surface character is part of its authenticity.'); ?>
                </p>
                <p>
                    <?php echo wp_kses_post(get_field('variation_text_2') ?: 'These details are not imperfections. They are the visible record of origin, material, and human hands.'); ?>
                </p>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="section-shipping">
        <div class="faq-shipping">
            <div class="eyebrow"><?php echo esc_html(get_field('faq_eyebrow') ?: 'Questions'); ?></div>
            <h2><?php echo wp_kses_post(get_field('faq_title') ?: 'Shipping & return details'); ?></h2>

            <div class="divider-shipping"></div>

            <?php 
            $faq_items = get_field('faq_items');
            if ($faq_items):
                foreach ($faq_items as $item):
            ?>
            <div class="faq-item">
                <h3><?php echo esc_html($item['question']); ?></h3>
                <p><?php echo esc_html($item['answer']); ?></p>
            </div>
            <?php 
                endforeach;
            else:
            ?>
            <div class="faq-item">
                <h3>When will my order be shipped?</h3>
                <p>Orders are prepared carefully before dispatch. Processing time may vary depending on order volume and product availability.</p>
            </div>
            <div class="faq-item">
                <h3>Will I receive tracking?</h3>
                <p>Yes. Once your order has been dispatched, tracking details will be sent to your email address.</p>
            </div>
            <div class="faq-item">
                <h3>Can I exchange my item?</h3>
                <p>Exchanges may be available for the same item in another size or colour, depending on stock availability.</p>
            </div>
            <div class="faq-item">
                <h3>What if my order is faulty or incorrect?</h3>
                <p>Please contact Client Care with your order number and clear photographs. Our team will review the issue and assist you with the next step.</p>
            </div>
            <div class="faq-item">
                <h3>Are shipping fees refundable?</h3>
                <p>Shipping fees may not be refundable unless the item is confirmed to be faulty or incorrect.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="contact-shipping bg-card">
        <div class="eyebrow"><?php echo esc_html(get_field('contact_eyebrow') ?: 'Need Assistance?'); ?></div>
        <h2><?php echo wp_kses_post(get_field('contact_title') ?: 'LOOMAAMA Client Care'); ?></h2>
        <p>
            <?php echo wp_kses_post(get_field('contact_text') ?: 'For returns, exchanges, shipping enquiries, or order support, our Client Care team is here to help.'); ?>
        </p>
        <a href="<?php echo esc_url(get_field('contact_button_link') ?: 'mailto:support@loomaama.com.au'); ?>" class="button-shipping">
            <?php echo esc_html(get_field('contact_button_text') ?: 'Contact Client Care'); ?>
        </a>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize jarallax for parallax hero
    if (typeof jarallax !== 'undefined') {
        var elements = document.querySelectorAll('.jarallax');
        
        elements.forEach(function(element) {
            if (element.jarallax) {
                element.jarallax.destroy();
            }
            
            <?php if ($has_video): ?>
            jarallax(element, {
                speed: 0.5,
                videoVolume: 0,
                videoLoop: true,
                videoStartTime: 0,
                videoEndTime: 0,
                imgPosition: '50% 50%',
                imgSize: 'cover',
                imgRepeat: 'no-repeat',
                videoSrc: 'mp4:<?php echo $hero_video; ?>'
            });
            <?php else: ?>
            jarallax(element, {
                speed: 0.5,
                imgPosition: '50% 50%',
                imgSize: 'cover',
                imgRepeat: 'no-repeat'
            });
            <?php endif; ?>
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