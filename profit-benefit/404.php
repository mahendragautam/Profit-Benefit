<?php
/**
 * 404 Error Page Template
 *
 * @package ProfitBenefit_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="error-404-page">
    <div class="container">
        <div class="error-404-content">
            <h1 class="error-404-title"><?php esc_html_e('404', 'profitbenefit-theme'); ?></h1>
            <h2><?php esc_html_e('Page Not Found', 'profitbenefit-theme'); ?></h2>
            <p><?php esc_html_e('Sorry, the page you are looking for does not exist or has been moved.', 'profitbenefit-theme'); ?></p>

            <div class="error-404-search">
                <?php get_search_form(); ?>
            </div>

            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary">
                <?php esc_html_e('Back to Homepage', 'profitbenefit-theme'); ?>
            </a>
        </div>

        <div class="error-404-suggestions">
            <h3><?php esc_html_e('You might be interested in:', 'profitbenefit-theme'); ?></h3>
            <div class="blog-grid">
                <?php
                $recent_posts = new WP_Query(array(
                    'posts_per_page' => 3,
                    'post_status' => 'publish'
                ));

                if ($recent_posts->have_posts()) :
                    while ($recent_posts->have_posts()) : $recent_posts->the_post();
                        get_template_part('template-parts/content');
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
