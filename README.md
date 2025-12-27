# Profit-Benefit (WordPress Theme)

[![Spec Sync](https://github.com/mahendragautam/Profit-Benefit/actions/workflows/spec-sync.yml/badge.svg)](https://github.com/mahendragautam/Profit-Benefit/actions/workflows/spec-sync.yml)
[![Package VSIX](https://github.com/mahendragautam/Profit-Benefit/actions/workflows/package-vsix.yml/badge.svg)](https://github.com/mahendragautam/Profit-Benefit/actions/workflows/package-vsix.yml)

This repository contains the Profit-Benefit WordPress theme and supporting documentation.

- **Theme code:** theme files are in the repository root (PHP templates, assets, styles).
- **Theme spec:** see the `Theme spec` folder for canonical agent specs and human-facing docs.

Theme spec summary:

- Machine specs: `Theme spec/machine-spec/` — machine-friendly canonical spec files used by automation.
- Human docs: `Theme spec/docs/` — implementation guides, migration notes, troubleshooting, and future improvements.

If you are contributing, start with the docs in `Theme spec/docs/` and refer to `Theme spec/machine-spec/` for the precise rule definitions.

Manual agent workflow
--------------------

You can run the WordPress Theme Agent manually from GitHub Actions:

- Workflow file: `.github/workflows/run-wordpress-agent-manual.yml`
- What it does: checks and syncs the machine-spec index by running `scripts/spec_sync.py`.
- How to run: open the repository on GitHub, go to Actions → "Run WordPress Theme Agent (manual)" → Run workflow. Optionally pass `agent` and `run_mode` inputs.

Locally you can run the same check with:

```bash
python3 scripts/spec_sync.py --agent wordpress.theme.agent --run-mode manual
```

Run from VS Code integrated terminal via local extension
-----------------------------------------------------

You can load the local VS Code extension to run the agent directly in the integrated terminal:

- Open this repo in VS Code and press `F5` to launch an Extension Development Host.
- In the development host window open Command Palette (Ctrl+Shift+P) and run `Run WordPress Theme Agent` or `Run WordPress Theme Agent (with args)`.

The extension files are under `.vscode/extensions/wordpress-theme-agent/`.

Agent management (in-IDE)
------------------------

The local extension now provides simple active-agent management:

- Use Command Palette → `Process Agent @-command` to enter `@` commands (e.g. `@security`, `@testing`, `@security.deactivate`, `@all.deactivate`).
- Click the `Agents:` status bar item or run `Show/Manage Active Agents` to manage active agents via quick pick UI.

The `wordpress.theme` agent remains the primary visible agent; other agents are activated on demand and can be deactivated individually or all at once.

Output & reports
----------------

Agent runs now stream output to the VS Code Output channel named `WordPress Theme Agent`. Each run also creates a timestamped report file under:

`.vscode/agent-reports/<ISO_TIMESTAMP>__<agents>.log`

The report opens automatically when the run completes.
