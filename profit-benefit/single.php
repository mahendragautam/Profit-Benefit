<?php
/**
 * Single Post Template
 * Converted from: blog-vps-hosting.html
 *
 * @package ProfitBenefit_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

if (have_posts()) : while (have_posts()) : the_post();
?>

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
            <span>›</span>
            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>">Blog</a>
            <?php
            $categories = get_the_category();
            if (!empty($categories)) :
            ?>
                <span>›</span>
                <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>"><?php echo esc_html($categories[0]->name); ?></a>
            <?php endif; ?>
            <span>›</span>
            <span><?php the_title(); ?></span>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-grid">
                <!-- Blog Post -->
                <article class="blog-post">
                    <div class="post-header">
                        <?php if (!empty($categories)) : ?>
                            <span class="post-category"><?php echo esc_html($categories[0]->name); ?></span>
                        <?php endif; ?>
                        <h1 class="post-title"><?php the_title(); ?></h1>
                        <div class="post-meta">
                            <div class="post-meta-item">
                                <div class="author-avatar"><?php echo strtoupper(substr(get_the_author(), 0, 2)); ?></div>
                                <span>By <strong><?php the_author(); ?></strong></span>
                            </div>
                            <div class="post-meta-item">📅 <?php echo get_the_date('F j, Y'); ?></div>
                            <div class="post-meta-item">⏱️ <?php echo esc_html(profitbenefit_reading_time()); ?> min read</div>
                            <div class="post-meta-item">🔄 Updated <?php echo get_the_modified_date('F j, Y'); ?></div>
                        </div>
                    </div>

                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('full', array('class' => 'featured-image', 'alt' => get_the_title())); ?>
                    <?php endif; ?>

                    <!-- Affiliate Disclosure -->
                    <div class="affiliate-disclosure">
                        <strong>⚠️ Affiliate Disclosure</strong>
                        This article contains affiliate links. When you sign up for a service through our links, we may earn a commission at no extra cost to you. This helps us maintain the site and continue providing honest, in-depth reviews. We only recommend services we've personally tested and believe offer genuine value.
                    </div>

                    <?php
                    // Quick Summary Box (for comparison/review posts)
                    $show_summary = get_post_meta(get_the_ID(), '_show_quick_summary', true);
                    if ($show_summary == '1') :
                        $summary_1_title = get_post_meta(get_the_ID(), '_summary_1_title', true);
                        $summary_1_value = get_post_meta(get_the_ID(), '_summary_1_value', true);
                        $summary_2_title = get_post_meta(get_the_ID(), '_summary_2_title', true);
                        $summary_2_value = get_post_meta(get_the_ID(), '_summary_2_value', true);
                        $summary_3_title = get_post_meta(get_the_ID(), '_summary_3_title', true);
                        $summary_3_value = get_post_meta(get_the_ID(), '_summary_3_value', true);

                        if ($summary_1_title && $summary_1_value) :
                    ?>
                    <!-- Quick Summary -->
                    <div class="summary-box">
                        <h3><?php esc_html_e('Quick Summary', 'profitbenefit-theme'); ?></h3>
                        <div class="summary-grid">
                            <?php if ($summary_1_title && $summary_1_value) : ?>
                            <div class="summary-item">
                                <div class="summary-item-title"><?php echo esc_html($summary_1_title); ?></div>
                                <div class="summary-item-value"><?php echo esc_html($summary_1_value); ?></div>
                            </div>
                            <?php endif; ?>

                            <?php if ($summary_2_title && $summary_2_value) : ?>
                            <div class="summary-item">
                                <div class="summary-item-title"><?php echo esc_html($summary_2_title); ?></div>
                                <div class="summary-item-value"><?php echo esc_html($summary_2_value); ?></div>
                            </div>
                            <?php endif; ?>

                            <?php if ($summary_3_title && $summary_3_value) : ?>
                            <div class="summary-item">
                                <div class="summary-item-title"><?php echo esc_html($summary_3_title); ?></div>
                                <div class="summary-item-value"><?php echo esc_html($summary_3_value); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                        endif;
                    endif;
                    ?>

                    <!-- Post Content -->
                    <div class="post-content">
                        <?php the_content(); ?>

                        <?php
                        // Demo Content Templates Below
                        // These are examples showing how to structure comparison tables and provider cards
                        // Copy and paste into your post content as needed
                        ?>

                        <!-- Example Comparison Table (copy to post content):
                        <div class="comparison-table-wrapper">
                            <table class="comparison-table">
                                <thead>
                                    <tr>
                                        <th>Provider</th>
                                        <th>Starting Price</th>
                                        <th>RAM</th>
                                        <th>Storage</th>
                                        <th>Rating</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="provider-name">DigitalOcean</td>
                                        <td class="price-cell">$6/mo</td>
                                        <td>1 GB</td>
                                        <td>25 GB SSD</td>
                                        <td class="rating">★★★★★ 4.8</td>
                                        <td><a href="#" class="table-cta" target="_blank" rel="noopener">Visit Site →</a></td>
                                    </tr>
                                    <tr>
                                        <td class="provider-name">Vultr</td>
                                        <td class="price-cell">$6/mo</td>
                                        <td>1 GB</td>
                                        <td>25 GB SSD</td>
                                        <td class="rating">★★★★★ 4.7</td>
                                        <td><a href="#" class="table-cta" target="_blank" rel="noopener">Visit Site →</a></td>
                                    </tr>
                                    <tr>
                                        <td class="provider-name">Linode (Akamai)</td>
                                        <td class="price-cell">$5/mo</td>
                                        <td>1 GB</td>
                                        <td>25 GB SSD</td>
                                        <td class="rating">★★★★☆ 4.6</td>
                                        <td><a href="#" class="table-cta" target="_blank" rel="noopener">Visit Site →</a></td>
                                    </tr>
                                    <tr>
                                        <td class="provider-name">Hostinger VPS</td>
                                        <td class="price-cell">$5.99/mo</td>
                                        <td>4 GB</td>
                                        <td>50 GB SSD</td>
                                        <td class="rating">★★★★★ 4.8</td>
                                        <td><a href="#" class="table-cta" target="_blank" rel="noopener">Visit Site →</a></td>
                                    </tr>
                                    <tr>
                                        <td class="provider-name">AWS Lightsail</td>
                                        <td class="price-cell">$5/mo</td>
                                        <td>1 GB</td>
                                        <td>40 GB SSD</td>
                                        <td class="rating">★★★★☆ 4.5</td>
                                        <td><a href="#" class="table-cta" target="_blank" rel="noopener">Visit Site →</a></td>
                                    </tr>
                                    <tr>
                                        <td class="provider-name">Kamatera</td>
                                        <td class="price-cell">$4/mo</td>
                                        <td>1 GB</td>
                                        <td>20 GB SSD</td>
                                        <td class="rating">★★★★☆ 4.4</td>
                                        <td><a href="#" class="table-cta" target="_blank" rel="noopener">Visit Site →</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        -->

                        <!-- Example Provider Card Structure (copy to post content):
                        <div class="provider-card" id="example-provider">
                            <span class="best-for">🏆 Best Overall</span>
                            <div class="provider-header">
                                <div class="provider-info">
                                    <h3>Provider Name</h3>
                                    <p class="provider-tagline">Brief tagline</p>
                                </div>
                                <div class="provider-price">
                                    <span class="price-label">Starting at</span>
                                    <div>
                                        <span class="price-amount">$6</span>
                                        <span class="price-period">/month</span>
                                    </div>
                                </div>
                            </div>

                            <div class="provider-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-text">4.8/5.0 based on 14,000+ reviews</span>
                            </div>

                            <p class="provider-description">
                                Description of the provider...
                            </p>

                            <div class="features-grid">
                                <div class="feature-item">
                                    <span class="feature-icon">⚡</span>
                                    <span>Feature 1</span>
                                </div>
                                <div class="feature-item">
                                    <span class="feature-icon">🌍</span>
                                    <span>Feature 2</span>
                                </div>
                            </div>

                            <div class="pros-cons">
                                <div class="pros">
                                    <h4>✓ Pros</h4>
                                    <ul>
                                        <li>Pro 1</li>
                                        <li>Pro 2</li>
                                    </ul>
                                </div>
                                <div class="cons">
                                    <h4>✗ Cons</h4>
                                    <ul>
                                        <li>Con 1</li>
                                        <li>Con 2</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="cta-buttons">
                                <a href="#" class="cta-primary" target="_blank" rel="noopener">
                                    Get Started →
                                </a>
                                <a href="#" class="cta-secondary">
                                    Read Full Review
                                </a>
                            </div>
                        </div>
                        -->
                    </div>

                    <!-- Tags -->
                    <?php
                    $tags = get_the_tags();
                    if ($tags) :
                    ?>
                        <div class="post-tags">
                            <h4>Tags:</h4>
                            <div class="tag-cloud">
                                <?php foreach ($tags as $tag) : ?>
                                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag"><?php echo esc_html($tag->name); ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Author Box -->
                    <div class="author-box">
                        <div class="author-avatar-large">
                            <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                        </div>
                        <div class="author-info">
                            <h4>About <?php the_author(); ?></h4>
                            <p><?php echo wp_kses_post(get_the_author_meta('description')); ?></p>
                        </div>
                    </div>

                    <!-- Comments -->
                    <?php
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </article>

                <!-- Sidebar -->
                <aside class="sidebar">
                    <?php
                    // Top Pick Widget (for review/comparison posts)
                    $show_top_pick = get_post_meta(get_the_ID(), '_show_top_pick', true);
                    if ($show_top_pick == '1') :
                        $top_pick_name = get_post_meta(get_the_ID(), '_top_pick_name', true);
                        $top_pick_price = get_post_meta(get_the_ID(), '_top_pick_price', true);
                        $top_pick_url = get_post_meta(get_the_ID(), '_top_pick_url', true);

                        if ($top_pick_name) :
                    ?>
                    <!-- Top Pick Widget -->
                    <div class="sidebar-widget">
                        <div class="top-pick">
                            <h4><?php esc_html_e("Editor's Choice", 'profitbenefit-theme'); ?></h4>
                            <div class="top-pick-name"><?php echo esc_html($top_pick_name); ?></div>
                            <?php if ($top_pick_price) : ?>
                                <div class="top-pick-price">Starting at <?php echo esc_html($top_pick_price); ?></div>
                            <?php endif; ?>
                            <?php if ($top_pick_url) : ?>
                                <a href="<?php echo esc_url($top_pick_url); ?>" class="top-pick-btn" target="_blank" rel="noopener">Get Started →</a>
                            <?php else : ?>
                                <a href="#" class="top-pick-btn">Learn More →</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                        endif;
                    endif;
                    ?>

                    <!-- Table of Contents -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Table of Contents</h3>
                        <ul class="toc-list">
                            <?php
                            // Get headings from post content for TOC
                            $content = get_the_content();
                            preg_match_all('/<h2[^>]*id=["\']([^"\']+)["\'][^>]*>(.*?)<\/h2>/i', $content, $h2_matches);

                            if (!empty($h2_matches[1])) :
                                foreach ($h2_matches[1] as $index => $id) :
                                    $title = strip_tags($h2_matches[2][$index]);
                            ?>
                                    <li class="toc-item"><a href="#<?php echo esc_attr($id); ?>" class="toc-link"><?php echo esc_html($title); ?></a></li>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    </div>

                    <!-- Categories Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Categories</h3>
                        <ul class="category-list">
                            <?php
                            $sidebar_cats = get_categories(array('number' => 6));
                            foreach ($sidebar_cats as $cat) :
                            ?>
                            <li class="category-item">
                                <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="category-link">
                                    <span><?php echo esc_html($cat->name); ?></span>
                                    <span class="category-count"><?php echo esc_html($cat->count); ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Popular Posts Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Popular Posts</h3>
                        <ul class="popular-posts">
                            <?php
                            $popular = new WP_Query(array('posts_per_page' => 3, 'meta_key' => 'post_views_count', 'orderby' => 'meta_value_num'));
                            if (!$popular->have_posts()) {
                                $popular = new WP_Query(array('posts_per_page' => 3));
                            }
                            while ($popular->have_posts()) : $popular->the_post();
                            ?>
                            <li class="popular-post">
                                <a href="<?php the_permalink(); ?>" class="popular-post-link">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'popular-post-thumb', 'alt' => get_the_title())); ?>
                                    <?php endif; ?>
                                    <div class="popular-post-info">
                                        <h4><?php the_title(); ?></h4>
                                        <span class="popular-post-date"><?php echo get_the_date('M j, Y'); ?></span>
                                    </div>
                                </a>
                            </li>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </ul>
                    </div>

                    <!-- Tags Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Popular Tags</h3>
                        <div class="tag-cloud">
                            <?php
                            $sidebar_tags = get_tags(array('number' => 10));
                            foreach ($sidebar_tags as $tag) :
                            ?>
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag"><?php echo esc_html($tag->name); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <!-- Related Posts -->
    <?php
    $related = new WP_Query(array(
        'category__in' => wp_get_post_categories(get_the_ID()),
        'post__not_in' => array(get_the_ID()),
        'posts_per_page' => 3,
        'orderby' => 'rand',
    ));

    if ($related->have_posts()) :
    ?>
    <section class="related-posts-section">
        <div class="container">
            <h2 class="section-title">Related Articles</h2>
            <div class="blog-grid cards-grid">
                <?php while ($related->have_posts()) : $related->the_post();
                    $categories = get_the_category();
                ?>
                <article class="blog-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium_large', array('class' => 'blog-card-image', 'alt' => get_the_title())); ?>
                    <?php endif; ?>
                    <div class="blog-card-content">
                        <?php if (!empty($categories)) : ?>
                            <span class="blog-card-category"><?php echo esc_html($categories[0]->name); ?></span>
                        <?php endif; ?>
                        <h3 class="blog-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <div class="blog-card-meta">
                            <span class="blog-card-meta-item">👤 <?php the_author(); ?></span>
                            <span class="blog-card-meta-item">📅 <?php echo get_the_date('M j, Y'); ?></span>
                        </div>
                        <p class="blog-card-excerpt">
                            <?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?>
                        </p>
                        <div class="blog-card-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more">Read More →</a>
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

<?php
endwhile;
endif;

get_footer();
