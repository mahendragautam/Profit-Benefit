---
```markdown
# Classic Theme Development — Templates & Functions

Load when: template, theme structure, functions.php, template hierarchy, child theme

Purpose: Provide clear, production-ready rules for classic (PHP template) themes, including required headers, templates, and best practices for `functions.php`.

Required files and headers

- `style.css` must include the theme header with `Theme Name`, `Author`, `Version`, and `Text Domain`.
- Provide `index.php` and at least one template (e.g. `single.php` or `page.php`).

`functions.php` guidelines

- Use action/filter hooks properly; do not run heavy queries on load. Wrap feature detection with `function_exists` where necessary.
- Example enqueue:

```php
function pb_enqueue_scripts() {
	wp_enqueue_style( 'pb-style', get_stylesheet_uri(), [], '1.0' );
	wp_enqueue_script( 'pb-main', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'pb_enqueue_scripts' );
```

Template hierarchy and child themes

- Follow WordPress template hierarchy. Ship a `screenshot.png` for the theme preview.
- Support child themes by avoiding hardcoding paths and using `get_template_directory()` vs `get_stylesheet_directory()` appropriately.

Localization

- Load theme textdomain in `after_setup_theme`:

```php
function pb_setup() {
	load_theme_textdomain( 'profit-benefit', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'pb_setup' );
```

Security & performance tips

- Escape outputs with `esc_html`, `esc_attr`, or `wp_kses_post` as appropriate.
- Avoid expensive queries in `functions.php` on every page load; use transients or hooks when possible.

```