<?php
/**
 * Template Name: Artisans Archive
 * Template Post Type: page
 *
 * @package Loomaama
 */

get_header();

// Get query variables
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$search_term = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
$artisan_type = isset($_GET['artisan_type']) ? sanitize_text_field($_GET['artisan_type']) : '';
$artisan_location = isset($_GET['artisan_location']) ? sanitize_text_field($_GET['artisan_location']) : '';

// Build query arguments
$args = array(
    'post_type' => 'artisan',
    'posts_per_page' => 12,
    'paged' => $paged,
    'post_status' => 'publish',
);

// Add search filter
if (!empty($search_term)) {
    $args['s'] = $search_term;
}

// Add taxonomy filters - FIXED taxonomy names
$tax_query = array();
if (!empty($artisan_type)) {
    $tax_query[] = array(
        'taxonomy' => 'artisan-type',  // From scf-export.json
        'field' => 'slug',
        'terms' => $artisan_type,
    );
}
if (!empty($artisan_location)) {
    $tax_query[] = array(
        'taxonomy' => 'artisan-location',  // From scf-export.json
        'field' => 'slug',
        'terms' => $artisan_location,
    );
}
if (!empty($tax_query)) {
    $args['tax_query'] = $tax_query;
}

$artisans_query = new WP_Query($args);

// Get all taxonomy terms for filters
$artisan_types = get_terms(array(
    'taxonomy' => 'artisan-type',
    'hide_empty' => true,
));
$artisan_locations = get_terms(array(
    'taxonomy' => 'artisan-location',
    'hide_empty' => true,
));
?>

<!-- Archive Header -->
<section class="archive-header py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <span class="small fw-bold text-muted text-uppercase letter-spacing-wide mb-2 d-block">
                    <?php _e('Our Community', 'loomaama'); ?>
                </span>
                <h1 class="display-3 serif mb-4">
                    <?php _e('Meet the Artisans', 'loomaama'); ?>
                </h1>
                <p class="lead text-muted">
                    <?php _e('Discover the skilled hands and passionate hearts behind each handcrafted piece. Every artisan brings generations of tradition and unique artistic vision to their work.', 'loomaama'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Filter Bar -->
<section class="filter-bar py-4 border-bottom sticky-filter">
    <div class="container">
        <div class="row align-items-center g-3">
            <!-- Search Form - FIXED archive link -->
            <div class="col-md-4">
                <form method="get" action="<?php echo esc_url(get_post_type_archive_link('artisan')); ?>" class="search-form">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control bg-transparent border-end-0 rounded-0" 
                               placeholder="<?php esc_attr_e('Search artisans...', 'loomaama'); ?>" 
                               value="<?php echo esc_attr($search_term); ?>">
                        <button class="btn btn-outline-dark border-start-0 rounded-0" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Filter Toggle for Mobile -->
            <div class="col-md-8">
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-dark rounded-0 filter-toggle d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="fas fa-filter me-2"></i>
                        <?php _e('Filter Artisans', 'loomaama'); ?>
                    </button>
                    
                    <!-- Desktop Filters -->
                    <div class="d-none d-md-flex gap-3">
                        <?php if (!empty($artisan_types)) : ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-dark rounded-0 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <?php echo !empty($artisan_type) ? esc_html(get_term_by('slug', $artisan_type, 'artisan-type')->name) : __('Artisan Type', 'loomaama'); ?>
                            </button>
                            <ul class="dropdown-menu rounded-0">
                                <li><a class="dropdown-item <?php echo empty($artisan_type) ? 'active' : ''; ?>" href="<?php echo esc_url(remove_query_arg('artisan_type')); ?>"><?php _e('All Types', 'loomaama'); ?></a></li>
                                <?php foreach ($artisan_types as $type) : ?>
                                <li>
                                    <a class="dropdown-item <?php echo $artisan_type == $type->slug ? 'active' : ''; ?>" 
                                       href="<?php echo esc_url(add_query_arg('artisan_type', $type->slug)); ?>">
                                        <?php echo esc_html($type->name); ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($artisan_locations)) : ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-dark rounded-0 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <?php echo !empty($artisan_location) ? esc_html(get_term_by('slug', $artisan_location, 'artisan-location')->name) : __('Location', 'loomaama'); ?>
                            </button>
                            <ul class="dropdown-menu rounded-0">
                                <li><a class="dropdown-item <?php echo empty($artisan_location) ? 'active' : ''; ?>" href="<?php echo esc_url(remove_query_arg('artisan_location')); ?>"><?php _e('All Locations', 'loomaama'); ?></a></li>
                                <?php foreach ($artisan_locations as $location) : ?>
                                <li>
                                    <a class="dropdown-item <?php echo $artisan_location == $location->slug ? 'active' : ''; ?>" 
                                       href="<?php echo esc_url(add_query_arg('artisan_location', $location->slug)); ?>">
                                        <?php echo esc_html($location->name); ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($search_term) || !empty($artisan_type) || !empty($artisan_location)) : ?>
                        <a href="<?php echo esc_url(get_post_type_archive_link('artisan')); ?>" class="btn btn-link text-decoration-none">
                            <i class="fas fa-times me-1"></i>
                            <?php _e('Clear All', 'loomaama'); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Collapsible Filters -->
        <div class="collapse mt-3 d-md-none" id="filterCollapse">
            <div class="card rounded-0 border-0 bg-light p-3">
                <?php if (!empty($artisan_types)) : ?>
                <div class="mb-3">
                    <label class="fw-bold mb-2 small text-uppercase"><?php _e('Artisan Type', 'loomaama'); ?></label>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo esc_url(remove_query_arg('artisan_type')); ?>" class="btn btn-sm <?php echo empty($artisan_type) ? 'btn-dark' : 'btn-outline-dark'; ?> rounded-0">
                            <?php _e('All', 'loomaama'); ?>
                        </a>
                        <?php foreach ($artisan_types as $type) : ?>
                        <a href="<?php echo esc_url(add_query_arg('artisan_type', $type->slug)); ?>" 
                           class="btn btn-sm <?php echo $artisan_type == $type->slug ? 'btn-dark' : 'btn-outline-dark'; ?> rounded-0">
                            <?php echo esc_html($type->name); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($artisan_locations)) : ?>
                <div class="mb-3">
                    <label class="fw-bold mb-2 small text-uppercase"><?php _e('Location', 'loomaama'); ?></label>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo esc_url(remove_query_arg('artisan_location')); ?>" class="btn btn-sm <?php echo empty($artisan_location) ? 'btn-dark' : 'btn-outline-dark'; ?> rounded-0">
                            <?php _e('All', 'loomaama'); ?>
                        </a>
                        <?php foreach ($artisan_locations as $location) : ?>
                        <a href="<?php echo esc_url(add_query_arg('artisan_location', $location->slug)); ?>" 
                           class="btn btn-sm <?php echo $artisan_location == $location->slug ? 'btn-dark' : 'btn-outline-dark'; ?> rounded-0">
                            <?php echo esc_html($location->name); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Results Info -->
