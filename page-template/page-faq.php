<?php
/**
 * Template Name: FAQ Template
 *
 * @package Loomaama
 */

get_header();
?>

<main>
    <!-- HERO SECTION -->
    <header class="faq-hero">
        <div class="faq-hero-content">
            <div class="eyebrow"><?php echo esc_html(get_field('faq_hero_eyebrow') ?: 'FAQ'); ?></div>
            <h1><?php echo wp_kses_post(get_field('faq_hero_title') ?: 'The Quiet Answers Behind Every Piece.'); ?></h1>
            <p><?php echo wp_kses_post(get_field('faq_hero_subtitle') ?: 'A quiet guide to the material, making, care, and service behind LOOMAAMA. Written for those who look closely before they choose.'); ?></p>

            <div class="faq-support-box">
                <?php 
                $support_items = get_field('faq_support_items');
                if ($support_items):
                    foreach ($support_items as $item):
                ?>
                <div class="support-item">
                    <span><?php echo esc_html($item['label']); ?></span>
                    <strong><?php echo esc_html($item['value']); ?></strong>
                </div>
                <?php 
                    endforeach;
                else:
                ?>
                <div class="support-item">
                    <span>Email</span>
                    <strong>support@loomaama.com.au</strong>
                </div>
                <div class="support-item">
                    <span>Returns</span>
                    <strong>Within 14 days</strong>
                </div>
                <div class="support-item">
                    <span>Support</span>
                    <strong>Monday to Friday</strong>
                </div>
                <?php endif; ?>
            </div>

            <div class="faq-editorial-note">
                <span></span>
                <p><?php echo esc_html(get_field('faq_editorial_note') ?: 'Material. Craft. Care. Service.'); ?></p>
                <span></span>
            </div>
        </div>
    </header>

    <!-- SERVICE CARDS SECTION -->
    <section class="faq-section">
        <div class="faq-section-heading">
            <h2><?php echo wp_kses_post(get_field('faq_service_title') ?: 'The LOOMAAMA Service'); ?></h2>
            <p><?php echo wp_kses_post(get_field('faq_service_subtitle') ?: 'A refined path through the essentials: material, craft, fit, care, orders, shipping, and returns.'); ?></p>
        </div>

        <div class="faq-cards">
            <?php 
            $service_cards = get_field('faq_service_cards');
            if ($service_cards):
                foreach ($service_cards as $index => $card):
                    $card_num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            ?>
            <article class="faq-card">
                <span class="card-number"><?php echo esc_html($card_num); ?></span>
                <h3><?php echo esc_html($card['title']); ?></h3>
                <p><?php echo esc_html($card['description']); ?></p>
            </article>
            <?php 
                endforeach;
            else:
            ?>
            <article class="faq-card">
                <span class="card-number">01</span>
                <h3>Orders</h3>
                <p>Track, change, or understand your order before it is prepared for dispatch.</p>
            </article>
            <article class="faq-card">
                <span class="card-number">02</span>
                <h3>Shipping</h3>
                <p>Delivery timelines, tracking information, and international shipping guidance.</p>
            </article>
            <article class="faq-card">
                <span class="card-number">03</span>
                <h3>Returns</h3>
                <p>Return eligibility, condition requirements, and our 14-day return guidance.</p>
            </article>
            <article class="faq-card">
                <span class="card-number">04</span>
                <h3>Material</h3>
                <p>Learn about wild Himalayan nettle, its texture, breathability, and natural character.</p>
            </article>
            <article class="faq-card">
                <span class="card-number">05</span>
                <h3>Care</h3>
                <p>Washing, drying, storing, and preserving your LOOMAAMA garment over time.</p>
            </article>
            <article class="faq-card">
                <span class="card-number">06</span>
                <h3>Craft</h3>
                <p>Handmade variation, artisan process, slow production, and the work behind each piece.</p>
            </article>
            <article class="faq-card">
                <span class="card-number">07</span>
                <h3>Fit</h3>
                <p>Choosing the right size, natural drape, comfort, and garment movement.</p>
            </article>
            <?php endif; ?>
        </div>
    </section>

    <!-- IMAGE STORY SECTION -->
    <section class="faq-image-story">
        <?php 
        $story_images = get_field('faq_story_images');
        if ($story_images && count($story_images) >= 2):
        ?>
        <div class="image-card" style="background-image:url('<?php echo esc_url($story_images[0]['image']); ?>');">
            <div class="image-caption">
                <span><?php echo esc_html($story_images[0]['label']); ?></span>
                <strong><?php echo esc_html($story_images[0]['caption']); ?></strong>
            </div>
        </div>
        <div class="image-card small" style="background-image:url('<?php echo esc_url($story_images[1]['image']); ?>');">
            <div class="image-caption">
                <span><?php echo esc_html($story_images[1]['label']); ?></span>
                <strong><?php echo esc_html($story_images[1]['caption']); ?></strong>
            </div>
        </div>
        <?php else: ?>
        <div class="image-card" style="background-image:url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=90');">
            <div class="image-caption">
                <span>Origin</span>
                <strong>From landscape to fibre, from fibre to cloth.</strong>
            </div>
        </div>
        <div class="image-card small" style="background-image:url('https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1000&q=90');">
            <div class="image-caption">
                <span>Care</span>
                <strong>Made to be worn slowly and kept with intention.</strong>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- FAQ ACCORDION SECTION -->
    <section class="faq-section">
        <div class="faq-section-heading">
            <h2><?php echo wp_kses_post(get_field('faq_accordion_title') ?: 'Questions We Are Asked Most'); ?></h2>
            <p><?php echo wp_kses_post(get_field('faq_accordion_subtitle') ?: 'Answers shaped around the questions customers ask before choosing, wearing, and caring for LOOMAAMA.'); ?></p>
        </div>

        <div class="faq-accordion-wrap">
            <?php 
            $faq_items = get_field('faq_accordion_items');
            if ($faq_items):
                $first = true;
                foreach ($faq_items as $item):
            ?>
            <details <?php echo $first ? 'open' : ''; ?>>
                <summary><?php echo esc_html($item['question']); ?></summary>
                <p><?php echo wp_kses_post($item['answer']); ?></p>
            </details>
            <?php 
                $first = false;
                endforeach;
            else:
                // Default FAQ items
            ?>
            <details open>
                <summary>What is Himalayan nettle?</summary>
                <p>Himalayan nettle is a naturally occurring plant fibre gathered from mountain regions of Nepal. Through careful hand processes, the fibre is transformed into cloth known for durability, breathability, and natural character.</p>
            </details>
            <details>
                <summary>Why does LOOMAAMA work with Himalayan nettle?</summary>
                <p>We work with Himalayan nettle because it carries rarity, strength, breathability, and cultural knowledge. It is not chosen for trend. It is chosen because the fibre already holds meaning before it becomes cloth.</p>
            </details>
            <details>
                <summary>Is Himalayan nettle soft?</summary>
                <p>Himalayan nettle has a natural texture when new. With wear and gentle care, the cloth relaxes and becomes softer while keeping its quiet structure.</p>
            </details>
            <details>
                <summary>Is the fabric suitable for sensitive skin?</summary>
                <p>LOOMAAMA uses plant-based Himalayan nettle and designs each piece to sit naturally against the skin. If your skin is highly reactive, we recommend checking the product details and choosing the fit that feels most comfortable for you.</p>
            </details>
            <details>
                <summary>Are LOOMAAMA garments handmade?</summary>
                <p>Yes. Each LOOMAAMA piece carries skilled handwork through fibre preparation, weaving, knitting, finishing, and final inspection.</p>
            </details>
            <details>
                <summary>Why does each piece look slightly different?</summary>
                <p>Small differences in surface, texture, and finish are part of working with natural fibre and human hands. They are not flaws. They are signs of the cloth's origin.</p>
            </details>
            <details>
                <summary>How long does a LOOMAAMA piece take to make?</summary>
                <p>The timing depends on the piece. Some require days of preparation, while others move through longer stages of fibre work, hand construction, and finishing before they are ready.</p>
            </details>
            <details>
                <summary>How should I wash my garment?</summary>
                <p>Use cold water, mild detergent, and a gentle cycle or hand wash. Avoid bleach, harsh chemicals, and excessive heat. Air drying is recommended.</p>
            </details>
            <details>
                <summary>Can I machine wash Himalayan nettle?</summary>
                <p>Yes, on a delicate cold cycle. We recommend using a laundry bag for extra protection and reshaping the garment gently before air drying.</p>
            </details>
            <details>
                <summary>Can I tumble dry my garment?</summary>
                <p>We recommend air drying only. Heat can affect the natural structure of the fibre and may change the way the garment falls.</p>
            </details>
            <details>
                <summary>Will the fabric change over time?</summary>
                <p>Yes. Himalayan nettle softens, relaxes, and develops more personal character with wear. This is part of the beauty of natural cloth.</p>
            </details>
            <details>
                <summary>How do I choose the right size?</summary>
                <p>Choose the size that allows the garment to sit comfortably and move naturally. LOOMAAMA pieces are designed to feel calm on the body, not restrictive.</p>
            </details>
            <details>
                <summary>Can I change or cancel my order?</summary>
                <p>Please contact us as soon as possible after placing your order. If your order has not entered preparation or dispatch, we will do our best to help.</p>
            </details>
            <details>
                <summary>How long does shipping take?</summary>
                <p>Delivery time depends on your location, carrier movement, and customs processing where applicable. Tracking details are shared once your order has been dispatched.</p>
            </details>
            <details>
                <summary>Do you ship internationally?</summary>
                <p>LOOMAAMA ships to selected international destinations. Delivery times may vary depending on destination, customs processing, and local carrier handling.</p>
            </details>
            <details>
                <summary>Will I need to pay customs duties?</summary>
                <p>Depending on your country, local duties, taxes, or import fees may apply. These charges are determined by local authorities and are not controlled by LOOMAAMA.</p>
            </details>
            <details>
                <summary>What is your return policy?</summary>
                <p>Eligible items may be returned within 14 days of delivery, provided they remain unused, unworn, and in original condition.</p>
            </details>
            <details>
                <summary>How do I start a return?</summary>
                <p>Contact our team with your order information. We will review the request and guide you through the return process with clear next steps.</p>
            </details>
            <details>
                <summary>Can sale or final pieces be returned?</summary>
                <p>Unless required by law, sale, final, or clearly marked non-returnable pieces may not be eligible for return. Please review the product page before purchasing.</p>
            </details>
            <details>
                <summary>Can I ask for personal guidance before ordering?</summary>
                <p>Yes. Some choices deserve conversation. You may contact our team for guidance on material, fit, care, or choosing the right piece.</p>
            </details>
            <details>
                <summary>What makes LOOMAAMA different from ordinary natural-fibre clothing?</summary>
                <p>LOOMAAMA is built around one rare material, Himalayan nettle, and the hands that know how to shape it. The difference is not only in the fibre, but in the pace, restraint, and care behind each piece.</p>
            </details>
            <details>
                <summary>Is Himalayan nettle lightweight or heavy?</summary>
                <p>The feel depends on the construction of the piece. Some LOOMAAMA garments are light and open, while others carry more structure. Each product page explains the intended feel and drape.</p>
            </details>
            <details>
                <summary>Does Himalayan nettle wrinkle?</summary>
                <p>Natural fibres may crease with movement and wear. We see this as part of the fabric's living character, not something that needs to be hidden.</p>
            </details>
            <details>
                <summary>Can I iron or steam my garment?</summary>
                <p>Light steaming is usually the gentlest option. If ironing is needed, use low heat and place a clean cloth between the iron and the garment.</p>
            </details>
            <details>
                <summary>How should I store my LOOMAAMA piece?</summary>
                <p>Store it clean, dry, and away from harsh sunlight. Fold heavier pieces to protect their shape and hang lighter pieces only when the garment structure allows it.</p>
            </details>
            <details>
                <summary>Will the colour look exactly like the website?</summary>
                <p>We photograph each piece carefully, but screen settings and natural fibre variation may cause slight differences. The final piece may carry subtle tonal character of its own.</p>
            </details>
            <details>
                <summary>Do you offer exchanges?</summary>
                <p>Exchange availability depends on stock and eligibility. Contact our team within the return window and we will guide you through the available options.</p>
            </details>
            <details>
                <summary>What condition must a return be in?</summary>
                <p>Returned pieces must be unused, unworn, unwashed, and in original condition with any original packaging or tags included where applicable.</p>
            </details>
            <details>
                <summary>What if my order arrives damaged or incorrect?</summary>
                <p>Please contact us as soon as possible with your order details and clear photos. Our team will review the issue and help resolve it with care.</p>
            </details>
            <details>
                <summary>Can I gift a LOOMAAMA piece?</summary>
                <p>Yes. LOOMAAMA pieces are suited to considered gifting. If you need guidance before choosing, our team can help you select a piece with the right material, fit, and feeling.</p>
            </details>
            <?php endif; ?>
        </div>
    </section>

    <!-- SPLIT VISUAL SECTION -->
    <section class="faq-split-visual">
        <?php 
        $visual_items = get_field('faq_visual_items');
        if ($visual_items):
            foreach ($visual_items as $item):
        ?>
        <div class="mini-image" data-label="<?php echo esc_attr($item['label']); ?>" style="background-image:url('<?php echo esc_url($item['image']); ?>');"></div>
        <?php 
            endforeach;
        else:
        ?>
        <div class="mini-image" data-label="Material" style="background-image:url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=900&q=90');"></div>
        <div class="mini-image" data-label="Touch" style="background-image:url('https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=900&q=90');"></div>
        <div class="mini-image" data-label="Stillness" style="background-image:url('https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=90');"></div>
        <?php endif; ?>
    </section>

    <!-- CONTACT SECTION -->
    <section class="faq-contact" id="contact">
        <div class="faq-contact-panel">
            <h2><?php echo wp_kses_post(get_field('faq_contact_title') ?: 'Not every answer lives here.'); ?></h2>
            <p><?php echo wp_kses_post(get_field('faq_contact_text') ?: 'For material, fit, order, or return guidance, our team will respond with care and clarity.'); ?></p>
            <a href="<?php echo esc_url(get_field('faq_contact_button_link') ?: 'mailto:support@loomaama.com.au'); ?>" class="faq-button">
                <?php echo esc_html(get_field('faq_contact_button_text') ?: 'Contact Our Team'); ?>
            </a>
        </div>
    </section>
</main>

<?php get_footer(); ?>