<?php
/**
 * Page Template
 *
 * @package ProfitBenefit_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

    <div class="page-header">
        <div class="container">
            <h1 class="page-title"><?php the_title(); ?></h1>
        </div>
    </div>

    <div class="main-content">
        <div class="container">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    the_content();
                endwhile;
            else :
                echo '<p>' . esc_html__( 'No content found.', 'profitbenefit-theme' ) . '</p>';
            endif;
            ?>
        </div>
    </div>

<?php
get_footer();
