<?php
/**
 * Front Page Template (Homepage)
 * Converted from: home-page-design.html
 *
 * @package ProfitBenefit_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();
?>

    <!-- AUTO-ROTATING TEXT CAROUSEL HERO BANNER -->
    <section class="hero-banner">
        <div class="hero-background"></div>
        <div class="hero-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <!-- Hero Message -->
        <div class="hero-content">
            <h1 class="hero-title"><span class="highlight">Stop Googling</span> for Business Tools</h1>
            <p class="hero-subtitle">Everything in one place — ratings, prices, and real user reviews</p>
            <div class="hero-cta">
                <a href="#" class="hero-button hero-button-primary">Browse Tools</a>
            </div>
        </div>

        <!-- Trust Badges -->
        <div class="trust-badges">
            <div class="trust-badge">
                <span class="badge-icon">⚡</span>
                <span>500+ Tools Listed</span>
            </div>
            <div class="trust-badge">
                <span class="badge-icon">📊</span>
                <span>Compare Prices & Features</span>
            </div>
            <div class="trust-badge">
                <span class="badge-icon">⭐</span>
                <span>Real User Ratings</span>
            </div>
        </div>
    </section>



    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-grid">
            <div class="hero-main">
                <?php
                $hero_posts = new WP_Query(array(
                    'posts_per_page' => 3,
                    'post__in' => get_option('sticky_posts'),
                    'ignore_sticky_posts' => 1,
                ));

                if (!$hero_posts->have_posts()) {
                    $hero_posts = new WP_Query(array('posts_per_page' => 3));
                }

                $slide_index = 0;
                while ($hero_posts->have_posts()) : $hero_posts->the_post();
                    $active_class = ($slide_index === 0) ? 'active' : '';
                ?>
                <div class="hero-slide <?php echo $active_class; ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('full', array('alt' => get_the_title())); ?>
                    <?php endif; ?>
                    <div class="hero-overlay">
                        <?php
                        $categories = get_the_category();
                        if (!empty($categories)) :
                        ?>
                            <span class="hero-category"><?php echo esc_html($categories[0]->name); ?></span>
                        <?php endif; ?>
                        <h2 class="hero-title"><?php the_title(); ?></h2>
                        <p class="hero-meta">By <?php the_author(); ?> • <?php echo get_the_date('F j, Y'); ?></p>
                    </div>
                </div>
                <?php
                    $slide_index++;
                endwhile;
                wp_reset_postdata();
                ?>
                <div class="hero-dots">
                    <span class="hero-dot active" data-slide="0"></span>
                    <span class="hero-dot" data-slide="1"></span>
                    <span class="hero-dot" data-slide="2"></span>
                </div>
            </div>
            <div class="hero-sidebar">
                <?php
                $sidebar_posts = new WP_Query(array(
                    'posts_per_page' => 2,
                    'offset' => 3,
                ));

                while ($sidebar_posts->have_posts()) : $sidebar_posts->the_post();
                    $categories = get_the_category();
                ?>
                <div class="hero-side-item">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
                    <?php endif; ?>
                    <div class="hero-side-overlay">
                        <?php if (!empty($categories)) : ?>
                            <span style="font-size: 11px; text-transform: uppercase; color: #52b788;"><?php echo esc_html($categories[0]->name); ?></span>
                        <?php endif; ?>
                        <h3 class="hero-side-title"><?php the_title(); ?></h3>
                    </div>
                </div>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>

    <!-- DON'T MISS Section with Dynamic Tabs -->
    <div class="dont-miss-section">
        <div class="dont-miss-container">
            <div class="category-header">
                <div class="dont-miss-label">DON'T MISS</div>
                <div class="category-tabs">
                    <!-- Tabs are built dynamically by JavaScript based on available pixel width -->
                </div>
            </div>

            <!-- All Category Content -->
            <div class="content-wrapper active" data-content="all">
                <?php
                $all_featured = new WP_Query(array('posts_per_page' => 1));
                if ($all_featured->have_posts()) : $all_featured->the_post();
                    $categories = get_the_category();
                ?>
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', array('class' => 'featured-image', 'alt' => get_the_title())); ?>
                        <?php endif; ?>
                        <?php if (!empty($categories)) : ?>
                            <span class="category-badge"><?php echo esc_html($categories[0]->name); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title"><?php the_title(); ?></h2>
                        <div class="featured-meta">
                            <span class="author"><?php the_author(); ?></span>
                            <span>•</span>
                            <span><?php echo get_the_date('F j, Y'); ?></span>
                        </div>
                        <p class="featured-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                        <span class="comment-count">💬 <?php comments_number('0 Comments', '1 Comment', '% Comments'); ?></span>
                    </div>
                    <div class="nav-arrows">
                        <button class="nav-arrow">‹</button>
                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <?php
                endif;
                wp_reset_postdata();
                ?>
                <div class="related-articles">
                    <?php
                    $related = new WP_Query(array('posts_per_page' => 4, 'offset' => 1));
                    while ($related->have_posts()) : $related->the_post();
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

            <!-- Health & Fitness Content -->
            <div class="content-wrapper" data-content="health">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Health & Fitness</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Increase your Endurance Through the Pilates Method</h2>
                        <div class="featured-meta">
                            <span class="author">Armin Vans</span>
                            <span>•</span>
                            <span>December 7, 2025</span>
                        </div>
                        <p class="featured-excerpt">Discover how Pilates can transform your fitness routine and build lasting endurance through mindful movement and core strengthening techniques...</p>
                        <span class="comment-count">💬 18 Comments</span>
                    </div>
                    <div class="nav-arrows">
                        <button class="nav-arrow">‹</button>
                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Ultimate Exercises to Improve Back Muscles</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Motivational Songs for Successful Workout</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Yoga Poses for Stress Relief</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Marathon Training: Complete Guide</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lifestyle Content -->
            <div class="content-wrapper" data-content="lifestyle">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Lifestyle</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Minimalist Living: Finding Joy in Less</h2>
                        <div class="featured-meta">
                            <span class="author">Emma Wilson</span>
                            <span>•</span>
                            <span>December 8, 2025</span>
                        </div>
                        <p class="featured-excerpt">The minimalist movement continues to grow as more people discover the freedom and fulfillment that comes from simplifying their lives...</p>
                        <span class="comment-count">💬 45 Comments</span>
                    </div>
                    <div class="nav-arrows">
                        <button class="nav-arrow">‹</button>
                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Home Design Trends for the New Year</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Morning Routines of Successful People</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Sustainable Fashion: Brands Making a Difference</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Work-Life Balance in the Digital Age</h3>
                            <p class="article-date">December 4, 2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Science Content -->
            <div class="content-wrapper" data-content="science">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Science</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Mars Mission Update: New Discoveries Change Our Understanding</h2>
                        <div class="featured-meta">
                            <span class="author">Dr. James Miller</span>
                            <span>•</span>
                            <span>December 8, 2025</span>
                        </div>
                        <p class="featured-excerpt">NASA's latest rover has uncovered evidence that could revolutionize our understanding of the Red Planet's geological history...</p>
                        <span class="comment-count">💬 78 Comments</span>
                    </div>
                    <div class="nav-arrows">
                        <button class="nav-arrow">‹</button>
                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Breakthrough in Cancer Research Announced</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>New Exoplanet Discovery Excites Astronomers</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Ocean Conservation: New Species Found</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Climate Research: New Models Released</h3>
                            <p class="article-date">December 4, 2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Food & Recipes Content -->
            <div class="content-wrapper" data-content="food">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Food & Recipes</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Plant-Based Revolution: Delicious Recipes That Changed My Life</h2>
                        <div class="featured-meta">
                            <span class="author">Chef Maria Santos</span>
                            <span>•</span>
                            <span>December 8, 2025</span>
                        </div>
                        <p class="featured-excerpt">Discover how embracing plant-based cooking can transform not just your diet but your entire relationship with food and health...</p>
                        <span class="comment-count">💬 53 Comments</span>
                    </div>
                    <div class="nav-arrows">
                        <button class="nav-arrow">‹</button>
                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Homemade Pizza: Master the Perfect Crust</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Summer Salads: Fresh and Healthy Ideas</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Decadent Desserts: Guilt-Free Indulgence</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Breakfast Ideas to Start Your Day Right</h3>
                            <p class="article-date">December 4, 2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Content -->
            <div class="content-wrapper" data-content="business">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Business</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Top CRM Tools for Small Businesses in 2024</h2>
                        <div class="featured-meta">
                            <span class="author">Sarah Johnson</span>
                            <span>•</span>
                            <span>December 8, 2025</span>
                        </div>
                        <p class="featured-excerpt">Discover the best customer relationship management tools that can transform your small business operations and boost productivity...</p>
                        <span class="comment-count">💬 42 Comments</span>
                    </div>
                    <div class="nav-arrows">
                        <button class="nav-arrow">‹</button>
                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Project Management Software Comparison</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Marketing Automation Tools Review</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Best VPS Hosting Providers 2024</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Email Marketing Platforms Compared</h3>
                            <p class="article-date">December 4, 2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tech Content -->
            <div class="content-wrapper" data-content="tech">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Technology</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">AI Revolution: How Machine Learning is Transforming Industries</h2>
                        <div class="featured-meta">
                            <span class="author">Dr. Michael Chen</span>
                            <span>•</span>
                            <span>December 8, 2025</span>
                        </div>
                        <p class="featured-excerpt">Explore the latest developments in artificial intelligence and machine learning that are reshaping business, healthcare, and daily life...</p>
                        <span class="comment-count">💬 67 Comments</span>
                    </div>
                    <div class="nav-arrows">
                        <button class="nav-arrow">‹</button>
                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>5G Networks: What You Need to Know</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Cloud Computing Trends for 2024</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Cybersecurity Best Practices</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Quantum Computing Breakthroughs</h3>
                            <p class="article-date">December 4, 2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sports Content -->
            <div class="content-wrapper" data-content="sports">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Sports</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Championship Final: Underdog Team's Journey to Victory</h2>
                        <div class="featured-meta">
                            <span class="author">Tom Martinez</span>
                            <span>•</span>
                            <span>December 8, 2025</span>
                        </div>
                        <p class="featured-excerpt">Follow the incredible story of how a small-market team defied all odds to capture their first championship title in franchise history...</p>
                        <span class="comment-count">💬 89 Comments</span>
                    </div>
                    <div class="nav-arrows">
                        <button class="nav-arrow">‹</button>
                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Olympic Athletes Training Secrets</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Top 10 Sports Moments of the Year</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Fitness Tips from Professional Athletes</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Stadium Technology Innovations</h3>
                            <p class="article-date">December 4, 2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Entertainment Content -->
            <div class="content-wrapper" data-content="entertainment">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Entertainment</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Award Season Preview: Top Films and Performances to Watch</h2>
                        <div class="featured-meta">
                            <span class="author">Emma Williams</span>
                            <span>•</span>
                            <span>December 8, 2025</span>
                        </div>
                        <p class="featured-excerpt">Get ready for the biggest night in entertainment with our comprehensive guide to this year's award season contenders and favorites...</p>
                        <span class="comment-count">💬 56 Comments</span>
                    </div>
                    <div class="nav-arrows">
                        <button class="nav-arrow">‹</button>
                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Streaming Services: What's Worth Watching</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Music Festival Season Guide</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Celebrity Interviews and Exclusives</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Behind the Scenes: Movie Production</h3>
                            <p class="article-date">December 4, 2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Travel Content -->
            <div class="content-wrapper" data-content="travel">
                <div class="featured-article">
                    <div class="featured-image-wrapper">
                        <span class="category-badge">Travel</span>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">Hidden Gems: 10 Underrated Destinations for 2024</h2>
                        <div class="featured-meta">
                            <span class="author">Jessica Parker</span>
                            <span>•</span>
                            <span>December 8, 2025</span>
                        </div>
                        <p class="featured-excerpt">Discover breathtaking locations off the beaten path that offer authentic experiences without the tourist crowds...</p>
                        <span class="comment-count">💬 78 Comments</span>
                    </div>
                    <div class="nav-arrows">
                        <button class="nav-arrow">‹</button>
                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <div class="related-articles">
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Budget Travel Tips and Tricks</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Sustainable Tourism Practices</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Solo Travel Safety Guide</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-info">
                            <h3>Best Travel Photography Spots</h3>
                            <p class="article-date">December 4, 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trending Section -->
    <section class="trending-section">
        <div class="trending-container">
            <div class="section-header">
                <h2 class="section-title">Trending Now</h2>
                <a href="#" class="view-all">View All →</a>
            </div>
            <div class="trending-scroll">
                <?php
                $trending = new WP_Query(array('posts_per_page' => 5, 'meta_key' => 'post_views_count', 'orderby' => 'meta_value_num'));
                if (!$trending->have_posts()) {
                    $trending = new WP_Query(array('posts_per_page' => 5));
                }

                $trending_num = 1;
                while ($trending->have_posts()) : $trending->the_post();
                    $categories = get_the_category();
                ?>
                <div class="trending-card">
                    <span class="trending-number"><?php echo str_pad($trending_num, 2, '0', STR_PAD_LEFT); ?></span>
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
                    <?php endif; ?>
                    <div class="trending-info">
                        <?php if (!empty($categories)) : ?>
                            <span class="trending-category"><?php echo esc_html($categories[0]->name); ?></span>
                        <?php endif; ?>
                        <h3 class="trending-title"><?php the_title(); ?></h3>
                    </div>
                </div>
                <?php
                    $trending_num++;
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>

    <!-- Category Grid Section -->
    <section class="category-grid-section">
        <div class="category-grid">
            <?php
            $category_slugs = array('world-news', 'technology', 'sports');
            $category_names = array('World News', 'Technology', 'Sports');

            for ($i = 0; $i < 3; $i++) :
                $cat = get_category_by_slug($category_slugs[$i]);
                if (!$cat) {
                    $categories = get_categories(array('number' => 3));
                    $cat = isset($categories[$i]) ? $categories[$i] : null;
                }

                if ($cat) :
                    $cat_posts = new WP_Query(array(
                        'cat' => $cat->term_id,
                        'posts_per_page' => 4,
                    ));
            ?>
            <div class="category-column">
                <div class="category-column-header">
                    <h3 class="category-column-title"><?php echo esc_html($cat->name); ?></h3>
                    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">See All</a>
                </div>
                <?php
                if ($cat_posts->have_posts()) :
                    $cat_posts->the_post();
                ?>
                <div class="category-featured">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium_large', array('alt' => get_the_title())); ?>
                    <?php endif; ?>
                    <div class="category-featured-content">
                        <h4 class="category-featured-title"><?php the_title(); ?></h4>
                        <p class="article-date"><?php echo get_the_date('F j, Y'); ?></p>
                    </div>
                </div>
                <div class="category-list">
                    <?php
                    $list_num = 1;
                    while ($cat_posts->have_posts() && $list_num <= 3) : $cat_posts->the_post();
                    ?>
                    <div class="category-list-item">
                        <span class="category-list-number"><?php echo $list_num; ?></span>
                        <h4 class="category-list-title"><?php the_title(); ?></h4>
                    </div>
                    <?php
                        $list_num++;
                    endwhile;
                    ?>
                </div>
                <?php
                endif;
                wp_reset_postdata();
                ?>
            </div>
            <?php
                endif;
            endfor;
            ?>
        </div>
    </section>

    <!-- Video Section -->
    <section class="video-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Videos</h2>
                <a href="#" class="view-all">View All →</a>
            </div>
            <div class="video-grid">
                <?php
                $video_posts = new WP_Query(array(
                    'posts_per_page' => 5,
                    'meta_key' => 'is_video',
                    'meta_value' => '1',
                ));

                if (!$video_posts->have_posts()) {
                    $video_posts = new WP_Query(array('posts_per_page' => 5));
                }

                if ($video_posts->have_posts()) :
                    $video_posts->the_post();
                ?>
                <div class="video-main">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array('alt' => get_the_title())); ?>
                    <?php endif; ?>
                    <div class="video-play-btn"></div>
                    <div class="video-overlay">
                        <span class="video-duration">12:45</span>
                        <h3 class="video-title"><?php the_title(); ?></h3>
                    </div>
                </div>
                <div class="video-list">
                    <?php
                    while ($video_posts->have_posts()) : $video_posts->the_post();
                    ?>
                    <div class="video-item">
                        <div class="video-item-thumb">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('thumbnail', array('alt' => get_the_title())); ?>
                            <?php endif; ?>
                            <span class="video-item-play"></span>
                        </div>
                        <div class="video-item-info">
                            <span class="video-item-duration">8:30</span>
                            <h4 class="video-item-title"><?php the_title(); ?></h4>
                        </div>
                    </div>
                    <?php
                    endwhile;
                    ?>
                </div>
                <?php
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>

    <!-- Opinion Section -->
    <section class="opinion-section">
        <div class="section-header">
            <h2 class="section-title">Expert Opinions</h2>
            <a href="#" class="view-all">View All →</a>
        </div>
        <div class="opinion-grid">
            <div class="opinion-card">
                <p class="opinion-quote">The future of sustainable energy lies not in one solution, but in a diverse portfolio of renewable technologies working together.</p>
                <p class="opinion-author">Dr. Robert Chen</p>
                <p class="opinion-role">Energy Policy Expert</p>
            </div>
            <div class="opinion-card">
                <p class="opinion-quote">Digital transformation is no longer optional. Companies that fail to adapt will find themselves left behind in the new economy.</p>
                <p class="opinion-author">Sarah Mitchell</p>
                <p class="opinion-role">Tech Industry Analyst</p>
            </div>
            <div class="opinion-card">
                <p class="opinion-quote">Global cooperation has never been more critical. The challenges we face today require solutions that transcend national borders.</p>
                <p class="opinion-author">Michael Okonjo</p>
                <p class="opinion-role">International Relations Scholar</p>
            </div>
            <div class="opinion-card">
                <p class="opinion-quote">Healthcare innovation must prioritize accessibility. Breakthroughs mean nothing if they don't reach the people who need them most.</p>
                <p class="opinion-author">Dr. Emily Watson</p>
                <p class="opinion-role">Healthcare Advocate</p>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="newsletter-container">
            <h2 class="newsletter-title">Stay Informed</h2>
            <p class="newsletter-text">Subscribe to our newsletter and never miss the stories that matter. Get daily updates delivered straight to your inbox.</p>
            <form class="newsletter-form" method="post" action="">
                <?php wp_nonce_field('profitbenefit_newsletter_subscribe', 'newsletter_nonce'); ?>
                <input type="email" class="newsletter-input" name="newsletter_email" placeholder="Enter your email address" required>
                <button type="submit" name="newsletter_submit" class="newsletter-btn">Subscribe</button>
            </form>
        </div>
    </section>

<?php
get_footer();
