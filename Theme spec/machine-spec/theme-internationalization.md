```markdown
# Internationalization (i18n) — Text Domains, Translations, and Languages

Load when: translate, i18n, localization, textdomain, languages

Purpose: Define required localization patterns so themes can be translated and internationalized correctly.

Rules

- Text domain: Use a single consistent text domain matching the theme folder slug (e.g., `profit-benefit`).
- Use translation functions: `__( 'String', 'text-domain' )`, `_e()`, `_n()`, `_x()` as appropriate. Do not concatenate translatable strings.
- Context: Use `_x()` when the same string has different meanings.
- Pluralization: Use `_n()` for plural strings and ensure translators get correct context.

Loading translations

- Load the textdomain in `after_setup_theme` with `load_theme_textdomain( 'text-domain', get_template_directory() . '/languages' );`.

Packaging

- Include a `languages/` folder with POT/PO/MO files for shipped translations when available. For development, include a `theme.pot` file.

Examples

```php
/* translators: %s: author name */
printf( esc_html__( 'Theme by %s', 'profit-benefit' ), esc_html( $author ) );
```

Testing

- Use `wp i18n make-pot` to generate POT files and verify strings are extracted correctly.

```
