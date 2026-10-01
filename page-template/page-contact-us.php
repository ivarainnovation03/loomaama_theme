<?php
/**
 * Template Name: Contact Us Template
 *
 * @package Loomaama
 */

get_header();
?>

<main>
    <!-- HERO SECTION WITH FLOATING ELEMENTS -->
    <header class="contact-hero">
        <div class="contact-hero-copy">
            <div class="eyebrow"><?php echo esc_html(get_field('contact_hero_eyebrow') ?: 'Loomaama Client Care'); ?></div>
            <h1><?php echo wp_kses_post(get_field('contact_hero_title') ?: 'Contact Us'); ?></h1>
            <p><?php echo wp_kses_post(get_field('contact_hero_subtitle') ?: 'Every garment begins with a conversation. If you need guidance, support, or answers, we are here to assist.'); ?></p>
        </div>
        <div class="contact-hero-art">
            <div class="orbit"></div>
            <div class="art-main" style="background-image: url('<?php echo esc_url(get_field('hero_main_image') ?: 'https://images.unsplash.com/photo-1515488764276-beab7607c1e6?auto=format&fit=crop&w=1800&q=80'); ?>');"></div>
            <div class="art-secondary" style="background-image: url('<?php echo esc_url(get_field('hero_secondary_image') ?: 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=1200&q=80'); ?>');"></div>
            <div class="hero-note">
                <div class="eyebrow"><?php echo esc_html(get_field('hero_note_eyebrow') ?: 'Care Note'); ?></div>
                <p><?php echo wp_kses_post(get_field('hero_note_text') ?: 'For fit help, include your measurements and the piece you are considering.'); ?></p>
            </div>
        </div>
    </header>

    <!-- PATHS / QUICK CARDS SECTION -->
    <section class="contact-paths">
        <div class="path-grid">
            <?php 
            $path_cards = get_field('path_cards');
            if ($path_cards):
                foreach ($path_cards as $index => $card):
                    $card_num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            ?>
            <div class="path-card">
                <span><?php echo esc_html($card_num); ?> · <?php echo esc_html($card['label']); ?></span>
                <h3><?php echo esc_html($card['title']); ?></h3>
                <p><?php echo esc_html($card['description']); ?></p>
            </div>
            <?php 
                endforeach;
            else:
            ?>
            <div class="path-card"><span>01 · Fit</span><h3>Size Guidance</h3><p>For choosing the right shape, size, and drape.</p></div>
            <div class="path-card"><span>02 · Care</span><h3>Garment Care</h3><p>For washing, drying, storing, and caring for nettle cloth.</p></div>
            <div class="path-card"><span>03 · Orders</span><h3>Order Support</h3><p>For shipping, tracking, returns, and exchanges.</p></div>
            <div class="path-card"><span>04 · Brand</span><h3>General Inquiry</h3><p>For collaborations, press, and questions about Loomaama.</p></div>
            <?php endif; ?>
        </div>
    </section>

    <!-- SUPPORT STAGE SECTION -->
    <section class="contact-support-stage">
        <div class="contact-intro">
            <div class="eyebrow"><?php echo esc_html(get_field('support_eyebrow') ?: 'What Do You Need?'); ?></div>
            <h2><?php echo wp_kses_post(get_field('support_title') ?: 'Support by moment, not by menu.'); ?></h2>
            <p><?php echo wp_kses_post(get_field('support_subtitle') ?: 'Every inquiry is guided to the right place, so your question receives the attention it deserves.'); ?></p>
        </div>
        <div class="support-grid">
            <?php 
            $support_cards = get_field('support_cards');
            if ($support_cards):
                foreach ($support_cards as $index => $card):
                    $card_num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            ?>
            <div class="support-card">
                <div class="num"><?php echo esc_html($card_num); ?></div>
                <h3><?php echo esc_html($card['title']); ?></h3>
                <p><?php echo esc_html($card['description']); ?></p>
            </div>
            <?php 
                endforeach;
            else:
            ?>
            <div class="support-card"><div class="num">01</div><h3>Before You Order</h3><p>Product guidance, garment measurements, size advice, material questions, and styling support.</p></div>
            <div class="support-card"><div class="num">02</div><h3>After You Order</h3><p>Order confirmation, shipping, delivery tracking, address changes, and payment questions.</p></div>
            <div class="support-card"><div class="num">03</div><h3>After You Receive</h3><p>Care guidance, returns, exchanges, packaging questions, and long-term garment support.</p></div>
            <?php endif; ?>
        </div>
    </section>

    <!-- FORM CINEMA SECTION (Split Layout) -->
    <section class="contact-form-cinema">
        <div class="form-side">
            <div class="eyebrow"><?php echo esc_html(get_field('form_eyebrow') ?: 'Contact Form'); ?></div>
            <h2><?php echo wp_kses_post(get_field('form_title') ?: 'Share the details with us.'); ?></h2>
            <p><?php echo wp_kses_post(get_field('form_subtitle') ?: 'The more we understand your garment, order, or question, the better we can guide you.'); ?></p>
        </div>
        
        <form class="form-box" id="contact-form" method="POST">
            <div class="form-grid">
                <div class="field">
                    <label><?php echo esc_html(get_field('form_first_name_label') ?: 'First Name'); ?></label>
                    <input type="text" name="first_name" placeholder="<?php esc_attr_e('Your first name', 'loomaama'); ?>" required>
                </div>
                <div class="field">
                    <label><?php echo esc_html(get_field('form_last_name_label') ?: 'Last Name'); ?></label>
                    <input type="text" name="last_name" placeholder="<?php esc_attr_e('Your last name', 'loomaama'); ?>" required>
                </div>
                <div class="field">
                    <label><?php echo esc_html(get_field('form_email_label') ?: 'Email'); ?></label>
                    <input type="email" name="email" placeholder="<?php esc_attr_e('you@example.com', 'loomaama'); ?>" required>
                </div>
                <div class="field">
                    <label><?php echo esc_html(get_field('form_inquiry_label') ?: 'Inquiry Type'); ?></label>
                    <select name="inquiry_type">
                        <?php 
                        $inquiry_types = get_field('inquiry_types');
                        if ($inquiry_types):
                            foreach ($inquiry_types as $type):
                        ?>
                        <option value="<?php echo esc_attr($type['value']); ?>"><?php echo esc_html($type['label']); ?></option>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <option>Size & Fit</option>
                        <option>Order Support</option>
                        <option>Care Help</option>
                        <option>Returns & Exchanges</option>
                        <option>Packaging</option>
                        <option>Collaboration / Press</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="field full">
                    <label><?php echo esc_html(get_field('form_order_label') ?: 'Order Number, if applicable'); ?></label>
                    <input type="text" name="order_number" placeholder="<?php esc_attr_e('Optional', 'loomaama'); ?>">
                </div>
                <div class="field full">
                    <label><?php echo esc_html(get_field('form_message_label') ?: 'Message'); ?></label>
                    <textarea name="message" placeholder="<?php esc_attr_e('Write your question here.', 'loomaama'); ?>" required></textarea>
                </div>
            </div>
            <?php wp_nonce_field('contact_form_submit', 'contact_nonce'); ?>
            <input type="hidden" name="action" value="submit_contact_form">
            <button type="submit" class="contact-button"><?php echo esc_html(get_field('form_button_text') ?: 'Submit Inquiry'); ?></button>
            <div id="form-message" class="form-message" style="display:none;"></div>
        </form>
    </section>

    <!-- IMAGE RIBBON SECTION -->
    <section class="contact-image-ribbon" style="background-image: url('<?php echo esc_url(get_field('ribbon_image') ?: 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=2000&q=80'); ?>');">
        <h2><?php echo wp_kses_post(get_field('ribbon_text') ?: 'The right answer should feel as considered as the garment itself.'); ?></h2>
    </section>

    <!-- DETAIL / BEFORE YOU WRITE SECTION -->
    <section class="contact-section">
        <div class="contact-intro">
            <div class="eyebrow"><?php echo esc_html(get_field('details_eyebrow') ?: 'Before You Write'); ?></div>
            <h2><?php echo wp_kses_post(get_field('details_title') ?: 'Useful details help us guide you better.'); ?></h2>
        </div>
        <div class="detail-list">
            <?php 
            $detail_items = get_field('detail_items');
            if ($detail_items):
                foreach ($detail_items as $item):
            ?>
            <div class="detail-row">
                <strong><?php echo esc_html($item['title']); ?></strong>
                <p><?php echo esc_html($item['description']); ?></p>
            </div>
            <?php 
                endforeach;
            else:
            ?>
            <div class="detail-row"><strong>For fit help</strong><p>Send your height, usual size, bust or chest, waist, hip, and the product you are considering.</p></div>
            <div class="detail-row"><strong>For order help</strong><p>Include your order number, email used at checkout, and a short description of the issue.</p></div>
            <div class="detail-row"><strong>For care help</strong><p>Tell us the garment type and whether your question is about washing, drying, storage, or repair guidance.</p></div>
            <div class="detail-row"><strong>For collaborations</strong><p>Share your name, organisation, purpose of inquiry, and any relevant links.</p></div>
            <?php endif; ?>
        </div>
    </section>

    <!-- FINAL CTA SECTION -->
    <section class="contact-final">
        <div class="final-inner">
            <div class="eyebrow"><?php echo esc_html(get_field('final_eyebrow') ?: 'Still Unsure?'); ?></div>
            <h2><?php echo wp_kses_post(get_field('final_title') ?: 'Every question deserves a considered answer.'); ?></h2>
            <p><?php echo wp_kses_post(get_field('final_text') ?: 'Send us what you know. We will help with the rest.'); ?></p>
            <a class="contact-button-final" href="#form"><?php echo esc_html(get_field('final_button_text') ?: 'Contact Loomaama'); ?></a>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const messageDiv = document.getElementById('form-message');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            formData.append('action', 'loomaama_contact_submit');
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    messageDiv.textContent = data.data.message || 'Your message has been sent. We\'ll respond within 48 hours.';
                    messageDiv.className = 'form-message success';
                    messageDiv.style.display = 'block';
                    form.reset();
                } else {
                    messageDiv.textContent = data.data.message || 'There was an error. Please try again.';
                    messageDiv.className = 'form-message error';
                    messageDiv.style.display = 'block';
                }
            } catch (error) {
                messageDiv.textContent = 'Network error. Please try again.';
                messageDiv.className = 'form-message error';
                messageDiv.style.display = 'block';
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                
                // Auto-hide message after 5 seconds
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 5000);
            }
        });
    }
});
</script>

<?php get_footer(); ?>