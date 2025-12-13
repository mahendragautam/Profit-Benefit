<?php
/**
 * Template part for displaying category boxes grid
 *
 * @package ProfitBenefit_Theme
 */

// Get top 6 categories by post count
$categories = get_categories( array(
    'orderby' => 'count',
    'order'   => 'DESC',
    'number'  => 6,
) );

if ( ! empty( $categories ) ) :
?>
    <div class="category-boxes-grid">
        <?php foreach ( $categories as $category ) : ?>
            <?php
            // Get the latest post from this category
            $cat_post = new WP_Query( array(
                'posts_per_page' => 1,
                'cat'            => $category->term_id,
            ) );

            if ( $cat_post->have_posts() ) : $cat_post->the_post();
            ?>
                <div class="category-box-dontmiss">
                    <div class="featured-image-wrapper">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'profitbenefit-featured', array( 'class' => 'featured-image' ) ); ?>
                        <?php else : ?>
                            <img src="https://via.placeholder.com/800x500" alt="<?php echo esc_attr( $category->name ); ?>" class="featured-image">
                        <?php endif; ?>
                        <span class="category-badge"><?php echo esc_html( $category->name ); ?></span>
                    </div>

                    <div class="featured-content">
                        <h3 class="featured-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <div class="featured-meta">
                            <span><?php echo get_the_author(); ?></span>
                            <span>•</span>
                            <span><?php echo get_the_date(); ?></span>
                            <span>•</span>
                            <span><?php echo esc_html( profitbenefit_reading_time() ); ?> min read</span>
                        </div>

                        <p class="featured-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>

                        <?php
                        // Get 3 more posts from this category
                        $related_posts = new WP_Query( array(
                            'posts_per_page' => 3,
                            'cat'            => $category->term_id,
                            'post__not_in'   => array( get_the_ID() ),
                        ) );

                        if ( $related_posts->have_posts() ) :
                        ?>
                            <div class="related-articles">
                                <?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
                                    <div class="article-item">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <?php the_post_thumbnail( 'profitbenefit-thumbnail', array( 'class' => 'article-thumbnail' ) ); ?>
                                            <?php else : ?>
                                                <img src="https://via.placeholder.com/80x60" alt="<?php the_title_attribute(); ?>" class="article-thumbnail">
                                            <?php endif; ?>
                                            <div class="article-info">
                                                <h3><?php the_title(); ?></h3>
                                                <p class="article-date"><?php echo get_the_date(); ?></p>
                                            </div>
                                        </a>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            <?php
            endif;
            wp_reset_postdata();
            ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
