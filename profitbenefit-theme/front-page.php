<?php
/**
 * Front Page Template (Homepage)
 * Converted from: home-page-design.html
 *
 * @package ProfitBenefit_Theme
 */

get_header();
?>

    <!-- Hero Banner with Gradient Background -->
    <section class="hero-banner">
        <div class="hero-background"></div>
        <div class="hero-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <!-- Hero Content with Gradient Text -->
        <div class="hero-content">
            <h1 class="hero-title hero-title-gradient">
                <span class="highlight"><?php echo esc_html( get_theme_mod( 'hero_highlight_text', 'Stop Googling' ) ); ?></span>
                <?php echo esc_html( get_theme_mod( 'hero_title_text', 'for Business Tools' ) ); ?>
            </h1>
            <p class="hero-subtitle"><?php echo esc_html( get_theme_mod( 'hero_subtitle_text', 'Everything in one place — ratings, prices, and real user reviews' ) ); ?></p>
            <div class="hero-cta">
                <a href="<?php echo esc_url( get_theme_mod( 'hero_button_url', '#products' ) ); ?>" class="hero-button hero-button-primary">
                    <?php echo esc_html( get_theme_mod( 'hero_button_text', 'Browse Tools' ) ); ?>
                </a>
            </div>
        </div>

        <!-- Trust Badges -->
        <div class="trust-badges">
            <div class="trust-badge">
                <span class="badge-icon">⚡</span>
                <span>500+ Tools Listed</span>
            </div>
            <div class="trust-badge">
                <span class="badge-icon">⭐</span>
                <span>Expert Reviews</span>
            </div>
            <div class="trust-badge">
                <span class="badge-icon">🔄</span>
                <span>Updated Daily</span>
            </div>
        </div>
    </section>

    <!-- DON'T MISS Section -->
    <?php get_template_part( 'template-parts/dont-miss-section' ); ?>

    <!-- Featured Posts Grid with Gradient Overlays -->
    <section class="featured-posts-section">
        <div class="container">
            <h2 class="section-title">Featured Articles</h2>
            <div class="blog-grid">
                <?php
                $featured_posts = new WP_Query( array(
                    'posts_per_page' => 9,
                    'post__in'       => get_option( 'sticky_posts' ),
                    'ignore_sticky_posts' => 1,
                ) );

                if ( ! $featured_posts->have_posts() ) {
                    $featured_posts = new WP_Query( array(
                        'posts_per_page' => 9,
                    ) );
                }

                while ( $featured_posts->have_posts() ) : $featured_posts->the_post();
                ?>
                    <article class="blog-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="blog-card-image-wrapper image-with-gradient">
                                <?php the_post_thumbnail( 'profitbenefit-featured', array( 'class' => 'blog-card-image' ) ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="blog-card-content">
                            <?php
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) :
                            ?>
                                <span class="blog-card-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                            <?php endif; ?>

                            <h3 class="blog-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <div class="blog-card-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <div class="blog-card-meta">
                                <span><?php echo get_the_author(); ?></span>
                                <span>•</span>
                                <span><?php echo get_the_date(); ?></span>
                                <span>•</span>
                                <span><?php echo esc_html( profitbenefit_reading_time() ); ?> min read</span>
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
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>

    <!-- Trending Posts Section -->
    <?php get_template_part( 'template-parts/trending-section' ); ?>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <h2>Stay Updated</h2>
                <p>Get the latest tool reviews and business insights delivered to your inbox.</p>
                <form class="newsletter-form" method="post" action="">
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <button type="submit" class="newsletter-btn">Subscribe</button>
                </form>
            </div>
        </div>
    </section>

<?php
get_footer();
