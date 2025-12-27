# WordPress Theme Development Specification

## Overview
This specification defines the complete standards and requirements for developing WordPress themes with 100% quality and marketplace compatibility.

## 1. Theme File Structure

### Required Core Files
```
theme-name/
├── style.css              # Main stylesheet with theme headers (REQUIRED)
├── index.php              # Main template fallback (REQUIRED)
├── functions.php          # Theme functions and features
├── screenshot.png         # Theme preview (1200x900px)
├── header.php             # Header template
├── footer.php             # Footer template
├── sidebar.php            # Sidebar template
├── single.php             # Single post template
├── page.php               # Single page template
├── archive.php            # Archive listing template
├── search.php             # Search results template
├── 404.php                # Error page template
├── comments.php           # Comments template
└── front-page.php         # Homepage template
```

### Template Hierarchy
Follow WordPress template hierarchy:
- `front-page.php` > `home.php` > `index.php` for homepage
- `single-{post-type}.php` > `single.php` > `singular.php` > `index.php` for posts
- `page-{slug}.php` > `page-{id}.php` > `page.php` > `singular.php` > `index.php` for pages
- `category-{slug}.php` > `category-{id}.php` > `category.php` > `archive.php` > `index.php` for categories

### Asset Organization
```
assets/
├── css/
│   ├── main.css           # Compiled/main styles
│   └── editor-style.css   # Editor styles
├── js/
│   ├── main.js            # Main JavaScript
│   └── customizer.js      # Customizer preview JS
├── images/
│   └── placeholder.png    # Fallback images
└── fonts/
    └── custom-fonts/      # Custom web fonts
```

### Template Parts
```
template-parts/
├── content.php            # Default post content
├── content-single.php     # Single post content
├── content-page.php       # Page content
├── content-none.php       # No content found
└── navigation/
    ├── header-nav.php
    └── footer-nav.php
```

## 2. Theme Headers (style.css)

### Required Headers
```css
/*
Theme Name: Theme Name
Theme URI: https://example.com/theme-name
Author: Author Name
Author URI: https://example.com
Description: Brief description (max 250 characters)
Version: 1.0.0
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: theme-textdomain
Domain Path: /languages
Tags: blog, custom-menu, featured-images, threaded-comments, translation-ready
*/
```

### Tag Requirements
Maximum 5 tags from approved list:
- Layout: one-column, two-columns, three-columns, four-columns, left-sidebar, right-sidebar, grid-layout
- Features: accessibility-ready, custom-background, custom-colors, custom-header, custom-logo, custom-menu, editor-style, featured-images, footer-widgets, full-width-template, microformats, post-formats, rtl-language-support, sticky-post, theme-options, threaded-comments, translation-ready
- Subject: blog, e-commerce, education, entertainment, food-and-drink, holiday, news, photography, portfolio

## 3. Theme Setup (functions.php)

### After Theme Setup Hook
```php
function themename_setup() {
    // Language support
    load_theme_textdomain( 'textdomain', get_template_directory() . '/languages' );

    // Feed links
    add_theme_support( 'automatic-feed-links' );

    // Title tag
    add_theme_support( 'title-tag' );

    // Post thumbnails
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 1200, 9999 );

    // Custom image sizes
    add_image_size( 'themename-featured', 800, 450, true );

    // HTML5 support
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'
    ) );

    // Custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Custom background
    add_theme_support( 'custom-background', array(
        'default-color' => 'ffffff',
    ) );

    // Responsive embeds
    add_theme_support( 'responsive-embeds' );

    // Editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor-style.css' );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'textdomain' ),
        'footer'  => __( 'Footer Menu', 'textdomain' ),
    ) );

    // Post formats (optional)
    add_theme_support( 'post-formats', array(
        'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'
    ) );
}
add_action( 'after_setup_theme', 'themename_setup' );
```

### Widget Areas
```php
function themename_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Main Sidebar', 'textdomain' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Widgets in this area will be shown in the sidebar.', 'textdomain' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'themename_widgets_init' );
```

### Script Enqueueing
```php
function themename_scripts() {
    // Styles
    wp_enqueue_style(
        'themename-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );

    // Scripts
    wp_enqueue_script(
        'themename-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array( 'jquery' ),
        wp_get_theme()->get( 'Version' ),
        true
    );

    // Comment reply
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'themename_scripts' );
```

## 4. Security Standards

### Direct Access Prevention
```php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
```

### Data Sanitization
```php
// Input sanitization
$value = sanitize_text_field( $_POST['field'] );
$email = sanitize_email( $_POST['email'] );
$url = esc_url_raw( $_POST['url'] );

// Output escaping
echo esc_html( $text );
echo esc_url( $url );
echo esc_attr( $attribute );
echo wp_kses_post( $content ); // For post content
```

