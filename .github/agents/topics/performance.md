# Performance - Complete Theme Optimization Guide

---
name: performance
hidden: true
protected: true
undeletable: true

> **Load this file when**: Keywords detected - optimize, performance, slow theme, Core Web Vitals, lazy load, minify, cache
>
> **Purpose**: Theme performance optimization patterns and frontend performance

---

## ⚡ CORE WEB VITALS OPTIMIZATION

### Largest Contentful Paint (LCP)

**Target: LCP < 2.5s**

```php
/**
 * Optimize hero image loading
 */
function themename_optimize_hero_image() {
    ?>
    <div class="hero">
        <?php if (has_post_thumbnail()) : ?>
            <?php
            // Use fetchpriority for hero images
            the_post_thumbnail('large', [
                'fetchpriority' => 'high',
                'loading' => 'eager', // Don't lazy load hero
                'alt' => get_the_title()
            ]);
            ?>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Preload critical images
 */
function themename_preload_critical_images() {
    if (is_front_page() && has_post_thumbnail()) {
        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        if ($image_url) {
            echo '<link rel="preload" as="image" href="' . esc_url($image_url) . '">';
        }
    }
}
add_action('wp_head', 'themename_preload_critical_images', 1);
```

### Cumulative Layout Shift (CLS)

**Target: CLS < 0.1**

```php
/**
 * Add image dimensions to prevent layout shift
 */
function themename_add_image_dimensions($html, $post_id, $post_thumbnail_id) {
    $image_meta = wp_get_attachment_metadata($post_thumbnail_id);
    
    if (!empty($image_meta['width']) && !empty($image_meta['height'])) {
        $html = str_replace(
            '<img',
            sprintf(
                '<img width="%d" height="%d"',
                $image_meta['width'],
                $image_meta['height']
            ),
            $html
        );
    }
    
    return $html;
}
add_filter('post_thumbnail_html', 'themename_add_image_dimensions', 10, 3);
```

### First Input Delay (FID)

**Target: FID < 100ms**

```php
/**
 * Defer non-critical JavaScript
 */
function themename_defer_scripts($tag, $handle, $src) {
    // Don't defer jQuery or critical scripts
    $critical_scripts = ['jquery-core', 'themename-critical'];
    
    if (in_array($handle, $critical_scripts)) {
        return $tag;
    }
    
    // Add defer attribute to other scripts
    return str_replace(' src', ' defer src', $tag);
}
add_filter('script_loader_tag', 'themename_defer_scripts', 10, 3);
```

---

## 🎨 ASSET OPTIMIZATION

### Enqueue Minified Assets

```php
/**
 * Enqueue optimized theme assets
 */
function themename_enqueue_assets() {
    $version = wp_get_theme()->get('Version');
    $is_dev = defined('WP_DEBUG') && WP_DEBUG;
    
    // Use minified files in production
    $suffix = $is_dev ? '' : '.min';
    
    // Main stylesheet
    wp_enqueue_style(
        'themename-style',
        get_template_directory_uri() . "/assets/css/style{$suffix}.css",
        [],
        $version
    );
    
    // Main JavaScript
    wp_enqueue_script(
        'themename-main',
        get_template_directory_uri() . "/assets/js/main{$suffix}.js",
        [],
        $version,
        true // Load in footer
    );
}
add_action('wp_enqueue_scripts', 'themename_enqueue_assets');
```

### Conditional Asset Loading

```php
/**
 * Load scripts only when needed
 */
function themename_conditional_assets() {
    // Load carousel only on front page
    if (is_front_page()) {
        wp_enqueue_script(
            'themename-carousel',
            get_template_directory_uri() . '/assets/js/carousel.min.js',
            [],
            '1.0.0',
            true
        );
    }
    
    // Load comment reply script only when needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Load gallery scripts only on pages with galleries
    if (is_singular() && has_shortcode(get_post()->post_content, 'gallery')) {
        wp_enqueue_script(
            'themename-gallery',
            get_template_directory_uri() . '/assets/js/gallery.min.js',
            [],
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'themename_conditional_assets');
```

### Inline Critical CSS

```php
/**
 * Inline critical above-the-fold CSS
 */
function themename_inline_critical_css() {
    $critical_css_file = get_template_directory() . '/assets/css/critical.css';
    
    if (file_exists($critical_css_file)) {
        $critical_css = file_get_contents($critical_css_file);
        echo '<style id="critical-css">' . $critical_css . '</style>';
    }
}
add_action('wp_head', 'themename_inline_critical_css', 1);

/**
 * Load full CSS asynchronously
 */
function themename_async_css($html, $handle) {
    if ($handle === 'themename-style') {
        $html = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
        $html .= '<noscript><link rel="stylesheet" href="' . esc_url(get_template_directory_uri() . '/assets/css/style.min.css') . '"></noscript>';
    }
    return $html;
}
add_filter('style_loader_tag', 'themename_async_css', 10, 2);
```

---

## 🖼️ IMAGE OPTIMIZATION

### Lazy Loading

