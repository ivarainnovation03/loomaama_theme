<?php
/**
 * Template Name: Blogs Archive
 *
 * @package Loomaama
 */

get_header();

// Get current archive title and description
$archive_title = '';
$archive_description = '';

if (is_category()) {
    $archive_title = single_cat_title('', false);
    $archive_description = category_description();
} elseif (is_tag()) {
    $archive_title = single_tag_title('', false);
    $archive_description = tag_description();
} elseif (is_author()) {
    $archive_title = get_the_author();
    $archive_description = get_the_author_meta('description');
} elseif (is_year()) {
    $archive_title = get_the_date('Y');
} elseif (is_month()) {
    $archive_title = get_the_date('F Y');
} elseif (is_day()) {
    $archive_title = get_the_date('F j, Y');
} else {
    $archive_title = post_type_archive_title('', false);
    if (empty($archive_title)) {
        $archive_title = __('Archives', 'loomaama');
    }
}
?>

<main id="main" class="archive-main py-5">
    <div class="container">
        
        <!-- Search Section at Top -->
        <section class="archive-search-section mb-5 pb-4 border-bottom">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="archive-search-form">
                        <div class="input-group">
                            <input type="text" 
                                   name="s" 
                                   class="form-control bg-transparent border-end-0" 
                                   placeholder="<?php esc_attr_e('Search articles...', 'loomaama'); ?>" 
                                   value="<?php echo get_search_query(); ?>"
                                   style="border-color: var(--border);">
                            <input type="hidden" name="post_type" value="post">
                            <button class="btn border-start-0" type="submit" style="border-color: var(--border);">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Archive Header -->
        <section class="archive-header text-center py-4 reveal">
            <h1 class="archive-title headline display-4 fw-medium mb-3"><?php echo esc_html($archive_title); ?></h1>
            <?php if ($archive_description) : ?>
                <div class="archive-description text-secondary mx-auto" style="max-width: 700px;">
                    <?php echo wp_kses_post($archive_description); ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Featured Article (First Post) -->
        <?php if (have_posts()) : 
            $first_post = true;
            $post_count = 0;
        ?>
        
        <?php while (have_posts()) : the_post(); 
            $post_count++;
            if ($first_post && $post_count === 1) : 
                $first_post = false;
                $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'loomaama-hero');
                if (!$featured_image) {
                    $featured_image = get_template_directory_uri() . '/assets/images/placeholder-featured.jpg';
                }
        ?>
        <section class="featured-article mb-5 pb-5 reveal">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="featured-image overflow-hidden">
                        <img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title_attribute(); ?>" class="w-100 h-100 object-fit-cover transition-scale">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="featured-content">
                        <div class="article-meta mb-3">
                            <span class="article-date text-secondary small text-uppercase tracking-wide">
                                <?php echo get_the_date(); ?>
                            </span>
                            <span class="mx-2 text-secondary">—</span>
                            <span class="article-category">
                                <?php 
                                $categories = get_the_category();
                                if (!empty($categories)) {
                                    echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="text-decoration-none small text-uppercase tracking-wide" style="color: var(--brand);">' . esc_html($categories[0]->name) . '</a>';
                                }
                                ?>
                            </span>
                        </div>
                        <h2 class="featured-title display-5 fw-medium mb-3"><?php the_title(); ?></h2>
                        <p class="featured-excerpt text-secondary mb-4">
                            <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                        </p>
                        <a href="<?php the_permalink(); ?>" class="read-more text-decoration-none small text-uppercase tracking-wide position-relative pb-1" style="color: var(--text-primary);">
                            <?php _e('Read the Story', 'loomaama'); ?> 
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Article Grid -->
        <section class="article-grid mb-5 pb-4">
            <div class="row">
        <?php 
            continue;
            endif; 
        ?>
        
        <!-- Grid Items -->
                <div class="col-md-6 col-lg-4">
                    <div class="grid-item h-100 reveal">
                        <a href="<?php the_permalink(); ?>" class="grid-image d-block overflow-hidden mb-3" style="aspect-ratio: 4/3;">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('loomaama-product-medium'); ?>" alt="<?php the_title_attribute(); ?>" class="w-100 h-100 object-fit-cover transition-scale">
                            <?php else : ?>
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-grid.jpg'); ?>" alt="<?php the_title_attribute(); ?>" class="w-100 h-100 object-fit-cover transition-scale">
                            <?php endif; ?>
                        </a>
                        <div class="article-meta mb-2">
                            <span class="article-date text-secondary small text-uppercase tracking-wide">
                                <?php echo get_the_date(); ?>
                            </span>
                            <span class="mx-1 text-secondary">—</span>
                            <span class="article-category">
                                <?php 
                                $categories = get_the_category();
                                if (!empty($categories)) {
                                    echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="text-decoration-none small text-uppercase tracking-wide" style="color: var(--brand);">' . esc_html($categories[0]->name) . '</a>';
                                }
                                ?>
                            </span>
                        </div>
                        <h3 class="grid-title h5 fw-medium mb-3"><?php the_title(); ?></h3>
                        <a href="<?php the_permalink(); ?>" class="read-more text-decoration-none small text-uppercase tracking-wide" style="color: var(--text-primary);">
                            <?php _e('Explore', 'loomaama'); ?> 
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
        
        <?php endwhile; ?>
            </div>
        </section>
        
        <!-- Pagination -->
        <section class="archive-pagination text-center my-5">
            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '<i class="fa-solid fa-chevron-left me-1"></i> ' . __('Previous', 'loomaama'),
                'next_text' => __('Next', 'loomaama') . ' <i class="fa-solid fa-chevron-right ms-1"></i>',
                'screen_reader_text' => ' ',
                'class' => 'pagination justify-content-center',
                'before_page_number' => '<span class="mx-1">',
                'after_page_number' => '</span>',
            ));
            ?>
        </section>
        
        <?php else : ?>
        
        <!-- No Results -->
        <section class="no-results text-center py-5 reveal">
            <h2 class="headline h3 mb-3"><?php _e('No Posts Found', 'loomaama'); ?></h2>
            <p class="text-secondary mb-4"><?php _e('Sorry, no posts matched your criteria. Try searching below:', 'loomaama'); ?></p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="archive-search-form">
                        <div class="input-group">
                            <input type="text" name="s" class="form-control" placeholder="<?php esc_attr_e('Search...', 'loomaama'); ?>">
                            <input type="hidden" name="post_type" value="post">
                            <button type="submit" class="btn btn-dark"><?php _e('Search', 'loomaama'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        
        <?php endif; ?>
        
        <!-- Newsletter Section -->
        <section class="newsletter-block bg-editorial text-center py-5 px-4 my-5 reveal">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <span class="category-label d-inline-block small text-uppercase tracking-wide text-secondary mb-3">
                        <?php _e('Stay Connected', 'loomaama'); ?>
                    </span>
                    <h2 class="newsletter-title h2 mb-3"><?php _e('Subscribe to the Journal', 'loomaama'); ?></h2>
                    <p class="text-secondary mb-4">
                        <?php _e('Receive monthly insights into our craft, sustainability vows, and early access to new collections.', 'loomaama'); ?>
                    </p>
                    <div class="newsletter-input-group d-flex flex-column flex-sm-row gap-3">
                        <input type="email" class="newsletter-input form-control rounded-0" placeholder="<?php esc_attr_e('Email Address', 'loomaama'); ?>">
                        <button class="newsletter-submit btn btn-dark rounded-0 px-4"><?php _e('Join Us', 'loomaama'); ?></button>
                    </div>
                </div>
            </div>
        </section>
        
    </div>
</main>

<style>
/* Additional custom styles that complement Bootstrap */
.tracking-wide {
    letter-spacing: 0.12em;
}

.object-fit-cover {
    object-fit: cover;
}

.transition-scale {
    transition: transform 0.6s cubic-bezier(0.19, 1, 0.22, 1);
}

.transition-scale:hover {
    transform: scale(1.05);
}

/* Archive Header */
.archive-title {
    letter-spacing: 0.15em;
}

/* Read More Link */
.read-more {
    position: relative;
    transition: color 0.3s ease;
}

.read-more::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background: var(--brand);
    transition: width 0.3s ease;
}

