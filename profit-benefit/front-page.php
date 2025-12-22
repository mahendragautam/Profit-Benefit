<?php
/**
 * Front Page Template (Homepage)
 * Converted from: home-page-design.html
 *
 * @package ProfitBenefit_Theme
 */

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
                    <?php else : ?>
                        <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1200&h=600&fit=crop" alt="<?php the_title_attribute(); ?>">
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
                    <?php else : ?>
                        <img src="https://images.unsplash.com/photo-1526628953301-3e589a6a8b74?w=400&h=300&fit=crop" alt="<?php the_title_attribute(); ?>">
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
                        <?php else : ?>
                            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&h=500&fit=crop" alt="<?php the_title_attribute(); ?>" class="featured-image">
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
                        <p class="featured-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
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
                        <?php else : ?>
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=200&h=150&fit=crop" alt="<?php the_title_attribute(); ?>" class="article-thumbnail">
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
                        <img src="https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&h=500&fit=crop" alt="Fitness" class="featured-image">
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
                        <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=200&h=150&fit=crop" alt="Exercise" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Ultimate Exercises to Improve Back Muscles</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=200&h=150&fit=crop" alt="Gym" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Motivational Songs for Successful Workout</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=200&h=150&fit=crop" alt="Yoga" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Yoga Poses for Stress Relief</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1599058917212-d750089bc07e?w=200&h=150&fit=crop" alt="Running" class="article-thumbnail">
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
                        <img src="https://images.unsplash.com/photo-1513151233558-d860c5398176?w=800&h=500&fit=crop" alt="Lifestyle" class="featured-image">
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
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=150&fit=crop" alt="Home" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Home Design Trends for the New Year</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?w=200&h=150&fit=crop" alt="Wellness" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Morning Routines of Successful People</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1512428559087-560fa5ceab42?w=200&h=150&fit=crop" alt="Fashion" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Sustainable Fashion: Brands Making a Difference</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=200&h=150&fit=crop" alt="Work" class="article-thumbnail">
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
                        <img src="https://images.unsplash.com/photo-1507413245164-6160d8298b31?w=800&h=500&fit=crop" alt="Science" class="featured-image">
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
                        <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=200&h=150&fit=crop" alt="Lab" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Breakthrough in Cancer Research Announced</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=200&h=150&fit=crop" alt="Space" class="article-thumbnail">
                        <div class="article-info">
                            <h3>New Exoplanet Discovery Excites Astronomers</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1518152006812-edab29b069ac?w=200&h=150&fit=crop" alt="Ocean" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Ocean Conservation: New Species Found</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1530973428-5bf2db2e4d71?w=200&h=150&fit=crop" alt="Climate" class="article-thumbnail">
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
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&h=500&fit=crop" alt="Food" class="featured-image">
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
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=200&h=150&fit=crop" alt="Pizza" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Homemade Pizza: Master the Perfect Crust</h3>
                            <p class="article-date">December 7, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=200&h=150&fit=crop" alt="Salad" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Summer Salads: Fresh and Healthy Ideas</h3>
                            <p class="article-date">December 6, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1551024601-bec78aea704b?w=200&h=150&fit=crop" alt="Dessert" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Decadent Desserts: Guilt-Free Indulgence</h3>
                            <p class="article-date">December 5, 2025</p>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?w=200&h=150&fit=crop" alt="Breakfast" class="article-thumbnail">
                        <div class="article-info">
                            <h3>Breakfast Ideas to Start Your Day Right</h3>
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
                    <?php else : ?>
                        <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=400&h=300&fit=crop" alt="<?php the_title_attribute(); ?>">
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
                    <?php else : ?>
                        <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&h=400&fit=crop" alt="<?php the_title_attribute(); ?>">
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
                    <?php else : ?>
                        <img src="https://images.unsplash.com/photo-1485846234645-a62644f84728?w=900&h=600&fit=crop" alt="<?php the_title_attribute(); ?>">
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
                            <?php else : ?>
                                <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=300&h=200&fit=crop" alt="<?php the_title_attribute(); ?>">
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
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop" alt="Author" class="opinion-avatar">
                <p class="opinion-quote">The future of sustainable energy lies not in one solution, but in a diverse portfolio of renewable technologies working together.</p>
                <p class="opinion-author">Dr. Robert Chen</p>
                <p class="opinion-role">Energy Policy Expert</p>
            </div>
            <div class="opinion-card">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop" alt="Author" class="opinion-avatar">
                <p class="opinion-quote">Digital transformation is no longer optional. Companies that fail to adapt will find themselves left behind in the new economy.</p>
                <p class="opinion-author">Sarah Mitchell</p>
                <p class="opinion-role">Tech Industry Analyst</p>
            </div>
            <div class="opinion-card">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Author" class="opinion-avatar">
                <p class="opinion-quote">Global cooperation has never been more critical. The challenges we face today require solutions that transcend national borders.</p>
                <p class="opinion-author">Michael Okonjo</p>
                <p class="opinion-role">International Relations Scholar</p>
            </div>
            <div class="opinion-card">
                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" alt="Author" class="opinion-avatar">
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
            <form class="newsletter-form">
                <input type="email" class="newsletter-input" placeholder="Enter your email address">
                <button type="submit" class="newsletter-btn">Subscribe</button>
            </form>
        </div>
    </section>

<?php
get_footer();
