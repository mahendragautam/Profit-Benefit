---
name: woocommerce
hidden: true
protected: true
undeletable: true
always_on: false
---

# WooCommerce Theme Development - Complete Guide

> **Load this file when**: Keywords detected - WooCommerce, shop, product, cart, checkout, ecommerce
>
> **Purpose**: Comprehensive WooCommerce theme integration patterns
>
> **Version**: 2.0 | **Last Updated**: December 2025

---

## 1. BASIC WOOCOMMERCE SUPPORT

### Enable WooCommerce in Theme

```php
<?php
/**
 * functions.php - Enable WooCommerce support
 */

function themename_woocommerce_support() {
    add_theme_support('woocommerce');

    // Enable product gallery features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'themename_woocommerce_support');
```

### Advanced Theme Support

```php
<?php
/**
 * Advanced WooCommerce theme support with custom image sizes
 */

function themename_woocommerce_setup() {
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => [
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ],
    ]);

    // Gallery features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'themename_woocommerce_setup');

/**
 * Custom image sizes for products
 */
function themename_woocommerce_image_sizes() {
    // Product thumbnails
    add_image_size('themename-woo-thumbnail', 300, 300, true);

    // Product single images
    add_image_size('themename-woo-single', 600, 600, true);

    // Product catalog images
    add_image_size('themename-woo-catalog', 400, 400, true);
}
add_action('after_setup_theme', 'themename_woocommerce_image_sizes');
```

### WooCommerce-Specific Enqueuing

```php
<?php
/**
 * Enqueue WooCommerce-specific styles and scripts
 */

function themename_woocommerce_scripts() {
    // Only load on WooCommerce pages
    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {

        // WooCommerce stylesheet
        wp_enqueue_style(
            'themename-woocommerce',
            get_template_directory_uri() . '/assets/css/woocommerce.min.css',
            [],
            wp_get_theme()->get('Version')
        );

        // WooCommerce scripts
        wp_enqueue_script(
            'themename-woocommerce',
            get_template_directory_uri() . '/assets/js/woocommerce.min.js',
            ['jquery'],
            wp_get_theme()->get('Version'),
            true
        );

        // Localize script for AJAX
        wp_localize_script('themename-woocommerce', 'themeNameWoo', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('themename-woo-nonce'),
            'cartUrl' => wc_get_cart_url(),
        ]);
    }
}
add_action('wp_enqueue_scripts', 'themename_woocommerce_scripts');
```

---

## 2. TEMPLATE STRUCTURE & HIERARCHY

### WooCommerce Template Hierarchy

```
WooCommerce Template Hierarchy:
1. Theme template (woocommerce/single-product.php)
2. Parent theme template
3. WooCommerce plugin template

Override Order:
/wp-content/themes/your-theme/woocommerce/single-product.php
→ Overrides
/wp-content/plugins/woocommerce/templates/single-product.php
```

### Recommended Template Structure

```
/wp-content/themes/your-theme/
├── woocommerce/
│   ├── archive-product.php           # Shop page
│   ├── single-product.php            # Single product
│   ├── cart/
│   │   ├── cart.php                  # Cart page
│   │   └── mini-cart.php             # Mini cart widget
│   ├── checkout/
│   │   ├── form-checkout.php         # Checkout form
│   │   ├── form-billing.php          # Billing fields
│   │   ├── form-shipping.php         # Shipping fields
│   │   └── thankyou.php              # Order received
│   ├── myaccount/
│   │   ├── my-account.php            # Account dashboard
│   │   ├── dashboard.php             # Dashboard content
│   │   ├── orders.php                # Orders list
│   │   └── form-login.php            # Login form
│   ├── single-product/
│   │   ├── product-image.php         # Product image gallery
│   │   ├── product-thumbnails.php    # Thumbnail images
│   │   ├── title.php                 # Product title
│   │   ├── price.php                 # Product price
│   │   ├── add-to-cart/
│   │   │   ├── simple.php            # Simple product button
│   │   │   ├── variable.php          # Variable product
│   │   │   └── grouped.php           # Grouped product
│   │   ├── meta.php                  # Product meta
│   │   ├── short-description.php     # Short description
│   │   ├── tabs/
│   │   │   ├── tabs.php              # Product tabs
│   │   │   ├── description.php       # Description tab
│   │   │   └── additional-information.php
│   │   └── related.php               # Related products
│   ├── content-product.php           # Product loop item
│   ├── content-single-product.php    # Single product content
│   └── global/
│       ├── breadcrumb.php            # Breadcrumbs
│       ├── quantity-input.php        # Quantity selector
│       └── wrapper-start.php         # Container start
```

