# Theme Security - Complete Guidelines & Examples

---
name: security
hidden: true

> **Load this file when**: Keywords detected - security, sanitize, escape, XSS, nonce, theme security, output escaping
> 
> **Purpose**: WordPress theme-specific security patterns and examples

---

## 🛡️ THEME SECURITY FUNDAMENTALS

**Core Principle**: Themes display content - security focuses on **output escaping** and **safe input handling** for theme options.

**Theme Security Is Different From Plugin Security**:
- ✅ Themes: Output escaping, Customizer sanitization, template security
- ❌ Plugins: Complex SQL, REST API, file uploads, custom tables

---

## 🎯 OUTPUT ESCAPING (CRITICAL FOR THEMES)

### Rule: ALWAYS Escape Output in Templates

Every piece of dynamic content in your theme templates MUST be escaped.

### Basic Output Escaping

```php
<?php
/**
 * header.php - Escaping examples
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    
    <!-- Page title - escaped automatically by wp_title() -->
    <title><?php wp_title('|', true, 'right'); ?></title>
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- Site title and description -->
<header class="site-header">
    <h1 class="site-title">
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <?php echo esc_html(get_bloginfo('name')); ?>
        </a>
    </h1>
    
    <?php
    $description = get_bloginfo('description');
    if ($description) :
    ?>
        <p class="site-description">
            <?php echo esc_html($description); ?>
        </p>
    <?php endif; ?>
</header>
```

### Common Escaping Functions for Themes

```php
<?php
// HTML content (text only, strips tags)
echo esc_html($text);
echo esc_html(get_the_title());
echo esc_html(get_bloginfo('name'));

// HTML attributes (class, id, data-*, etc.)
echo '<div class="' . esc_attr($class_name) . '">';
echo '<input type="text" value="' . esc_attr($value) . '">';
echo '<img alt="' . esc_attr(get_the_title()) . '">';

// URLs (href, src attributes)
echo '<a href="' . esc_url($link) . '">Link</a>';
echo '<img src="' . esc_url($image_url) . '">';
echo '<link rel="stylesheet" href="' . esc_url($css_url) . '">';

// JavaScript strings
echo '<script>var siteUrl = "' . esc_js(home_url()) . '";</script>';

// Rich content (allows safe HTML tags)
echo wp_kses_post($post_content);
echo wp_kses_post(get_the_content());

// Translations with escaping
echo esc_html__('Hello World', 'themename');
echo esc_attr__('Button Label', 'themename');
esc_html_e('Read More', 'themename');
```

### Template Examples with Proper Escaping

#### Single Post Template

```php
<?php
/**
 * single.php - Single post template
 */
get_header();
?>

<main id="main" class="site-main">
    <?php
    while (have_posts()) :
        the_post();
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Post Title - ESCAPED -->
            <h1 class="entry-title">
                <?php echo esc_html(get_the_title()); ?>
            </h1>
            
            <!-- Post Meta - ESCAPED -->
            <div class="entry-meta">
                <span class="posted-on">
                    <?php
                    printf(
                        /* translators: %s: post date */
                        esc_html__('Posted on %s', 'themename'),
                        '<time datetime="' . esc_attr(get_the_date('c')) . '">' .
                        esc_html(get_the_date()) .
                        '</time>'
                    );
                    ?>
                </span>
                
                <span class="byline">
                    <?php
                    printf(
                        /* translators: %s: author name */
                        esc_html__('by %s', 'themename'),
                        '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' .
                        esc_html(get_the_author()) .
                        '</a>'
                    );
                    ?>
                </span>
            </div>
            
            <!-- Featured Image - ESCAPED -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail">
                    <?php
                    the_post_thumbnail('large', [
                        'alt' => esc_attr(get_the_title())
                    ]);
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- Post Content - wp_kses_post allows safe HTML -->
            <div class="entry-content">
                <?php
                the_content();
                
                wp_link_pages([
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'themename'),
                    'after'  => '</div>',
                ]);
                ?>
            </div>
            
            <!-- Post Categories - ESCAPED -->
            <?php
            $categories = get_the_category();
            if ($categories) :
            ?>
                <div class="entry-categories">
                    <span class="cat-label"><?php esc_html_e('Categories:', 'themename'); ?></span>
                    <?php
                    foreach ($categories as $category) :
                        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link">';
                        echo esc_html($category->name);
                        echo '</a>';
                    endforeach;
                    ?>
                </div>
            <?php endif; ?>
            
        </article>
        
        <?php
        // Comments template
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
        
    <?php endwhile; ?>
</main>

<?php
get_sidebar();
get_footer();
```

