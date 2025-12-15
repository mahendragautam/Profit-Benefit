<?php
/**
 * Single Post Template
 * Converted from: blog-vps-hosting.html
 *
 * @package ProfitBenefit_Theme
 */

get_header();

// Track post views
if ( is_single() && ! is_preview() ) {
    profitbenefit_set_post_views( get_the_ID() );
}
?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <!-- Post Header with Gradient Background -->
        <div class="post-header">
            <div class="container">
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) :
                ?>
                    <span class="post-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                <?php endif; ?>

                <h1 class="post-title"><?php the_title(); ?></h1>

                <div class="post-meta">
                    <span class="author">By <?php echo get_the_author(); ?></span>
                    <span>•</span>
                    <span><?php echo get_the_date(); ?></span>
                    <span>•</span>
                    <span><?php echo esc_html( profitbenefit_reading_time() ); ?> min read</span>
                    <span>•</span>
                    <span><?php echo esc_html( profitbenefit_get_post_views( get_the_ID() ) ); ?> views</span>
                </div>
            </div>
        </div>

        <!-- Featured Image with Gradient Overlay -->
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="featured-image-section single-post-image image-with-gradient">
                <div class="container">
                    <?php the_post_thumbnail( 'profitbenefit-hero', array( 'class' => 'featured-image' ) ); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="single-post-content">
            <div class="container">
                <div class="content-layout">
                    <!-- Article Content -->
                    <article class="article-main">
                        <div class="post-content">
                            <?php the_content(); ?>
                        </div>

                        <!-- Post Tags -->
                        <?php
                        $tags = get_the_tags();
                        if ( $tags ) :
                        ?>
                            <div class="post-tags">
                                <h3><?php esc_html_e( 'Tags:', 'profitbenefit-theme' ); ?></h3>
                                <div class="tag-cloud">
                                    <?php foreach ( $tags as $tag ) : ?>
                                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag">
                                            <?php echo esc_html( $tag->name ); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Author Box -->
                        <div class="author-box">
                            <div class="author-avatar">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?>
                            </div>
                            <div class="author-info">
                                <h4><?php esc_html_e( 'About the Author', 'profitbenefit-theme' ); ?></h4>
                                <h5><?php echo get_the_author(); ?></h5>
                                <p><?php echo get_the_author_meta( 'description' ); ?></p>
                            </div>
                        </div>

                        <!-- Post Navigation -->
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();

                        if ( $prev_post || $next_post ) :
                        ?>
                            <div class="post-navigation">
                                <?php if ( $prev_post ) : ?>
                                    <a href="<?php echo get_permalink( $prev_post->ID ); ?>" class="nav-previous">
                                        <span class="nav-label">← Previous Post</span>
                                        <span class="nav-title"><?php echo esc_html( $prev_post->post_title ); ?></span>
                                    </a>
                                <?php endif; ?>

                                <?php if ( $next_post ) : ?>
                                    <a href="<?php echo get_permalink( $next_post->ID ); ?>" class="nav-next">
                                        <span class="nav-label">Next Post →</span>
                                        <span class="nav-title"><?php echo esc_html( $next_post->post_title ); ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Comments -->
                        <?php
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;
                        ?>
                    </article>

                    <!-- Sidebar -->
                    <aside class="article-sidebar">
                        <?php get_sidebar(); ?>
                    </aside>
                </div>
            </div>
        </div>

        <!-- Related Posts with Gradient Overlays -->
        <?php
        $related_posts = new WP_Query( array(
            'category__in'   => wp_get_post_categories( get_the_ID() ),
            'post__not_in'   => array( get_the_ID() ),
            'posts_per_page' => 3,
            'orderby'        => 'rand',
        ) );

        if ( $related_posts->have_posts() ) :
        ?>
            <section class="related-posts-section">
                <div class="container">
                    <h2 class="section-title"><?php esc_html_e( 'Related Articles', 'profitbenefit-theme' ); ?></h2>
                    <div class="related-posts-grid">
                        <?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
                            <article class="blog-card">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="blog-card-image-wrapper image-with-gradient">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail( 'profitbenefit-featured', array( 'class' => 'blog-card-image' ) ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="blog-card-content">
                                    <h3 class="blog-card-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <div class="blog-card-meta">
                                        <span><?php echo get_the_date(); ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        <?php
        endif;
        wp_reset_postdata();
        ?>

    <?php endwhile; endif; ?>

<?php
get_footer();
