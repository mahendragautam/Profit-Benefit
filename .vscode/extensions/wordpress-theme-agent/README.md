# WordPress Theme Agent (local VS Code extension)

This minimal extension provides two commands that open an integrated terminal and run the theme agent script (`scripts/spec_sync.py`):

- `Run WordPress Theme Agent` — runs `python3 scripts/spec_sync.py --agent wordpress.theme.agent --run-mode manual`.
- `Run WordPress Theme Agent (with args)` — prompts for `agent` and `run_mode`, then runs the script.

Agent management and @-commands

This extension now supports a simple agent stack manager. `wordpress.theme` is always active and visible.

	- `@security` — activate the `security` agent (stack becomes `wordpress.theme+security`)
	- `@testing` — activate `testing` (stack becomes `wordpress.theme+security+testing`)
	- `@security.deactivate` — deactivate `security` (stack updates)
	- `@all.deactivate` — deactivate all secondary agents and return to `wordpress.theme` only


The status bar shows the current active stack (e.g. `Agents: wordpress.theme+security+testing`).

Output & reports

When you run the agent via `Run WordPress Theme Agent` or `Run WordPress Theme Agent (with args)`, output is shown in the `WordPress Theme Agent` Output channel. A timestamped report is also saved under the workspace path:

`.vscode/agent-reports/<ISO_TIMESTAMP>__<agents>.log`

The report file is opened automatically after the run finishes. This provides a reproducible artifact you can attach to issues or CI.

How to use

Notes

Configuration
- `wordpressThemeAgent.showKnownAgents` (boolean, default `false`) — when `false`, the agent management UI hides non-active known agents (like `security`, `testing`). Set to `true` to reveal known agents for quick activation.
 - Activated agents are persisted in workspace storage. The active stack is saved under the workspace key `wordpressThemeAgent.active` so your activated agents survive VS Code restarts. Use `Show/Manage Active Agents` → `Deactivate all` to reset to `wordpress.theme` only.
- Press F5 to launch an Extension Development Host (recommended for quick testing).