<?php if ($artisans_query->have_posts()) : ?>
<section class="results-info py-3 border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <span class="small text-muted">
                <?php printf(
                    __('Showing %d of %d artisans', 'loomaama'),
                    $artisans_query->post_count,
                    $artisans_query->found_posts
                ); ?>
            </span>
            <?php if (!empty($search_term)) : ?>
            <span class="small text-muted">
                <?php printf(__('Search results for: "%s"', 'loomaama'), esc_html($search_term)); ?>
            </span>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Artisans Grid -->
<section class="artisans-grid py-5">
    <div class="container">
        <div class="row g-4">
            <?php while ($artisans_query->have_posts()) : $artisans_query->the_post();
                $artisan_id = get_the_ID();
                
                // Get artisan metadata
                $years_crafting = get_post_meta($artisan_id, 'years_crafting', true);
                $apprentices = get_post_meta($artisan_id, 'apprentices', true);
                $type_terms = get_the_terms($artisan_id, 'artisan-type');
                $location_terms = get_the_terms($artisan_id, 'artisan-location');
                $featured_image = get_the_post_thumbnail_url($artisan_id, 'loomaama-product-medium');
                $quote = get_post_meta($artisan_id, 'quote', true);
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="artisan-card h-100 bg-white border hover-shadow transition">
                    <!-- Artisan Image -->
                    <div class="artisan-card__image position-relative overflow-hidden">
                        <a href="<?php the_permalink(); ?>" class="d-block">
                            <?php if ($featured_image) : ?>
                                <img src="<?php echo esc_url($featured_image); ?>" 
                                     alt="<?php the_title_attribute(); ?>" 
                                     class="w-100 h-100 object-fit-cover transition"
                                     loading="lazy">
                            <?php else : ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="fas fa-user-circle fa-4x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                        
                        <!-- Artisan Type Badge -->
                        <?php if ($type_terms && !is_wp_error($type_terms)) : ?>
                            <span class="artisan-type-badge position-absolute top-0 start-0 m-3">
                                <?php echo esc_html($type_terms[0]->name); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Artisan Info -->
                    <div class="artisan-card__content p-4">
                        <h3 class="artisan-card__title h5 mb-2">
                            <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none hover-brand">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <?php if ($location_terms && !is_wp_error($location_terms)) : ?>
                            <p class="artisan-card__location small text-muted mb-3">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                <?php echo esc_html($location_terms[0]->name); ?>
                            </p>
                        <?php endif; ?>

                        <!-- Stats -->
                        <div class="artisan-card__stats d-flex gap-3 mb-3 pb-2 border-bottom">
                            <?php if ($years_crafting) : ?>
                            <div class="stat-item text-center">
                                <span class="stat-value d-block fw-bold h6 mb-0"><?php echo esc_html($years_crafting); ?></span>
                                <span class="stat-label small text-muted"><?php _e('Years', 'loomaama'); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($apprentices) : ?>
                            <div class="stat-item text-center">
                                <span class="stat-value d-block fw-bold h6 mb-0"><?php echo esc_html($apprentices); ?></span>
                                <span class="stat-label small text-muted"><?php _e('Apprentices', 'loomaama'); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Quote -->
                        <?php if ($quote) : ?>
                            <p class="artisan-card__quote small text-muted font-italic mb-3">
                                <i class="fas fa-quote-left me-1 opacity-50"></i>
                                <?php echo wp_trim_words($quote, 15, '...'); ?>
                            </p>
                        <?php endif; ?>

                        <!-- Excerpt -->
                        <p class="artisan-card__excerpt small text-secondary mb-3">
                            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                        </p>

                        <!-- View Button -->
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline-dark rounded-0 w-100 py-2 text-uppercase letter-spacing-wide" style="font-size: 11px;">
                            <?php _e('Discover Artisan', 'loomaama'); ?>
                            
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <!-- Pagination - FIXED add_args parameter names -->
        <?php if ($artisans_query->max_num_pages > 1) : ?>
        <div class="pagination-wrapper mt-5">
            <?php
            echo paginate_links(array(
                'total' => $artisans_query->max_num_pages,
                'current' => $paged,
                'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Previous', 'loomaama'),
                'next_text' => __('Next', 'loomaama') . ' <i class="fas fa-chevron-right"></i>',
                'type' => 'list',
                'add_args' => array_filter(array(
                    'search' => $search_term,
                    'artisan_type' => $artisan_type,
                    'artisan_location' => $artisan_location,
                )),
            ));
            ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php else : ?>