#### Archive Template

```php
<?php
/**
 * archive.php - Archive template
 */
get_header();
?>

<main id="main" class="site-main">
    
    <?php if (have_posts()) : ?>
        
        <!-- Archive Header - ESCAPED -->
        <header class="page-header">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>
        
        <!-- Posts Loop -->
        <div class="posts-grid">
            <?php
            while (have_posts()) :
                the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                    
                    <!-- Thumbnail - ESCAPED -->
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="post-thumbnail-link">
                            <?php
                            the_post_thumbnail('medium', [
                                'alt' => esc_attr(get_the_title())
                            ]);
                            ?>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Title - ESCAPED -->
                    <h2 class="entry-title">
                        <a href="<?php echo esc_url(get_permalink()); ?>">
                            <?php echo esc_html(get_the_title()); ?>
                        </a>
                    </h2>
                    
                    <!-- Excerpt - ESCAPED -->
                    <div class="entry-summary">
                        <?php echo wp_kses_post(get_the_excerpt()); ?>
                    </div>
                    
                    <!-- Read More Link - ESCAPED -->
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="read-more">
                        <?php esc_html_e('Read More', 'themename'); ?>
                        <span class="screen-reader-text">
                            <?php
                            /* translators: %s: post title */
                            printf(
                                esc_html__('about %s', 'themename'),
                                esc_html(get_the_title())
                            );
                            ?>
                        </span>
                    </a>
                    
                </article>
            <?php endwhile; ?>
        </div>
        
        <!-- Pagination - WordPress handles escaping -->
        <?php
        the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => esc_html__('Previous', 'themename'),
            'next_text' => esc_html__('Next', 'themename'),
        ]);
        ?>
        
    <?php else : ?>
        
        <!-- No Posts Found - ESCAPED -->
        <div class="no-results">
            <h1 class="page-title"><?php esc_html_e('Nothing Found', 'themename'); ?></h1>
            <p><?php esc_html_e('No posts were found matching your criteria.', 'themename'); ?></p>
        </div>
        
    <?php endif; ?>
    
</main>

<?php
get_sidebar();
get_footer();
```

---

## 🎨 CUSTOMIZER SECURITY

### Sanitization Callbacks for Theme Options

