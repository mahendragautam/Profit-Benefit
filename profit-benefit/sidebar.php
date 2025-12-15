<?php
/**
 * Sidebar Template
 *
 * @package ProfitBenefit_Theme
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<div class="sidebar">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</div>
