<?php
/**
 * The front page template file
 *
 * @package ProfitBenefit_Theme
 */

get_header();
?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title"><?php echo esc_html( get_theme_mod( 'homepage_title', 'Latest Articles & Guides' ) ); ?></h1>
            <p class="page-subtitle"><?php echo esc_html( get_theme_mod( 'homepage_subtitle', 'Expert insights on business tools, software reviews, and productivity tips' ) ); ?></p>
        </div>
    </div>

    <!-- DON'T MISS Section -->
    <?php get_template_part( 'template-parts/dont-miss-section' ); ?>

    <!-- Widgets and Categories Section -->
    <section class="widgets-categories-section">
        <div class="widgets-categories-grid">
            <!-- Left Content -->
            <div class="left-content">
                <!-- Trending Box -->
                <?php get_template_part( 'template-parts/trending-box' ); ?>

                <!-- Category Boxes Grid -->
                <?php get_template_part( 'template-parts/category-boxes' ); ?>
            </div>

            <!-- Right Sidebar -->
            <?php get_sidebar(); ?>
        </div>
    </section>

    <!-- Recent Posts Section -->
    <?php
    $recent_posts = new WP_Query( array(
        'posts_per_page' => 9,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    if ( $recent_posts->have_posts() ) :
    ?>
        <div class="main-content">
            <div class="container">
                <h2 class="section-title"><?php esc_html_e( 'Recent Articles', 'profitbenefit-theme' ); ?></h2>
                <div class="blog-grid">
                    <?php
                    while ( $recent_posts->have_posts() ) :
                        $recent_posts->the_post();
                        get_template_part( 'template-parts/content', get_post_format() );
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>

                <div style="text-align: center; margin-top: 40px;">
                    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="read-more" style="font-size: 18px;">
                        <?php esc_html_e( 'View All Posts', 'profitbenefit-theme' ); ?> →
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php
get_footer();
