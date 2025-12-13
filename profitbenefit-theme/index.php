<?php
/**
 * The main template file
 *
 * @package ProfitBenefit_Theme
 */

get_header();
?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title"><?php bloginfo( 'name' ); ?></h1>
            <p class="page-subtitle"><?php bloginfo( 'description' ); ?></p>
        </div>
    </div>

    <!-- Main Content -->
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