```php
<?php
/**
 * inc/customizer.php - Customizer settings with sanitization
 */

function themename_customize_register($wp_customize) {
    
    // Text Field
    $wp_customize->add_setting('themename_footer_text', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field', // Remove HTML tags
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('themename_footer_text', [
        'label'    => __('Footer Text', 'themename'),
        'section'  => 'title_tagline',
        'type'     => 'text',
    ]);
    
    // Textarea with HTML
    $wp_customize->add_setting('themename_footer_html', [
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post', // Allow safe HTML
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('themename_footer_html', [
        'label'    => __('Footer HTML', 'themename'),
        'section'  => 'title_tagline',
        'type'     => 'textarea',
    ]);
    
    // URL Field
    $wp_customize->add_setting('themename_social_facebook', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw', // Sanitize URL
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('themename_social_facebook', [
        'label'    => __('Facebook URL', 'themename'),
        'section'  => 'title_tagline',
        'type'     => 'url',
    ]);
    
    // Email Field
    $wp_customize->add_setting('themename_contact_email', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_email', // Sanitize email
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('themename_contact_email', [
        'label'    => __('Contact Email', 'themename'),
        'section'  => 'title_tagline',
        'type'     => 'email',
    ]);
    
    // Checkbox
    $wp_customize->add_setting('themename_show_sidebar', [
        'default'           => true,
        'sanitize_callback' => 'themename_sanitize_checkbox', // Custom sanitization
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('themename_show_sidebar', [
        'label'    => __('Show Sidebar', 'themename'),
        'section'  => 'title_tagline',
        'type'     => 'checkbox',
    ]);
    
    // Select Field
    $wp_customize->add_setting('themename_layout', [
        'default'           => 'full-width',
        'sanitize_callback' => 'themename_sanitize_layout', // Validate against allowed values
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('themename_layout', [
        'label'    => __('Layout', 'themename'),
        'section'  => 'title_tagline',
        'type'     => 'select',
        'choices'  => [
            'full-width' => __('Full Width', 'themename'),
            'boxed'      => __('Boxed', 'themename'),
            'sidebar'    => __('With Sidebar', 'themename'),
        ],
    ]);
    
    // Color Field
    $wp_customize->add_setting('themename_primary_color', [
        'default'           => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color', // Validate hex color
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'themename_primary_color',
        [
            'label'    => __('Primary Color', 'themename'),
            'section'  => 'colors',
        ]
    ));
}
add_action('customize_register', 'themename_customize_register');

/**
 * Custom sanitization callbacks
 */

// Checkbox sanitization
function themename_sanitize_checkbox($checked) {
    return (isset($checked) && $checked === true) ? true : false;
}

// Layout sanitization
function themename_sanitize_layout($input) {
    $valid_layouts = ['full-width', 'boxed', 'sidebar'];
    
    if (in_array($input, $valid_layouts, true)) {
        return $input;
    }
    
    return 'full-width'; // Default fallback
}

// Integer sanitization
function themename_sanitize_number($input) {
    return absint($input);
}

// Select sanitization (generic)
function themename_sanitize_select($input, $setting) {
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return array_key_exists($input, $choices) ? $input : $setting->default;
}
```

### Using Customizer Values Safely in Templates

```php
<?php
/**
 * footer.php - Using Customizer values with escaping
 */
?>

<footer class="site-footer">
    <div class="footer-content">
        
        <?php
        // Get and escape footer text
        $footer_text = get_theme_mod('themename_footer_text', '');
        if ($footer_text) :
        ?>
            <p class="footer-text">
                <?php echo esc_html($footer_text); ?>
            </p>
        <?php endif; ?>
        
        <?php
        // Get and output footer HTML (already sanitized with wp_kses_post)
        $footer_html = get_theme_mod('themename_footer_html', '');
        if ($footer_html) :
        ?>
            <div class="footer-html">
                <?php echo wp_kses_post($footer_html); ?>
            </div>
        <?php endif; ?>
        
        <?php
        // Social links with escaping
        $facebook_url = get_theme_mod('themename_social_facebook', '');
        if ($facebook_url) :
        ?>
            <a href="<?php echo esc_url($facebook_url); ?>" 
               class="social-link" 
               target="_blank" 
               rel="noopener noreferrer">
                <?php esc_html_e('Facebook', 'themename'); ?>
            </a>
        <?php endif; ?>
        
        <?php
        // Contact email with escaping
        $contact_email = get_theme_mod('themename_contact_email', '');
        if ($contact_email) :
        ?>
            <a href="mailto:<?php echo esc_attr(antispambot($contact_email)); ?>" 
               class="email-link">
                <?php echo esc_html(antispambot($contact_email)); ?>
            </a>
        <?php endif; ?>
        
    </div>
    
    <div class="site-info">
        <?php
        printf(
            /* translators: %s: site name */
            esc_html__('Copyright © %s. All rights reserved.', 'themename'),
            esc_html(get_bloginfo('name'))
        );
        ?>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
```

