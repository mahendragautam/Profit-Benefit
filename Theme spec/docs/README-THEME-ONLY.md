# WordPress Theme Development Agent - Specification (Docs Copy)

(Content copied from root README-THEME-ONLY.md)

# WordPress Theme Development Agent — Spec (Docs)

This folder organizes the theme specification into two sets:

- `machine-spec/` — files the agent loads at runtime (topic-based, canonical names)
- `docs/` — implementation guides and reference documents for humans

Machine-spec files (topic → file):

- **Core rules:** [Theme spec/machine-spec/theme-agent-core.md](Theme spec/machine-spec/theme-agent-core.md)
- **Classic theme patterns:** [Theme spec/machine-spec/theme-classic-theme.md](Theme spec/machine-spec/theme-classic-theme.md)
- **Block / FSE:** [Theme spec/machine-spec/theme-block-theme.md](Theme spec/machine-spec/theme-block-theme.md)
- **Accessibility / WCAG:** [Theme spec/machine-spec/theme-accessibility.md](Theme spec/machine-spec/theme-accessibility.md)
- **Security:** [Theme spec/machine-spec/theme-security.md](Theme spec/machine-spec/theme-security.md)
- **Performance:** [Theme spec/machine-spec/theme-performance.md](Theme spec/machine-spec/theme-performance.md)
- **Testing & CI:** [Theme spec/machine-spec/theme-testing.md](Theme spec/machine-spec/theme-testing.md)
- **WooCommerce theme:** [Theme spec/machine-spec/theme-woocommerce.md](Theme spec/machine-spec/theme-woocommerce.md)
- **Usage / how-to:** [Theme spec/machine-spec/theme-usage-guide.md](Theme spec/machine-spec/theme-usage-guide.md)

Docs and guides (human-readable reference):

- [Theme spec/docs/IMPLEMENTATION-GUIDE.md](Theme spec/docs/IMPLEMENTATION-GUIDE.md)
- [Theme spec/docs/TROUBLESHOOTING-GUIDE.md](Theme spec/docs/TROUBLESHOOTING-GUIDE.md)
- [Theme spec/docs/MIGRATION-GUIDE.md](Theme spec/docs/MIGRATION-GUIDE.md)
- [Theme spec/docs/COMPLETION-SUMMARY.md](Theme spec/docs/COMPLETION-SUMMARY.md)
- [Theme spec/docs/INDEX.md](Theme spec/docs/INDEX.md)
- [Theme spec/docs/FUTURE-IMPROVEMENTS.md](Theme spec/docs/FUTURE-IMPROVEMENTS.md)

Notes:

- Filenames under `machine-spec/` were standardized to be short and topic-focused (e.g. `performance.md`, `security.md`).
- The older filenames were migrated and removed from the root; the canonical copies live in `machine-spec/` and `docs/`.

If you want, I can update any other README files or add a top-level index linking to these canonical locations.