```php
/**
 * Enable native lazy loading for images
 */
function themename_lazy_load_images($content) {
    // Skip if it's the first image (might be hero)
    static $first_image = true;
    
    if ($first_image && is_singular()) {
        $first_image = false;
        return $content;
    }
    
    // Add loading="lazy" to images
    $content = preg_replace(
        '/<img((?!loading)[^>]*)>/i',
        '<img$1 loading="lazy">',
        $content
    );
    
    return $content;
}
add_filter('the_content', 'themename_lazy_load_images');
add_filter('post_thumbnail_html', 'themename_lazy_load_images');
add_filter('widget_text', 'themename_lazy_load_images');
```

### Responsive Images

```php
/**
 * Add custom image sizes
 */
function themename_image_sizes() {
    // Thumbnail sizes
    add_image_size('themename-thumbnail', 350, 233, true);
    add_image_size('themename-medium', 750, 500, true);
    add_image_size('themename-large', 1200, 800, true);
    
    // Hero/banner sizes
    add_image_size('themename-hero', 1920, 800, true);
    add_image_size('themename-hero-mobile', 768, 500, true);
}
add_action('after_setup_theme', 'themename_image_sizes');

/**
 * Output responsive image with art direction
 */
function themename_responsive_hero() {
    if (!has_post_thumbnail()) {
        return;
    }
    
    $desktop_url = get_the_post_thumbnail_url(null, 'themename-hero');
    $mobile_url = get_the_post_thumbnail_url(null, 'themename-hero-mobile');
    $alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
    ?>
    <picture>
        <source media="(max-width: 768px)" srcset="<?php echo esc_url($mobile_url); ?>">
        <source media="(min-width: 769px)" srcset="<?php echo esc_url($desktop_url); ?>">
        <img 
            src="<?php echo esc_url($desktop_url); ?>" 
            alt="<?php echo esc_attr($alt); ?>"
            fetchpriority="high"
            loading="eager"
            width="1920"
            height="800"
        >
    </picture>
    <?php
}
```

### WebP Support

```php
/**
 * Enable WebP support
 */
function themename_enable_webp_upload($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'themename_enable_webp_upload');

/**
 * Display WebP with fallback
 */
function themename_webp_image($attachment_id, $size = 'large') {
    $image_url = wp_get_attachment_image_url($attachment_id, $size);
    $webp_url = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $image_url);
    $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
    ?>
    <picture>
        <source type="image/webp" srcset="<?php echo esc_url($webp_url); ?>">
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy">
    </picture>
    <?php
}
```

---

## 📦 TEMPLATE FRAGMENT CACHING

### Cache Expensive Template Parts

```php
/**
 * Cache sidebar widgets
 */
function themename_cached_sidebar() {
    if (!is_active_sidebar('sidebar-1')) {
        return;
    }
    
    // Cache key includes user login status for dynamic content
    $cache_key = 'themename_sidebar_' . (is_user_logged_in() ? 'logged_in' : 'guest');
    
    $output = get_transient($cache_key);
    
    if ($output === false) {
        ob_start();
        dynamic_sidebar('sidebar-1');
        $output = ob_get_clean();
        
        // Cache for 1 hour
        set_transient($cache_key, $output, HOUR_IN_SECONDS);
    }
    
    echo $output;
}

/**
 * Clear sidebar cache when widget updated
 */
function themename_clear_sidebar_cache() {
    delete_transient('themename_sidebar_logged_in');
    delete_transient('themename_sidebar_guest');
}
add_action('update_option_sidebars_widgets', 'themename_clear_sidebar_cache');
```

### Cache Navigation Menus

```php
/**
 * Cache navigation menu output
 */
function themename_cached_nav_menu($args) {
    $cache_key = 'themename_nav_' . md5(serialize($args));
    
    $output = get_transient($cache_key);
    
    if ($output === false) {
        ob_start();
        wp_nav_menu($args);
        $output = ob_get_clean();
        
        // Cache for 12 hours
        set_transient($cache_key, $output, 12 * HOUR_IN_SECONDS);
    }
    
    echo $output;
}

/**
 * Clear menu cache on update
 */
function themename_clear_menu_cache($menu_id) {
    // Clear all menu transients
    global $wpdb;
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_themename_nav_%' 
         OR option_name LIKE '_transient_timeout_themename_nav_%'"
    );
}
add_action('wp_update_nav_menu', 'themename_clear_menu_cache');
```

---

## 🔧 WORDPRESS QUERY OPTIMIZATION

### Optimize Main Query

```php
/**
 * Optimize archive queries
 */
function themename_optimize_archive_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Limit posts per page on archives
        if ($query->is_archive() || $query->is_search()) {
            $query->set('posts_per_page', 12);
        }
        
        // Exclude certain post types from search
        if ($query->is_search()) {
            $query->set('post_type', ['post', 'page']);
        }
    }
}
add_action('pre_get_posts', 'themename_optimize_archive_query');
```

### Disable Unused Features

```php
/**
 * Disable features for better performance
 */
function themename_disable_unused_features() {
    // Disable emojis if not needed
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    
    // Disable embeds if not needed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    
    // Remove WordPress version
    remove_action('wp_head', 'wp_generator');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove RSS feed links if not using feeds
    // remove_action('wp_head', 'feed_links', 2);
    // remove_action('wp_head', 'feed_links_extra', 3);
}
add_action('init', 'themename_disable_unused_features');
```

