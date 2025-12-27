<?php
/**
 * Index Template (Fallback)
 *
 * @package ProfitBenefit_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();
?>

    <div class="page-header">
        <div class="container">
            <h1 class="page-title"><?php bloginfo( 'name' ); ?></h1>
            <p class="page-subtitle"><?php bloginfo( 'description' ); ?></p>
        </div>
    </div>

    <div class="main-content">
        <div class="container">
            <?php if ( have_posts() ) : ?>
                <div class="blog-grid">
                    <?php
                    while ( have_posts() ) : the_post();
                        get_template_part( 'template-parts/content', get_post_format() );
                    endwhile;
                    ?>
                </div>

                <div class="pagination">
                    <?php
                    echo paginate_links( array(
                        'prev_text' => '←',
                        'next_text' => '→',
                        'type'      => 'list',
                    ) );
                    ?>
                </div>
            <?php else : ?>
                <p><?php esc_html_e( 'No posts found.', 'profitbenefit-theme' ); ?></p>
            <?php endif; ?>
        </div>
    </div>

<?php
get_footer();
