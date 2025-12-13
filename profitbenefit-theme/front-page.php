<?php
/**
 * The front page template file
 *
 * @package ProfitBenefit_Theme
 */

get_header();
?>

    <!-- Animated Hero Banner -->
    <section class="hero-banner">
        <div class="hero-background"></div>
        <div class="hero-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <div class="hero-content">
            <h1 class="hero-title">
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
                <span class="badge-icon">✓</span>
                <span><?php echo esc_html( get_theme_mod( 'trust_badge_1', '500+ Tools Reviewed' ) ); ?></span>
            </div>
            <div class="trust-badge">
                <span class="badge-icon">★</span>
                <span><?php echo esc_html( get_theme_mod( 'trust_badge_2', 'Expert Comparisons' ) ); ?></span>
            </div>
            <div class="trust-badge">
                <span class="badge-icon">⚡</span>
                <span><?php echo esc_html( get_theme_mod( 'trust_badge_3', 'Updated Weekly' ) ); ?></span>
            </div>
        </div>
    </section>

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

    <!-- Recent Posts Section -->
    <?php
    $recent_posts = new WP_Query( array(
        'posts_per_page' => 9,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    if ( $recent_posts->have_posts() ) :
    ?>
        <div class="main-content">
            <div class="container">
                <h2 class="section-title"><?php esc_html_e( 'Recent Articles', 'profitbenefit-theme' ); ?></h2>
                <div class="blog-grid">
                    <?php
                    while ( $recent_posts->have_posts() ) :
                        $recent_posts->the_post();
                        get_template_part( 'template-parts/content', get_post_format() );
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>

                <div style="text-align: center; margin-top: 40px;">
                    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="read-more" style="font-size: 18px;">
                        <?php esc_html_e( 'View All Posts', 'profitbenefit-theme' ); ?> →
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php
get_footer();
