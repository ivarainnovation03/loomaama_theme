<?php
/**
 * Template Name: Single Artisan Detail
 * Template Post Type: artisan
 *
 * @package Loomaama
 */

get_header();
?>

<?php
if (have_posts()) :
    while (have_posts()) : the_post();
        $artisan_id = get_the_ID();
        
        // Get artisan metadata (using correct meta keys from scf-export.json)
        $years_crafting = get_post_meta($artisan_id, 'years_crafting', true);
        $apprentices = get_post_meta($artisan_id, 'apprentices', true);
        $pieces_made = get_post_meta($artisan_id, 'pieces_made', true);
        $custom_commissions = get_post_meta($artisan_id, 'custom_commissions', true);
        $lead_time = get_post_meta($artisan_id, 'lead_time', true);
        $support_description = get_post_meta($artisan_id, 'support_description', true);
        $quote = get_post_meta($artisan_id, 'quote', true);
        $process_description = get_post_meta($artisan_id, 'process_description', true);
        $process_specialty = get_post_meta($artisan_id, 'process_specialty', true);
        
        // Get artisan taxonomies - FIXED taxonomy names to match scf-export.json
        $type_terms = get_the_terms($artisan_id, 'artisan-type');  // Changed from 'artisan-types'
        $location_terms = get_the_terms($artisan_id, 'artisan-location');  // Changed from 'artisans-location'
        
        // Get all type terms (if multiple)
        $all_types = array();
        if ($type_terms && !is_wp_error($type_terms)) {
            foreach ($type_terms as $term) {
                $all_types[] = $term->name;
            }
        }
        
        // Get all location terms
        $all_locations = array();
        if ($location_terms && !is_wp_error($location_terms)) {
            foreach ($location_terms as $term) {
                $all_locations[] = $term->name;
            }
        }
        
        // Get featured image
        $featured_image = get_the_post_thumbnail_url($artisan_id, 'full');
        
        // Get gallery images - FIXED to handle different return formats
        $gallery_images = get_post_meta($artisan_id, 'gallery_images', true);
        
        // Handle gallery images (could be array or string)
        $gallery_array = array();
        if (!empty($gallery_images)) {
            if (is_array($gallery_images)) {
                $gallery_array = $gallery_images;
            } elseif (is_string($gallery_images)) {
                $gallery_array = explode(',', $gallery_images);
            }
        }
        
        // Get artisan name for dynamic text
        $artisan_name = get_the_title();
?>