---

## 3. SHOP PAGE CUSTOMIZATION

### Override Shop Layout

```php
<?php
/**
 * woocommerce/archive-product.php - Custom shop page
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop');
?>

<div class="woocommerce-shop-page">

    <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
        <h1 class="woocommerce-products-header__title page-title">
            <?php echo esc_html(woocommerce_page_title(false)); ?>
        </h1>
    <?php endif; ?>

    <?php
    /**
     * Hook: woocommerce_archive_description
     *
     * @hooked woocommerce_taxonomy_archive_description - 10
     * @hooked woocommerce_product_archive_description - 10
     */
    do_action('woocommerce_archive_description');
    ?>

    <?php if (woocommerce_product_loop()) : ?>

        <?php
        /**
         * Hook: woocommerce_before_shop_loop
         *
         * @hooked woocommerce_output_all_notices - 10
         * @hooked woocommerce_result_count - 20
         * @hooked woocommerce_catalog_ordering - 30
         */
        do_action('woocommerce_before_shop_loop');
        ?>

        <?php woocommerce_product_loop_start(); ?>

        <?php
        if (wc_get_loop_prop('total')) {
            while (have_posts()) {
                the_post();

                /**
                 * Hook: woocommerce_shop_loop
                 */
                do_action('woocommerce_shop_loop');

                wc_get_template_part('content', 'product');
            }
        }
        ?>

        <?php woocommerce_product_loop_end(); ?>

        <?php
        /**
         * Hook: woocommerce_after_shop_loop
         *
         * @hooked woocommerce_pagination - 10
         */
        do_action('woocommerce_after_shop_loop');
        ?>

    <?php else : ?>

        <?php
        /**
         * Hook: woocommerce_no_products_found
         *
         * @hooked wc_no_products_found - 10
         */
        do_action('woocommerce_no_products_found');
        ?>

    <?php endif; ?>

</div>

<?php
get_footer('shop');
```

### Customize Products Per Page

```php
<?php
/**
 * Change products per page
 */
function themename_products_per_page() {
    return 12; // Show 12 products per page
}
add_filter('loop_shop_per_page', 'themename_products_per_page', 20);
```

### Customize Columns

```php
<?php
/**
 * Change shop columns
 */
function themename_shop_columns() {
    return 4; // 4 columns
}
add_filter('loop_shop_columns', 'themename_shop_columns');
```

---

## 4. PRODUCT LOOP CUSTOMIZATION

### Custom Product Loop Item

