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
    // Google Fonts
    wp_enqueue_style(
        'profitbenefit-google-fonts',
        'https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=DM+Sans:wght@400;500;700&display=swap',
        array(),
        null
    );

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
 */
function profitbenefit_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'profitbenefit_excerpt_length' );

/**
 * Custom excerpt more
 */
function profitbenefit_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'profitbenefit_excerpt_more' );

/**
 * Calculate reading time
 */
function profitbenefit_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil( $word_count / 200 );
    return $reading_time;
}

/**
 * Track post views
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
 */
function profitbenefit_customize_register( $wp_customize ) {
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
 */
function profitbenefit_default_menu() {
    echo '<ul>';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'profitbenefit-theme' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/blog' ) ) . '">' . esc_html__( 'Blog', 'profitbenefit-theme' ) . '</a></li>';
    echo '</ul>';
}
