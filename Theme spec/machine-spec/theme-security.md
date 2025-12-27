---
```markdown
# Theme Security — Escaping, Sanitization, and Best Practices

Load when: security, sanitize, escape, XSS, nonce, capability

Purpose: Provide concrete coding rules for secure theme development to prevent XSS, CSRF, and other common vulnerabilities.

Output escaping

- Escape all data printed to HTML. Use the appropriate escaping function:
	- `esc_html( $value )` for HTML body text
	- `esc_attr( $value )` for attribute values
	- `esc_url( $url )` for URLs
	- `wp_kses_post( $html )` for allowed HTML from trusted sources

Example:

```php
echo '<h1>' . esc_html( get_the_title() ) . '</h1>';
```

Input sanitization

- Sanitize data before saving to the database. Use `sanitize_text_field`, `wp_kses_post`, `intval`, or `sanitize_email` as appropriate.

Example on saving post meta:

```php
update_post_meta( $post_id, '_rating', intval( $_POST['rating'] ?? 0 ) );
```

Nonces and capability checks

- Protect state-changing operations with `check_admin_referer()` or `wp_verify_nonce()`.
- Always verify user capabilities with `current_user_can()` before allowing privileged actions.

Files and uploads

- Do not trust uploaded file names or MIME types; use `wp_handle_upload()` and verify via file type checks.

Security checklist

- No direct database queries without prepared statements or proper escaping.
- All template outputs escaped.
- Nonces and capability checks present on admin actions and forms.

```