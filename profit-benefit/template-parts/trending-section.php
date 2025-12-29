<?php
/**
 * Template part for displaying trending posts section
 *
 * @package ProfitBenefit_Theme
 */

$trending_posts = profitbenefit_get_trending_posts( 8 );

if ( $trending_posts->have_posts() ) :
?>
    <section class="trending-section">
        <div class="container">
            <h2 class="section-title">🔥 <?php esc_html_e( 'TRENDING NOW', 'profitbenefit-theme' ); ?></h2>
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
                                <?php the_post_thumbnail( 'profitbenefit-trending', array( 'alt' => get_the_title() ) ); ?>
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
    </section>
<?php endif; ?>