### Nonce Verification
```php
// Form generation
wp_nonce_field( 'action_name', 'nonce_name' );

// Form processing
if ( ! isset( $_POST['nonce_name'] ) ||
     ! wp_verify_nonce( $_POST['nonce_name'], 'action_name' ) ) {
    return;
}
```

### Capability Checks
```php
if ( ! current_user_can( 'edit_theme_options' ) ) {
    return;
}
```

### SQL Queries
```php
global $wpdb;
$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->posts} WHERE post_type = %s",
        $post_type
    )
);
```

## 5. Internationalization (i18n)

### Text Domain
Must match the theme slug exactly.

### Translation Functions
```php
// Simple string
__( 'Text', 'textdomain' );

// Echo string
_e( 'Text', 'textdomain' );

// With variables
printf(
    __( 'Posted by %s', 'textdomain' ),
    get_the_author()
);

// Plural forms
_n(
    '%s comment',
    '%s comments',
    $count,
    'textdomain'
);

// Context
_x( 'Post', 'noun', 'textdomain' );

// Escaped output
esc_html__( 'Text', 'textdomain' );
esc_html_e( 'Text', 'textdomain' );
esc_attr__( 'Text', 'textdomain' );
```

### Translation Files
```
languages/
├── textdomain.pot         # Template file
├── textdomain-en_US.po    # Translation source
└── textdomain-en_US.mo    # Compiled translation
```

## 6. Accessibility Standards

### WCAG 2.1 Level AA Compliance
- Proper heading hierarchy (H1 → H2 → H3)
- Skip to content links
- ARIA labels and landmarks
- Keyboard navigation support
- Focus indicators
- Color contrast ratios (4.5:1 for normal text, 3:1 for large text)
- Alt text for all images
- Form labels

### Accessibility Markup
```php
<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'textdomain' ); ?>">

<a class="skip-link screen-reader-text" href="#content">
    <?php esc_html_e( 'Skip to content', 'textdomain' ); ?>
</a>

<button aria-expanded="false" aria-controls="primary-menu">
    <?php esc_html_e( 'Menu', 'textdomain' ); ?>
</button>
```

## 7. Performance Optimization

### Query Optimization
```php
// Use transients for expensive queries
$cached_data = get_transient( 'theme_cache_key' );
if ( false === $cached_data ) {
    $cached_data = expensive_query();
    set_transient( 'theme_cache_key', $cached_data, HOUR_IN_SECONDS );
}

// Efficient WP_Query
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 10,
    'no_found_rows'  => true, // Skip pagination count
    'fields'         => 'ids', // Only get IDs if that's all you need
);
```

### Asset Loading
```php
// Conditional loading
if ( is_front_page() ) {
    wp_enqueue_script( 'homepage-slider' );
}

// Defer/async JavaScript
wp_script_add_data( 'script-handle', 'defer', true );
wp_script_add_data( 'script-handle', 'async', true );
```

### Image Optimization
- Lazy loading: `loading="lazy"` attribute
- Responsive images: `srcset` and `sizes`
- WebP format support
- Proper image sizes registered

## 8. Documentation Requirements

### PHPDoc Standards
```php
/**
 * Function description
 *
 * Longer description explaining the function's purpose,
 * usage context, and any important notes.
 *
 * @since 1.0.0
 * @param string $param1 Description of parameter.
 * @param int    $param2 Description of parameter. Default 0.
 * @return bool True on success, false on failure.
 */
function themename_function( $param1, $param2 = 0 ) {
    // Function code
}
```

### File Headers
```php
<?php
/**
 * File description
 *
 * @package ThemeName
 * @subpackage TemplateType
 * @since 1.0.0
 */
```

## 9. Theme Customizer

### Settings and Controls
```php
function themename_customize_register( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'section_id', array(
        'title'    => __( 'Section Title', 'textdomain' ),
        'priority' => 30,
    ) );

    // Add setting
    $wp_customize->add_setting( 'setting_id', array(
        'default'           => 'default_value',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh', // or 'postMessage'
    ) );

    // Add control
    $wp_customize->add_control( 'setting_id', array(
        'label'   => __( 'Setting Label', 'textdomain' ),
        'section' => 'section_id',
        'type'    => 'text',
    ) );
}
add_action( 'customize_register', 'themename_customize_register' );
```

## 10. Testing Requirements

### Theme Check Plugin
- Must pass Theme Check plugin without errors
- Warnings should be addressed if possible

### Required Tests
- [ ] Test with default WordPress content
- [ ] Test with sample posts (text, images, galleries)
- [ ] Test all page templates
- [ ] Test widgets in all widget areas
- [ ] Test navigation menus
- [ ] Test customizer settings
- [ ] Test comments functionality
- [ ] Test responsive breakpoints (mobile, tablet, desktop)
- [ ] Cross-browser testing (Chrome, Firefox, Safari, Edge)
- [ ] Accessibility testing (keyboard navigation, screen readers)
- [ ] Performance testing (PageSpeed Insights, GTmetrix)
- [ ] Plugin compatibility (major plugins like WooCommerce, Contact Form 7)

