<?php
/**
 * Sidebar Template
 *
 * @package ProfitBenefit_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<div class="sidebar">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</div>