<!-- No Results -->
<section class="no-results py-5">
    <div class="container text-center py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="fas fa-users fa-4x text-muted mb-4 opacity-25"></i>
                <h2 class="serif mb-3"><?php _e('No Artisans Found', 'loomaama'); ?></h2>
                <p class="text-muted mb-4">
                    <?php _e('We couldn\'t find any artisans matching your criteria. Try adjusting your filters or search term.', 'loomaama'); ?>
                </p>
                <a href="<?php echo esc_url(get_post_type_archive_link('artisan')); ?>" class="btn btn-dark rounded-0 px-4 py-2">
                    <?php _e('View All Artisans', 'loomaama'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

<!-- Newsletter Section -->
<section class="newsletter-section py-5 bg-card mt-5">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <span class="small fw-bold text-muted text-uppercase letter-spacing-wide mb-2 d-block">
                    <?php _e('Stay Connected', 'loomaama'); ?>
                </span>
                <h2 class="serif h2 mb-3"><?php _e('Support Artisan Stories', 'loomaama'); ?></h2>
                <p class="text-muted mb-4">
                    <?php _e('Subscribe to receive stories from our artisans, new collection launches, and exclusive updates.', 'loomaama'); ?>
                </p>
                
                <?php if (function_exists('mc4wp_show_form')) : ?>
                    <?php mc4wp_show_form(); ?>
                <?php else : ?>
                <form method="post" class="newsletter-form">
                    <div class="input-group">
                        <input type="email" name="email" class="form-control rounded-0 border-end-0" placeholder="<?php esc_attr_e('Your email address', 'loomaama'); ?>" required>
                        <button type="submit" class="btn btn-dark rounded-0 px-4">
                            <?php _e('Subscribe', 'loomaama'); ?>
                        </button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preserve filter values on form submit
    const filterButtons = document.querySelectorAll('.dropdown-item, .filter-bar .btn-sm');
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && href !== '#') {
                // Let the link work normally for navigation
                return true;
            }
        });
    });
    
    // Add active state styling to filter buttons
    const currentUrl = window.location.href;
    document.querySelectorAll('.dropdown-item').forEach(item => {
        if (currentUrl.includes(item.getAttribute('href')) && item.getAttribute('href') !== '#') {
            item.classList.add('active');
        }
    });
});
</script>

<?php get_footer(); ?>