```php
<?php
/**
 * woocommerce/content-product.php - Product loop item
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

// Ensure visibility
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<li <?php wc_product_class('product-item', $product); ?>>

    <div class="product-item__wrapper">

        <!-- Product Image -->
        <div class="product-item__image">
            <a href="<?php echo esc_url(get_permalink()); ?>">
                <?php echo wp_kses_post($product->get_image('medium')); ?>
            </a>

            <!-- Sale Badge -->
            <?php if ($product->is_on_sale()) : ?>
                <span class="product-item__badge sale">
                    <?php esc_html_e('Sale!', 'themename'); ?>
                </span>
            <?php endif; ?>

            <!-- Quick View Button (custom) -->
            <button class="product-item__quick-view"
                    data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                <?php esc_html_e('Quick View', 'themename'); ?>
            </button>
        </div>

        <!-- Product Details -->
        <div class="product-item__details">

            <!-- Category -->
            <?php
            $categories = get_the_terms($product->get_id(), 'product_cat');
            if ($categories && !is_wp_error($categories)) :
            ?>
                <div class="product-item__category">
                    <?php echo esc_html($categories[0]->name); ?>
                </div>
            <?php endif; ?>

            <!-- Title -->
            <h3 class="product-item__title">
                <a href="<?php echo esc_url(get_permalink()); ?>">
                    <?php echo esc_html(get_the_title()); ?>
                </a>
            </h3>

            <!-- Rating -->
            <?php if ($rating_html = wc_get_rating_html($product->get_average_rating())) : ?>
                <div class="product-item__rating">
                    <?php echo wp_kses_post($rating_html); ?>
                </div>
            <?php endif; ?>

            <!-- Price -->
            <div class="product-item__price">
                <?php echo wp_kses_post($product->get_price_html()); ?>
            </div>

            <!-- Add to Cart -->
            <div class="product-item__add-to-cart">
                <?php woocommerce_template_loop_add_to_cart(); ?>
            </div>

        </div>

    </div>

</li>
```

---

## 5. SINGLE PRODUCT PAGE

### Custom Single Product Layout

```php
<?php
/**
 * woocommerce/single-product.php - Single product page
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop');

while (have_posts()) :
    the_post();
    wc_get_template_part('content', 'single-product');
endwhile;

get_footer('shop');
```

### Customize Product Tabs

```php
<?php
/**
 * Add custom tab to product page
 */
function themename_custom_product_tab($tabs) {
    // Add shipping tab
    $tabs['shipping'] = [
        'title'    => __('Shipping Info', 'themename'),
        'priority' => 50,
        'callback' => 'themename_shipping_tab_content',
    ];

    // Remove reviews tab (optional)
    // unset($tabs['reviews']);

    // Reorder tabs
    $tabs['description']['priority'] = 10;
    $tabs['additional_information']['priority'] = 20;
    $tabs['reviews']['priority'] = 30;

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'themename_custom_product_tab');

/**
 * Shipping tab content
 */
function themename_shipping_tab_content() {
    ?>
    <h2><?php esc_html_e('Shipping Information', 'themename'); ?></h2>
    <p><?php esc_html_e('Free shipping on orders over $50.', 'themename'); ?></p>
    <p><?php esc_html_e('Estimated delivery: 3-5 business days.', 'themename'); ?></p>
    <?php
}
```

### Customize Product Gallery

```php
<?php
/**
 * Customize gallery thumbnail columns
 */
function themename_gallery_thumbnail_columns() {
    return 4; // 4 thumbnails
}
add_filter('woocommerce_product_thumbnails_columns', 'themename_gallery_thumbnail_columns');

/**
 * Customize gallery image size
 */
function themename_gallery_image_size() {
    return 'woocommerce_single';
}
add_filter('woocommerce_gallery_image_size', 'themename_gallery_image_size');
```

---

## 6. CART PAGE CUSTOMIZATION

### AJAX Add to Cart

```javascript
// assets/js/woocommerce.js
(function($) {
    'use strict';

    // AJAX Add to Cart
    $(document).on('click', '.ajax_add_to_cart', function(e) {
        e.preventDefault();

        const button = $(this);
        const productId = button.data('product_id');

        button.addClass('loading').prop('disabled', true);

        $.ajax({
            url: themeNameWoo.ajaxUrl,
            type: 'POST',
            data: {
                action: 'themename_ajax_add_to_cart',
                nonce: themeNameWoo.nonce,
                product_id: productId,
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    $('.cart-count').text(response.data.cart_count);

                    // Show notification
                    showNotification('Product added to cart!');

                    // Trigger WooCommerce event
                    $(document.body).trigger('added_to_cart', [
                        response.data.fragments,
                        response.data.cart_hash,
                        button
                    ]);
                }
            },
            complete: function() {
                button.removeClass('loading').prop('disabled', false);
            }
        });
    });

    // Update cart quantities
    $(document).on('change', '.cart-item__quantity input', function() {
        const input = $(this);
        const cartItemKey = input.data('cart-item-key');
        const quantity = input.val();

        updateCartQuantity(cartItemKey, quantity);
    });

    function updateCartQuantity(cartItemKey, quantity) {
        $.ajax({
            url: themeNameWoo.ajaxUrl,
            type: 'POST',
            data: {
                action: 'themename_update_cart_quantity',
                nonce: themeNameWoo.nonce,
                cart_item_key: cartItemKey,
                quantity: quantity,
            },
            success: function(response) {
                if (response.success) {
                    // Update cart totals
                    $('.cart-subtotal').html(response.data.subtotal);
                    $('.cart-total').html(response.data.total);
                }
            }
        });
    }

})(jQuery);
```