### Debug Mode Testing
Test with:
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );
```

## 11. Coding Standards

### WordPress Coding Standards
- Follow WordPress PHP Coding Standards
- Use WordPress CSS Coding Standards
- Use WordPress JavaScript Coding Standards
- Use WordPress HTML Coding Standards

### PHP Standards
- Opening braces on same line for functions/controls
- Yoda conditions: `if ( true === $var )`
- Single quotes for strings (unless parsing needed)
- Space after keywords and control structures
- No closing PHP tag at end of files

### CSS Standards
- Use tabs for indentation
- Space after property colons
- One selector per line
- Properties on separate lines
- Lowercase hex colors
- Prefixing for custom properties

### JavaScript Standards
- Use camelCase for variable names
- Use strict equality (`===`)
- Semicolons required
- Proper spacing around operators

## 12. GPL Licensing

### License Requirements
- Theme must be 100% GPL compatible
- All bundled resources must be GPL compatible
- License headers in all PHP files
- Include license.txt file

### Compatible Licenses
- GPL v2 or later
- MIT
- Apache 2.0
- BSD
- CC0 (Public Domain)

### Incompatible Licenses
- Proprietary licenses
- Creative Commons with NC (non-commercial)
- Creative Commons with ND (no derivatives)

## 13. Version Control

### .gitignore
```
node_modules/
.DS_Store
.sass-cache/
*.log
*.zip
.env
```

### Semantic Versioning
- MAJOR.MINOR.PATCH (e.g., 1.0.0)
- MAJOR: Breaking changes
- MINOR: New features, backward compatible
- PATCH: Bug fixes, backward compatible

## 14. Theme Options Best Practices

### Use Customizer, Not Theme Options Page
- All settings should use WordPress Customizer
- Live preview when possible
- No custom options pages (unless absolutely necessary)

### Default Values
- Always provide sensible defaults
- Theme should work without configuration

### Data Storage
- Use theme mods: `get_theme_mod()`, `set_theme_mod()`
- Clear on theme switch if appropriate

## 15. Child Theme Support

### Make Theme Extensible
```php
// Use get_template_directory() for parent
// Use get_stylesheet_directory() for child

// Allow child theme overrides
if ( ! function_exists( 'themename_function' ) ) {
    function themename_function() {
        // Function code
    }
}

// Use action hooks
do_action( 'themename_before_header' );
do_action( 'themename_after_header' );
```

### Child Theme Template
```css
/*
Theme Name: Parent Theme Child
Template: parent-theme-folder
*/
```

## 16. Prohibited Practices

### DO NOT:
- ❌ Use `@ini_set` or other PHP settings changes
- ❌ Hard-code scripts/styles (use `wp_enqueue_*`)
- ❌ Include admin/plugin territory functionality
- ❌ Use `eval()`, `base64_encode()`, `base64_decode()`, `gzinflate()`, `str_rot13()`
- ❌ Generate errors/warnings/notices
- ❌ Modify core WordPress files
- ❌ Override user choices (search engine visibility, etc.)
- ❌ Phone home without explicit user permission
- ❌ Use shortened URLs or analytics tracking
- ❌ Include cryptocurrency miners
- ❌ Include advertising (unless user-controlled)

## 17. Required Functionality

### Must Support:
- ✅ Dynamic titles
- ✅ Dynamic sidebars
- ✅ Navigation menus
- ✅ Post thumbnails
- ✅ Comments
- ✅ RSS feeds
- ✅ Pagination
- ✅ Breadcrumbs (recommended)
- ✅ Search functionality
- ✅ 404 page

## 18. Recommended Features

### Enhanced User Experience:
- Sticky header
- Back to top button
- Social sharing
- Reading time
- View counter
- Related posts
- Post navigation
- Author bio
- Table of contents
- Dark mode toggle

## 19. Mobile-First Development

### Responsive Design Requirements
- Mobile-first CSS approach
- Proper viewport meta tag
- Touch-friendly elements (min 44x44px)
- No horizontal scrolling
- Readable font sizes (min 16px)
- Appropriate spacing on mobile

### Breakpoints
```css
/* Mobile first */
.element { }

/* Tablet */
@media (min-width: 768px) { }

/* Desktop */
@media (min-width: 1024px) { }

/* Large desktop */
@media (min-width: 1280px) { }
```

## 20. Browser Support

### Minimum Browser Versions
- Chrome: Last 2 versions
- Firefox: Last 2 versions
- Safari: Last 2 versions
- Edge: Last 2 versions
- iOS Safari: Last 2 versions
- Android Chrome: Last 2 versions

### Graceful Degradation
- Progressive enhancement approach
- Fallbacks for modern features
- Polyfills only when necessary

---

**Version:** 1.0.0
**Last Updated:** 2025-12-26
**Compliance Level:** WordPress.org & Commercial Marketplace Standards
