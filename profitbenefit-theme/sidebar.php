<div class="right-sidebar">
    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    <?php else : ?>

        <!-- Search Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title"><?php esc_html_e( 'Search', 'profitbenefit-theme' ); ?></h3>
            <?php get_search_form(); ?>
        </div>

        <!-- Categories Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title"><?php esc_html_e( 'Categories', 'profitbenefit-theme' ); ?></h3>
            <ul class="category-list">
                <?php
                $categories = get_categories( array(
                    'orderby' => 'count',
                    'order'   => 'DESC',
                    'number'  => 5,
                ) );

                foreach ( $categories as $category ) :
                ?>
                    <li class="category-item">
                        <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="category-link">
                            <span><?php echo esc_html( $category->name ); ?></span>
                            <span class="category-count"><?php echo esc_html( $category->count ); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Popular Posts Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title"><?php esc_html_e( 'Popular Posts', 'profitbenefit-theme' ); ?></h3>
            <ul class="popular-posts">
                <?php
                $popular_posts = new WP_Query( array(
                    'posts_per_page' => 3,
                    'meta_key'       => 'post_views_count',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'DESC',
                ) );

                if ( ! $popular_posts->have_posts() ) {
                    $popular_posts = new WP_Query( array(
                        'posts_per_page' => 3,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ) );
                }

                while ( $popular_posts->have_posts() ) :
                    $popular_posts->the_post();
                ?>
                    <li class="popular-post">
                        <a href="<?php the_permalink(); ?>" class="popular-post-link">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'profitbenefit-thumbnail', array( 'class' => 'popular-post-thumb' ) ); ?>
                            <?php else : ?>
                                <img src="https://via.placeholder.com/70x70" alt="<?php the_title_attribute(); ?>" class="popular-post-thumb">
                            <?php endif; ?>
                            <div class="popular-post-info">
                                <h4><?php the_title(); ?></h4>
                                <span class="popular-post-date"><?php echo get_the_date(); ?></span>
                            </div>
                        </a>
                    </li>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </ul>
        </div>

        <!-- Popular Tags Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title"><?php esc_html_e( 'Popular Tags', 'profitbenefit-theme' ); ?></h3>
            <div class="tag-cloud">
                <?php
                $tags = get_tags( array(
                    'orderby' => 'count',
                    'order'   => 'DESC',
                    'number'  => 15,
                ) );

                foreach ( $tags as $tag ) :
                ?>
                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag">
                        <?php echo esc_html( $tag->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    <?php endif; ?>
</div>
