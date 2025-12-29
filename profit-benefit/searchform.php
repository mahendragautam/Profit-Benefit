<?php
/**
 * Search Form Template
 *
 * @package ProfitBenefit_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text"><?php esc_html_e('Search for:', 'profitbenefit-theme'); ?></span>
        <input type="search"
               class="search-field"
               placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'profitbenefit-theme'); ?>"
               value="<?php echo esc_attr(get_search_query()); ?>"
               name="s" />
    </label>
    <button type="submit" class="search-submit">
        <?php esc_html_e('Search', 'profitbenefit-theme'); ?>
    </button>
</form>