.read-more:hover {
    color: var(--brand) !important;
}

.read-more:hover::after {
    width: 100%;
}

/* Pagination Styling - Override Bootstrap defaults */
.archive-pagination .pagination {
    gap: 8px;
}

.archive-pagination .page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 10px;
    border: 1px solid var(--border);
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.3s ease;
    font-family: var(--font-headline);
    font-size: 14px;
    background: transparent;
}

.archive-pagination .page-numbers.current {
    background: var(--brand);
    border-color: var(--brand);
    color: white;
}

.archive-pagination .page-numbers:hover:not(.current) {
    border-color: var(--brand);
    color: var(--brand);
}

.archive-pagination .dots {
    border: none;
}

/* Grid Item Hover */
.grid-item {
    transition: transform 0.3s ease;
}

.grid-item:hover {
    transform: translateY(-5px);
}

/* Newsletter Block Background */
.bg-editorial {
    background-color: var(--bg-editorial);
    border: 1px solid var(--border);
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .featured-title {
        font-size: 2rem !important;
    }
}

@media (max-width: 768px) {
    .archive-title {
        font-size: 2rem !important;
    }
    
    .featured-title {
        font-size: 1.5rem !important;
    }
    
    .newsletter-title {
        font-size: 1.5rem !important;
    }
}
</style>

<?php get_footer(); ?>