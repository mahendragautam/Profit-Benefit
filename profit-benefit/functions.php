<?php
/**
 * ProfitBenefit Theme Functions
 *
 * @package ProfitBenefit_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Setup
 */
function profitbenefit_theme_setup() {
    // Add default posts and comments RSS feed links
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );

    // Add custom image sizes
    add_image_size( 'profitbenefit-hero', 1200, 600, true );
    add_image_size( 'profitbenefit-featured', 800, 500, true );
    add_image_size( 'profitbenefit-thumbnail', 150, 150, true );
    add_image_size( 'profitbenefit-trending', 400, 300, true );

    // Register navigation menus
    register_nav_menus( array(
        'top-menu'      => esc_html__( 'Top Menu', 'profitbenefit-theme' ),
        'main-menu'     => esc_html__( 'Main Menu', 'profitbenefit-theme' ),
        'footer-menu-1' => esc_html__( 'Footer Menu 1', 'profitbenefit-theme' ),
        'footer-menu-2' => esc_html__( 'Footer Menu 2', 'profitbenefit-theme' ),
        'footer-menu-3' => esc_html__( 'Footer Menu 3', 'profitbenefit-theme' ),
    ) );

    // HTML5 markup support
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Custom logo support
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Editor styles
    add_theme_support( 'editor-styles' );

    // Responsive embeds
    add_theme_support( 'responsive-embeds' );

    // Custom header
    add_theme_support( 'custom-header' );

    // Custom background
    add_theme_support( 'custom-background' );
}
add_action( 'after_setup_theme', 'profitbenefit_theme_setup' );

/**
 * Enqueue scripts and styles
 */
function profitbenefit_enqueue_scripts() {
    // Main stylesheet
    wp_enqueue_style(
        'profitbenefit-main-style',
        get_template_directory_uri() . '/assets/css/main.css',
        array(),
        filemtime( get_template_directory() . '/assets/css/main.css' )
    );

    // Theme stylesheet (required)
    wp_enqueue_style(
        'profitbenefit-style',
        get_stylesheet_uri(),
        array( 'profitbenefit-main-style' ),
        wp_get_theme()->get( 'Version' )
    );

    // Main JavaScript (if exists)
    if ( file_exists( get_template_directory() . '/assets/js/main.js' ) ) {
        wp_enqueue_script(
            'profitbenefit-main-js',
            get_template_directory_uri() . '/assets/js/main.js',
            array(),
            filemtime( get_template_directory() . '/assets/js/main.js' ),
            true
        );
    }

    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'profitbenefit_enqueue_scripts' );

/**
 * Register widget areas
 */
function profitbenefit_widgets_init() {
    // Main Sidebar
    register_sidebar( array(
        'name'          => esc_html__( 'Main Sidebar', 'profitbenefit-theme' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'profitbenefit-theme' ),
        'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    // Footer Widget Areas
    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array(
            'name'          => sprintf( esc_html__( 'Footer Widget %d', 'profitbenefit-theme' ), $i ),
            'id'            => 'footer-' . $i,
            'description'   => sprintf( esc_html__( 'Add widgets here to appear in footer column %d.', 'profitbenefit-theme' ), $i ),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>',
        ) );
    }
}
add_action( 'widgets_init', 'profitbenefit_widgets_init' );

/**
 * Custom excerpt length
 *
 * Sets the number of words displayed in post excerpts.
 *
 * @since 1.0.0
 * @param int $length Default excerpt length.
 * @return int Modified excerpt length (25 words).
 */
function profitbenefit_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'profitbenefit_excerpt_length' );

/**
 * Custom excerpt more
 *
 * Changes the default excerpt "read more" text.
 *
 * @since 1.0.0
 * @param string $more Default "read more" text.
 * @return string Modified "read more" text (...).
 */
function profitbenefit_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'profitbenefit_excerpt_more' );

/**
 * Calculate reading time
 *
 * Calculates estimated reading time based on word count.
 * Assumes average reading speed of 200 words per minute.
 *
 * @since 1.0.0
 * @return int Reading time in minutes.
 */