---

## 📝 FORM SECURITY IN THEMES

### Search Form with Security

```php
<?php
/**
 * searchform.php - Secure search form
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-field">
        <span class="screen-reader-text"><?php esc_html_e('Search for:', 'themename'); ?></span>
        <input 
            type="search" 
            id="search-field" 
            class="search-field" 
            placeholder="<?php echo esc_attr__('Search...', 'themename'); ?>" 
            value="<?php echo esc_attr(get_search_query()); ?>" 
            name="s" 
            required
        >
    </label>
    <button type="submit" class="search-submit">
        <?php esc_html_e('Search', 'themename'); ?>
    </button>
</form>
```

### Comment Form (WordPress Handles Security)

```php
<?php
/**
 * comments.php - Comments with WordPress security
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            printf(
                /* translators: %s: number of comments */
                esc_html(_n('%s Comment', '%s Comments', $comment_count, 'themename')),
                esc_html(number_format_i18n($comment_count))
            );
            ?>
        </h2>
        
        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
            ]);
            ?>
        </ol>
        
        <?php
        the_comments_pagination([
            'prev_text' => esc_html__('Previous', 'themename'),
            'next_text' => esc_html__('Next', 'themename'),
        ]);
        ?>
        
    <?php endif; ?>
    
    <?php
    // Comment form - WordPress handles nonces and sanitization
    comment_form([
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h3>',
        'label_submit'       => esc_html__('Post Comment', 'themename'),
    ]);
    ?>
    
</div>
```

---

## 🔒 NAVIGATION MENU SECURITY

### Custom Walker with Escaping

```php
<?php
/**
 * inc/class-custom-walker.php - Custom nav walker with proper escaping
 */

class Themename_Custom_Walker extends Walker_Nav_Menu {
    
    /**
     * Start element output with proper escaping
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
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
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
```

---

## 🎯 WIDGET SECURITY

### Custom Widget with Sanitization

```php
<?php
/**
 * inc/class-custom-widget.php - Secure custom widget
 */

class Themename_Recent_Posts_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'themename_recent_posts',
            __('Theme Recent Posts', 'themename'),
            ['description' => __('Display recent posts with thumbnail', 'themename')]
        );
    }
    
    /**
     * Widget output with proper escaping
     */
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
    
    /**
     * Widget form with escaped output
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'themename'); ?>
            </label>
            <input 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                type="text" 
                value="<?php echo esc_attr($title); ?>"
            >
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>">
                <?php esc_html_e('Number of posts:', 'themename'); ?>
            </label>
            <input 
                class="tiny-text" 
                id="<?php echo esc_attr($this->get_field_id('number')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('number')); ?>" 
                type="number" 
                step="1" 
                min="1" 
                value="<?php echo esc_attr($number); ?>" 
                size="3"
            >
        </p>
        <?php
    }
    
    /**
     * Update widget with sanitization
     */
    public function update($new_instance, $old_instance) {
        $instance = [];
        
        $instance['title'] = !empty($new_instance['title']) 
            ? sanitize_text_field($new_instance['title']) 
            : '';
        
        $instance['number'] = !empty($new_instance['number']) 
            ? absint($new_instance['number']) 
            : 5;
        
        return $instance;
    }
}

// Register widget
function themename_register_widgets() {
    register_widget('Themename_Recent_Posts_Widget');
}
add_action('widgets_init', 'themename_register_widgets');
```

---

## 🚨 COMMON THEME SECURITY MISTAKES

### ❌ Bad Examples (NEVER DO THIS)

