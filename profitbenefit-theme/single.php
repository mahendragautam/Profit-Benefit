<?php
/**
 * The template for displaying single posts
 *
 * @package ProfitBenefit_Theme
 */

get_header();

// Track post views
if ( is_single() && ! is_preview() ) {
    profitbenefit_set_post_views( get_the_ID() );
}
?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) :
                ?>
                    <span class="blog-card-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                <?php endif; ?>
                <h1 class="page-title"><?php the_title(); ?></h1>
                <p class="page-subtitle">
                    <span><?php echo get_the_author(); ?></span> •
                    <span><?php echo get_the_date(); ?></span> •
                    <span><?php echo esc_html( profitbenefit_reading_time() ); ?> min read</span>
                </p>
            <?php endwhile; endif; rewind_posts(); ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="content-grid">
                <div style="grid-column: span 2;">
                    <?php
                    if ( have_posts() ) :
                        while ( have_posts() ) :
                            the_post();
                    ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="featured-image-wrapper" style="margin-bottom: 30px;">
                                        <?php the_post_thumbnail( 'large', array( 'class' => 'featured-image' ) ); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="post-content">
                                    <?php the_content(); ?>
                                </div>

                                <?php
                                // Post tags
                                $tags = get_the_tags();
                                if ( $tags ) :
                                ?>
                                    <div class="post-tags" style="margin-top: 30px;">
                                        <div class="tag-cloud">
                                            <?php foreach ( $tags as $tag ) : ?>
                                                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag">
                                                    <?php echo esc_html( $tag->name ); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php
                                // If comments are open or there are comments, load the comment template
                                if ( comments_open() || get_comments_number() ) :
                                    comments_template();
                                endif;
                                ?>
                            </article>

                            <?php
                            // Previous/Next post navigation
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();

                            if ( $prev_post || $next_post ) :
                            ?>
                                <div class="post-navigation" style="margin-top: 40px; display: flex; justify-content: space-between; gap: 20px;">
                                    <?php if ( $prev_post ) : ?>
                                        <a href="<?php echo get_permalink( $prev_post->ID ); ?>" class="nav-previous">
                                            ← <?php echo esc_html( $prev_post->post_title ); ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ( $next_post ) : ?>
                                        <a href="<?php echo get_permalink( $next_post->ID ); ?>" class="nav-next">
                                            <?php echo esc_html( $next_post->post_title ); ?> →
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                    <?php
                        endwhile;
                    endif;
                    ?>
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </div>

<?php
get_footer();
