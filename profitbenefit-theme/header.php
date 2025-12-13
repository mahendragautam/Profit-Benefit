<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div><?php echo esc_html( get_theme_mod( 'header_email', '📧 hello@profitbenefit.com' ) ); ?></div>
            <div class="top-bar-links">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'top-menu',
                    'menu_class'     => '',
                    'container'      => false,
                    'fallback_cb'    => false,
                ) );
                ?>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="200" viewBox="0 0 1500 449.999984">
                        <path fill="#2d6a4f" d="M 312.511719 182.871094 L 312.511719 363.582031 L 214.480469 363.582031 L 214.480469 182.871094 L 132.160156 182.871094 L 132.160156 104.933594 L 394.832031 104.933594 L 394.832031 182.871094 Z M 312.511719 182.871094"/>
                        <text fill="#2d6a4f" font-family="Arial, sans-serif" font-size="180" font-weight="bold" x="380" y="360">Benefit.co</text>
                        <text fill="#8895a7" font-family="Arial, sans-serif" font-size="120" font-weight="300" x="380" y="210">Tools.</text>
                    </svg>
                    <?php
                }
                ?>
            </a>
            <nav class="main-nav">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'main-menu',
                    'menu_class'     => '',
                    'container'      => false,
                    'fallback_cb'    => 'profitbenefit_default_menu',
                ) );
                ?>
            </nav>
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">☰</button>
        </div>
    </header>
