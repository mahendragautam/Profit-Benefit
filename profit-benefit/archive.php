<?php
/**
 * Archive Template (Blog Listing Page)
 * Converted from: blog-listing.html
 *
 * @package ProfitBenefit_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();
?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Latest Articles & Guides</h1>
            <p class="page-subtitle">Expert insights on business tools, software reviews, and productivity tips</p>
        </div>
    </div>

    <!-- DON'T MISS Section -->
    <div class="dont-miss-section">
        <div class="dont-miss-container">
            <div class="category-header">
                <div class="dont-miss-label">DON'T MISS</div>
                <div class="category-tabs">
                    <button class="tab-button active" data-tab="all">All</button>
                    <button class="tab-button" data-tab="crm">CRM Tools</button>
                    <button class="tab-button" data-tab="marketing">Marketing</button>
                    <button class="tab-button" data-tab="project">Project Mgmt</button>
                    <button class="tab-button" data-tab="analytics">Analytics</button>
                    <button class="tab-button" data-tab="hosting">Hosting</button>
                </div>
            </div>

            <!-- All Category Content -->
            <div class="content-wrapper active" data-content="all">
                <?php
                $dont_miss_all = new WP_Query(array('posts_per_page' => 1, 'offset' => 0));
                if ($dont_miss_all->have_posts()) : $dont_miss_all->the_post();
                ?>
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', array('class' => 'featured-image', 'alt' => get_the_title())); ?>
                        <?php endif; ?>
                        <span class="category-badge">Featured Review</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="featured-meta">
                            <span class="author"><?php the_author(); ?></span>
                            <span>•</span>
                            <span><?php echo get_the_date('F j, Y'); ?></span>
                        </div>
                        <p class="featured-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                    </div>
                </div>
                <?php
                endif;
                wp_reset_postdata();
                ?>
                <div class="related-articles">
                    <?php
                    $related_all = new WP_Query(array('posts_per_page' => 4, 'offset' => 1));
                    while ($related_all->have_posts()) : $related_all->the_post();
                    ?>
                    <div class="article-item">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail', array('class' => 'article-thumbnail', 'alt' => get_the_title())); ?>
                        <?php endif; ?>
                        <div class="article-info">
                            <h3><?php the_title(); ?></h3>
                            <p class="article-date"><?php echo get_the_date('F j, Y'); ?></p>
                        </div>
                    </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>

            <!-- CRM Tools Content -->
            <div class="content-wrapper" data-content="crm">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">CRM Tools</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">How to Choose the Right CRM for Your Business in 2024</h2>
                        <div class="featured-meta">
                            <span class="author">John Doe</span>
                            <span>•</span>
                            <span>December 8, 2024</span>
                        </div>
                        <p class="featured-excerpt">Choosing the right CRM system can transform how you manage customer relationships. Learn the key features to look for and compare top options including Salesforce, HubSpot, Pipedrive, and Zoho CRM.</p>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Salesforce vs HubSpot: Which CRM is Better?</h3>
                            <p class="article-date">December 6, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Pipedrive Review: Best CRM for Small Teams?</h3>
                            <p class="article-date">December 4, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Zoho CRM Complete Guide and Pricing</h3>
                            <p class="article-date">December 2, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>CRM Integration Tips for Better Workflow</h3>
                            <p class="article-date">November 30, 2024</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Marketing Tools Content -->
            <div class="content-wrapper" data-content="marketing">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Marketing Tools</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Email Marketing Platforms: Complete Comparison Guide</h2>
                        <div class="featured-meta">
                            <span class="author">Mike Johnson</span>
                            <span>•</span>
                            <span>December 3, 2024</span>
                        </div>
                        <p class="featured-excerpt">From Mailchimp to ConvertKit - we've tested them all. Get an honest comparison of features, pricing, deliverability, and ease of use for top email marketing platforms.</p>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Best SEO Tools for 2024: Complete Guide</h3>
                            <p class="article-date">November 28, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Social Media Schedulers: Top 5 Compared</h3>
                            <p class="article-date">November 26, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Content Marketing Tools That Actually Work</h3>
                            <p class="article-date">November 24, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Marketing Analytics Platforms Review</h3>
                            <p class="article-date">November 22, 2024</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Management Content -->
            <div class="content-wrapper" data-content="project">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Project Management</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Top 10 Project Management Tools for Remote Teams</h2>
                        <div class="featured-meta">
                            <span class="author">Sarah Miller</span>
                            <span>•</span>
                            <span>December 5, 2024</span>
                        </div>
                        <p class="featured-excerpt">Discover the best project management software for distributed teams. Compare features, pricing, and find the perfect fit for your workflow.</p>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Asana vs Monday.com: Which is Better?</h3>
                            <p class="article-date">December 2, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>ClickUp Complete Review and Guide</h3>
                            <p class="article-date">November 30, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Trello vs Notion: Complete Comparison</h3>
                            <p class="article-date">November 28, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Jira for Agile Teams: Complete Guide</h3>
                            <p class="article-date">November 26, 2024</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Content -->
            <div class="content-wrapper" data-content="analytics">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Analytics</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Business Analytics Tools: Complete Comparison</h2>
                        <div class="featured-meta">
                            <span class="author">Rachel Adams</span>
                            <span>•</span>
                            <span>December 7, 2024</span>
                        </div>
                        <p class="featured-excerpt">From Google Analytics to Mixpanel - compare the best analytics platforms for your business needs and budget.</p>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Google Analytics 4: Complete Guide</h3>
                            <p class="article-date">December 5, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Mixpanel vs Amplitude Comparison</h3>
                            <p class="article-date">December 3, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Tableau for Business Intelligence</h3>
                            <p class="article-date">December 1, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Looker Studio Tutorial and Tips</h3>
                            <p class="article-date">November 29, 2024</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hosting Content -->
            <div class="content-wrapper" data-content="hosting">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Hosting</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Best VPS Hosting Providers for 2024 - Complete Comparison</h2>
                        <div class="featured-meta">
                            <span class="author">David Chen</span>
                            <span>•</span>
                            <span>November 25, 2024</span>
                        </div>
                        <p class="featured-excerpt">Comprehensive comparison of top VPS hosting providers including DigitalOcean, Vultr, Linode, AWS Lightsail, and Hetzner. Find the perfect hosting solution for your business.</p>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Cloud Hosting vs VPS: Which to Choose?</h3>
                            <p class="article-date">November 23, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Best Shared Hosting Providers 2024</h3>
                            <p class="article-date">November 21, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Managed WordPress Hosting Guide</h3>
                            <p class="article-date">November 19, 2024</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Dedicated Server Hosting Comparison</h3>
                            <p class="article-date">November 17, 2024</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Widgets and Categories Section (2 Column Grid) -->
    <section class="widgets-categories-section">
        <div class="widgets-container">
            <!-- Trending Box -->
            <div class="trending-box">
                <h3 class="trending-box-title">🔥 Trending Now</h3>
                <div class="trending-box-scroll">
                    <?php
                    $trending_box = new WP_Query(array('posts_per_page' => 5, 'meta_key' => 'post_views_count', 'orderby' => 'meta_value_num'));
                    if (!$trending_box->have_posts()) {
                        $trending_box = new WP_Query(array('posts_per_page' => 5));
                    }
                    while ($trending_box->have_posts()) : $trending_box->the_post();
                        $categories = get_the_category();
                    ?>
                    <div class="trending-box-item">
                        <?php if (!empty($categories)) : ?>
                            <span class="trending-box-category"><?php echo esc_html($categories[0]->name); ?></span>
                        <?php endif; ?>
                        <h4 class="trending-box-item-title"><?php the_title(); ?></h4>
                    </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>

            <!-- Categories Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">Categories</h3>
                <ul class="category-list">
                    <?php
                    $categories = get_categories(array('number' => 6));
                    foreach ($categories as $category) :
                    ?>
                    <li class="category-item">
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-link">
                            <span><?php echo esc_html($category->name); ?></span>
                            <span class="category-count"><?php echo esc_html($category->count); ?></span>
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
                                <?php the_post_thumbnail('thumbnail', array('class' => 'popular-post-thumb')); ?>
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

            <!-- Empty for spacing -->
            <div class="widget-empty"></div>
            <div class="widget-empty"></div>

            <div class="sidebar-widget">
                <h3 class="widget-title">Popular Tags</h3>
                <div class="tag-cloud">
                    <?php
                    $tags = get_tags(array('number' => 10));
                    foreach ($tags as $tag) :
                    ?>
                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag"><?php echo esc_html($tag->name); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Boxes Section (2 columns) -->
    <section class="category-boxes-section">
        <div class="category-boxes-grid">
            <?php
            $category_boxes = array(
                array('title' => 'CRM Tools', 'links' => array('Salesforce Complete Guide', 'HubSpot CRM Review', 'Pipedrive for Small Teams', 'Zoho CRM Features')),
                array('title' => 'Marketing Tools', 'links' => array('Mailchimp vs ConvertKit', 'SEO Tools Comparison', 'Social Media Schedulers', 'Content Marketing Tools')),
                array('title' => 'Project Management', 'links' => array('Asana vs Monday.com', 'ClickUp Complete Review', 'Trello vs Notion', 'Jira for Agile Teams')),
                array('title' => 'Finance Tools', 'links' => array('QuickBooks vs Xero', 'FreshBooks Review', 'Wave Accounting Guide', 'Best Invoicing Software')),
                array('title' => 'SEO Tools', 'links' => array('Best SEO Tools 2024', 'Ahrefs vs SEMrush', 'Moz Pro Review', 'Keyword Research Tools')),
                array('title' => 'Productivity', 'links' => array('Slack vs Microsoft Teams', 'Notion Complete Guide', 'Time Tracking Apps', 'Note-Taking Software')),
            );

            foreach ($category_boxes as $box) :
            ?>
            <div class="category-box">
                <h3 class="category-box-title"><?php echo esc_html($box['title']); ?></h3>
                <ul class="category-box-list">
                    <?php foreach ($box['links'] as $link) : ?>
                        <li class="category-box-item"><a href="#" class="category-box-link"><?php echo esc_html($link); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-grid">
                <!-- Other Blog Posts Sidebar -->
                <aside class="other-posts-sidebar">
                    <h3 class="widget-title">Other Articles</h3>
                    <ul class="other-posts-list">
                        <?php
                        $other_posts = new WP_Query(array('posts_per_page' => 6));
                        while ($other_posts->have_posts()) : $other_posts->the_post();
                            $categories = get_the_category();
                        ?>
                        <li class="other-post-item">
                            <a href="<?php the_permalink(); ?>" class="other-post-link">
                                <?php if (!empty($categories)) : ?>
                                    <span class="other-post-category"><?php echo esc_html($categories[0]->name); ?></span>
                                <?php endif; ?>
                                <h4 class="other-post-title"><?php the_title(); ?></h4>
                                <p class="other-post-meta"><?php echo get_the_date('M j, Y'); ?> • <?php echo esc_html(profitbenefit_reading_time()); ?> min read</p>
                            </a>
                        </li>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </ul>
                </aside>

                <!-- Blog Grid -->
                <div class="blog-grid">
                    <?php
                    if (have_posts()) :
                        while (have_posts()) : the_post();
                            $categories = get_the_category();
                    ?>
                    <!-- Blog Card -->
                    <article class="blog-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large', array('class' => 'blog-card-image', 'alt' => get_the_title())); ?>
                        <?php endif; ?>
                        <div class="blog-card-content">
                            <?php if (!empty($categories)) : ?>
                                <span class="blog-card-category"><?php echo esc_html($categories[0]->name); ?></span>
                            <?php endif; ?>
                            <h2 class="blog-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="blog-card-meta">
                                <span class="blog-card-meta-item">👤 <?php the_author(); ?></span>
                                <span class="blog-card-meta-item">📅 <?php echo get_the_date('M j, Y'); ?></span>
                                <span class="blog-card-meta-item">⏱️ <?php echo esc_html(profitbenefit_reading_time()); ?> min read</span>
                            </div>
                            <p class="blog-card-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </p>
                            <div class="blog-card-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more">Read More →</a>
                            </div>
                        </div>
                    </article>
                    <?php
                        endwhile;
                    endif;
                    ?>
                </div>

                <!-- Sidebar -->
                <aside class="sidebar">
                    <!-- Search Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Search</h3>
                        <form class="search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                            <input type="text" class="search-input" name="s" placeholder="Search articles..." value="<?php echo get_search_query(); ?>">
                            <button type="submit" class="search-btn" aria-label="<?php esc_attr_e('Search', 'profitbenefit-theme'); ?>">
                                <span class="search-icon"></span>
                            </button>
                        </form>
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
                            $sidebar_popular = new WP_Query(array('posts_per_page' => 3));
                            while ($sidebar_popular->have_posts()) : $sidebar_popular->the_post();
                            ?>
                            <li class="popular-post">
                                <a href="<?php the_permalink(); ?>" class="popular-post-link">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'popular-post-thumb')); ?>
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

            <!-- Pagination -->
            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'prev_text' => '‹',
                    'next_text' => '›',
                    'mid_size' => 2,
                    'end_size' => 1,
                    'before_page_number' => '',
                    'type' => 'plain',
                ));
                ?>
            </div>
        </div>
    </main>

<?php
get_footer();