### AJAX Cart Handlers

```php
<?php
/**
 * AJAX add to cart
 */
function themename_ajax_add_to_cart() {
    check_ajax_referer('themename-woo-nonce', 'nonce');

    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

    if (!$product_id) {
        wp_send_json_error(['message' => __('Invalid product', 'themename')]);
    }

    $added = WC()->cart->add_to_cart($product_id, 1);

    if ($added) {
        wp_send_json_success([
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'fragments'  => apply_filters('woocommerce_add_to_cart_fragments', []),
            'cart_hash'  => WC()->cart->get_cart_hash(),
        ]);
    } else {
        wp_send_json_error(['message' => __('Could not add product', 'themename')]);
    }
}
add_action('wp_ajax_themename_ajax_add_to_cart', 'themename_ajax_add_to_cart');
add_action('wp_ajax_nopriv_themename_ajax_add_to_cart', 'themename_ajax_add_to_cart');

/**
 * AJAX update cart quantity
 */
function themename_update_cart_quantity() {
    check_ajax_referer('themename-woo-nonce', 'nonce');

    $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field($_POST['cart_item_key']) : '';
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

    if (!$cart_item_key) {
        wp_send_json_error();
    }

    WC()->cart->set_quantity($cart_item_key, $quantity);
    WC()->cart->calculate_totals();

    wp_send_json_success([
        'subtotal' => WC()->cart->get_cart_subtotal(),
        'total'    => WC()->cart->get_total(),
    ]);
}
add_action('wp_ajax_themename_update_cart_quantity', 'themename_update_cart_quantity');
add_action('wp_ajax_nopriv_themename_update_cart_quantity', 'themename_update_cart_quantity');
```

### Mini Cart Widget

```php
<?php
/**
 * Custom mini cart
 */
function themename_mini_cart() {
    ?>
    <div class="mini-cart">
        <button class="mini-cart__toggle">
            <span class="mini-cart__icon">🛒</span>
            <span class="mini-cart__count cart-count">
                <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
            </span>
        </button>

        <div class="mini-cart__dropdown">
            <?php woocommerce_mini_cart(); ?>
        </div>
    </div>
    <?php
}
```

---

## 7. CHECKOUT CUSTOMIZATION

### Customize Checkout Fields

```php
<?php
/**
 * Customize checkout fields
 */
function themename_customize_checkout_fields($fields) {
    // Make phone optional
    $fields['billing']['billing_phone']['required'] = false;

    // Change field placeholder
    $fields['billing']['billing_first_name']['placeholder'] = __('First Name', 'themename');

    // Remove field
    unset($fields['billing']['billing_company']);

    // Add custom field
    $fields['billing']['billing_custom_field'] = [
        'type'        => 'text',
        'label'       => __('Custom Field', 'themename'),
        'placeholder' => __('Enter custom data', 'themename'),
        'required'    => false,
        'class'       => ['form-row-wide'],
        'priority'    => 25,
    ];

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'themename_customize_checkout_fields');
```

### Add Custom Checkout Step

