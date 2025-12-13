<?php
/**
 * The template for displaying archive pages
 *
 * @package ProfitBenefit_Theme
 */

get_header();
?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">
                <?php
                if ( is_category() ) {
                    single_cat_title();
                } elseif ( is_tag() ) {
                    single_tag_title();
                } elseif ( is_author() ) {
                    the_author();
                } elseif ( is_date() ) {
                    echo get_the_date( 'F Y' );
                } else {
                    esc_html_e( 'Latest Articles & Guides', 'profitbenefit-theme' );
                }
                ?>
            </h1>
            <p class="page-subtitle">
                <?php
                if ( is_category() ) {
                    echo esc_html( category_description() );
                } else {
                    esc_html_e( 'Expert insights on business tools, software reviews, and productivity tips', 'profitbenefit-theme' );
                }
                ?>
            </p>
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

    <!-- Main Content - Blog Posts Grid -->
    <?php if ( have_posts() ) : ?>
        <div class="main-content">
            <div class="container">
                <div class="blog-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/content', get_post_format() );
                    endwhile;
                    ?>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <?php
                    echo paginate_links( array(
                        'prev_text' => '←',
                        'next_text' => '→',
                        'type'      => 'list',
                    ) );
                    ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="main-content">
            <div class="container">
                <p><?php esc_html_e( 'No posts found.', 'profitbenefit-theme' ); ?></p>
            </div>
        </div>
    <?php endif; ?>

<?php
get_footer();