function profitbenefit_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil( $word_count / 200 );
    return $reading_time;
}

/**
 * Track post views
 *
 * Increments view counter in post meta when post is viewed.
 * Creates meta if it doesn't exist, updates if it does.
 *
 * @since 1.0.0
 * @param int $post_id Post ID to track views for.
 * @return void
 */
function profitbenefit_set_post_views( $post_id ) {
    $count_key = 'post_views_count';
    $count = get_post_meta( $post_id, $count_key, true );
    if ( $count == '' ) {
        $count = 0;
        delete_post_meta( $post_id, $count_key );
        add_post_meta( $post_id, $count_key, '0' );
    } else {
        $count++;
        update_post_meta( $post_id, $count_key, $count );
    }
}

/**
 * Get post views count
 *
 * Retrieves the number of times a post has been viewed.
 *
 * @since 1.0.0
 * @param int $post_id Post ID to get views for.
 * @return string View count (returns '0' if no views recorded).
 */
function profitbenefit_get_post_views( $post_id ) {
    $count_key = 'post_views_count';
    $count = get_post_meta( $post_id, $count_key, true );
    if ( $count == '' ) {
        return '0';
    }
    return $count;
}

/**
 * Get trending posts
 *
 * Retrieves posts ordered by view count.
 * Falls back to recent posts if no view data exists.
 *
 * @since 1.0.0
 * @param int $count Number of posts to retrieve. Default 8.
 * @return WP_Query Query object containing trending posts.
 */
function profitbenefit_get_trending_posts( $count = 8 ) {
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'meta_key'       => 'post_views_count',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );

    $trending_posts = new WP_Query( $args );

    if ( ! $trending_posts->have_posts() ) {
        // Fallback to recent posts
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => $count,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        $trending_posts = new WP_Query( $args );
    }

    return $trending_posts;
}

/**
 * Customizer settings
 *
 * Registers theme customizer settings for hero banner, footer, and social links.
 *
 * @since 1.0.0
 * @param WP_Customize_Manager $wp_customize WordPress Customizer Manager object.
 * @return void
 */
