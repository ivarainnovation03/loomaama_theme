<?php
/**
 * Single Post Template
 *
 * @package Loomaama
 */

get_header();
?>
<main id="main" class="single-main">
    <div class="single-container">

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <article class="single-article">
            
            <!-- Hero Section - Editorial Style -->
            <div class="single-hero">
                <div class="single-hero-inner">
                    <div class="single-eyebrow">
                        <?php 
                        $categories = get_the_category();
                        if (!empty($categories)) {
                            echo '<span class="eyebrow-category">' . esc_html($categories[0]->name) . '</span>';
                        }
                        ?>
                        <span class="eyebrow-divider">—</span>
                        <span class="eyebrow-date"><?php echo get_the_date(); ?></span>
                    </div>
                    
                    <h1 class="single-title"><?php the_title(); ?></h1>
                    
                    <?php if (has_excerpt()) : ?>
                    <div class="single-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="single-author-section">
                        <div class="author-avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 60, '', '', array('class' => 'author-avatar-img')); ?>
                        </div>
                        <div class="author-info">
                            <span class="author-name"><?php the_author(); ?></span>
                            <span class="author-title">Loomaama</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
            <div class="single-featured">
                <div class="single-featured-image image">
                    <?php the_post_thumbnail('full', array('class' => 'featured-img')); ?>
                </div>
                <?php 
                $caption = get_the_post_thumbnail_caption();
                if ($caption) : ?>
                <div class="featured-caption"><?php echo esc_html($caption); ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Content Area -->
            <div class="single-content-wrapper">
                <div class="single-content">
                    <?php the_content(); ?>
                    
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . __('Pages:', 'loomaama'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
            </div>
            
            <!-- Tags Section -->
            <?php $tags = get_the_tags(); ?>
            <?php if ($tags) : ?>
            <div class="single-tags-wrapper">
                <div class="single-tags">
                    <span class="tags-label">Topics</span>
                    <div class="tags-list">
                        <?php foreach ($tags as $tag) : ?>
                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-link">
                                <?php echo esc_html($tag->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Post Navigation -->
            <div class="single-navigation">
                <div class="nav-previous">
                    <?php previous_post_link('%link', '<span class="nav-label">Previous</span><span class="nav-title">%title</span>'); ?>
                </div>
                <div class="nav-next">
                    <?php next_post_link('%link', '<span class="nav-label">Next</span><span class="nav-title">%title</span>'); ?>
                </div>
            </div>
            
        </article>
        
        <?php endwhile; endif; ?>
        
        <!-- Related Posts -->
        <?php
        $categories = get_the_category();
        if (!empty($categories)) {
            $category_ids = wp_list_pluck($categories, 'term_id');
            $related_args = array(
                'post_type' => 'post',
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'term_id',
                        'terms' => $category_ids,
                    ),
                ),
            );
            $related_posts = new WP_Query($related_args);
            
            if ($related_posts->have_posts()) :
        ?>
        <section class="related-section">
            <div class="related-header">
                <span class="related-eyebrow">Continue Reading</span>
                <h2 class="related-title">You Might Also Like</h2>
            </div>
            <div class="related-grid">
                <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                <article class="related-card">
                    <a href="<?php the_permalink(); ?>" class="related-image-link">
                        <div class="related-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php else : ?>
                                <div class="related-image-placeholder"></div>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="related-meta">
                        <span class="related-category">
                            <?php 
                            $cats = get_the_category();
                            echo esc_html($cats[0]->name ?? 'Journal');
                            ?>
                        </span>
                        <span class="related-date"><?php echo get_the_date(); ?></span>
                    </div>
                    <h3 class="related-post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <a href="<?php the_permalink(); ?>" class="related-read-more">Read Story →</a>
                </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
        <?php endif; } ?>
                <!-- Related Posts -->
        <?php
        $categories = get_the_category();
        if (!empty($categories)) {
            $category_ids = wp_list_pluck($categories, 'term_id');
            $related_args = array(
                'post_type' => 'post',
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'term_id',
                        'terms' => $category_ids,
                    ),
                ),
            );
            $related_posts = new WP_Query($related_args);
            
            if ($related_posts->have_posts()) :
        ?>
        <section class="related-posts my-5 pt-4 reveal">
            <h2 class="related-title text-center h2 mb-5"><?php _e('You Might Also Like', 'loomaama'); ?></h2>
            <div class="row g-4">
                <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                <div class="col-md-6 col-lg-4">
                    <div class="related-item h-100">
                        <a href="<?php the_permalink(); ?>" class="related-image d-block overflow-hidden mb-3" style="aspect-ratio: 4/3;">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('loomaama-product-medium'); ?>" alt="<?php the_title_attribute(); ?>" class="w-100 h-100 object-fit-cover transition-scale">
                            <?php else : ?>
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-grid.jpg'); ?>" alt="<?php the_title_attribute(); ?>" class="w-100 h-100 object-fit-cover transition-scale">
                            <?php endif; ?>
                        </a>
                        <div class="related-meta mb-2">
                            <span class="related-date text-secondary small text-uppercase tracking-wide"><?php echo get_the_date(); ?></span>
                        </div>
                        <h3 class="related-post-title h6 fw-medium mb-3">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none" style="color: var(--text-primary); transition: color 0.3s ease;"><?php the_title(); ?></a>
                        </h3>
                        <a href="<?php the_permalink(); ?>" class="read-more text-decoration-none small text-uppercase tracking-wide" style="color: var(--text-primary);">
                            <?php _e('Read More', 'loomaama'); ?> 
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
        <?php endif; } ?>
        
        
        <!-- Newsletter Section -->
        <section class="newsletter-section">
            <div class="newsletter-inner">
                <span class="newsletter-eyebrow">Newsletter</span>
                <h2 class="newsletter-title">Learn what you wear.</h2>
                <p class="newsletter-text">Receive material guides, care notes, artisan stories, and new collection updates. No noise. Only useful stories from Loomaama.</p>
                <div class="newsletter-form">
                    <input type="email" placeholder="Enter your email" class="newsletter-input">
                    <button class="newsletter-submit">Subscribe</button>
                </div>
            </div>
        </section>
        
        <!-- Comments -->
        <?php if (comments_open() || get_comments_number()) : ?>
        <section class="comments-section">
            <div class="comments-inner">
                <?php comments_template(); ?>
            </div>
        </section>
        <?php endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>