<?php
/**
 * Theme Header Template
 *
 * @package ProfitBenefit_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> style="scroll-behavior: smooth;">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo( 'name' ); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div>
                <span><?php echo date_i18n( 'l, F d, Y' ); ?></span>
            </div>
            <div style="display: flex; align-items: center; gap: 20px;">
                <div>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'top-menu',
                        'menu_class'     => '',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'items_wrap'     => '%3$s',
                        'link_before'    => '',
                        'link_after'     => '',
                    ) );
                    ?>
                </div>
                <div class="social-icons">
                    <a href="#">𝕏</a>
                    <a href="#">f</a>
                    <a href="#">in</a>
                    <a href="#">▶</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div>
                <span><?php echo date_i18n( 'l, F d, Y' ); ?></span>
            </div>
            <div style="display: flex; align-items: center; gap: 20px;">
                <div>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'top-menu',
                        'menu_class'     => '',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'items_wrap'     => '%3$s',
                        'link_before'    => '',
                        'link_after'     => '',
                    ) );
                    ?>
                </div>
                <div class="social-icons">
                    <a href="#">𝕏</a>
                    <a href="#">f</a>
                    <a href="#">in</a>
                    <a href="#">▶</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    ?>
                    <div class="logo">
                        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
                    </div>
                    <?php
                }
                ?>
                <nav>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'main-menu',
                        'menu_class'     => '',
                        'container'      => false,
                        'fallback_cb'    => 'profitbenefit_default_menu',
                    ) );
                    ?>
                </nav>
                <button class="search-btn" aria-label="<?php esc_attr_e('Search', 'profitbenefit-theme'); ?>">
                    <span class="search-icon"></span>
                </button>
            </div>
        </div>
    </header>
