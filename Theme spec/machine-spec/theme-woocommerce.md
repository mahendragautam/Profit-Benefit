---
```markdown
# WooCommerce Theme Development — Compatibility & Templates

Load when: WooCommerce, shop, product, cart, checkout, e-commerce

Purpose: Ensure the theme integrates with WooCommerce templates, supports shop pages, and avoids styling/markup conflicts.

Integration rules

- Declare WooCommerce support only when the theme styles and templates are tested: `add_theme_support( 'woocommerce' );`.
- Provide template compatibility via `woocommerce.php` or use WooCommerce template wrappers to avoid overriding templates unless necessary.

Template overrides

- If overriding WooCommerce templates in `woocommerce/`, keep a manifest of overridden files and track upstream changes.

Cart and checkout

- Ensure forms on cart/checkout are accessible and that payment pages use HTTPS and server-side validation.

Styling

- Avoid CSS specificity that breaks WooCommerce components. scope theme styles where possible and allow WooCommerce classes to render correctly.

Performance

- Lazy load product images on archive pages, but ensure LCP product image is prioritized.

Testing

- Test shop, product, cart, checkout, account pages with sample data. Run accessibility checks on checkout flow.

```