```php
<?php
// ❌ WRONG: No escaping
<h1><?php echo get_the_title(); ?></h1>

// ❌ WRONG: Direct output of user input
<div><?php echo $_GET['name']; ?></div>

// ❌ WRONG: No URL escaping
<a href="<?php echo home_url(); ?>">Home</a>

// ❌ WRONG: No attribute escaping
<div class="<?php echo $custom_class; ?>"></div>

// ❌ WRONG: No sanitization in Customizer
$wp_customize->add_setting('my_setting', [
    'default' => '',
    // Missing sanitize_callback!
]);

// ❌ WRONG: eval() or create_function()
eval($_POST['code']);
create_function('$a', 'return $a * 2;');
```

### ✅ Good Examples (ALWAYS DO THIS)

```php
<?php
// ✅ CORRECT: Escaped output
<h1><?php echo esc_html(get_the_title()); ?></h1>

// ✅ CORRECT: Sanitize then escape user input
<div><?php echo esc_html(sanitize_text_field($_GET['name'])); ?></div>

// ✅ CORRECT: URL escaping
<a href="<?php echo esc_url(home_url()); ?>">Home</a>

// ✅ CORRECT: Attribute escaping
<div class="<?php echo esc_attr($custom_class); ?>"></div>

// ✅ CORRECT: Sanitization in Customizer
$wp_customize->add_setting('my_setting', [
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
]);

// ✅ CORRECT: Use proper PHP functions
// No eval() - use proper code structure instead
```

---

## ✅ THEME SECURITY CHECKLIST

Before deploying your theme:

### Output Escaping
- [ ] All `get_the_title()` wrapped in `esc_html()`
- [ ] All `get_permalink()` wrapped in `esc_url()`
- [ ] All dynamic classes wrapped in `esc_attr()`
- [ ] All image alt text wrapped in `esc_attr()`
- [ ] All URLs wrapped in `esc_url()`
- [ ] Rich content uses `wp_kses_post()`
- [ ] Translations use escaping functions

### Customizer
- [ ] All settings have `sanitize_callback`
- [ ] Text fields use `sanitize_text_field()`
- [ ] URLs use `esc_url_raw()`
- [ ] Emails use `sanitize_email()`
- [ ] Rich text uses `wp_kses_post()`
- [ ] Select fields validate against allowed values
- [ ] Checkboxes return boolean

### Templates
- [ ] No direct `$_GET` or `$_POST` usage
- [ ] No `eval()` or `create_function()`
- [ ] No unescaped output
- [ ] All user input sanitized
- [ ] All database queries use `$wpdb->prepare()` (if any)

### Widgets
- [ ] Form fields escaped
- [ ] Update method sanitizes input
- [ ] Widget output escaped

### Navigation
- [ ] Custom walkers escape output
- [ ] Menu URLs escaped with `esc_url()`
- [ ] Menu titles escaped with `esc_html()`

---

## 🎯 QUICK REFERENCE

### When to Use Each Esc
<function_results>
<invoke name="artifacts">
<parameter name="command">update</parameter>
<parameter name="id">theme_security_md</parameter>
<parameter name="old_str">---
🎯 QUICK REFERENCE
When to Use Each Esc</parameter>
<parameter name="new_str">---
🎯 QUICK REFERENCE
When to Use Each Escaping Function
FunctionUse CaseExampleesc_html()Plain text contentPost titles, site namesesc_attr()HTML attributesclass, id, alt, titleesc_url()URLs in outputLinks, images, stylesheetsesc_js()JavaScript stringsInline script variableswp_kses_post()Rich HTML contentPost content, widget contentsanitize_text_field()Text inputForm fields, Customizersanitize_email()Email addressesEmail inputsesc_url_raw()URL sanitizationSaving URLs to databaseabsint()Positive integersPost IDs, counts
Escaping Flowchart
Dynamic Content?
    ↓
    YES → What type?
        ↓
        Text only → esc_html()
        HTML attribute → esc_attr()
        URL → esc_url()
        JavaScript → esc_js()
        Rich HTML → wp_kses_post()
    ↓
    NO → Output directly (static HTML)