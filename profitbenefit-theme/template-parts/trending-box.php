<?php
/**
 * Template part for displaying trending posts box
 *
 * @package ProfitBenefit_Theme
 */

$trending_posts = profitbenefit_get_trending_posts( 8 );

if ( $trending_posts->have_posts() ) :
?>
    <div class="trending-box">
        <h2 class="trending-box-title">🔥 <?php esc_html_e( 'TRENDING NOW', 'profitbenefit-theme' ); ?></h2>
        <div class="trending-scroll">
            <?php
            $count = 1;
            while ( $trending_posts->have_posts() ) :
                $trending_posts->the_post();
                $categories = get_the_category();
            ?>
                <div class="trending-card">
                    <span class="trending-number"><?php echo esc_html( $count ); ?></span>
                    <a href="<?php the_permalink(); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'profitbenefit-trending' ); ?>
                        <?php else : ?>
                            <img src="https://via.placeholder.com/400x300" alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                        <div class="trending-info">
                            <?php if ( ! empty( $categories ) ) : ?>
                                <div class="trending-category"><?php echo esc_html( $categories[0]->name ); ?></div>
                            <?php endif; ?>
                            <div class="trending-title"><?php the_title(); ?></div>
                        </div>
                    </a>
                </div>
            <?php
                $count++;
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
<?php endif; ?>
