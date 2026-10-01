<?php
/**
 * Comment Template
 *
 * @package Loomaama
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    
    <?php if (have_comments()) : ?>
        <h2 class="comments-title h4 mb-4 pb-2 border-bottom" style="border-color: var(--border);">
            <?php
            $comments_number = get_comments_number();
            if (1 === $comments_number) {
                printf(
                    /* translators: %s: post title */
                    esc_html__('One thought on &ldquo;%s&rdquo;', 'loomaama'),
                    '<span class="fw-normal">' . get_the_title() . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: number of comments, 2: post title */
                    esc_html(_nx('%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comments_number, 'comments title', 'loomaama')),
                    number_format_i18n($comments_number),
                    '<span class="fw-normal">' . get_the_title() . '</span>'
                );
            }
            ?>
        </h2>

        <!-- Comment Pagination (Top) -->
        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="comment-navigation mb-4" aria-label="<?php esc_attr_e('Comment Navigation', 'loomaama'); ?>">
                <div class="d-flex justify-content-between">
                    <div class="nav-previous">
                        <?php previous_comments_link('<i class="fa-solid fa-arrow-left me-2"></i> ' . __('Older Comments', 'loomaama')); ?>
                    </div>
                    <div class="nav-next">
                        <?php next_comments_link(__('Newer Comments', 'loomaama') . ' <i class="fa-solid fa-arrow-right ms-2"></i>'); ?>
                    </div>
                </div>
            </nav>
        <?php endif; ?>

        <!-- Comment List -->
        <ol class="comment-list list-unstyled mb-4">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
                'callback'    => 'loomaama_comment_callback',
                'walker'      => new Loomaama_Comment_Walker(),
            ));
            ?>
        </ol>

        <!-- Comment Pagination (Bottom) -->
        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="comment-navigation mt-4" aria-label="<?php esc_attr_e('Comment Navigation', 'loomaama'); ?>">
                <div class="d-flex justify-content-between">
                    <div class="nav-previous">
                        <?php previous_comments_link('<i class="fa-solid fa-arrow-left me-2"></i> ' . __('Older Comments', 'loomaama')); ?>
                    </div>
                    <div class="nav-next">
                        <?php next_comments_link(__('Newer Comments', 'loomaama') . ' <i class="fa-solid fa-arrow-right ms-2"></i>'); ?>
                    </div>
                </div>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

    <!-- Comment Form -->
    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <div class="alert alert-warning mt-4" role="alert">
            <?php esc_html_e('Comments are closed.', 'loomaama'); ?>
        </div>
    <?php endif; ?>

    <?php
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');
    $required_attribute = ($req ? ' required' : '');
    $required_indicator = ($req ? ' <span class="text-danger">*</span>' : '');

    $comments_args = array(
        'class_form'           => 'comment-form row g-3 mt-3',
        'class_submit'         => 'submit btn btn-dark rounded-0 px-5 py-2',
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title h5 mb-4">',
        'title_reply_after'    => '</h3>',
        'title_reply'          => __('Leave a Comment', 'loomaama'),
        'title_reply_to'       => __('Reply to %s', 'loomaama'),
        'cancel_reply_before'  => ' <small class="cancel-reply-link ms-3">',
        'cancel_reply_after'   => '</small>',
        'label_submit'         => __('Post Comment', 'loomaama'),
        'submit_button'        => '<button type="submit" class="%1$s" id="%2$s" name="%3$s">%4$s</button>',
        'submit_field'         => '<div class="form-submit col-12 mt-4">%1$s %2$s</div>',
        'format'               => 'html5',

        'fields'               => apply_filters('comment_form_default_fields', array(
            'author' => '
                <div class="col-md-6">
                    <label for="author" class="form-label small text-uppercase tracking-wide text-secondary">' . __('Name', 'loomaama') . $required_indicator . '</label>
                    <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" class="form-control rounded-0" ' . $aria_req . $required_attribute . '>
                </div>',
            'email'  => '
                <div class="col-md-6">
                    <label for="email" class="form-label small text-uppercase tracking-wide text-secondary">' . __('Email', 'loomaama') . $required_indicator . '</label>
                    <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" class="form-control rounded-0" ' . $aria_req . $required_attribute . '>
                </div>',
            'url'    => '
                <div class="col-12">
                    <label for="url" class="form-label small text-uppercase tracking-wide text-secondary">' . __('Website', 'loomaama') . '</label>
                    <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" class="form-control rounded-0">
                </div>',
            'cookies' => '
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input rounded-0" type="checkbox" id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" value="yes"' . (empty($commenter['comment_author_email']) ? '' : ' checked') . '>
                        <label class="form-check-label small text-secondary" for="wp-comment-cookies-consent">
                            ' . __('Save my name and email in this browser for the next time I comment.', 'loomaama') . '
                        </label>
                    </div>
                </div>',
        )),

        'comment_field'        => '
            <div class="col-12">
                <label for="comment" class="form-label small text-uppercase tracking-wide text-secondary">' . __('Comment', 'loomaama') . ' <span class="text-danger">*</span></label>
                <textarea id="comment" name="comment" class="form-control rounded-0" rows="6" required></textarea>
            </div>',
        
        'must_log_in'          => '
            <div class="alert alert-info mb-4" role="alert">
                <i class="fa-solid fa-circle-info me-2"></i>
                ' . sprintf(
                    __('You must be <a href="%s" class="alert-link">logged in</a> to post a comment.', 'loomaama'),
                    wp_login_url(apply_filters('the_permalink', get_permalink()))
                ) . '
            </div>',
        
        'logged_in_as'         => '
            <div class="alert alert-light mb-4 border" role="alert">
                <i class="fa-solid fa-user me-2"></i>
                ' . sprintf(
                    __('Logged in as <a href="%1$s" class="alert-link">%2$s</a>.', 'loomaama'),
                    admin_url('profile.php'),
                    $user_identity
                ) . '
                <a href="' . wp_logout_url(apply_filters('the_permalink', get_permalink())) . '" class="float-end text-decoration-none small">' . __('Log out?', 'loomaama') . '</a>
            </div>',
        
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
    );

    comment_form($comments_args);
    ?>

