<?php
/**
 * Archive Template (Blog Listing Page)
 * Converted from: blog-listing.html
 *
 * @package ProfitBenefit_Theme
 */

get_header();
?>

    <!-- Page Header with Gradient Background -->
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

    <!-- Trending Section with Gradient Overlays -->
    <?php get_template_part( 'template-parts/trending-section' ); ?>

    <!-- Main Content: Blog Posts Grid -->
    <section class="main-content">
        <div class="container">
            <?php if ( have_posts() ) : ?>
                <div class="blog-grid">
                    <?php
                    while ( have_posts() ) : the_post();
                    ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card' ); ?>>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="blog-card-image-wrapper image-with-gradient">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'profitbenefit-featured', array( 'class' => 'blog-card-image' ) ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="blog-card-content">
                                <?php
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) :
                                ?>
                                    <span class="blog-card-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                                <?php endif; ?>

                                <h2 class="blog-card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>

                                <div class="blog-card-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>

                                <div class="blog-card-meta">
                                    <span class="blog-card-meta-item"><?php echo get_the_author(); ?></span>
                                    <span class="blog-card-meta-item">•</span>
                                    <span class="blog-card-meta-item"><?php echo get_the_date(); ?></span>
                                    <span class="blog-card-meta-item">•</span>
                                    <span class="blog-card-meta-item"><?php echo esc_html( profitbenefit_reading_time() ); ?> min read</span>
                                </div>

                                <div class="blog-card-footer">
                                    <a href="<?php the_permalink(); ?>" class="read-more">
                                        <?php esc_html_e( 'Read More', 'profitbenefit-theme' ); ?> →
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php
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
            <?php else : ?>
                <p><?php esc_html_e( 'No posts found.', 'profitbenefit-theme' ); ?></p>
            <?php endif; ?>
        </div>
    </section>

<?php
get_footer();
