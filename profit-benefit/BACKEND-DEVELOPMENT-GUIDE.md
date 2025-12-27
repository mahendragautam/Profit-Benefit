# WordPress Theme Backend Development Guide

**Complete Step-by-Step Documentation for Creating Backend Features**

**Theme:** ProfitBenefit Tools
**Version:** 1.0.0
**Last Updated:** December 27, 2025
**For:** WordPress 6.0+, PHP 7.4+

---

## Table of Contents

1. [Introduction](#introduction)
2. [Backend Architecture Overview](#backend-architecture-overview)
3. [Meta Boxes (Custom Fields)](#meta-boxes-custom-fields)
4. [WordPress Customizer](#wordpress-customizer)
5. [Admin Settings Pages](#admin-settings-pages)
6. [Custom Post Types](#custom-post-types)
7. [Custom Taxonomies](#custom-taxonomies)
8. [AJAX Handlers](#ajax-handlers)
9. [Database Operations](#database-operations)
10. [REST API Integration](#rest-api-integration)
11. [Admin Notices & Alerts](#admin-notices--alerts)
12. [Security Best Practices](#security-best-practices)
13. [Performance Optimization](#performance-optimization)
14. [Testing & Debugging](#testing--debugging)

---

## Introduction

This guide provides comprehensive, step-by-step instructions for creating backend features in WordPress themes. Each section includes:

- **Concept explanation**
- **Step-by-step implementation**
- **Complete code examples**
- **Security best practices**
- **Common pitfalls to avoid**

### Prerequisites

- WordPress 6.0 or higher installed
- PHP 7.4 or higher
- Basic understanding of PHP and WordPress
- Text editor or IDE
- Local development environment (Local, XAMPP, or similar)

---

## Backend Architecture Overview

### WordPress Backend Components

```
WordPress Backend Structure
│
├── Admin Dashboard
│   ├── Meta Boxes (Post/Page custom fields)
│   ├── Settings Pages (Custom admin pages)
│   ├── Customizer (Live preview settings)
│   └── Admin Notices (Alerts/Messages)
│
├── Data Layer
│   ├── Post Meta (Custom post data)
│   ├── Options API (Settings storage)
│   ├── Transients API (Cached data)
│   └── Custom Tables (Advanced data)
│
├── API Layer
│   ├── REST API (External access)
│   ├── AJAX Handlers (Async operations)
│   └── Hooks/Filters (WordPress integration)
│
└── Security Layer
    ├── Nonces (CSRF protection)
    ├── Capability Checks (Permission control)
    ├── Data Sanitization (Input cleaning)
    └── Data Escaping (Output safety)
```

### File Organization Best Practices

```
your-theme/
│
├── functions.php              # Main theme setup
├── inc/                       # Backend components
│   ├── meta-boxes.php         # Meta box definitions
│   ├── customizer.php         # Customizer settings
│   ├── admin-settings.php     # Settings pages
│   ├── post-types.php         # Custom post types
│   ├── taxonomies.php         # Custom taxonomies
│   ├── ajax-handlers.php      # AJAX callbacks
│   └── rest-api.php           # REST API endpoints
│
├── admin/                     # Admin-specific assets
│   ├── css/
│   │   └── admin-styles.css
│   ├── js/
│   │   └── admin-scripts.js
│   └── templates/
│       └── settings-page.php
│
└── assets/                    # Frontend assets
    ├── css/
    ├── js/
    └── images/
```

---

## Meta Boxes (Custom Fields)

Meta boxes add custom fields to post/page edit screens. They allow editors to input additional data.

### Step 1: Understanding Meta Boxes

**What are Meta Boxes?**
- Admin UI panels on post/page edit screens
- Store custom data (post meta) in the database
- Examples: SEO fields, custom layouts, featured options

**When to Use:**
- Adding custom fields to posts/pages
- Creating advanced content options
- Building page builders
- Adding editor preferences

### Step 2: Creating Your First Meta Box

**Location:** `functions.php` or `inc/meta-boxes.php`

```php
<?php
/**
 * Register Meta Boxes
 *
 * @package ProfitBenefit
 * @since 1.0.0
 */

// Hook into the admin initialization
add_action('add_meta_boxes', 'profitbenefit_register_meta_boxes');

/**
 * Register all meta boxes
 */
function profitbenefit_register_meta_boxes() {

    // Register Quick Summary meta box
    add_meta_box(
        'profitbenefit_quick_summary',           // Unique ID
        __('Quick Summary', 'profitbenefit-theme'), // Title
        'profitbenefit_quick_summary_callback',  // Callback function
        'post',                                   // Post type (post, page, custom)
        'side',                                   // Context (normal, side, advanced)
        'high'                                    // Priority (high, low, default)
    );

    // Register Featured Post meta box
    add_meta_box(
        'profitbenefit_featured_post',
        __('Featured Post Options', 'profitbenefit-theme'),
        'profitbenefit_featured_post_callback',
        array('post', 'page'),                    // Multiple post types
        'normal',
        'default'
    );
}
```

### Step 3: Creating the Meta Box Display

**The callback function renders the HTML:**

```php
<?php
/**
 * Quick Summary meta box HTML
 *
 * @param WP_Post $post Current post object
 */
function profitbenefit_quick_summary_callback($post) {

    // Step 3.1: Add nonce for security
    wp_nonce_field(
        'profitbenefit_quick_summary_nonce_action',  // Action
        'profitbenefit_quick_summary_nonce'          // Name
    );

    // Step 3.2: Get existing values (if any)
    $saved_data = get_post_meta($post->ID, '_profitbenefit_quick_summary', true);

    // Step 3.3: Set default values
    $defaults = array(
        'point_1' => '',
        'point_2' => '',
        'point_3' => '',
        'point_4' => ''
    );

    // Merge saved data with defaults
    $data = wp_parse_args($saved_data, $defaults);

    // Step 3.4: Output HTML form
    ?>
    <div class="profitbenefit-meta-box">
        <p>
            <label for="quick_summary_point_1">
                <?php esc_html_e('Key Point 1:', 'profitbenefit-theme'); ?>
            </label>
            <input
                type="text"
                id="quick_summary_point_1"
                name="quick_summary_point_1"
                value="<?php echo esc_attr($data['point_1']); ?>"
                class="widefat"
            >
        </p>

        <p>
            <label for="quick_summary_point_2">
                <?php esc_html_e('Key Point 2:', 'profitbenefit-theme'); ?>
            </label>
            <input
                type="text"
                id="quick_summary_point_2"
                name="quick_summary_point_2"
                value="<?php echo esc_attr($data['point_2']); ?>"
                class="widefat"
            >
        </p>

        <p>
            <label for="quick_summary_point_3">
                <?php esc_html_e('Key Point 3:', 'profitbenefit-theme'); ?>
            </label>
            <input
                type="text"
                id="quick_summary_point_3"
                name="quick_summary_point_3"
                value="<?php echo esc_attr($data['point_3']); ?>"
                class="widefat"
            >
        </p>

        <p>
            <label for="quick_summary_point_4">
                <?php esc_html_e('Key Point 4:', 'profitbenefit-theme'); ?>
            </label>
            <input
                type="text"
                id="quick_summary_point_4"
                name="quick_summary_point_4"
                value="<?php echo esc_attr($data['point_4']); ?>"
                class="widefat"
            >
        </p>

        <p class="description">
            <?php esc_html_e('Enter up to 4 key points for the Quick Summary box.', 'profitbenefit-theme'); ?>
        </p>
    </div>

    <style>
        .profitbenefit-meta-box label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .profitbenefit-meta-box input[type="text"] {
            margin-bottom: 10px;
        }
    </style>
    <?php
}
```

### Step 4: Saving Meta Box Data

**Hook into post save action:**

```php
<?php
/**
 * Save meta box data when post is saved
 */
add_action('save_post', 'profitbenefit_save_quick_summary_meta');

function profitbenefit_save_quick_summary_meta($post_id) {

    // Step 4.1: Security checks

    // Check if nonce is set
    if (!isset($_POST['profitbenefit_quick_summary_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce(
        $_POST['profitbenefit_quick_summary_nonce'],
        'profitbenefit_quick_summary_nonce_action'
    )) {
        return;
    }

    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Step 4.2: Sanitize and save data

    $summary_data = array(
        'point_1' => isset($_POST['quick_summary_point_1'])
            ? sanitize_text_field($_POST['quick_summary_point_1'])
            : '',
        'point_2' => isset($_POST['quick_summary_point_2'])
            ? sanitize_text_field($_POST['quick_summary_point_2'])
            : '',
        'point_3' => isset($_POST['quick_summary_point_3'])
            ? sanitize_text_field($_POST['quick_summary_point_3'])
            : '',
        'point_4' => isset($_POST['quick_summary_point_4'])
            ? sanitize_text_field($_POST['quick_summary_point_4'])
            : ''
    );

    // Step 4.3: Update post meta
    update_post_meta(
        $post_id,
        '_profitbenefit_quick_summary',  // Meta key (prefix with _ to hide)
        $summary_data                     // Meta value
    );
}
```

### Step 5: Retrieving Meta Box Data in Templates

**In your theme template files (single.php, content.php, etc.):**

```php
<?php
/**
 * Display Quick Summary in template
 */
function profitbenefit_display_quick_summary() {

    // Get the data
    $summary = get_post_meta(get_the_ID(), '_profitbenefit_quick_summary', true);

    // Check if data exists
    if (empty($summary)) {
        return;
    }

    // Filter out empty points
    $points = array_filter($summary);

    // If no points, return
    if (empty($points)) {
        return;
    }

    // Display the summary box
    ?>
    <div class="quick-summary-box">
        <h3><?php esc_html_e('Quick Summary', 'profitbenefit-theme'); ?></h3>
        <ul>
            <?php foreach ($points as $point) : ?>
                <?php if (!empty($point)) : ?>
                    <li><?php echo esc_html($point); ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

// Usage in single.php:
// profitbenefit_display_quick_summary();
```

### Step 6: Advanced Meta Box Examples

#### Checkbox Meta Box

```php
<?php
/**
 * Featured Post checkbox meta box
 */
function profitbenefit_featured_post_callback($post) {

    wp_nonce_field('profitbenefit_featured_nonce_action', 'profitbenefit_featured_nonce');

    $is_featured = get_post_meta($post->ID, '_profitbenefit_is_featured', true);
    ?>
    <p>
        <label>
            <input
                type="checkbox"
                name="profitbenefit_is_featured"
                value="1"
                <?php checked($is_featured, '1'); ?>
            >
            <?php esc_html_e('Mark this post as featured', 'profitbenefit-theme'); ?>
        </label>
    </p>
    <?php
}

/**
 * Save featured post checkbox
 */
function profitbenefit_save_featured_meta($post_id) {

    // Security checks (same as before)
    if (!isset($_POST['profitbenefit_featured_nonce'])) return;
    if (!wp_verify_nonce($_POST['profitbenefit_featured_nonce'], 'profitbenefit_featured_nonce_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Save checkbox value
    $is_featured = isset($_POST['profitbenefit_is_featured']) ? '1' : '0';
    update_post_meta($post_id, '_profitbenefit_is_featured', $is_featured);
}
add_action('save_post', 'profitbenefit_save_featured_meta');
```

#### Select Dropdown Meta Box

```php
<?php
/**
 * Post Layout select meta box
 */
function profitbenefit_post_layout_callback($post) {

    wp_nonce_field('profitbenefit_layout_nonce_action', 'profitbenefit_layout_nonce');

    $current_layout = get_post_meta($post->ID, '_profitbenefit_post_layout', true);

    $layouts = array(
        'default'   => __('Default', 'profitbenefit-theme'),
        'wide'      => __('Wide Layout', 'profitbenefit-theme'),
        'full'      => __('Full Width', 'profitbenefit-theme'),
        'sidebar'   => __('With Sidebar', 'profitbenefit-theme'),
    );
    ?>
    <p>
        <label for="profitbenefit_post_layout">
            <?php esc_html_e('Select Post Layout:', 'profitbenefit-theme'); ?>
        </label>
        <select name="profitbenefit_post_layout" id="profitbenefit_post_layout" class="widefat">
            <?php foreach ($layouts as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($current_layout, $value); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php
}

/**
 * Save layout select
 */
function profitbenefit_save_layout_meta($post_id) {

    // Security checks
    if (!isset($_POST['profitbenefit_layout_nonce'])) return;
    if (!wp_verify_nonce($_POST['profitbenefit_layout_nonce'], 'profitbenefit_layout_nonce_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Sanitize and save
    $layout = isset($_POST['profitbenefit_post_layout'])
        ? sanitize_text_field($_POST['profitbenefit_post_layout'])
        : 'default';

    update_post_meta($post_id, '_profitbenefit_post_layout', $layout);
}
add_action('save_post', 'profitbenefit_save_layout_meta');
```

#### Textarea Meta Box

```php
<?php
/**
 * Custom excerpt meta box
 */
function profitbenefit_custom_excerpt_callback($post) {

    wp_nonce_field('profitbenefit_excerpt_nonce_action', 'profitbenefit_excerpt_nonce');

    $custom_excerpt = get_post_meta($post->ID, '_profitbenefit_custom_excerpt', true);
    ?>
    <p>
        <label for="profitbenefit_custom_excerpt">
            <?php esc_html_e('Custom Excerpt (optional):', 'profitbenefit-theme'); ?>
        </label>
        <textarea
            name="profitbenefit_custom_excerpt"
            id="profitbenefit_custom_excerpt"
            rows="4"
            class="widefat"
        ><?php echo esc_textarea($custom_excerpt); ?></textarea>
    </p>
    <p class="description">
        <?php esc_html_e('This will override the default excerpt.', 'profitbenefit-theme'); ?>
    </p>
    <?php
}

/**
 * Save textarea
 */
function profitbenefit_save_excerpt_meta($post_id) {

    // Security checks
    if (!isset($_POST['profitbenefit_excerpt_nonce'])) return;
    if (!wp_verify_nonce($_POST['profitbenefit_excerpt_nonce'], 'profitbenefit_excerpt_nonce_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Sanitize and save
    $excerpt = isset($_POST['profitbenefit_custom_excerpt'])
        ? sanitize_textarea_field($_POST['profitbenefit_custom_excerpt'])
        : '';

    update_post_meta($post_id, '_profitbenefit_custom_excerpt', $excerpt);
}
add_action('save_post', 'profitbenefit_save_excerpt_meta');
```

### Meta Box Best Practices

✅ **DO:**
- Always use nonces for security
- Sanitize ALL user input
- Escape ALL output
- Check user capabilities
- Use descriptive meta key names
- Prefix meta keys with underscore (_) to hide from custom fields UI
- Provide clear labels and descriptions
- Set default values

❌ **DON'T:**
- Trust user input without sanitization
- Skip nonce verification
- Forget autosave checks
- Use generic meta key names
- Store complex HTML without wp_kses()
- Forget to check post type

---

## WordPress Customizer

The Customizer allows users to modify theme settings with live preview.

### Step 1: Understanding the Customizer

**What is the Customizer?**
- Live preview interface for theme settings
- Built into WordPress core
- Accessible at Appearance → Customize
- Changes preview in real-time
- Settings saved to options table

**When to Use:**
- Theme colors and fonts
- Layout options
- Header/footer content
- Logo and site identity
- Any setting that affects appearance

### Step 2: Registering Customizer Settings

**Location:** `inc/customizer.php`

```php
<?php
/**
 * Customizer Settings
 *
 * @package ProfitBenefit
 * @since 1.0.0
 */

/**
 * Register customizer settings
 */
function profitbenefit_customize_register($wp_customize) {

    // Step 2.1: Add a new section
    $wp_customize->add_section('profitbenefit_hero_section', array(
        'title'       => __('Hero Banner Settings', 'profitbenefit-theme'),
        'description' => __('Customize the homepage hero banner', 'profitbenefit-theme'),
        'priority'    => 30,  // Position in customizer
    ));

    // Step 2.2: Add setting for hero title
    $wp_customize->add_setting('profitbenefit_hero_title', array(
        'default'           => __('Welcome to ProfitBenefit', 'profitbenefit-theme'),
        'sanitize_callback' => 'sanitize_text_field',  // Security!
        'transport'         => 'refresh',  // or 'postMessage' for live preview
    ));

    // Step 2.3: Add control (UI) for the setting
    $wp_customize->add_control('profitbenefit_hero_title', array(
        'label'       => __('Hero Title', 'profitbenefit-theme'),
        'description' => __('Main heading for hero banner', 'profitbenefit-theme'),
        'section'     => 'profitbenefit_hero_section',
        'type'        => 'text',
        'priority'    => 10,
    ));

    // Step 2.4: Add setting for hero subtitle
    $wp_customize->add_setting('profitbenefit_hero_subtitle', array(
        'default'           => __('Discover amazing content', 'profitbenefit-theme'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('profitbenefit_hero_subtitle', array(
        'label'    => __('Hero Subtitle', 'profitbenefit-theme'),
        'section'  => 'profitbenefit_hero_section',
        'type'     => 'text',
        'priority' => 20,
    ));

    // Step 2.5: Add setting for button text
    $wp_customize->add_setting('profitbenefit_hero_button_text', array(
        'default'           => __('Explore Now', 'profitbenefit-theme'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('profitbenefit_hero_button_text', array(
        'label'    => __('Button Text', 'profitbenefit-theme'),
        'section'  => 'profitbenefit_hero_section',
        'type'     => 'text',
        'priority' => 30,
    ));

    // Step 2.6: Add setting for button URL
    $wp_customize->add_setting('profitbenefit_hero_button_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',  // URL-specific sanitization
    ));

    $wp_customize->add_control('profitbenefit_hero_button_url', array(
        'label'       => __('Button URL', 'profitbenefit-theme'),
        'description' => __('Link for the hero button', 'profitbenefit-theme'),
        'section'     => 'profitbenefit_hero_section',
        'type'        => 'url',
        'priority'    => 40,
    ));
}

add_action('customize_register', 'profitbenefit_customize_register');
```

### Step 3: Different Control Types

```php
<?php
/**
 * Examples of different customizer control types
 */
function profitbenefit_customize_controls($wp_customize) {

    // Create a section for examples
    $wp_customize->add_section('profitbenefit_examples', array(
        'title'    => __('Control Examples', 'profitbenefit-theme'),
        'priority' => 35,
    ));

    // TEXTAREA Control
    $wp_customize->add_setting('profitbenefit_footer_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('profitbenefit_footer_text', array(
        'label'   => __('Footer Text', 'profitbenefit-theme'),
        'section' => 'profitbenefit_examples',
        'type'    => 'textarea',
    ));

    // CHECKBOX Control
    $wp_customize->add_setting('profitbenefit_show_sidebar', array(
        'default'           => true,
        'sanitize_callback' => 'profitbenefit_sanitize_checkbox',
    ));

    $wp_customize->add_control('profitbenefit_show_sidebar', array(
        'label'   => __('Show Sidebar', 'profitbenefit-theme'),
        'section' => 'profitbenefit_examples',
        'type'    => 'checkbox',
    ));

    // SELECT (Dropdown) Control
    $wp_customize->add_setting('profitbenefit_layout', array(
        'default'           => 'wide',
        'sanitize_callback' => 'profitbenefit_sanitize_layout',
    ));

    $wp_customize->add_control('profitbenefit_layout', array(
        'label'   => __('Site Layout', 'profitbenefit-theme'),
        'section' => 'profitbenefit_examples',
        'type'    => 'select',
        'choices' => array(
            'boxed' => __('Boxed', 'profitbenefit-theme'),
            'wide'  => __('Wide', 'profitbenefit-theme'),
            'full'  => __('Full Width', 'profitbenefit-theme'),
        ),
    ));

    // RADIO Control
    $wp_customize->add_setting('profitbenefit_header_style', array(
        'default'           => 'style1',
        'sanitize_callback' => 'profitbenefit_sanitize_header_style',
    ));

    $wp_customize->add_control('profitbenefit_header_style', array(
        'label'   => __('Header Style', 'profitbenefit-theme'),
        'section' => 'profitbenefit_examples',
        'type'    => 'radio',
        'choices' => array(
            'style1' => __('Style 1', 'profitbenefit-theme'),
            'style2' => __('Style 2', 'profitbenefit-theme'),
            'style3' => __('Style 3', 'profitbenefit-theme'),
        ),
    ));

    // COLOR Control
    $wp_customize->add_setting('profitbenefit_primary_color', array(
        'default'           => '#2d6a4f',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'profitbenefit_primary_color',
        array(
            'label'   => __('Primary Color', 'profitbenefit-theme'),
            'section' => 'profitbenefit_examples',
        )
    ));

    // IMAGE Upload Control
    $wp_customize->add_setting('profitbenefit_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'profitbenefit_logo',
        array(
            'label'       => __('Upload Logo', 'profitbenefit-theme'),
            'section'     => 'profitbenefit_examples',
            'settings'    => 'profitbenefit_logo',
            'description' => __('Upload your site logo', 'profitbenefit-theme'),
        )
    ));

    // NUMBER Control (WP 4.6+)
    $wp_customize->add_setting('profitbenefit_posts_per_page', array(
        'default'           => 10,
        'sanitize_callback' => 'absint',  // Absolute integer
    ));

    $wp_customize->add_control('profitbenefit_posts_per_page', array(
        'label'       => __('Posts Per Page', 'profitbenefit-theme'),
        'section'     => 'profitbenefit_examples',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 50,
            'step' => 1,
        ),
    ));
}

add_action('customize_register', 'profitbenefit_customize_controls');
```

### Step 4: Sanitization Callbacks

**CRITICAL for security - never skip this!**

```php
<?php
/**
 * Sanitization functions for customizer
 */

/**
 * Sanitize checkbox
 */
function profitbenefit_sanitize_checkbox($checked) {
    return ((isset($checked) && true === $checked) ? true : false);
}

/**
 * Sanitize layout choices
 */
function profitbenefit_sanitize_layout($input) {
    $valid = array('boxed', 'wide', 'full');

    if (in_array($input, $valid, true)) {
        return $input;
    }

    return 'wide'; // Default
}

/**
 * Sanitize header style
 */
function profitbenefit_sanitize_header_style($input) {
    $valid = array('style1', 'style2', 'style3');

    if (in_array($input, $valid, true)) {
        return $input;
    }

    return 'style1'; // Default
}

/**
 * Sanitize select with allowed values
 */
function profitbenefit_sanitize_select($input, $setting) {

    // Get list of choices from the control
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If input is valid, return it; otherwise, return default
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}
```

### Step 5: Using Customizer Values in Templates

**In your theme templates:**

```php
<?php
/**
 * Example: Hero section in index.php or front-page.php
 */
?>

<section class="hero-banner">
    <div class="hero-content">
        <h1 class="hero-title">
            <?php echo esc_html(get_theme_mod('profitbenefit_hero_title', 'Welcome to ProfitBenefit')); ?>
        </h1>

        <p class="hero-subtitle">
            <?php echo esc_html(get_theme_mod('profitbenefit_hero_subtitle', 'Discover amazing content')); ?>
        </p>

        <?php
        $button_text = get_theme_mod('profitbenefit_hero_button_text', 'Explore Now');
        $button_url  = get_theme_mod('profitbenefit_hero_button_url', '#');

        if (!empty($button_text)) :
        ?>
            <a href="<?php echo esc_url($button_url); ?>" class="hero-button">
                <?php echo esc_html($button_text); ?>
            </a>
        <?php endif; ?>
    </div>
</section>
```

**Using color in CSS:**

```php
<?php
/**
 * Output custom CSS based on customizer settings
 */
function profitbenefit_customizer_css() {

    $primary_color = get_theme_mod('profitbenefit_primary_color', '#2d6a4f');
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr($primary_color); ?>;
        }

        .button,
        .hero-button,
        a:hover {
            background-color: <?php echo esc_attr($primary_color); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'profitbenefit_customizer_css');
```

### Step 6: Live Preview (postMessage)

**For instant preview without page reload:**

```php
<?php
/**
 * Enable live preview for specific settings
 */
function profitbenefit_customize_register_live($wp_customize) {

    // Change transport to 'postMessage'
    $wp_customize->add_setting('profitbenefit_hero_title', array(
        'default'           => __('Welcome', 'profitbenefit-theme'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',  // Enable live preview
    ));

    // Add control (same as before)
    $wp_customize->add_control('profitbenefit_hero_title', array(
        'label'   => __('Hero Title', 'profitbenefit-theme'),
        'section' => 'profitbenefit_hero_section',
        'type'    => 'text',
    ));
}

/**
 * Enqueue customizer preview JavaScript
 */
function profitbenefit_customize_preview_js() {
    wp_enqueue_script(
        'profitbenefit-customizer-preview',
        get_template_directory_uri() . '/admin/js/customizer-preview.js',
        array('customize-preview'),
        '1.0.0',
        true
    );
}
add_action('customize_preview_init', 'profitbenefit_customize_preview_js');
```

**Create:** `admin/js/customizer-preview.js`

```javascript
/**
 * Customizer Live Preview
 */
(function($) {
    'use strict';

    // Hero Title live update
    wp.customize('profitbenefit_hero_title', function(value) {
        value.bind(function(newval) {
            $('.hero-title').text(newval);
        });
    });

    // Hero Subtitle live update
    wp.customize('profitbenefit_hero_subtitle', function(value) {
        value.bind(function(newval) {
            $('.hero-subtitle').text(newval);
        });
    });

    // Primary Color live update
    wp.customize('profitbenefit_primary_color', function(value) {
        value.bind(function(newval) {
            $('style#profitbenefit-primary-color').remove();
            $('head').append(
                '<style id="profitbenefit-primary-color">' +
                ':root { --primary-color: ' + newval + '; }' +
                '</style>'
            );
        });
    });

})(jQuery);
```

---

## Admin Settings Pages

Create custom admin pages for theme settings.

### Step 1: Creating a Settings Page

**Location:** `inc/admin-settings.php`

```php
<?php
/**
 * Admin Settings Page
 *
 * @package ProfitBenefit
 * @since 1.0.0
 */

/**
 * Add admin menu page
 */
function profitbenefit_add_admin_menu() {

    add_theme_page(
        __('ProfitBenefit Settings', 'profitbenefit-theme'),  // Page title
        __('Theme Settings', 'profitbenefit-theme'),          // Menu title
        'manage_options',                                      // Capability
        'profitbenefit-settings',                             // Menu slug
        'profitbenefit_settings_page_html'                    // Callback function
    );
}
add_action('admin_menu', 'profitbenefit_add_admin_menu');

/**
 * Register settings
 */
function profitbenefit_settings_init() {

    // Register a new setting
    register_setting(
        'profitbenefit_options',        // Option group
        'profitbenefit_options',        // Option name
        'profitbenefit_sanitize_options' // Sanitize callback
    );

    // Add a settings section
    add_settings_section(
        'profitbenefit_section_general',                           // ID
        __('General Settings', 'profitbenefit-theme'),            // Title
        'profitbenefit_section_general_callback',                 // Callback
        'profitbenefit-settings'                                   // Page
    );

    // Add settings fields
    add_settings_field(
        'profitbenefit_field_enable_features',                    // ID
        __('Enable Advanced Features', 'profitbenefit-theme'),   // Title
        'profitbenefit_field_enable_features_callback',          // Callback
        'profitbenefit-settings',                                 // Page
        'profitbenefit_section_general'                          // Section
    );

    add_settings_field(
        'profitbenefit_field_api_key',
        __('API Key', 'profitbenefit-theme'),
        'profitbenefit_field_api_key_callback',
        'profitbenefit-settings',
        'profitbenefit_section_general'
    );
}
add_action('admin_init', 'profitbenefit_settings_init');

/**
 * Section callback
 */
function profitbenefit_section_general_callback() {
    ?>
    <p><?php esc_html_e('Configure general theme settings below.', 'profitbenefit-theme'); ?></p>
    <?php
}

/**
 * Field callbacks
 */
function profitbenefit_field_enable_features_callback() {
    $options = get_option('profitbenefit_options');
    $checked = isset($options['enable_features']) ? checked($options['enable_features'], 1, false) : '';
    ?>
    <label>
        <input
            type="checkbox"
            name="profitbenefit_options[enable_features]"
            value="1"
            <?php echo $checked; ?>
        >
        <?php esc_html_e('Enable advanced theme features', 'profitbenefit-theme'); ?>
    </label>
    <?php
}

function profitbenefit_field_api_key_callback() {
    $options = get_option('profitbenefit_options');
    $value = isset($options['api_key']) ? $options['api_key'] : '';
    ?>
    <input
        type="text"
        name="profitbenefit_options[api_key]"
        value="<?php echo esc_attr($value); ?>"
        class="regular-text"
    >
    <p class="description">
        <?php esc_html_e('Enter your API key for third-party integrations.', 'profitbenefit-theme'); ?>
    </p>
    <?php
}

/**
 * Sanitize options
 */
function profitbenefit_sanitize_options($input) {

    $sanitized = array();

    if (isset($input['enable_features'])) {
        $sanitized['enable_features'] = 1;
    } else {
        $sanitized['enable_features'] = 0;
    }

    if (isset($input['api_key'])) {
        $sanitized['api_key'] = sanitize_text_field($input['api_key']);
    }

    return $sanitized;
}

/**
 * Settings page HTML
 */
function profitbenefit_settings_page_html() {

    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Add error/success messages
    if (isset($_GET['settings-updated'])) {
        add_settings_error(
            'profitbenefit_messages',
            'profitbenefit_message',
            __('Settings Saved', 'profitbenefit-theme'),
            'success'
        );
    }

    settings_errors('profitbenefit_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <form action="options.php" method="post">
            <?php
            settings_fields('profitbenefit_options');
            do_settings_sections('profitbenefit-settings');
            submit_button(__('Save Settings', 'profitbenefit-theme'));
            ?>
        </form>
    </div>
    <?php
}
```

### Step 2: Custom Admin Page with Tabs

```php
<?php
/**
 * Tabbed admin settings page
 */
function profitbenefit_tabbed_settings_page() {

    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('ProfitBenefit Settings', 'profitbenefit-theme'); ?></h1>

        <h2 class="nav-tab-wrapper">
            <a href="?page=profitbenefit-settings&tab=general" class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('General', 'profitbenefit-theme'); ?>
            </a>
            <a href="?page=profitbenefit-settings&tab=appearance" class="nav-tab <?php echo $active_tab === 'appearance' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Appearance', 'profitbenefit-theme'); ?>
            </a>
            <a href="?page=profitbenefit-settings&tab=advanced" class="nav-tab <?php echo $active_tab === 'advanced' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Advanced', 'profitbenefit-theme'); ?>
            </a>
        </h2>

        <form method="post" action="options.php">
            <?php
            if ($active_tab === 'general') {
                settings_fields('profitbenefit_general_options');
                do_settings_sections('profitbenefit-general');
            } elseif ($active_tab === 'appearance') {
                settings_fields('profitbenefit_appearance_options');
                do_settings_sections('profitbenefit-appearance');
            } else {
                settings_fields('profitbenefit_advanced_options');
                do_settings_sections('profitbenefit-advanced');
            }

            submit_button();
            ?>
        </form>
    </div>
    <?php
}
```

---

## AJAX Handlers

Handle asynchronous operations in admin and frontend.

### Step 1: Creating an AJAX Handler

**Location:** `inc/ajax-handlers.php`

```php
<?php
/**
 * AJAX Handlers
 *
 * @package ProfitBenefit
 * @since 1.0.0
 */

/**
 * Newsletter subscription AJAX handler
 */
function profitbenefit_newsletter_subscribe_ajax() {

    // Step 1: Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'profitbenefit_newsletter_subscribe')) {
        wp_send_json_error(array(
            'message' => __('Security check failed.', 'profitbenefit-theme')
        ));
    }

    // Step 2: Validate and sanitize input
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        wp_send_json_error(array(
            'message' => __('Email is required.', 'profitbenefit-theme')
        ));
    }

    $email = sanitize_email($_POST['email']);

    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => __('Please enter a valid email address.', 'profitbenefit-theme')
        ));
    }

    // Step 3: Check if already subscribed
    $subscribers = get_option('profitbenefit_newsletter_subscribers', array());

    if (in_array($email, $subscribers, true)) {
        wp_send_json_error(array(
            'message' => __('You are already subscribed!', 'profitbenefit-theme')
        ));
    }

    // Step 4: Add subscriber
    $subscribers[] = $email;
    update_option('profitbenefit_newsletter_subscribers', $subscribers);

    // Step 5: Send success response
    wp_send_json_success(array(
        'message' => __('Thank you for subscribing!', 'profitbenefit-theme')
    ));
}

// Register AJAX actions
add_action('wp_ajax_profitbenefit_newsletter_subscribe', 'profitbenefit_newsletter_subscribe_ajax');
add_action('wp_ajax_nopriv_profitbenefit_newsletter_subscribe', 'profitbenefit_newsletter_subscribe_ajax');
```

### Step 2: Frontend JavaScript for AJAX

**Location:** `assets/js/main.js`

```javascript
/**
 * Newsletter subscription AJAX
 */
(function($) {
    'use strict';

    $('#newsletter-form').on('submit', function(e) {
        e.preventDefault();

        const $form = $(this);
        const $button = $form.find('button[type="submit"]');
        const $message = $('#newsletter-message');
        const email = $form.find('input[name="email"]').val();

        // Disable button during request
        $button.prop('disabled', true).text('Subscribing...');

        // Make AJAX request
        $.ajax({
            url: profitbenefit_ajax.ajax_url,  // Localized variable
            type: 'POST',
            data: {
                action: 'profitbenefit_newsletter_subscribe',
                nonce: profitbenefit_ajax.newsletter_nonce,
                email: email
            },
            success: function(response) {
                if (response.success) {
                    $message.html('<p class="success">' + response.data.message + '</p>');
                    $form[0].reset();
                } else {
                    $message.html('<p class="error">' + response.data.message + '</p>');
                }
            },
            error: function() {
                $message.html('<p class="error">An error occurred. Please try again.</p>');
            },
            complete: function() {
                $button.prop('disabled', false).text('Subscribe');
            }
        });
    });

})(jQuery);
```

### Step 3: Localize Script

**In `functions.php`:**

```php
<?php
/**
 * Enqueue scripts with localized variables
 */
function profitbenefit_enqueue_scripts() {

    wp_enqueue_script(
        'profitbenefit-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        '1.0.0',
        true
    );

    // Localize script with AJAX data
    wp_localize_script('profitbenefit-main', 'profitbenefit_ajax', array(
        'ajax_url'         => admin_url('admin-ajax.php'),
        'newsletter_nonce' => wp_create_nonce('profitbenefit_newsletter_subscribe'),
    ));
}
add_action('wp_enqueue_scripts', 'profitbenefit_enqueue_scripts');
```

---

## Database Operations

### Working with Post Meta

```php
<?php
/**
 * Post Meta Operations
 */

// Add/Update post meta
update_post_meta($post_id, '_profitbenefit_views', 100);

// Get post meta
$views = get_post_meta($post_id, '_profitbenefit_views', true);  // true = single value

// Get all meta for a post
$all_meta = get_post_meta($post_id);

// Delete post meta
delete_post_meta($post_id, '_profitbenefit_views');

// Add unique meta (won't update if exists)
add_post_meta($post_id, '_profitbenefit_unique', 'value', true);
```

### Working with Options

```php
<?php
/**
 * Options API
 */

// Add option
add_option('profitbenefit_setting', 'value');

// Get option with default
$setting = get_option('profitbenefit_setting', 'default_value');

// Update option (adds if doesn't exist)
update_option('profitbenefit_setting', 'new_value');

// Delete option
delete_option('profitbenefit_setting');

// Autoload option (loaded on every page - use sparingly)
add_option('profitbenefit_important', 'value', '', 'yes');
```

### Working with Transients (Cached Data)

```php
<?php
/**
 * Transients API - Temporary cached data
 */

// Set transient (expires in 1 hour)
set_transient('profitbenefit_trending_posts', $posts_array, HOUR_IN_SECONDS);

// Get transient
$cached_posts = get_transient('profitbenefit_trending_posts');

if (false === $cached_posts) {
    // Transient expired or doesn't exist
    // Generate new data
    $cached_posts = profitbenefit_get_trending_posts();
    set_transient('profitbenefit_trending_posts', $cached_posts, HOUR_IN_SECONDS);
}

// Delete transient
delete_transient('profitbenefit_trending_posts');
```

---

## REST API Integration

### Creating Custom REST Endpoints

**Location:** `inc/rest-api.php`

```php
<?php
/**
 * REST API Endpoints
 *
 * @package ProfitBenefit
 * @since 1.0.0
 */

/**
 * Register custom REST routes
 */
function profitbenefit_register_rest_routes() {

    // Register route: /wp-json/profitbenefit/v1/posts
    register_rest_route('profitbenefit/v1', '/posts', array(
        'methods'             => 'GET',
        'callback'            => 'profitbenefit_get_posts_rest',
        'permission_callback' => '__return_true',  // Public endpoint
    ));

    // Register route with parameter: /wp-json/profitbenefit/v1/post/123
    register_rest_route('profitbenefit/v1', '/post/(?P<id>\d+)', array(
        'methods'             => 'GET',
        'callback'            => 'profitbenefit_get_single_post_rest',
        'permission_callback' => '__return_true',
        'args'                => array(
            'id' => array(
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            ),
        ),
    ));

    // Protected endpoint (requires authentication)
    register_rest_route('profitbenefit/v1', '/admin/settings', array(
        'methods'             => 'POST',
        'callback'            => 'profitbenefit_update_settings_rest',
        'permission_callback' => function() {
            return current_user_can('manage_options');
        },
    ));
}
add_action('rest_api_init', 'profitbenefit_register_rest_routes');

/**
 * Get posts REST callback
 */
function profitbenefit_get_posts_rest($request) {

    $params = $request->get_params();

    $args = array(
        'posts_per_page' => isset($params['per_page']) ? absint($params['per_page']) : 10,
        'paged'          => isset($params['page']) ? absint($params['page']) : 1,
    );

    $query = new WP_Query($args);

    $posts = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $posts[] = array(
                'id'      => get_the_ID(),
                'title'   => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'link'    => get_permalink(),
                'date'    => get_the_date('c'),  // ISO 8601 format
            );
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response($posts, 200);
}

/**
 * Get single post REST callback
 */
function profitbenefit_get_single_post_rest($request) {

    $post_id = $request['id'];
    $post = get_post($post_id);

    if (!$post) {
        return new WP_Error('no_post', 'Post not found', array('status' => 404));
    }

    $data = array(
        'id'      => $post->ID,
        'title'   => $post->post_title,
        'content' => apply_filters('the_content', $post->post_content),
        'excerpt' => $post->post_excerpt,
        'author'  => get_the_author_meta('display_name', $post->post_author),
        'date'    => get_the_date('c', $post),
    );

    return new WP_REST_Response($data, 200);
}
```

---

## Security Best Practices

### 1. Nonce Verification (CSRF Protection)

```php
<?php
// Creating nonce
wp_nonce_field('my_action_name', 'my_nonce_field');

// Verifying nonce
if (!isset($_POST['my_nonce_field']) || !wp_verify_nonce($_POST['my_nonce_field'], 'my_action_name')) {
    die('Security check failed');
}
```

### 2. Capability Checks

```php
<?php
// Check if user can edit posts
if (!current_user_can('edit_posts')) {
    wp_die('You do not have permission to access this page.');
}

// Common capabilities:
// - 'manage_options' (Administrator)
// - 'edit_posts' (Editor, Author)
// - 'edit_published_posts' (Author)
// - 'read' (Subscriber)
```

### 3. Data Sanitization (Input)

```php
<?php
// Text field
$text = sanitize_text_field($_POST['text']);

// Textarea
$textarea = sanitize_textarea_field($_POST['textarea']);

// Email
$email = sanitize_email($_POST['email']);

// URL
$url = esc_url_raw($_POST['url']);

// Integer
$number = absint($_POST['number']);

// HTML content (allows specific tags)
$html = wp_kses_post($_POST['content']);
```

### 4. Data Escaping (Output)

```php
<?php
// Plain text
echo esc_html($text);

// HTML attributes
echo '<div class="' . esc_attr($class) . '">';

// URLs
echo '<a href="' . esc_url($url) . '">';

// JavaScript
echo '<script>var data = ' . esc_js($data) . ';</script>';

// Textarea
echo '<textarea>' . esc_textarea($content) . '</textarea>';
```

---

## Performance Optimization

### 1. Use Transients for Expensive Queries

```php
<?php
function profitbenefit_get_trending_posts_cached() {

    $cache_key = 'profitbenefit_trending_posts';
    $cached = get_transient($cache_key);

    if (false !== $cached) {
        return $cached;
    }

    // Expensive query
    $posts = new WP_Query(array(
        'posts_per_page' => 10,
        'meta_key'       => '_profitbenefit_views',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    ));

    // Cache for 1 hour
    set_transient($cache_key, $posts, HOUR_IN_SECONDS);

    return $posts;
}
```

### 2. Limit Database Queries

```php
<?php
// Bad: Multiple queries in loop
foreach ($posts as $post) {
    $author = get_user_by('id', $post->post_author);  // N queries!
}

// Good: Single query with JOIN
$query = new WP_Query(array(
    'posts_per_page' => 10,
    // WordPress will automatically JOIN authors
));
```

### 3. Enqueue Scripts Conditionally

```php
<?php
function profitbenefit_conditional_scripts() {

    // Only load on single posts
    if (is_single()) {
        wp_enqueue_script('profitbenefit-comments');
    }

    // Only load on homepage
    if (is_front_page()) {
        wp_enqueue_script('profitbenefit-slider');
    }
}
add_action('wp_enqueue_scripts', 'profitbenefit_conditional_scripts');
```

---

## Testing & Debugging

### 1. Debug Mode

**In `wp-config.php`:**

```php
<?php
// Enable debug mode (development only!)
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);     // Log errors to wp-content/debug.log
define('WP_DEBUG_DISPLAY', false); // Don't show errors on screen
define('SCRIPT_DEBUG', true);      // Use non-minified scripts
```

### 2. Error Logging

```php
<?php
// Write to debug.log
error_log('Debug message: ' . print_r($variable, true));

// Conditional debugging
if (WP_DEBUG) {
    error_log('This only logs in debug mode');
}
```

### 3. Query Monitor Plugin

Install Query Monitor plugin to see:
- Database queries
- PHP errors
- Hooks fired
- HTTP requests
- Script dependencies

---

## Summary Checklist

When creating backend features, always:

✅ **Security:**
- [ ] Add nonces to all forms
- [ ] Verify nonces on submission
- [ ] Check user capabilities
- [ ] Sanitize all input
- [ ] Escape all output

✅ **Best Practices:**
- [ ] Use WordPress APIs (don't reinvent)
- [ ] Prefix all functions and variables
- [ ] Translate all strings
- [ ] Add inline documentation
- [ ] Follow WordPress coding standards

✅ **Performance:**
- [ ] Cache expensive queries with transients
- [ ] Enqueue scripts conditionally
- [ ] Minimize database queries
- [ ] Use proper WordPress hooks

✅ **Testing:**
- [ ] Test with WP_DEBUG enabled
- [ ] Check for PHP errors
- [ ] Verify data saves correctly
- [ ] Test with different user roles
- [ ] Validate with Theme Check plugin

---

## Additional Resources

**Official Documentation:**
- [WordPress Codex](https://codex.wordpress.org/)
- [Developer Handbook](https://developer.wordpress.org/)
- [Plugin API Reference](https://developer.wordpress.org/reference/)

**Coding Standards:**
- [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- [HTML Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/html/)
- [JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)

**Tools:**
- [Theme Check Plugin](https://wordpress.org/plugins/theme-check/)
- [Query Monitor](https://wordpress.org/plugins/query-monitor/)
- [Debug Bar](https://wordpress.org/plugins/debug-bar/)

---

**Document Version:** 1.0.0
**Last Updated:** December 27, 2025
**Maintained by:** ProfitBenefit Development Team

For questions or issues, please refer to the WordPress Developer Handbook or consult the WordPress.org support forums.