</div>

<style>
/* Comment List Styles */
.comment-list .comment {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.comment-list .comment:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.comment-list .comment-body {
    position: relative;
}

.comment-list .comment-author {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.comment-list .comment-author .avatar {
    border-radius: 50%;
    width: 50px;
    height: 50px;
    object-fit: cover;
}

.comment-list .comment-author .fn {
    font-family: var(--font-headline);
    font-weight: 600;
    font-size: 1rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    font-style: normal;
}

.comment-list .comment-author .fn a {
    color: var(--text-primary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.comment-list .comment-author .fn a:hover {
    color: var(--brand);
}

.comment-list .comment-metadata {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-bottom: 0.75rem;
}

.comment-list .comment-metadata a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.comment-list .comment-metadata a:hover {
    color: var(--brand);
}

.comment-list .comment-content {
    font-size: 0.95rem;
    line-height: 1.7;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.comment-list .comment-content p:last-child {
    margin-bottom: 0;
}

.comment-list .reply {
    margin-top: 0.75rem;
}

.comment-list .reply .comment-reply-link {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    text-decoration: none;
    color: var(--brand);
    font-family: var(--font-headline);
    transition: color 0.3s ease;
}

.comment-list .reply .comment-reply-link:hover {
    color: var(--brand-hover);
}

.comment-list .reply .comment-reply-link i {
    font-size: 0.7rem;
    margin-right: 0.25rem;
}

/* Child Comments (Nested) */
.comment-list .children {
    list-style: none;
    margin-left: 3rem;
    margin-top: 1.5rem;
    padding-left: 1.5rem;
    border-left: 2px solid var(--border);
}

@media (max-width: 768px) {
    .comment-list .children {
        margin-left: 1rem;
        padding-left: 1rem;
    }
}

.comment-list .children .comment {
    border-bottom-color: rgba(222, 215, 205, 0.3);
}

/* By Author Styling */
.comment-list .bypostauthor > .comment-body {
    background: rgba(158, 133, 116, 0.05);
    padding: 1.5rem;
    margin: -1.5rem 0;
}

.comment-list .bypostauthor > .comment-body .comment-author .fn::after {
    content: 'Author';
    display: inline-block;
    font-size: 0.65rem;
    font-weight: normal;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    background: var(--brand);
    color: white;
    padding: 0.2rem 0.5rem;
    margin-left: 0.75rem;
    vertical-align: middle;
}

/* Pingbacks & Trackbacks */
.comment-list .pingback,
.comment-list .trackback {
    padding: 1rem 0;
    border-bottom: 1px solid var(--border);
}

.comment-list .pingback .comment-body,
.comment-list .trackback .comment-body {
    font-size: 0.9rem;
}

.comment-list .pingback .edit-link,
.comment-list .trackback .edit-link {
    margin-left: 1rem;
    font-size: 0.75rem;
}

/* Comment Form Styles */
.comment-form .form-label {
    font-size: 0.7rem;
    letter-spacing: 0.1em;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.comment-form .form-control {
    border: 1px solid var(--border);
    background: var(--bg-main);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.comment-form .form-control:focus {
    border-color: var(--brand);
    box-shadow: none;
    outline: none;
}

.comment-form .form-check-input {
    border: 1px solid var(--border);
    background-color: var(--bg-main);
}

.comment-form .form-check-input:checked {
    background-color: var(--brand);
    border-color: var(--brand);
}

.comment-form .form-check-input:focus {
    box-shadow: none;
    border-color: var(--brand);
}

.comment-form .submit {
    font-family: var(--font-headline);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-size: 0.75rem;
    transition: all 0.3s ease;
}

.comment-form .submit:hover {
    background: var(--brand-hover);
    transform: translateY(-2px);
}

/* Cancel Reply Link */
.cancel-reply-link {
    font-size: 0.7rem;
    font-weight: normal;
}

.cancel-reply-link a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.cancel-reply-link a:hover {
    color: var(--brand);
}

/* Alert Styles */
.alert-light {
    background-color: var(--bg-editorial);
    border-color: var(--border);
    color: var(--text-primary);
}

.alert-light .alert-link {
    color: var(--brand);
}

/* Comment Navigation */
.comment-navigation a {
    text-decoration: none;
    color: var(--text-primary);
    font-size: 0.85rem;
    font-family: var(--font-headline);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: color 0.3s ease;
}

.comment-navigation a:hover {
    color: var(--brand);
}

/* Tracking Wide Utility */
.tracking-wide {
    letter-spacing: 0.12em;
}
</style>

<?php
/**
 * Custom Comment Callback Function
 * 
 * @param WP_Comment $comment The comment object.
 * @param array      $args    Comment arguments.
 * @param int        $depth   Comment depth.
 */
function loomaama_comment_callback($comment, $args, $depth) {
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> <?php comment_class('mb-4 pb-4 border-bottom', $comment); ?> id="comment-<?php comment_ID(); ?>">
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            
            <div class="comment-author d-flex align-items-center gap-3 mb-2">
                <?php echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-circle')); ?>
                <div>
                    <cite class="fn fw-semibold">
                        <?php echo get_comment_author_link($comment); ?>
                    </cite>
                </div>
            </div>

            <div class="comment-metadata mb-2">
                <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>" class="text-secondary text-decoration-none small">
                    <i class="fa-regular fa-calendar-alt me-1"></i>
                    <?php echo get_comment_date('', $comment) . ' ' . __('at', 'loomaama') . ' ' . get_comment_time('', $comment); ?>
                </a>
                <?php edit_comment_link(__('Edit', 'loomaama'), ' <span class="edit-link ms-2">', '</span>'); ?>
            </div>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <?php if ('0' == $comment->comment_approved) : ?>
                <div class="alert alert-warning py-2 px-3 mb-3 small" role="alert">
                    <i class="fa-regular fa-clock me-2"></i>
                    <?php _e('Your comment is awaiting moderation.', 'loomaama'); ?>
                </div>
            <?php endif; ?>

            <div class="reply">
                <?php
                comment_reply_link(array_merge($args, array(
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<i class="fa-regular fa-reply me-1"></i> ',
                    'reply_text' => __('Reply', 'loomaama'),
                )));
                ?>
            </div>

        </article>
    <?php
}

/**
 * Custom Comment Walker Class for Better Nesting
 */
class Loomaama_Comment_Walker extends Walker_Comment {
    
    /**
     * Start the element output.
     */
    public function start_el(&$output, $comment, $depth = 0, $args = array(), $id = 0) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment;
        
        if (!empty($args['callback'])) {
            ob_start();
            call_user_func($args['callback'], $comment, $args, $depth);
            $output .= ob_get_clean();
            return;
        }
        
        ob_start();
        
        $tag = ('div' === $args['style']) ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> <?php comment_class('mb-4 pb-4 border-bottom', $comment); ?> id="comment-<?php comment_ID(); ?>">
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                
                <div class="comment-author d-flex align-items-center gap-3 mb-2">
                    <?php echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-circle')); ?>
                    <div>
                        <cite class="fn fw-semibold">
                            <?php echo get_comment_author_link($comment); ?>
                        </cite>
                        <?php if ($comment->user_id === get_the_author_meta('ID')) : ?>
                            <span class="badge bg-dark ms-2 small text-uppercase tracking-wide"><?php _e('Author', 'loomaama'); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="comment-metadata mb-2">
                    <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>" class="text-secondary text-decoration-none small">
                        <i class="fa-regular fa-calendar-alt me-1"></i>
                        <?php echo get_comment_date('', $comment) . ' ' . __('at', 'loomaama') . ' ' . get_comment_time('', $comment); ?>
                    </a>
                    <?php edit_comment_link(__('Edit', 'loomaama'), ' <span class="edit-link ms-2">', '</span>'); ?>
                </div>

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>

                <?php if ('0' == $comment->comment_approved) : ?>
                    <div class="alert alert-warning py-2 px-3 mb-3 small" role="alert">
                        <i class="fa-regular fa-clock me-2"></i>
                        <?php _e('Your comment is awaiting moderation.', 'loomaama'); ?>
                    </div>
                <?php endif; ?>

                <div class="reply mt-2">
                    <?php
                    comment_reply_link(array_merge($args, array(
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'before'    => '<i class="fa-regular fa-reply me-1"></i> ',
                        'reply_text' => __('Reply', 'loomaama'),
                    )));
                    ?>
                </div>

            </article>
        <?php
        $output .= ob_get_clean();
    }
    
    /**
     * End the element output.
     */
    public function end_el(&$output, $comment, $depth = 0, $args = array()) {
        if (!empty($args['end-callback'])) {
            ob_start();
            call_user_func($args['end-callback'], $comment, $args, $depth);
            $output .= ob_get_clean();
            return;
        }
        $output .= "</li>\n";
    }
}