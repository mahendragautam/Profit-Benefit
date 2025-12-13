<?php
/**
 * Template part for displaying DON'T MISS section
 *
 * @package ProfitBenefit_Theme
 */

// Get featured post (sticky post or latest post)
$featured_args = array(
    'posts_per_page' => 1,
    'post__in'       => get_option( 'sticky_posts' ),
);

$featured_query = new WP_Query( $featured_args );

if ( ! $featured_query->have_posts() ) {
    $featured_query = new WP_Query( array(
        'posts_per_page' => 1,
        'orderby'        => 'date',
    ) );
}

// Get related articles (4 recent posts excluding featured)
$related_args = array(
    'posts_per_page' => 4,
    'orderby'        => 'date',
    'post__not_in'   => $featured_query->have_posts() ? array( $featured_query->posts[0]->ID ) : array(),
);
$related_query = new WP_Query( $related_args );

// Get categories for tabs
$categories = get_categories( array(
    'orderby' => 'count',
    'order'   => 'DESC',
    'number'  => 5,
) );
?>

<div class="dont-miss-section">
    <div class="dont-miss-container">
        <div class="category-header">
            <div class="dont-miss-label"><?php esc_html_e( "DON'T MISS", 'profitbenefit-theme' ); ?></div>
            <div class="category-tabs">
                <button class="tab-button active" data-tab="all"><?php esc_html_e( 'All', 'profitbenefit-theme' ); ?></button>
                <?php foreach ( $categories as $category ) : ?>
                    <button class="tab-button" data-tab="<?php echo esc_attr( $category->slug ); ?>">
                        <?php echo esc_html( $category->name ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- All Category Content -->
        <div class="content-wrapper active" data-content="all">
            <?php if ( $featured_query->have_posts() ) : ?>
                <?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
                    <div class="featured-article">
                        <div class="featured-image-wrapper">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'profitbenefit-featured', array( 'class' => 'featured-image' ) ); ?>
                            <?php else : ?>
                                <img src="https://via.placeholder.com/800x500" alt="<?php the_title_attribute(); ?>" class="featured-image">
                            <?php endif; ?>
                            <?php
                            $categories_list = get_the_category();
                            if ( ! empty( $categories_list ) ) :
                            ?>
                                <span class="category-badge"><?php echo esc_html( $categories_list[0]->name ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="featured-content">
                            <h2 class="featured-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="featured-meta">
                                <span class="author"><?php echo get_the_author(); ?></span>
                                <span>•</span>
                                <span><?php echo get_the_date(); ?></span>
                            </div>
                            <p class="featured-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 30 ); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>

            <?php if ( $related_query->have_posts() ) : ?>
                <div class="related-articles">
                    <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                        <div class="article-item">
                            <a href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'profitbenefit-thumbnail', array( 'class' => 'article-thumbnail' ) ); ?>
                                <?php else : ?>
                                    <img src="https://via.placeholder.com/120x90" alt="<?php the_title_attribute(); ?>" class="article-thumbnail">
                                <?php endif; ?>
                                <div class="article-info">
                                    <h3><?php the_title(); ?></h3>
                                    <p class="article-date"><?php echo get_the_date(); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
