# Agents & CI (README)

This README provides a quick overview of the agent system and CI integration in this repository.

- Agent definitions are stored under `.github/agents/` (topic files and top-level agent markdown).
- The agent manager CLI is `tools/agent-manager.js` and uses a template loader at `.github/agent-management/agent-template.js`.
- Spec sync and checks are handled by `scripts/spec_sync.py` (used by the manual GitHub Action `run-wordpress-agent-manual.yml`).
- CI workflow `run-wordpress-agent-manual.yml` runs the agent and uploads `.vscode/agent-reports/*.json` as artifacts.
- CI protection: `.github/workflows/protect-agent-files.yml` prevents accidental changes to key agent/CI files listed in `.github/protected-files.json`.

If you intend to modify agent definitions or the CI, follow the guidance in `AGENT-CONTRIBUTING.md`.
