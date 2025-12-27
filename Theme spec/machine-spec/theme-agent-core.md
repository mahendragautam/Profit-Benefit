# WordPress Theme Development Agent - Core Rules

> **Version**: 2.0 | **Last Updated**: December 2025
> 
> **Your Role**: Expert WordPress Theme Developer
> 
> **Always Active**: This file is permanently loaded

---

(This file is the canonical agent core ruleset.)
This file defines the core rules and behavior for automation and agents interacting with the theme repository. Treat this as the authoritative policy for how to evaluate files in `machine-spec` and the `docs` folder.

Key responsibilities

- Validate structure: Themes must follow the WordPress template hierarchy and include required files such as `style.css` with proper header comments, `index.php`, `functions.php`, and accessible templates.
 - Security-first: All output must be escaped; all input sanitized. See `Theme spec/machine-spec/theme-security.md` for examples.
 - Accessibility baseline: Enforce WCAG 2.1 AA baseline from `Theme spec/machine-spec/theme-accessibility.md`.
 - Performance expectations: Follow `Theme spec/machine-spec/theme-performance.md` recommendations; avoid render-blocking CSS and large unused assets.
 - Testing: Run `Theme spec/machine-spec/theme-testing.md` checks automatically where possible.

Agent behavior rules

- When evaluating changes, check only files under the theme root and `Theme spec/` unless a PR explicitly modifies other areas.
 - Use `Theme spec/machine-spec/index.md` as the canonical list of automation-loaded spec files.
 - When a new spec file is added, require an update to `Theme spec/machine-spec/index.md` and ensure `scripts/spec_sync.py` passes.

Metadata and linting

- Enforce PHP_CodeSniffer WordPress standards for PHP files and ESLint for JS where applicable.
- Require Theme Check pass for release branches unless explicitly documented.

Error handling

- Report missing required files, failed automated tests, and critical accessibility/security issues as failing checks.

```
