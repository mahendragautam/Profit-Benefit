    <!-- Footer -->
    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <h3><?php bloginfo( 'name' ); ?></h3>
                <p><?php echo esc_html( get_theme_mod( 'footer_description', 'Your trusted directory for discovering the best business tools, software reviews, and productivity resources to grow your business.' ) ); ?></p>
                <div class="footer-social">
                    <a href="#" aria-label="Twitter">𝕏</a>
                    <a href="#" aria-label="Facebook">f</a>
                    <a href="#" aria-label="LinkedIn">in</a>
                    <a href="#" aria-label="YouTube">▶</a>
                </div>
            </div>

            <div class="footer-column">
                <h4><?php esc_html_e( 'Categories', 'profitbenefit-theme' ); ?></h4>
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer-menu-1',
                    'menu_class'     => '',
                    'container'      => 'ul',
                    'fallback_cb'    => false,
                ) );
                ?>
            </div>

            <div class="footer-column">
                <h4><?php esc_html_e( 'Company', 'profitbenefit-theme' ); ?></h4>
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer-menu-2',
                    'menu_class'     => '',
                    'container'      => 'ul',
                    'fallback_cb'    => false,
                ) );
                ?>
            </div>

            <div class="footer-column">
                <h4><?php esc_html_e( 'Legal', 'profitbenefit-theme' ); ?></h4>
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer-menu-3',
                    'menu_class'     => '',
                    'container'      => 'ul',
                    'fallback_cb'    => false,
                ) );
                ?>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'profitbenefit-theme' ); ?></p>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