```php
<?php
/**
 * Add gift message field to checkout
 */
function themename_add_gift_message_field() {
    ?>
    <div class="checkout-gift-message">
        <h3><?php esc_html_e('Gift Message', 'themename'); ?></h3>
        <p>
            <textarea
                name="gift_message"
                class="input-text"
                placeholder="<?php esc_attr_e('Optional gift message', 'themename'); ?>"
                rows="4"
            ></textarea>
        </p>
    </div>
    <?php
}
add_action('woocommerce_after_order_notes', 'themename_add_gift_message_field');

/**
 * Save gift message
 */
function themename_save_gift_message($order_id) {
    if (isset($_POST['gift_message']) && !empty($_POST['gift_message'])) {
        $gift_message = sanitize_textarea_field($_POST['gift_message']);
        update_post_meta($order_id, '_gift_message', $gift_message);
    }
}
add_action('woocommerce_checkout_update_order_meta', 'themename_save_gift_message');
```

---

## 8. MY ACCOUNT PAGE

### Custom Account Menu Items

```php
<?php
/**
 * Add custom account menu item
 */
function themename_custom_account_menu_items($items) {
    // Remove downloads
    unset($items['downloads']);

    // Add custom item before logout
    $logout = $items['customer-logout'];
    unset($items['customer-logout']);

    $items['wishlist'] = __('Wishlist', 'themename');
    $items['customer-logout'] = $logout;

    return $items;
}
add_filter('woocommerce_account_menu_items', 'themename_custom_account_menu_items');

/**
 * Register custom endpoint
 */
function themename_register_wishlist_endpoint() {
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
}
add_action('init', 'themename_register_wishlist_endpoint');

/**
 * Wishlist endpoint content
 */
function themename_wishlist_endpoint_content() {
    ?>
    <div class="woocommerce-MyAccount-wishlist">
        <h2><?php esc_html_e('My Wishlist', 'themename'); ?></h2>
        <p><?php esc_html_e('Your saved products appear here.', 'themename'); ?></p>
    </div>
    <?php
}
add_action('woocommerce_account_wishlist_endpoint', 'themename_wishlist_endpoint_content');
```

---

## 9. WOOCOMMERCE HOOKS REFERENCE

### Most Useful Hooks

#### Shop/Archive Hooks
```php
// Before main content
add_action('woocommerce_before_main_content', 'callback');

// After main content
add_action('woocommerce_after_main_content', 'callback');

// Before shop loop
add_action('woocommerce_before_shop_loop', 'callback');

// After shop loop
add_action('woocommerce_after_shop_loop', 'callback');

// Before shop loop item
add_action('woocommerce_before_shop_loop_item', 'callback');

// After shop loop item
add_action('woocommerce_after_shop_loop_item', 'callback');
```

#### Single Product Hooks
```php
// Before single product
add_action('woocommerce_before_single_product', 'callback');

// Before product summary
add_action('woocommerce_before_single_product_summary', 'callback');

// Product summary
add_action('woocommerce_single_product_summary', 'callback', priority);

// After product summary
add_action('woocommerce_after_single_product_summary', 'callback');

// After single product
add_action('woocommerce_after_single_product', 'callback');
```

#### Cart Hooks
```php
// Before cart
add_action('woocommerce_before_cart', 'callback');

// Before cart table
add_action('woocommerce_before_cart_table', 'callback');

// Cart contents
add_action('woocommerce_cart_contents', 'callback');

// After cart
add_action('woocommerce_after_cart', 'callback');
```

#### Checkout Hooks
```php
// Before checkout form
add_action('woocommerce_before_checkout_form', 'callback');

// After checkout form
add_action('woocommerce_after_checkout_form', 'callback');

// Review order before payment
add_action('woocommerce_review_order_before_payment', 'callback');

// After checkout
add_action('woocommerce_after_checkout_billing_form', 'callback');
```

---

## 10. WOOCOMMERCE CONDITIONAL TAGS

```php
<?php
// Shop page
if (is_shop()) { }

// Product category
if (is_product_category()) { }

// Product tag
if (is_product_tag()) { }

// Single product
if (is_product()) { }

// Cart page
if (is_cart()) { }

// Checkout page
if (is_checkout()) { }

// Account page
if (is_account_page()) { }

// Any WooCommerce page
if (is_woocommerce()) { }

// On sale
if ($product->is_on_sale()) { }

// In stock
if ($product->is_in_stock()) { }

// Purchasable
if ($product->is_purchasable()) { }
```

