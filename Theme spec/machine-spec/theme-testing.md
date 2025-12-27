---
```markdown
# Theme Testing — Automated and Manual Checks

Load when: test theme, theme check, validate theme, accessibility test, browser test, CI

Purpose: Provide a test matrix and commands to validate theme quality across accessibility, code quality, and visual regression.

Automated checks (CI)

- PHP lint: `php -l` on all PHP files.
- PHPCS: Use WordPress Coding Standards via `phpcs --standard=WordPress`.
- Theme Check: run `vendor/bin/theme-check` or the `theme-check` CLI against the theme folder.
- JavaScript lint: `eslint` for JS sources when present.

Accessibility & visual tests

- Run `axe` or `pa11y` as part of CI against rendered pages.
- Visual regression: use BackstopJS or Playwright snapshots for critical templates.

Local commands (examples)

```bash
# PHP lint
find . -name "*.php" -print0 | xargs -0 -n1 php -l

# PHPCS (install via composer)
./vendor/bin/phpcs --standard=WordPress --extensions=php

# Theme Check (WP CLI or composer package)
./vendor/bin/theme-check path/to/theme

# Run Python accessibility checks (example)
pa11y https://local.test/
```

Release criteria

- No PHP parse errors, no PHPCS critical errors, Theme Check passes without fatal issues, accessibility auto-checks report zero critical failures, visual regressions acceptable.

```