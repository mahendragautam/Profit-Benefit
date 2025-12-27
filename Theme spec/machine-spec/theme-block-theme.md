---
```markdown
# Block Theme Development — Full Site Editing (FSE)

Load when: block theme, theme.json, FSE, full site editing, patterns, templates

Purpose: Rules and examples for building block themes that use `theme.json`, block templates, template parts, and patterns while remaining compatible and performant.

Key rules

- `theme.json`: Provide a minimal `theme.json` with only required settings and clear comments for any presets. Keep file size small; avoid large custom property payloads.
- Templates & parts: Ship `templates/index.html`, `templates/single.html`, `parts/header.html`, `parts/footer.html` as needed. Use patterns for repeated structures.
- Markup: Use standard block markup; avoid embedding large inline styles. Prefer `theme.json` for colors, typography, spacing.

Compatibility

- Provide fallback PHP templates (`index.php`, `single.php`) for older installations when necessary.
- When registering patterns that include markup meant for editors, avoid hardcoded IDs and ensure content is sanitized when rendered on the front-end.

Example minimal `theme.json`:

```json
{
	"version": 2,
	"settings": {
		"color": { "custom": true, "customGradient": true },
		"typography": { "fontSizes": [ { "slug": "normal", "size": 16 } ] }
	}
}
```

Editor patterns

- Provide JSON pattern files under `patterns/` and register them in `theme.json` when useful. Keep patterns modular and accessible.

Performance and assets

- Avoid large editor-only assets in production builds. Use `wp_enqueue_block_style` and conditional loading when possible.

Release checklist for block themes

- `theme.json` validated and minimized.
- Templates present for the main entry points and responsive breakpoints tested.
- Accessibility and performance checks passed.

```