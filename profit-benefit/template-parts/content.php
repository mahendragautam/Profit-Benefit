<?php
/**
 * Template part for displaying posts in blog grid
 *
 * @package ProfitBenefit_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="blog-card-image-wrapper image-with-gradient">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'profitbenefit-featured', array( 'class' => 'blog-card-image', 'alt' => get_the_title() ) ); ?>
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
            <?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?>
        </div>

        <div class="blog-card-meta">
            <span class="blog-card-meta-item"><?php echo esc_html( get_the_author() ); ?></span>
            <span class="blog-card-meta-item">•</span>
            <span class="blog-card-meta-item"><?php echo esc_html( profitbenefit_print_date() ); ?></span>
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