function profitbenefit_customize_register( $wp_customize ) {
    // Capability check - only allow users who can edit theme options
    if (!current_user_can('edit_theme_options')) {
        return;
    }

    // Header Email
    $wp_customize->add_setting( 'header_email', array(
        'default'           => '📧 hello@profitbenefit.com',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'header_email', array(
        'label'   => __( 'Header Email', 'profitbenefit-theme' ),
        'section' => 'title_tagline',
        'type'    => 'text',
    ) );

    // Footer Description
    $wp_customize->add_setting( 'footer_description', array(
        'default'           => 'Your trusted source for business tool reviews and comparisons.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'footer_description', array(
        'label'   => __( 'Footer Description', 'profitbenefit-theme' ),
        'section' => 'title_tagline',
        'type'    => 'textarea',
    ) );

    // Hero Banner Section
    $wp_customize->add_section( 'hero_banner_section', array(
        'title'    => __( 'Hero Banner Settings', 'profitbenefit-theme' ),
        'priority' => 30,
    ) );

    // Hero Highlight Text
    $wp_customize->add_setting( 'hero_highlight_text', array(
        'default'           => 'Stop Googling',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_highlight_text', array(
        'label'   => __( 'Hero Highlight Text', 'profitbenefit-theme' ),
        'section' => 'hero_banner_section',
        'type'    => 'text',
    ) );

    // Hero Title
    $wp_customize->add_setting( 'hero_title_text', array(
        'default'           => 'for Business Tools',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_title_text', array(
        'label'   => __( 'Hero Title Text', 'profitbenefit-theme' ),
        'section' => 'hero_banner_section',
        'type'    => 'text',
    ) );

    // Hero Subtitle
    $wp_customize->add_setting( 'hero_subtitle_text', array(
        'default'           => 'Everything in one place — ratings, prices, and real user reviews',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_subtitle_text', array(
        'label'   => __( 'Hero Subtitle', 'profitbenefit-theme' ),
        'section' => 'hero_banner_section',
        'type'    => 'text',
    ) );

    // Hero Button Text
    $wp_customize->add_setting( 'hero_button_text', array(
        'default'           => 'Browse Tools',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_button_text', array(
        'label'   => __( 'Hero Button Text', 'profitbenefit-theme' ),
        'section' => 'hero_banner_section',
        'type'    => 'text',
    ) );

    // Hero Button URL
    $wp_customize->add_setting( 'hero_button_url', array(
        'default'           => '#products',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'hero_button_url', array(
        'label'   => __( 'Hero Button URL', 'profitbenefit-theme' ),
        'section' => 'hero_banner_section',
        'type'    => 'url',
    ) );
}
add_action( 'customize_register', 'profitbenefit_customize_register' );

/**
 * Default menu fallback
 *
 * Provides a fallback menu when no menu is assigned.
 * Displays Home and Blog links.
 *
 * @since 1.0.0
 * @return void
 */
function profitbenefit_default_menu() {
    echo '<ul>';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'profitbenefit-theme' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/blog' ) ) . '">' . esc_html__( 'Blog', 'profitbenefit-theme' ) . '</a></li>';
    echo '</ul>';
}

/**
 * Get cached trending posts
 *
 * Retrieves trending posts with 1-hour cache for better performance.
 * Automatically clears cache when posts are updated.
 *
 * @since 1.0.0
 * @param int $count Number of posts to retrieve. Default 5.
 * @return WP_Query Query object containing cached trending posts.
 */
function profitbenefit_get_trending_posts_cached($count = 5) {
    $cache_key = 'profitbenefit_trending_' . $count;
    $trending = get_transient($cache_key);

    if (false === $trending) {
        $trending = profitbenefit_get_trending_posts($count);
        // Cache for 1 hour
        set_transient($cache_key, $trending, HOUR_IN_SECONDS);
    }

    return $trending;
}

/**
 * Clear trending posts cache
 *
 * Clears all trending posts caches when a post is saved or updated.
 * Ensures fresh data after content changes.
 *
 * @since 1.0.0
 * @param int $post_id Post ID being saved.
 * @return void
 */
function profitbenefit_clear_trending_cache($post_id) {
    // Don't clear cache for autosaves or revisions
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    // Clear all common trending post counts (3-8 posts)
    for ($i = 3; $i <= 8; $i++) {
        delete_transient('profitbenefit_trending_' . $i);
    }
}
add_action('save_post', 'profitbenefit_clear_trending_cache');
add_action('delete_post', 'profitbenefit_clear_trending_cache');

/**
 * Handle newsletter subscription
 *
 * Processes newsletter form submissions with nonce verification.
 * Validates email and can be extended to integrate with email services.
 *
 * @since 1.0.0
 * @return void
 */
function profitbenefit_handle_newsletter_subscribe() {
    // Check if form was submitted
    if (!isset($_POST['newsletter_submit'])) {
        return;
    }

    // Verify nonce
    if (!isset($_POST['newsletter_nonce']) ||
        !wp_verify_nonce($_POST['newsletter_nonce'], 'profitbenefit_newsletter_subscribe')) {
        return;
    }

    // Sanitize and validate email
    $email = isset($_POST['newsletter_email']) ? sanitize_email($_POST['newsletter_email']) : '';

    if (!is_email($email)) {
        return;
    }

    // Here you can add integration with email service (Mailchimp, etc.)
    // For now, just log it (you can extend this)

    // Example: Save to custom table or use WordPress options
    // update_option('profitbenefit_newsletter_subscribers', $email, false);

    // Redirect to prevent form resubmission
    wp_safe_redirect(add_query_arg('newsletter', 'subscribed', home_url('/')));
    exit;
}
add_action('template_redirect', 'profitbenefit_handle_newsletter_subscribe');

/**
 * Add Meta Boxes for Quick Summary and Top Pick
 *
 * Adds admin interface for enabling and configuring Quick Summary
 * and Top Pick widgets on single post pages.
 *
 * @since 1.0.0
 * @return void
 */
function profitbenefit_add_post_meta_boxes() {
    add_meta_box(
        'profitbenefit_quick_summary',
        'Quick Summary Box',
        'profitbenefit_quick_summary_callback',
        'post',
        'normal',
        'default'
    );

    add_meta_box(
        'profitbenefit_top_pick',
        'Editor\'s Choice / Top Pick',
        'profitbenefit_top_pick_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'profitbenefit_add_post_meta_boxes');

/**
 * Quick Summary Meta Box Callback
 *
 * Renders the admin interface for configuring Quick Summary box.
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object
 * @return void
 */
function profitbenefit_quick_summary_callback($post) {
    wp_nonce_field('profitbenefit_quick_summary_nonce', 'profitbenefit_quick_summary_nonce_field');

    $show_summary = get_post_meta($post->ID, '_show_quick_summary', true);
    $summary_1_title = get_post_meta($post->ID, '_summary_1_title', true);
    $summary_1_value = get_post_meta($post->ID, '_summary_1_value', true);
    $summary_2_title = get_post_meta($post->ID, '_summary_2_title', true);
    $summary_2_value = get_post_meta($post->ID, '_summary_2_value', true);
    $summary_3_title = get_post_meta($post->ID, '_summary_3_title', true);
    $summary_3_value = get_post_meta($post->ID, '_summary_3_value', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="_show_quick_summary" value="1" <?php checked($show_summary, '1'); ?>>
            Enable Quick Summary Box (for comparison/review posts)
        </label>
    </p>

    <div style="margin-top: 15px;">
        <h4>Summary Item 1</h4>
        <p>
            <label>Title:<br>
                <input type="text" name="_summary_1_title" value="<?php echo esc_attr($summary_1_title); ?>" style="width: 100%;" placeholder="e.g., Best Overall">
            </label>
        </p>
        <p>
            <label>Value:<br>
                <input type="text" name="_summary_1_value" value="<?php echo esc_attr($summary_1_value); ?>" style="width: 100%;" placeholder="e.g., DigitalOcean">
            </label>
        </p>
    </div>

    <div style="margin-top: 15px;">
        <h4>Summary Item 2</h4>
        <p>
            <label>Title:<br>
                <input type="text" name="_summary_2_title" value="<?php echo esc_attr($summary_2_title); ?>" style="width: 100%;" placeholder="e.g., Best for Beginners">
            </label>
        </p>
        <p>
            <label>Value:<br>
                <input type="text" name="_summary_2_value" value="<?php echo esc_attr($summary_2_value); ?>" style="width: 100%;" placeholder="e.g., Hostinger">
            </label>
        </p>
    </div>

    <div style="margin-top: 15px;">
        <h4>Summary Item 3</h4>
        <p>
            <label>Title:<br>
                <input type="text" name="_summary_3_title" value="<?php echo esc_attr($summary_3_title); ?>" style="width: 100%;" placeholder="e.g., Best Performance">
            </label>
        </p>
        <p>
            <label>Value:<br>
                <input type="text" name="_summary_3_value" value="<?php echo esc_attr($summary_3_value); ?>" style="width: 100%;" placeholder="e.g., Vultr">
            </label>
        </p>
    </div>
    <?php
}

/**
 * Top Pick Meta Box Callback
 *
 * Renders the admin interface for configuring Top Pick widget.
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object
 * @return void
 */
function profitbenefit_top_pick_callback($post) {
    wp_nonce_field('profitbenefit_top_pick_nonce', 'profitbenefit_top_pick_nonce_field');

    $show_top_pick = get_post_meta($post->ID, '_show_top_pick', true);
    $top_pick_name = get_post_meta($post->ID, '_top_pick_name', true);
    $top_pick_price = get_post_meta($post->ID, '_top_pick_price', true);
    $top_pick_url = get_post_meta($post->ID, '_top_pick_url', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="_show_top_pick" value="1" <?php checked($show_top_pick, '1'); ?>>
            Show Top Pick Widget
        </label>
    </p>

    <p>
        <label>Product/Service Name:<br>
            <input type="text" name="_top_pick_name" value="<?php echo esc_attr($top_pick_name); ?>" style="width: 100%;" placeholder="e.g., DigitalOcean">
        </label>
    </p>

    <p>
        <label>Starting Price:<br>
            <input type="text" name="_top_pick_price" value="<?php echo esc_attr($top_pick_price); ?>" style="width: 100%;" placeholder="e.g., $6/month">
        </label>
    </p>

    <p>
        <label>Affiliate URL:<br>
            <input type="url" name="_top_pick_url" value="<?php echo esc_attr($top_pick_url); ?>" style="width: 100%;" placeholder="https://example.com/affiliate-link">
        </label>
    </p>
    <?php
}

/**
 * Save Post Meta
 *
 * Saves Quick Summary and Top Pick meta data when post is saved.
 *
 * @since 1.0.0
 * @param int $post_id Post ID
 * @return void
 */
function profitbenefit_save_post_meta($post_id) {
    // Check if our nonces are set
    if (!isset($_POST['profitbenefit_quick_summary_nonce_field']) && !isset($_POST['profitbenefit_top_pick_nonce_field'])) {
        return;
    }

    // Verify nonces
    $quick_summary_nonce = isset($_POST['profitbenefit_quick_summary_nonce_field']) ? $_POST['profitbenefit_quick_summary_nonce_field'] : '';
    $top_pick_nonce = isset($_POST['profitbenefit_top_pick_nonce_field']) ? $_POST['profitbenefit_top_pick_nonce_field'] : '';

    if ($quick_summary_nonce && !wp_verify_nonce($quick_summary_nonce, 'profitbenefit_quick_summary_nonce')) {
        return;
    }

    if ($top_pick_nonce && !wp_verify_nonce($top_pick_nonce, 'profitbenefit_top_pick_nonce')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save Quick Summary fields
    $show_summary = isset($_POST['_show_quick_summary']) ? '1' : '0';
    update_post_meta($post_id, '_show_quick_summary', $show_summary);

    if (isset($_POST['_summary_1_title'])) {
        update_post_meta($post_id, '_summary_1_title', sanitize_text_field($_POST['_summary_1_title']));
    }
    if (isset($_POST['_summary_1_value'])) {
        update_post_meta($post_id, '_summary_1_value', sanitize_text_field($_POST['_summary_1_value']));
    }
    if (isset($_POST['_summary_2_title'])) {
        update_post_meta($post_id, '_summary_2_title', sanitize_text_field($_POST['_summary_2_title']));
    }
    if (isset($_POST['_summary_2_value'])) {
        update_post_meta($post_id, '_summary_2_value', sanitize_text_field($_POST['_summary_2_value']));
    }
    if (isset($_POST['_summary_3_title'])) {
        update_post_meta($post_id, '_summary_3_title', sanitize_text_field($_POST['_summary_3_title']));
    }
    if (isset($_POST['_summary_3_value'])) {
        update_post_meta($post_id, '_summary_3_value', sanitize_text_field($_POST['_summary_3_value']));
    }

    // Save Top Pick fields
    $show_top_pick = isset($_POST['_show_top_pick']) ? '1' : '0';
    update_post_meta($post_id, '_show_top_pick', $show_top_pick);

    if (isset($_POST['_top_pick_name'])) {
        update_post_meta($post_id, '_top_pick_name', sanitize_text_field($_POST['_top_pick_name']));
    }
    if (isset($_POST['_top_pick_price'])) {
        update_post_meta($post_id, '_top_pick_price', sanitize_text_field($_POST['_top_pick_price']));
    }
    if (isset($_POST['_top_pick_url'])) {
        update_post_meta($post_id, '_top_pick_url', esc_url_raw($_POST['_top_pick_url']));
    }
}
add_action('save_post', 'profitbenefit_save_post_meta');