---

## 11. WOOCOMMERCE BLOCKS SUPPORT

### Register Block Patterns

```php
<?php
/**
 * Register WooCommerce block patterns
 */
function themename_register_woo_patterns() {
    if (function_exists('register_block_pattern')) {
        register_block_pattern(
            'themename/product-hero',
            [
                'title'       => __('Product Hero Section', 'themename'),
                'description' => __('Hero section with featured product', 'themename'),
                'categories'  => ['woocommerce'],
                'content'     => '<!-- wp:woocommerce/featured-product /-->',
            ]
        );
    }
}
add_action('init', 'themename_register_woo_patterns');
```

### Style WooCommerce Blocks

```css
/* WooCommerce Blocks Styling */

/* Products Block */
.wp-block-woocommerce-products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 2rem;
}

/* Product Grid */
.wc-block-grid__products {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
}

@media (max-width: 768px) {
    .wc-block-grid__products {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Cart Block */
.wp-block-woocommerce-cart {
    max-width: 1200px;
    margin: 0 auto;
}

/* Checkout Block */
.wp-block-woocommerce-checkout {
    max-width: 1200px;
    margin: 0 auto;
}
```

---

## 12. PERFORMANCE OPTIMIZATION

### Disable WooCommerce Scripts on Non-Shop Pages

```php
<?php
/**
 * Disable WooCommerce scripts on non-shop pages
 */
function themename_disable_woo_scripts() {
    if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
        // Disable WooCommerce styles
        wp_dequeue_style('woocommerce-general');
        wp_dequeue_style('woocommerce-layout');
        wp_dequeue_style('woocommerce-smallscreen');

        // Disable WooCommerce scripts
        wp_dequeue_script('wc-cart-fragments');
        wp_dequeue_script('woocommerce');
        wp_dequeue_script('wc-add-to-cart');
    }
}
add_action('wp_enqueue_scripts', 'themename_disable_woo_scripts', 99);
```

### Optimize Product Queries

```php
<?php
/**
 * Optimize related products query
 */
function themename_related_products_args($args) {
    $args['posts_per_page'] = 4; // Show only 4 related products
    $args['columns'] = 4;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'themename_related_products_args');
```

---

## 13. TESTING CHECKLIST

### WooCommerce Theme Testing

```
Shop Page:
✓ Products display correctly
✓ Grid layout responsive
✓ Pagination works
✓ Sorting works
✓ Filtering works (if added)
✓ Product images load

Single Product:
✓ Gallery works (zoom, lightbox, slider)
✓ Variations work (if variable product)
✓ Add to cart works
✓ Quantity selector works
✓ Product tabs display
✓ Related products show

Cart:
✓ Add to cart works
✓ Update quantities works
✓ Remove items works
✓ Cart totals correct
✓ Proceed to checkout works
✓ Mini cart updates

Checkout:
✓ Billing fields work
✓ Shipping fields work
✓ Payment methods display
✓ Place order works
✓ Order received page shows
✓ Email notifications sent

My Account:
✓ Login/register works
✓ Dashboard displays
✓ Orders list shows
✓ Edit account works
✓ Logout works
```

---

## ✅ WOOCOMMERCE THEME CHECKLIST

### Before Release:

- [ ] WooCommerce support enabled
- [ ] Product gallery features working
- [ ] Shop page layout responsive
- [ ] Single product page customized
- [ ] Cart page functional
- [ ] Checkout page functional
- [ ] My Account page working
- [ ] WooCommerce hooks properly used
- [ ] AJAX cart functional (if implemented)
- [ ] Payment gateways tested
- [ ] Email templates styled (optional)
- [ ] WooCommerce blocks supported
- [ ] Mobile checkout optimized
- [ ] Performance optimized
- [ ] Security verified (nonces, escaping)

---

**Complete WooCommerce theme integration guide!** 🛒
