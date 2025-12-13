    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-widget">
                    <h3><?php bloginfo( 'name' ); ?></h3>
                    <p><?php echo esc_html( get_theme_mod( 'footer_description', 'Your trusted source for business tool reviews and comparisons. Find the perfect software for your needs.' ) ); ?></p>
                </div>

                <div class="footer-widget">
                    <h3><?php esc_html_e( 'Quick Links', 'profitbenefit-theme' ); ?></h3>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer-menu-1',
                        'menu_class'     => 'footer-links',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ) );
                    ?>
                </div>

                <div class="footer-widget">
                    <h3><?php esc_html_e( 'Categories', 'profitbenefit-theme' ); ?></h3>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer-menu-2',
                        'menu_class'     => 'footer-links',
                        'container'      => false,
                        'fallback_cb'    => 'profitbenefit_footer_categories',
                    ) );
                    ?>
                </div>

                <div class="footer-widget">
                    <h3><?php esc_html_e( 'Legal', 'profitbenefit-theme' ); ?></h3>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer-menu-3',
                        'menu_class'     => 'footer-links',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ) );
                    ?>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?> - <?php esc_html_e( 'All rights reserved', 'profitbenefit-theme' ); ?></p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
