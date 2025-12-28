# Classic WordPress Theme Development - Complete Guide

---
name: classic
hidden: true
protected: true
undeletable: true

> **Load this file when**: Keywords detected - template, theme structure, functions.php, template hierarchy, child theme, classic theme
>
> **Purpose**: Comprehensive patterns for classic (PHP template) WordPress themes
>
> **Version**: 2.0 | **Last Updated**: December 2025

---

## 📋 TABLE OF CONTENTS

1. [Theme Structure & Required Files](#1-theme-structure--required-files)
2. [style.css - Theme Header](#2-stylecss---theme-header)
3. [functions.php - Complete Setup](#3-functionsphp---complete-setup)
4. [Template Hierarchy & Files](#4-template-hierarchy--files)
5. [Template Parts](#5-template-parts)
6. [Custom Template Tags](#6-custom-template-tags)
7. [Customizer API (Advanced)](#7-customizer-api-advanced)
8. [Navigation Menus (Advanced)](#8-navigation-menus-advanced)
9. [Widget Areas & Custom Widgets](#9-widget-areas--custom-widgets)
10. [Custom Page Templates](#10-custom-page-templates)
11. [AJAX in Themes](#11-ajax-in-themes)
12. [Custom Post Queries](#12-custom-post-queries)
13. [Post Meta & Custom Fields](#13-post-meta--custom-fields)
14. [Pagination & Load More](#14-pagination--load-more)
15. [Child Theme Development](#15-child-theme-development)
16. [Production Checklist](#16-production-checklist)

---

## 1. THEME STRUCTURE & REQUIRED FILES

### Minimum Required Files:
```
/wp-content/themes/themename/
├── style.css              # Theme header (REQUIRED)
├── index.php             # Main template fallback (REQUIRED)
├── functions.php         # Theme setup
├── screenshot.png        # Theme preview (1200x900px)
└── readme.txt           # Theme documentation
```

### Recommended Complete Structure:
```
/wp-content/themes/themename/
├── style.css
├── functions.php
├── index.php
├── screenshot.png
├── readme.txt
│
├── header.php
├── footer.php
├── sidebar.php
├── searchform.php
├── comments.php
│
├── single.php           # Single post
├── page.php             # Static page
├── archive.php          # Archive pages
├── category.php         # Category archive
├── tag.php              # Tag archive
├── author.php           # Author archive
├── date.php             # Date archive
├── search.php           # Search results
├── 404.php              # Not found
├── attachment.php       # Media attachment
│
├── template-parts/      # Reusable template parts
│   ├── content.php
│   ├── content-single.php
│   ├── content-page.php
│   ├── content-search.php
│   ├── content-none.php
│   └── navigation.php
│
├── inc/                 # Include files
│   ├── template-tags.php
│   ├── template-functions.php
│   ├── customizer.php
│   └── class-walker-nav-menu.php
│
├── page-templates/      # Custom page templates
│   ├── full-width.php
│   └── landing-page.php
│
├── assets/              # Theme assets
│   ├── css/
│   ├── js/
│   ├── images/
│   └── fonts/
│
└── languages/           # Translation files
    └── themename.pot
```

---

## 2. STYLE.CSS - THEME HEADER

### Complete Theme Header (WordPress.org Compliant):

```css
/*!
Theme Name:   My Awesome Theme
Theme URI:    https://example.com/my-awesome-theme/
Author:       Your Name
Author URI:   https://example.com/
Description:  A beautiful, accessible, and fast WordPress theme designed for bloggers and businesses. Features include responsive design, customizable colors, multiple layout options, and full WooCommerce support.
Version:      1.0.0
Tested up to: 6.9
Requires at least: 6.0
Requires PHP: 8.0
License:      GNU General Public License v2 or later
License URI:  http://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  themename
Tags:         blog, e-commerce, responsive-layout, accessibility-ready, custom-colors, custom-menu, featured-images, footer-widgets, full-width-template, theme-options, threaded-comments, translation-ready
Domain Path:  /languages/

This theme, like WordPress, is licensed under the GPL.
Use it to make something cool, have fun, and share what you've learned.
*/
```

---

## 3. FUNCTIONS.PHP - COMPLETE SETUP

```php
<?php
/**
 * Theme Functions and Definitions
 *
 * @package ThemeName
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('THEMENAME_VERSION', '1.0.0');

/**
 * Theme setup
 */
function themename_setup() {
    load_theme_textdomain('themename', get_template_directory() . '/languages');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    add_image_size('themename-featured', 1200, 600, true);
    add_image_size('themename-thumbnail', 400, 300, true);
    
    register_nav_menus([
        'primary' => esc_html__('Primary Menu', 'themename'),
        'footer'  => esc_html__('Footer Menu', 'themename'),
    ]);
    
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    
    add_theme_support('customize-selective-refresh-widgets');
    
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    
    add_theme_support('custom-background', [
        'default-color' => 'ffffff',
    ]);
    
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
    
    $GLOBALS['content_width'] = 1200;
}
add_action('after_setup_theme', 'themename_setup');

/**
 * Register widget areas
 */
function themename_widgets_init() {
    register_sidebar([
        'name'          => esc_html__('Primary Sidebar', 'themename'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'themename'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
    
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar([
            'name'          => sprintf(esc_html__('Footer Column %d', 'themename'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(esc_html__('Footer column %d widgets.', 'themename'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
    }
}
add_action('widgets_init', 'themename_widgets_init');

/**
 * Enqueue scripts and styles
 */
function themename_scripts() {
    wp_enqueue_style(
        'themename-style',
        get_stylesheet_uri(),
        [],
        THEMENAME_VERSION
    );
    
    wp_enqueue_style(
        'themename-main',
        get_template_directory_uri() . '/assets/css/main.min.css',
        [],
        THEMENAME_VERSION
    );
    
    wp_enqueue_script(
        'themename-navigation',
        get_template_directory_uri() . '/assets/js/navigation.min.js',
        [],
        THEMENAME_VERSION,
        true
    );
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    wp_localize_script('themename-navigation', 'themeNameData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('themename-ajax-nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'themename_scripts');

/**
 * Load include files
 */
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';

if (class_exists('WooCommerce')) {
    require get_template_directory() . '/inc/woocommerce.php';
}
```

---

## 4. TEMPLATE HIERARCHY & FILES

### Template Hierarchy Flow:

```
Single Post:
single-{post-type}-{slug}.php
→ single-{post-type}.php
→ single.php
→ singular.php
→ index.php

Page:
{custom-template}.php
→ page-{slug}.php
→ page-{id}.php
→ page.php
→ singular.php
→ index.php

Archive:
archive-{post-type}.php
→ archive.php
→ index.php

Category:
category-{slug}.php
→ category-{id}.php
→ category.php
→ archive.php
→ index.php
```

### header.php (Production-Ready):

```php
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e('Skip to content', 'themename'); ?>
    </a>

    <header id="masthead" class="site-header">
        <div class="site-branding">
            <?php
            if (has_custom_logo()) {
                the_custom_logo();
            } else {
                ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                </h1>
                <?php
                $description = get_bloginfo('description', 'display');
                if ($description || is_customize_preview()) {
                    ?>
                    <p class="site-description"><?php echo esc_html($description); ?></p>
                    <?php
                }
            }
            ?>
        </div>

        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'themename'); ?>">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="screen-reader-text"><?php esc_html_e('Menu', 'themename'); ?></span>
                <span class="menu-icon" aria-hidden="true"></span>
            </button>
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'fallback_cb'    => false,
            ]);
            ?>
        </nav>
    </header>
```

### footer.php (Production-Ready):

```php
    <footer id="colophon" class="site-footer">
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
            <div class="footer-widgets">
                <div class="footer-widget-area">
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <?php if (is_active_sidebar('footer-' . $i)) : ?>
                            <div class="footer-column">
                                <?php dynamic_sidebar('footer-' . $i); ?>
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="site-info">
            <div class="site-info-text">
                <?php
                printf(
                    esc_html__('© %1$s %2$s. All rights reserved.', 'themename'),
                    date_i18n('Y'),
                    get_bloginfo('name')
                );
                ?>
            </div>
            <?php
            if (has_nav_menu('footer')) {
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'menu_class'     => 'footer-menu',
                    'container'      => 'nav',
                    'container_class'=> 'footer-navigation',
                    'depth'          => 1,
                ]);
            }
            ?>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
```

### index.php (Main Loop):

```php
<?php get_header(); ?>

<main id="primary" class="site-main">
    <?php
    if (have_posts()) :
        
        if (is_home() && !is_front_page()) :
            ?>
            <header>
                <h1 class="page-title screen-reader-text">
                    <?php single_post_title(); ?>
                </h1>
            </header>
            <?php
        endif;
        
        while (have_posts()) :
            the_post();
            get_template_part('template-parts/content', get_post_type());
        endwhile;
        
        the_posts_navigation([
            'prev_text' => esc_html__('Older posts', 'themename'),
            'next_text' => esc_html__('Newer posts', 'themename'),
        ]);
        
    else :
        get_template_part('template-parts/content', 'none');
    endif;
    ?>
</main>

<?php
get_sidebar();
get_footer();
```

### single.php (Single Post):

```php
<?php get_header(); ?>

<main id="primary" class="site-main">
    <?php
    while (have_posts()) :
        the_post();
        get_template_part('template-parts/content', 'single');
        
        the_post_navigation([
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'themename') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'themename') . '</span> <span class="nav-title">%title</span>',
        ]);
        
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        
    endwhile;
    ?>
</main>

<?php
get_sidebar();
get_footer();
```

### page.php (Static Page):

```php
<?php get_header(); ?>

<main id="primary" class="site-main">
    <?php
    while (have_posts()) :
        the_post();
        get_template_part('template-parts/content', 'page');
        
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        
    endwhile;
    ?>
</main>

<?php
get_sidebar();
get_footer();
```

### archive.php (Archive Pages):

```php
<?php get_header(); ?>

<main id="primary" class="site-main">
    <?php if (have_posts()) : ?>
        
        <header class="page-header">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>
        
        <div class="posts-grid">
            <?php
            while (have_posts()) :
                the_post();
                get_template_part('template-parts/content', get_post_format());
            endwhile;
            ?>
        </div>
        
        <?php
        the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => esc_html__('« Previous', 'themename'),
            'next_text' => esc_html__('Next »', 'themename'),
        ]);
        ?>
        
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php
get_sidebar();
get_footer();
```

### search.php (Search Results):

```php
<?php get_header(); ?>

<main id="primary" class="site-main">
    <?php if (have_posts()) : ?>
        
        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf(
                    /* translators: %s: search query */
                    esc_html__('Search Results for: %s', 'themename'),
                    '<span>' . esc_html(get_search_query()) . '</span>'
                );
                ?>
            </h1>
        </header>
        
        <?php
        while (have_posts()) :
            the_post();
            get_template_part('template-parts/content', 'search');
        endwhile;
        
        the_posts_pagination();
        ?>
        
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php
get_sidebar();
get_footer();
```

### 404.php (Not Found):

```php
<?php get_header(); ?>

<main id="primary" class="site-main">
    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Oops! That page can't be found.', 'themename'); ?></h1>
        </header>

        <div class="page-content">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'themename'); ?></p>
            <?php get_search_form(); ?>
            
            <?php
            // Show recent posts
            $recent_posts = new WP_Query([
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'post_status'    => 'publish',
            ]);
            
            if ($recent_posts->have_posts()) :
                ?>
                <h2><?php esc_html_e('Recent Posts', 'themename'); ?></h2>
                <ul class="recent-posts">
                    <?php
                    while ($recent_posts->have_posts()) :
                        $recent_posts->the_post();
                        ?>
                        <li>
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php echo esc_html(get_the_title()); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <?php
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

---

## 5. TEMPLATE PARTS

### template-parts/content.php (Archive/Blog):

```php
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;
        
        if ('post' === get_post_type()) :
            ?>
            <div class="entry-meta">
                <?php
                themename_posted_on();
                themename_posted_by();
                ?>
            </div>
            <?php
        endif;
        ?>
    </header>

    <?php themename_post_thumbnail(); ?>

    <div class="entry-content">
        <?php
        the_excerpt();
        ?>
    </div>

    <footer class="entry-footer">
        <?php themename_entry_footer(); ?>
    </footer>
</article>
```

### template-parts/content-single.php:

```php
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        
        <?php if ('post' === get_post_type()) : ?>
            <div class="entry-meta">
                <?php
                themename_posted_on();
                themename_posted_by();
                ?>
            </div>
        <?php endif; ?>
    </header>

    <?php themename_post_thumbnail(); ?>

    <div class="entry-content">
        <?php
        the_content(sprintf(
            wp_kses(
                __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'themename'),
                ['span' => ['class' => []]]
            ),
            esc_html(get_the_title())
        ));
        
        wp_link_pages([
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'themename'),
            'after'  => '</div>',
        ]);
        ?>
    </div>

    <footer class="entry-footer">
        <?php themename_entry_footer(); ?>
    </footer>
</article>
```

### template-parts/content-none.php:

```php
<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Nothing Found', 'themename'); ?></h1>
    </header>

    <div class="page-content">
        <?php
        if (is_home() && current_user_can('publish_posts')) :
            ?>
            <p>
                <?php
                printf(
                    wp_kses(
                        __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'themename'),
                        ['a' => ['href' => []]]
                    ),
                    esc_url(admin_url('post-new.php'))
                );
                ?>
            </p>
            <?php
        elseif (is_search()) :
            ?>
            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'themename'); ?></p>
            <?php
            get_search_form();
        else :
            ?>
            <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'themename'); ?></p>
            <?php
            get_search_form();
        endif;
        ?>
    </div>
</section>
```

---

## 6. CUSTOM TEMPLATE TAGS

### inc/template-tags.php:

```php
<?php
/**
 * Custom template tags
 *
 * @package ThemeName
 */

if (!function_exists('themename_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time
     */
    function themename_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }
        
        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );
        
        $posted_on = sprintf(
            /* translators: %s: post date */
            esc_html_x('Posted on %s', 'post date', 'themename'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );
        
        echo '<span class="posted-on">' . $posted_on . '</span>';
    }
endif;

if (!function_exists('themename_posted_by')) :
    /**
     * Prints HTML with meta information for the current author
     */
    function themename_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author */
            esc_html_x('by %s', 'post author', 'themename'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );
        
        echo '<span class="byline"> ' . $byline . '</span>';
    }
endif;

if (!function_exists('themename_post_thumbnail')) :
    /**
     * Displays post thumbnail
     */
    function themename_post_thumbnail() {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }
        
        if (is_singular()) :
            ?>
            <div class="post-thumbnail">
                <?php
                the_post_thumbnail('themename-featured', [
                    'alt' => the_title_attribute(['echo' => false]),
                    'loading' => 'eager',
                ]);
                ?>
            </div>
            <?php
        else :
            ?>
            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail('themename-thumbnail', [
                    'alt' => the_title_attribute(['echo' => false]),
                    'loading' => 'lazy',
                ]);
                ?>
            </a>
            <?php
        endif;
    }
endif;

if (!function_exists('themename_entry_footer')) :
    /**
     * Prints categories and tags
     */
    function themename_entry_footer() {
        if ('post' === get_post_type()) {
            $categories_list = get_the_category_list(esc_html__(', ', 'themename'));
            if ($categories_list) {
                printf(
                    '<span class="cat-links">' . esc_html__('Posted in %1$s', 'themename') . '</span>',
                    $categories_list
                );
            }
            
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'themename'));
            if ($tags_list) {
                printf(
                    '<span class="tags-links">' . esc_html__('Tagged %1$s', 'themename') . '</span>',
                    $tags_list
                );
            }
        }
        
        edit_post_link(
            sprintf(
                wp_kses(
                    __('Edit <span class="screen-reader-text">%s</span>', 'themename'),
                    ['span' => ['class' => []]]
                ),
                esc_html(get_the_title())
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;
```

---

## 7. CUSTOMIZER API (ADVANCED)

### inc/customizer.php (Complete):

```php
<?php
/**
 * Theme Customizer
 *
 * @package ThemeName
 */

function themename_customize_register($wp_customize) {
    
    // Add custom panel
    $wp_customize->add_panel('themename_options', [
        'title'       => esc_html__('Theme Options', 'themename'),
        'description' => esc_html__('Customize your theme settings.', 'themename'),
        'priority'    => 30,
    ]);
    
    // Layout section
    $wp_customize->add_section('themename_layout', [
        'title'    => esc_html__('Layout Options', 'themename'),
        'panel'    => 'themename_options',
        'priority' => 10,
    ]);
    
    // Sidebar position
    $wp_customize->add_setting('sidebar_position', [
        'default'           => 'right',
        'sanitize_callback' => 'themename_sanitize_sidebar_position',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('sidebar_position', [
        'label'    => esc_html__('Sidebar Position', 'themename'),
        'section'  => 'themename_layout',
        'type'     => 'radio',
        'choices'  => [
            'left'  => esc_html__('Left', 'themename'),
            'right' => esc_html__('Right', 'themename'),
            'none'  => esc_html__('No Sidebar', 'themename'),
        ],
    ]);
    
    // Colors section
    $wp_customize->add_section('themename_colors', [
        'title'    => esc_html__('Color Options', 'themename'),
        'panel'    => 'themename_options',
        'priority' => 20,
    ]);
    
    // Primary color
    $wp_customize->add_setting('primary_color', [
        'default'           => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', [
        'label'    => esc_html__('Primary Color', 'themename'),
        'section'  => 'themename_colors',
        'settings' => 'primary_color',
    ]));
    
    // Selective refresh
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('primary_color', [
            'selector'        => ':root',
            'render_callback' => '__return_false',
        ]);
    }
}
add_action('customize_register', 'themename_customize_register');

/**
 * Sanitization callbacks
 */
function themename_sanitize_sidebar_position($input) {
    $valid = ['left', 'right', 'none'];
    return in_array($input, $valid, true) ? $input : 'right';
}

/**
 * Binds JS handlers for Customizer
 */
function themename_customize_preview_js() {
    wp_enqueue_script(
        'themename-customizer',
        get_template_directory_uri() . '/assets/js/customizer.js',
        ['customize-preview'],
        THEMENAME_VERSION,
        true
    );
}
add_action('customize_preview_init', 'themename_customize_preview_js');

/**
 * Output custom CSS
 */
function themename_customizer_css() {
    $primary_color = get_theme_mod('primary_color', '#0073aa');
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr($primary_color); ?>;
        }
        a, .site-title a:hover {
            color: <?php echo esc_attr($primary_color); ?>;
        }
        .button, button[type="submit"] {
            background-color: <?php echo esc_attr($primary_color); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'themename_customizer_css');
```

---

## 8. NAVIGATION MENUS (ADVANCED)

### Custom Walker for Navigation:

```php
// inc/class-walker-nav-menu.php
<?php
class ThemeName_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        if ($args->walker->has_children) {
            $classes[] = 'menu-item-has-children';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . '>';
        
        $atts = [
            'title'  => !empty($item->attr_title) ? $item->attr_title : '',
            'target' => !empty($item->target) ? $item->target : '',
            'rel'    => !empty($item->xfn) ? $item->xfn : '',
            'href'   => !empty($item->url) ? $item->url : '',
        ];
        
        if ($item->current) {
            $atts['aria-current'] = 'page';
        }
        
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
        
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . esc_html($title) . $args->link_after;
        
        if ($args->walker->has_children && 0 === $depth) {
            $item_output .= '<button class="submenu-toggle" aria-expanded="false"><span class="screen-reader-text">' . esc_html__('Expand child menu', 'themename') . '</span></button>';
        }
        
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
```

---

## 9. WIDGET AREAS & CUSTOM WIDGETS

### Custom Widget Example:

```php
// inc/class-recent-posts-widget.php
<?php
class ThemeName_Recent_Posts_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'themename_recent_posts',
            esc_html__('Theme Recent Posts', 'themename'),
            ['description' => esc_html__('Display recent posts with thumbnails.', 'themename')]
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $title = apply_filters('widget_title', $title);
        
        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }
        
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        
        $recent_posts = new WP_Query([
            'posts_per_page'      => $number,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'no_found_rows'       => true,
        ]);
        
        if ($recent_posts->have_posts()) :
            ?>
            <ul class="recent-posts-widget">
                <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                    <li>
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php echo esc_url(get_permalink()); ?>" class="post-thumbnail">
                                <?php the_post_thumbnail('thumbnail', ['alt' => esc_attr(get_the_title())]); ?>
                            </a>
                        <?php endif; ?>
                        
                        <div class="post-details">
                            <a href="<?php echo esc_url(get_permalink()); ?>" class="post-title">
                                <?php echo esc_html(get_the_title()); ?>
                            </a>
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="post-date">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php
            wp_reset_postdata();
        endif;
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'themename'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>">
                <?php esc_html_e('Number of posts:', 'themename'); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = !empty($new_instance['number']) ? absint($new_instance['number']) : 5;
        return $instance;
    }
}

function themename_register_widgets() {
    register_widget('ThemeName_Recent_Posts_Widget');
}
add_action('widgets_init', 'themename_register_widgets');
```

---

## 10. CUSTOM PAGE TEMPLATES

### page-templates/full-width.php:

```php
<?php
/**
 * Template Name: Full Width
 * Template Post Type: page
 *
 * @package ThemeName
 */

get_header();
?>

<main id="primary" class="site-main full-width">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header>
            
            <?php themename_post_thumbnail(); ?>
            
            <div class="entry-content">
                <?php
                the_content();
                
                wp_link_pages([
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'themename'),
                    'after'  => '</div>',
                ]);
                ?>
            </div>
        </article>
        <?php
        
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        
    endwhile;
    ?>
</main>

<?php get_footer(); ?>
```

---

## 11. AJAX IN THEMES

### Load More Posts (AJAX):

```php
// functions.php
function themename_load_more_posts() {
    check_ajax_referer('themename-ajax-nonce', 'nonce');
    
    $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? absint($_POST['posts_per_page']) : 6;
    
    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
    ];
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        ob_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', get_post_format());
        }
        
        $html = ob_get_clean();
        
        wp_send_json_success([
            'html'      => $html,
            'has_more'  => $page < $query->max_num_pages,
            'next_page' => $page + 1,
        ]);
    } else {
        wp_send_json_error(['message' => esc_html__('No more posts found.', 'themename')]);
    }
    
    wp_die();
}
add_action('wp_ajax_themename_load_more', 'themename_load_more_posts');
add_action('wp_ajax_nopriv_themename_load_more', 'themename_load_more_posts');
```

### JavaScript for Load More:

```javascript
// assets/js/load-more.js
(function($) {
    'use strict';
    
    let currentPage = 1;
    const postsContainer = $('.posts-grid');
    const loadMoreBtn = $('.load-more-btn');
    
    loadMoreBtn.on('click', function(e) {
        e.preventDefault();
        
        const button = $(this);
        button.prop('disabled', true).text('Loading...');
        
        $.ajax({
            url: themeNameData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'themename_load_more',
                nonce: themeNameData.nonce,
                page: currentPage + 1,
                posts_per_page: 6
            },
            success: function(response) {
                if (response.success) {
                    postsContainer.append(response.data.html);
                    currentPage = response.data.next_page;
                    
                    if (!response.data.has_more) {
                        button.remove();
                    } else {
                        button.prop('disabled', false).text('Load More');
                    }
                }
            },
            error: function() {
                alert('Error loading posts. Please try again.');
                button.prop('disabled', false).text('Load More');
            }
        });
    });
})(jQuery);
```

---

## 12. CUSTOM POST QUERIES

### Featured Posts Query:

```php
<?php
$featured_args = [
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'meta_query'     => [
        [
            'key'   => '_is_featured',
            'value' => '1',
        ],
    ],
    'orderby'        => 'date',
    'order'          => 'DESC',
];

$featured_query = new WP_Query($featured_args);

if ($featured_query->have_posts()) :
    ?>
    <section class="featured-posts">
        <h2><?php esc_html_e('Featured Posts', 'themename'); ?></h2>
        <div class="posts-grid">
            <?php
            while ($featured_query->have_posts()) :
                $featured_query->the_post();
                get_template_part('template-parts/content', 'featured');
            endwhile;
            ?>
        </div>
    </section>
    <?php
    wp_reset_postdata();
endif;
?>
```

### Related Posts Query:

```php
<?php
function themename_get_related_posts($post_id, $number = 3) {
    $categories = wp_get_post_categories($post_id);
    
    if (empty($categories)) {
        return false;
    }
    
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $number,
        'post__not_in'   => [$post_id],
        'category__in'   => $categories,
        'orderby'        => 'rand',
    ];
    
    return new WP_Query($args);
}

// Usage in single.php
$related = themename_get_related_posts(get_the_ID(), 3);

if ($related && $related->have_posts()) :
    ?>
    <section class="related-posts">
        <h2><?php esc_html_e('Related Posts', 'themename'); ?></h2>
        <div class="posts-grid">
            <?php
            while ($related->have_posts()) :
                $related->the_post();
                get_template_part('template-parts/content', 'related');
            endwhile;
            ?>
        </div>
    </section>
    <?php
    wp_reset_postdata();
endif;
?>
```

---

## 13. POST META & CUSTOM FIELDS

### Save Custom Meta Box:

```php
function themename_save_featured_meta($post_id) {
    if (!isset($_POST['themename_featured_nonce']) || !wp_verify_nonce($_POST['themename_featured_nonce'], 'themename_save_featured')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $is_featured = isset($_POST['themename_is_featured']) ? '1' : '0';
    update_post_meta($post_id, '_is_featured', $is_featured);
}
add_action('save_post', 'themename_save_featured_meta');

function themename_featured_meta_box() {
    add_meta_box(
        'themename_featured',
        esc_html__('Featured Post', 'themename'),
        'themename_featured_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'themename_featured_meta_box');

function themename_featured_meta_box_callback($post) {
    wp_nonce_field('themename_save_featured', 'themename_featured_nonce');
    $is_featured = get_post_meta($post->ID, '_is_featured', true);
    ?>
    <label>
        <input type="checkbox" name="themename_is_featured" value="1" <?php checked($is_featured, '1'); ?>>
        <?php esc_html_e('Mark as featured post', 'themename'); ?>
    </label>
    <?php
}
```

---

## 14. PAGINATION & LOAD MORE

### Numeric Pagination:

```php
function themename_numeric_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max = intval($wp_query->max_num_pages);
    
    echo '<nav class="pagination" role="navigation">';
    
    if ($paged > 1) {
        echo '<a class="prev page-numbers" href="' . esc_url(get_pagenum_link($paged - 1)) . '">' . esc_html__('« Previous', 'themename') . '</a>';
    }
    
    for ($i = 1; $i <= $max; $i++) {
        if ($i == $paged) {
            echo '<span class="page-numbers current">' . $i . '</span>';
        } else {
            echo '<a class="page-numbers" href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a>';
        }
    }
    
    if ($paged < $max) {
        echo '<a class="next page-numbers" href="' . esc_url(get_pagenum_link($paged + 1)) . '">' . esc_html__('Next »', 'themename') . '</a>';
    }
    
    echo '</nav>';
}
```

---

## 15. CHILD THEME DEVELOPMENT

### Child Theme Structure:

```
/wp-content/themes/themename-child/
├── style.css              # Child theme stylesheet (REQUIRED)
├── functions.php          # Child theme functions (REQUIRED)
├── screenshot.png         # Child theme screenshot
└── readme.txt            # Child theme documentation
```

### Child Theme style.css:

```css
/*
Theme Name:   Theme Name Child
Theme URI:    https://example.com/themename-child/
Description:  Child theme for Theme Name
Author:       Your Name
Author URI:   https://example.com/
Template:     themename
Version:      1.0.0
License:      GNU General Public License v2 or later
License URI:  http://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  themename-child
*/

/* Add your custom styles below */
```

### Child Theme functions.php:

```php
<?php
/**
 * Child Theme Functions
 *
 * @package ThemeName_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue parent and child theme styles
 */
function themename_child_enqueue_styles() {
    // Parent theme stylesheet
    wp_enqueue_style(
        'themename-parent-style',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme()->parent()->get('Version')
    );
    
    // Child theme stylesheet
    wp_enqueue_style(
        'themename-child-style',
        get_stylesheet_uri(),
        ['themename-parent-style'],
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'themename_child_enqueue_styles');

/**
 * Override parent theme functions
 */
function themename_child_custom_function() {
    // Your custom code here
}
```

---

## 16. PRODUCTION CHECKLIST

### Before Submitting to WordPress.org:

- [ ] **Theme Check plugin** passes with zero errors
- [ ] **PHPCS** (WordPress Coding Standards) passes
- [ ] All output properly escaped (`esc_html()`, `esc_attr()`, `esc_url()`)
- [ ] All input sanitized (`sanitize_text_field()`, `wp_kses_post()`)
- [ ] Translation-ready (textdomain correct, strings translatable)
- [ ] GPL-licensed (GPLv2 or later)
- [ ] No hardcoded URLs (`get_template_directory_uri()` used)
- [ ] No plugin functionality in theme
- [ ] Accessibility standards met (WCAG 2.1 AA)
- [ ] `readme.txt` included
- [ ] `screenshot.png` (1200x900px) included
- [ ] Child theme compatible
- [ ] Mobile responsive (all viewports)
- [ ] Cross-browser tested (Chrome, Firefox, Safari, Edge)
- [ ] No JavaScript errors in console
- [ ] No PHP errors/warnings
- [ ] Performance optimized (Lighthouse score > 90)
- [ ] Assets minified (CSS/JS)
- [ ] Images optimized
- [ ] Theme Unit Test data displays correctly

### WordPress.org Required Files:

```
/themename/
├── style.css         # With proper theme header
├── index.php         # Main template
├── functions.php     # Theme setup
├── readme.txt        # Theme documentation
├── screenshot.png    # 1200x900px
└── languages/
    └── themename.pot # Translation template
```

---

## ✅ FINAL NOTES

### Best Practices:
1. **Always escape output** in templates
2. **Always sanitize input** from forms/AJAX
3. **Use template hierarchy** correctly
4. **Prefix all functions** with theme slug
5. **Make themes translation-ready**
6. **Support child themes**
7. **Follow accessibility standards**
8. **Optimize for performance**
9. **Test on WordPress 6.0+**
10. **Keep themes focused on presentation** (no plugin functionality)

### Common Mistakes to Avoid:
- ❌ Hardcoding content in templates
- ❌ Including plugin functionality
- ❌ Not escaping output
- ❌ Not sanitizing input
- ❌ Breaking child theme compatibility
- ❌ Using `eval()` or `create_function()`
- ❌ Direct database queries without `$wpdb->prepare()`
- ❌ Not following template hierarchy
- ❌ Skipping accessibility features
- ❌ Not testing on mobile devices

---

**This guide covers ALL essential classic theme development patterns for production-ready WordPress themes!** 🚀