<?php
/**
 * Search Results Template
 *
 * @package ProfitBenefit_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<div class="search-results-page">
    <div class="page-header">
        <div class="container">
            <?php if ( have_posts() ) : ?>
                <h1 class="page-title">
                    <?php
                    /* translators: %s: search query */
                    printf( esc_html__( 'Search Results for: %s', 'profitbenefit-theme' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
                    ?>
                </h1>
                <p class="page-subtitle">
                    <?php
                    /* translators: %s: number of results */
                    printf( esc_html__( 'Found %s results', 'profitbenefit-theme' ), $wp_query->found_posts );
                    ?>
                </p>
            <?php else : ?>
                <h1 class="page-title"><?php esc_html_e( 'No Results Found', 'profitbenefit-theme' ); ?></h1>
                <p class="page-subtitle">
                    <?php
                    /* translators: %s: search query */
                    printf( esc_html__( 'Sorry, no results found for: %s', 'profitbenefit-theme' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
                    ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <?php if ( have_posts() ) : ?>
                <div class="blog-grid cards-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/content', 'search' );
                    endwhile;
                    ?>
                </div>

                <div class="pagination">
                    <?php
                    the_posts_pagination(
                        array(
                            'mid_size'  => 2,
                            'prev_text' => esc_html__( 'Previous', 'profitbenefit-theme' ),
                            'next_text' => esc_html__( 'Next', 'profitbenefit-theme' ),
                        )
                    );
                    ?>
                </div>
            <?php else : ?>
                <div class="no-results">
                    <h3><?php esc_html_e( 'Try searching with different keywords', 'profitbenefit-theme' ); ?></h3>
                    <div class="search-form-wrapper">
                        <?php get_search_form(); ?>
                    </div>

                    <div class="suggestions">
                        <h4><?php esc_html_e( 'Popular Articles', 'profitbenefit-theme' ); ?></h4>
                        <div class="blog-grid cards-grid">
                            <?php
                            $popular = new WP_Query(
                                array(
                                    'posts_per_page' => 6,
                                    'post_status'    => 'publish',
                                )
                            );

                            if ( $popular->have_posts() ) :
                                while ( $popular->have_posts() ) :
                                    $popular->the_post();
                                    get_template_part( 'template-parts/content', 'popular' );
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>