<!-- Artisan Profile Hero -->
<section class="profile-hero mt-1 mb-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="profile-image-container position-relative">
                    <?php if ($featured_image) : ?>
                        <img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title_attribute(); ?>" class="img-fluid w-100">
                    <?php else : ?>
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-artisan.jpg'); ?>" alt="<?php the_title_attribute(); ?>" class="img-fluid w-100">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-7">
                <?php if (!empty($all_types)) : ?>
                    <?php foreach ($all_types as $type) : ?>
                        <span class="rounded-pill bg-primary-light artisan-tag letter-spacing-wide d-inline-block mb-3"><?php echo esc_html($type); ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <h1 class="display-3 serif mb-3"><?php the_title(); ?></h1>
                
                <?php if (!empty($all_locations)) : ?>
                    <p class="h5 text-muted mb-4 serif">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <?php echo esc_html(implode(', ', $all_locations)); ?>
                    </p>
                <?php endif; ?>
                
                <div class="lead text-dark mb-5">
                    <?php the_excerpt(); ?>
                </div>
                
                <div class="row g-3">
                    <?php if ($years_crafting) : ?>
                    <div class="col-sm-4 col-6">
                        <div class="stat-box text-center p-3 bg-light">
                            <span class="d-block h3 serif mb-0 text-brand"><?php echo esc_html($years_crafting); ?></span>
                            <span class="small text-uppercase fw-bold text-muted"><?php _e('Years Crafting', 'loomaama'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($apprentices) : ?>
                    <div class="col-sm-4 col-6">
                        <div class="stat-box text-center p-3 bg-light">
                            <span class="d-block h3 serif mb-0 text-brand"><?php echo esc_html($apprentices); ?></span>
                            <span class="small text-uppercase fw-bold text-muted"><?php _e('Apprentices', 'loomaama'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($pieces_made) : ?>
                    <div class="col-sm-4 col-6">
                        <div class="stat-box text-center p-3 bg-light">
                            <span class="d-block h3 serif mb-0 text-brand"><?php echo number_format((int)$pieces_made); ?></span>
                            <span class="small text-uppercase fw-bold text-muted"><?php _e('Pieces Made', 'loomaama'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Narrative Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="narrative-text">
                    <?php the_content(); ?>
                    
                    <?php if ($quote) : ?>
                    <div class="quote-block my-5 p-4 border-start border-4 border-primary bg-light">
                        <i class="fas fa-quote-left fa-2x text-muted opacity-50 mb-3 d-block"></i>
                        <h3 class="serif h4 fst-italic mb-0">"<?php echo esc_html($quote); ?>"</h3>
                        <p class="text-muted mt-2 mb-0">— <?php echo esc_html($artisan_name); ?></p>
                    </div>
                    <?php endif; ?>
                    
                   
                    
                    
                </div>

                <!-- Process Gallery -->
                <?php if (!empty($gallery_array)) : ?>
                <div class="mt-5">
                    <h4 class="serif mb-4"><?php _e('Process Gallery', 'loomaama'); ?></h4>
                    <div class="row g-3">
                        <?php foreach (array_slice($gallery_array, 0, 4) as $index => $image_url) : ?>
                        <div class="col-md-6">
                            <div class="gallery-item">
                                <img src="<?php echo esc_url($image_url); ?>" class="img-fluid w-100" style="height: 250px; object-fit: cover;" alt="<?php printf(__('Process image %d of %s', 'loomaama'), $index + 1, $artisan_name); ?>">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="support-card shadow-sm p-4 sticky-top" style="top: 100px;">
                    <h4 class="serif mb-3">
                        <?php 
                        // Dynamic gender-neutral support text
                        printf(__('Support %s\'s Work', 'loomaama'), $artisan_name); 
                        ?>
                    </h4>
                    
                    <?php if ($support_description) : ?>
                        <p class="small text-muted mb-4"><?php echo nl2br(esc_html($support_description)); ?></p>
                    <?php else : ?>
                        <p class="small text-muted mb-4">
                            <?php printf(__('A portion of every sale from %s\'s collection goes directly to support their community and craft.', 'loomaama'), $artisan_name); ?>
                        </p>
                    <?php endif; ?>
                    
                    <hr class="my-4">
                    
                    <?php if ($custom_commissions) : ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                            <span class="small fw-bold"><?php _e('Accepting Custom Commissions', 'loomaama'); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($lead_time) : ?>
                        <p class="small text-muted mb-4">
                            <?php printf(__('Estimated lead time for custom pieces is currently %s.', 'loomaama'), esc_html($lead_time)); ?>
                        </p>
                    <?php endif; ?>
                    
                    <button class="btn btn-dark w-100 rounded-pill py-3 fw-bold mb-3" data-bs-toggle="modal" data-bs-target="#commissionModal">
                        <?php _e('INQUIRE ABOUT COMMISSIONS', 'loomaama'); ?>
                    </button>
                    
                    <button class="btn btn-outline-dark w-100 rounded-pill py-3 fw-bold" onclick="window.location.href='<?php echo esc_url(get_post_type_archive_link('artisan')); ?>'">
                        <?php _e('MEET MORE ARTISANS', 'loomaama'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products Section (Optional - if WooCommerce integration exists) -->
<?php
// You can add a query here to show products made by this artisan
// This assumes products have a relationship field or taxonomy linking to artisans
$related_products = array();
// Add your product query logic here if needed
?>

<?php if (!empty($related_products)) : ?>
<section class="related-products py-5 bg-light">
    <div class="container">
        <h2 class="serif text-center mb-5"><?php printf(__('Shop %s\'s Creations', 'loomaama'), $artisan_name); ?></h2>
        <div class="row g-4">
            <?php foreach ($related_products as $product) : ?>
                <!-- Product card here -->
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Commission Modal -->
<div class="modal fade" id="commissionModal" tabindex="-1" aria-labelledby="commissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title serif" id="commissionModalLabel">
                    <?php printf(__('Custom Commission Inquiry for %s', 'loomaama'), $artisan_name); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">
                    <?php printf(__('You\'re inquiring about a custom commission with %s. Please fill out the form below and we\'ll get back to you within 2-3 business days.', 'loomaama'), $artisan_name); ?>
                </p>
                
                <form id="commissionForm" method="post">
                    <div class="mb-3">
                        <label for="commissionName" class="form-label"><?php _e('Your Name', 'loomaama'); ?> <span class="text-brand">*</span></label>
                        <input type="text" class="form-control" id="commissionName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="commissionEmail" class="form-label"><?php _e('Your Email', 'loomaama'); ?> <span class="text-brand">*</span></label>
                        <input type="email" class="form-control" id="commissionEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="commissionMessage" class="form-label"><?php _e('Tell us about your custom piece', 'loomaama'); ?> <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="commissionMessage" name="message" rows="4" placeholder="<?php esc_attr_e('Describe what you\'re looking for (colors, dimensions, style, etc.)', 'loomaama'); ?>" required></textarea>
                    </div>
                    <input type="hidden" name="artisan_id" value="<?php echo esc_attr($artisan_id); ?>">
                    <input type="hidden" name="artisan_name" value="<?php echo esc_attr($artisan_name); ?>">
                    <button type="submit" class="btn btn-dark w-100 py-2 fw-bold"><?php _e('SUBMIT INQUIRY', 'loomaama'); ?></button>
                </form>
                
                <p class="small text-muted mt-3">
                    <?php _e('We\'ll respond within 2-3 business days with availability and pricing details.', 'loomaama'); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
    endwhile;
else :
    ?>
    <section class="py-5">
        <div class="container text-center py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <i class="fas fa-user-circle fa-4x text-muted mb-4 opacity-25"></i>
                    <h2 class="serif mb-3"><?php _e('Artisan Not Found', 'loomaama'); ?></h2>
                    <p class="text-muted mb-4">
                        <?php _e('Sorry, the artisan you\'re looking for doesn\'t exist or has been removed.', 'loomaama'); ?>
                    </p>
                    <a href="<?php echo esc_url(get_post_type_archive_link('artisan')); ?>" class="btn btn-dark rounded-pill px-5 py-3">
                        <?php _e('Back to Artisans Directory', 'loomaama'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php
endif;
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle commission form submission
    const commissionForm = document.getElementById('commissionForm');
    if (commissionForm) {
        commissionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'submit_commission_inquiry');
            
            fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('<?php _e('Thank you for your inquiry! We\'ll get back to you soon.', 'loomaama'); ?>');
                    bootstrap.Modal.getInstance(document.getElementById('commissionModal')).hide();
                    commissionForm.reset();
                } else {
                    alert('<?php _e('There was an error. Please try again.', 'loomaama'); ?>');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('<?php _e('There was an error. Please try again or contact us directly.', 'loomaama'); ?>');
            });
        });
    }
    
    // Add smooth scroll to sections
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});
</script>

<style>
/* Additional styles for single artisan page */
.profile-hero {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 4rem 0;
}

.stat-box {
    transition: transform 0.3s ease;
    border-radius: 8px;
}

.stat-box:hover {
    transform: translateY(-5px);
}

.artisan-tag {
    background: rgba(var(--bs-primary-rgb), 0.1);
    color: var(--bs-primary);
    padding: 0.35rem 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.quote-block {
    border-left-width: 4px !important;
    transition: all 0.3s ease;
}

.quote-block:hover {
    transform: translateX(5px);
}

.support-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.support-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.gallery-item {
    overflow: hidden;
    border-radius: 8px;
    cursor: pointer;
}

.gallery-item img {
    transition: transform 0.5s ease;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .profile-hero {
        padding: 2rem 0;
    }
    
    .display-3 {
        font-size: 2.5rem;
    }
}
</style>

<?php get_footer(); ?>