### Limit Post Revisions

```php
/**
 * Limit post revisions (in wp-config.php or theme)
 * Add to functions.php for theme-specific setting
 */
if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 3);
}
```

---

## 🚀 FONT OPTIMIZATION

### Preload Web Fonts

```php
/**
 * Preload critical web fonts
 */
function themename_preload_fonts() {
    ?>
    <link rel="preload" href="<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/inter-var.woff2'); ?>" as="font" type="font/woff2" crossorigin>
    <?php
}
add_action('wp_head', 'themename_preload_fonts', 1);
```

### Optimize Google Fonts

```php
/**
 * Optimize Google Fonts loading
 */
function themename_google_fonts() {
    ?>
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Load fonts with display=swap -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <?php
}
add_action('wp_head', 'themename_google_fonts', 1);
```

### Self-Host Fonts (Better Performance)

```css
/* assets/css/fonts.css */
@font-face {
    font-family: 'Inter';
    font-style: normal;
    font-weight: 400;
    font-display: swap;
    src: url('../fonts/inter-regular.woff2') format('woff2');
}

@font-face {
    font-family: 'Inter';
    font-style: normal;
    font-weight: 700;
    font-display: swap;
    src: url('../fonts/inter-bold.woff2') format('woff2');
}
```

---

## 📊 PERFORMANCE MONITORING

### Track Core Web Vitals

```javascript
// assets/js/performance.js
(function() {
    'use strict';
    
    // Track Largest Contentful Paint (LCP)
    if ('PerformanceObserver' in window) {
        const lcpObserver = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            const lastEntry = entries[entries.length - 1];
            
            console.log('LCP:', lastEntry.renderTime || lastEntry.loadTime);
            
            // Send to analytics if needed
            if (window.gtag) {
                gtag('event', 'LCP', {
                    value: lastEntry.renderTime || lastEntry.loadTime,
                    metric_id: 'LCP'
                });
            }
        });
        
        lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });
        
        // Track First Input Delay (FID)
        const fidObserver = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            entries.forEach((entry) => {
                console.log('FID:', entry.processingStart - entry.startTime);
                
                if (window.gtag) {
                    gtag('event', 'FID', {
                        value: entry.processingStart - entry.startTime,
                        metric_id: 'FID'
                    });
                }
            });
        });
        
        fidObserver.observe({ entryTypes: ['first-input'] });
        
        // Track Cumulative Layout Shift (CLS)
        let clsScore = 0;
        const clsObserver = new PerformanceObserver((list) => {
            for (const entry of list.getEntries()) {
                if (!entry.hadRecentInput) {
                    clsScore += entry.value;
                }
            }
            console.log('CLS:', clsScore);
        });
        
        clsObserver.observe({ entryTypes: ['layout-shift'] });
    }
})();
```

### Admin Performance Dashboard

```php
/**
 * Add performance info to admin bar
 */
function themename_admin_bar_performance($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $page_load_time = timer_stop(0, 3);
    $queries = get_num_queries();
    
    $wp_admin_bar->add_node([
        'id'    => 'theme-performance',
        'title' => sprintf(
            '⚡ %ss | %d queries',
            $page_load_time,
            $queries
        ),
        'href'  => admin_url('admin.php?page=themename-performance')
    ]);
}
add_action('admin_bar_menu', 'themename_admin_bar_performance', 999);
```

---

## ✅ THEME PERFORMANCE CHECKLIST

### Assets
- [ ] CSS minified and combined
- [ ] JavaScript minified and deferred
- [ ] Critical CSS inlined
- [ ] Non-critical CSS loaded async
- [ ] Fonts preloaded or self-hosted
- [ ] No unused CSS/JS enqueued

### Images
- [ ] Lazy loading enabled (except hero)
- [ ] Responsive images configured
- [ ] WebP format used where possible
- [ ] Image dimensions specified
- [ ] Hero images use fetchpriority="high"

### Core Web Vitals
- [ ] LCP < 2.5s
- [ ] FID < 100ms
- [ ] CLS < 0.1
- [ ] Hero images not lazy loaded
- [ ] Layout shifts prevented

### Caching
- [ ] Template fragments cached
- [ ] Navigation menus cached
- [ ] Sidebar widgets cached
- [ ] Cache cleared on updates

### WordPress Optimizations
- [ ] Unused features disabled
- [ ] Post revisions limited
- [ ] Queries optimized
- [ ] Conditional asset loading

### Monitoring
- [ ] Performance metrics tracked
- [ ] Admin bar shows page stats
- [ ] Core Web Vitals monitored
- [ ] Slow pages identified

### Target Metrics
- **PageSpeed score**: 90+ (mobile & desktop)
- **LCP**: < 2.5s
- **FID**: < 100ms
- **CLS**: < 0.1
- **Time to Interactive**: < 3.5s
- **Page load**: < 3s

---

**Optimize for exceptional user experience!** ⚡