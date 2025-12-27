---
```markdown
# Accessibility — WCAG 2.1 AA Requirements for Themes

**Load when**: accessibility, a11y, WCAG, ARIA, keyboard navigation, screen reader

Purpose: Define concrete, testable accessibility requirements a WordPress theme must satisfy to meet WCAG 2.1 AA and WordPress accessibility expectations.

Summary of requirements

- Keyboard focus: All interactive controls (menus, links, forms, widgets) must be operable using keyboard only, with a visible focus indicator.
- Semantic HTML: Use correct HTML elements (nav, header, main, footer, form, button) and ensure headings follow a logical order.
- ARIA only when necessary: Prefer native semantics. When ARIA is used, follow ARIA Authoring Practices and ensure roles/states are updated.
- Color contrast: Text and interactive controls must meet 4.5:1 contrast ratio for normal text and 3:1 for large text.
- Images and media: Provide meaningful alt text for informative images; decorative images should use empty alt attributes. Provide captions/transcripts as required.
- Skip links: Provide a `skip to content` link that becomes visible on keyboard focus.
- Forms: Label every form control (`<label for=>` or `aria-label`) and associate errors clearly; use `aria-invalid` and `aria-describedby` for messages.
- Focus management: After AJAX updates or navigation, ensure focus moves to the relevant heading or status region.

Automated checks (baseline)

- Run axe or pa11y on major pages (home, post, single, archive, search, 404, shop/cart if WooCommerce). Fail on critical violations.
- Check color contrast with automated tools; flag any failures for manual review.

Manual checks (must pass)

- Keyboard walk: Tab through the page and ensure all interactive items are reachable and usable.
- Screen reader test: Confirm important content and navigation are readable and in an expected order with NVDA/VoiceOver.

Implementation notes and examples

- Skip link example:

```html
<a class="skip-link" href="#site-content">Skip to content</a>
```

- Accessible menu (use button for toggles and `aria-expanded`):

```php
<button class="menu-toggle" aria-expanded="false" aria-controls="site-navigation">Menu</button>
<nav id="site-navigation" role="navigation">...</nav>
```

- Form labeling:

```php
<label for="search-field">Search</label>
<input id="search-field" name="s" type="search">
```

Resources and references

- WCAG 2.1 (https://www.w3.org/TR/WCAG21/)
- ARIA Authoring Practices (https://www.w3.org/TR/wai-aria-practices/)
- WordPress Accessibility Handbook

Accept/reject criteria

- A theme passes accessibility spec when automated scans show zero critical WCAG failures and manual keyboard/screen-reader checks pass for primary templates.

```