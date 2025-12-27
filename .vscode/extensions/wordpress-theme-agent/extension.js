const vscode = require('vscode');
const path = require('path');
const fs = require('fs');
const { spawn } = require('child_process');

/**
 * Activate the extension.
 * Registers two commands:
 * - `wordpressThemeAgent.run` runs the default agent command in an integrated terminal.
 * - `wordpressThemeAgent.runWithArgs` prompts for agent/run-mode and runs the command.
 */
function activate(context) {
  // Agent manager: keeps a stack/list of active agents. `wordpress.theme` is always present.
  const AgentManager = function(statusBar, context) {
    this.statusBar = statusBar;
    this.context = context;
    // Populate active agents. Priority order:
    // 1. Read .github/agents/active.json (CLI source of truth)
    // 2. Fallback to persisted workspaceState
    // Always ensure 'wordpress.theme' is present.
    this.active = ['wordpress.theme'];
    try {
      const ws = (vscode.workspace.workspaceFolders && vscode.workspace.workspaceFolders[0])
        ? vscode.workspace.workspaceFolders[0].uri.fsPath
        : undefined;
      if (ws) {
        const activePath = path.join(ws, '.github', 'agents', 'active.json');
        if (fs.existsSync(activePath)) {
          try {
            const raw = fs.readFileSync(activePath, 'utf8');
            const arr = JSON.parse(raw || '[]');
            if (Array.isArray(arr) && arr.length > 0) {
              for (const a of arr) {
                if (typeof a !== 'string') continue;
                if (a.startsWith('topics/')) {
                  const b = path.basename(a, '.md');
                  if (b && b !== 'wordpress.theme' && !this.active.includes(b)) this.active.push(b);
                } else if (a.toLowerCase().endsWith('.md')) {
                  const b = path.basename(a, '.md');
                  if (b && b !== 'wordpress.theme' && !this.active.includes(b)) this.active.push(b);
                } else {
                  if (a !== 'wordpress.theme' && !this.active.includes(a)) this.active.push(a);
                }
              }
            }
          } catch (e) {
            // ignore parse errors and fallback to workspace state
          }
        }
      }
      // If CLI active.json was empty, fallback to persisted workspaceState
      if (this.active.length <= 1) {
        const saved = (context && context.workspaceState) ? context.workspaceState.get('wordpressThemeAgent.active') : undefined;
        if (Array.isArray(saved) && saved.length > 0) {
          for (const s of saved) {
            if (!this.active.includes(s)) this.active.push(s);
          }
        }
      }
    } catch (e) {
      // ensure default
      if (!Array.isArray(this.active) || this.active.length === 0) this.active = ['wordpress.theme'];
    }
    this.updateStatus();
  };
  AgentManager.prototype.getActive = function() { return Array.from(this.active); };
  AgentManager.prototype.isActive = function(name) { return this.active.indexOf(name) !== -1; };
  AgentManager.prototype.activate = function(name) {
    if (!name) return;
    if (this.isActive(name)) return;
    this.active.push(name);
    this.updateStatus();
    try {
      if (this.context && this.context.workspaceState) this.context.workspaceState.update('wordpressThemeAgent.active', this.active);
    } catch (e) {
      console.error('Failed to persist active agents', e);
    }
    // Bridge: invoke CLI agent-manager to keep repository active.json and copilot file in sync
    try {
      const ws = (this.context && this.context.workspaceFolders && this.context.workspaceFolders[0])
        ? this.context.workspaceFolders[0].uri.fsPath
        : (this.context && this.context.extensionPath) ? path.join(this.context.extensionPath, '..', '..', '..') : undefined;
      if (ws) {
        const script = path.join(ws, 'tools', 'agent-manager.js');
        const nodeCmd = process.platform === 'win32' ? 'node' : 'node';
        const proc = spawn(nodeCmd, [script, 'activate', name], { cwd: ws, shell: false });
        proc.stdout.on('data', d => console.log('[agent-manager]', d.toString()));
        proc.stderr.on('data', d => console.error('[agent-manager]', d.toString()));
      }
    } catch (e) {
      console.error('Failed to invoke CLI agent-manager on activate', e);
    }
  };
  AgentManager.prototype.deactivate = function(name) {
    if (!name) return;
    // never remove the base wordpress.theme via single deactivate
    if (name === 'wordpress.theme') return;
    this.active = this.active.filter(a => a !== name);
    this.updateStatus();
    try {
      if (this.context && this.context.workspaceState) this.context.workspaceState.update('wordpressThemeAgent.active', this.active);
    } catch (e) {
      console.error('Failed to persist active agents', e);
    }
    // Bridge: notify CLI manager to deactivate
    try {
      const ws = (this.context && this.context.workspaceFolders && this.context.workspaceFolders[0])
        ? this.context.workspaceFolders[0].uri.fsPath
        : (this.context && this.context.extensionPath) ? path.join(this.context.extensionPath, '..', '..', '..') : undefined;
      if (ws) {
        const script = path.join(ws, 'tools', 'agent-manager.js');
        const nodeCmd = process.platform === 'win32' ? 'node' : 'node';
        const proc = spawn(nodeCmd, [script, 'deactivate', name], { cwd: ws, shell: false });
        proc.stdout.on('data', d => console.log('[agent-manager]', d.toString()));
        proc.stderr.on('data', d => console.error('[agent-manager]', d.toString()));
      }
    } catch (e) {
      console.error('Failed to invoke CLI agent-manager on deactivate', e);
    }
  };
  AgentManager.prototype.deactivateAll = function() {
    this.active = ['wordpress.theme'];
    this.updateStatus();
    try {
      if (this.context && this.context.workspaceState) this.context.workspaceState.update('wordpressThemeAgent.active', this.active);
    } catch (e) {
      console.error('Failed to persist active agents', e);
    }
    // Bridge: clear CLI active list
    try {
      const ws = (this.context && this.context.workspaceFolders && this.context.workspaceFolders[0])
        ? this.context.workspaceFolders[0].uri.fsPath
        : (this.context && this.context.extensionPath) ? path.join(this.context.extensionPath, '..', '..', '..') : undefined;
      if (ws) {
        const script = path.join(ws, 'tools', 'agent-manager.js');
        const nodeCmd = process.platform === 'win32' ? 'node' : 'node';
        const proc = spawn(nodeCmd, [script, 'deactivate'], { cwd: ws, shell: false });
        proc.stdout.on('data', d => console.log('[agent-manager]', d.toString()));
        proc.stderr.on('data', d => console.error('[agent-manager]', d.toString()));
      }
    } catch (e) {
      console.error('Failed to invoke CLI agent-manager on deactivateAll', e);
    }
  };
  AgentManager.prototype.updateStatus = function() {
    if (!this.statusBar) return;
    this.statusBar.text = `Agents: ${this.active.join('+')}`;
    this.statusBar.tooltip = `Active agents: ${this.active.join(', ')}\nClick to manage agents`;
  };

  const statusBar = vscode.window.createStatusBarItem(vscode.StatusBarAlignment.Left, 100);
  statusBar.command = 'wordpressThemeAgent.showAgents';
  statusBar.show();
  const manager = new AgentManager(statusBar, context);
  // Detect other installed extensions that may provide an Agent/GPT status menu
  async function detectConflictingExtensions() {
    try {
      const ignored = (context && context.workspaceState) ? context.workspaceState.get('wordpressThemeAgent.ignoredConflicts') : [];
      const exts = vscode.extensions.all || [];
      const suspects = exts.filter(e => {
        const name = (e.packageJSON && (e.packageJSON.displayName || e.packageJSON.name)) || '';
        const id = e.id || '';
        // match likely chat/agent providers but ignore this extension itself
        const isSelf = id.includes('wordpress-theme-agent') || name.toLowerCase().includes('wordpress theme agent');
        if (isSelf) return false;
        if (ignored && ignored.indexOf(id) !== -1) return false;
        return /\b(agent|gpt|copilot|chat|assistant|openai)\b/i.test(name + ' ' + id);
      });
      if (suspects.length === 0) return;
      // Prompt for each suspect (but only one-by-one to avoid spamming)
      const s = suspects[0];
      const display = (s.packageJSON && (s.packageJSON.displayName || s.packageJSON.name)) || s.id;
      const choice = await vscode.window.showInformationMessage(
        `Detected another extension that may contribute an Agent/GPT menu: ${display}. Disable it for this workspace to avoid UI conflicts?`,
        'Disable in Workspace', 'Ignore'
      );
      if (choice === 'Disable in Workspace') {
        try {
          // Ask VS Code to disable the extension in workspace
          await vscode.commands.executeCommand('workbench.extensions.disableExtension', s.id);
          const r = await vscode.window.showInformationMessage('Disabled. Reload window to apply changes.', 'Reload Now');
          if (r === 'Reload Now') await vscode.commands.executeCommand('workbench.action.reloadWindow');
        } catch (e) {
          console.error('Failed to disable extension', s.id, e);
          vscode.window.showErrorMessage(`Failed to disable extension ${display}. You can disable it from Extensions view.`);
        }
      } else if (choice === 'Ignore') {
        try {
          const cur = (context && context.workspaceState) ? context.workspaceState.get('wordpressThemeAgent.ignoredConflicts') || [] : [];
          cur.push(s.id);
          if (context && context.workspaceState) await context.workspaceState.update('wordpressThemeAgent.ignoredConflicts', Array.from(new Set(cur)));
        } catch (e) {}
      }
    } catch (e) { console.error('Conflict detection failed', e); }
  }
  detectConflictingExtensions();
  // known agents list for context updates and contributions
  const knownAgents = ['security', 'testing'];

  // update when-clause contexts for agent visibility and setting
  async function updateAgentContexts() {
    try {
      const cfg = vscode.workspace.getConfiguration();
      const showKnown = cfg.get('wordpressThemeAgent.showKnownAgents', false);
      await vscode.commands.executeCommand('setContext', 'wordpressThemeAgent.showKnownAgents', showKnown);
      // set dot-free context keys that the package.json menus reference
      await vscode.commands.executeCommand('setContext', 'wordpressThemeAgent.agentVisibleSecurity', manager.isActive('security'));
      await vscode.commands.executeCommand('setContext', 'wordpressThemeAgent.agentVisibleTesting', manager.isActive('testing'));
    } catch (e) {
      console.error('Failed to update agent contexts', e);
    }
  }

  // expose manager change hook so it can notify context updates
  manager.onChange = updateAgentContexts;
  // initial set
  updateAgentContexts();

  // watch configuration changes for showKnownAgents
  const cfgListener = vscode.workspace.onDidChangeConfiguration(e => {
    if (e.affectsConfiguration('wordpressThemeAgent.showKnownAgents')) updateAgentContexts();
  });
  context.subscriptions.push(cfgListener);

  // register activation/deactivation commands for known agents (conditional via package.json)
  const activateSecurity = vscode.commands.registerCommand('wordpressThemeAgent.activate.security', () => { manager.activate('security'); updateAgentContexts(); });
  const deactivateSecurity = vscode.commands.registerCommand('wordpressThemeAgent.deactivate.security', () => { manager.deactivate('security'); updateAgentContexts(); });
  const activateTesting = vscode.commands.registerCommand('wordpressThemeAgent.activate.testing', () => { manager.activate('testing'); updateAgentContexts(); });
  const deactivateTesting = vscode.commands.registerCommand('wordpressThemeAgent.deactivate.testing', () => { manager.deactivate('testing'); updateAgentContexts(); });
  const clearState = vscode.commands.registerCommand('wordpressThemeAgent.clearState', async () => {
    try {
      if (context && context.workspaceState) {
        await context.workspaceState.update('wordpressThemeAgent.active', ['wordpress.theme']);
        manager.deactivateAll();
        updateAgentContexts();
        vscode.window.showInformationMessage('WordPress Theme Agent persisted state cleared.');
      }
    } catch (e) {
      console.error('Failed to clear persisted agent state', e);
      vscode.window.showErrorMessage('Failed to clear persisted agent state');
    }
  });
  context.subscriptions.push(activateSecurity, deactivateSecurity, activateTesting, deactivateTesting);
  context.subscriptions.push(clearState);

  // Output channel and report saver
  const out = vscode.window.createOutputChannel('WordPress Theme Agent');
  const diagCollection = vscode.languages.createDiagnosticCollection('wordpressThemeAgent');
  context.subscriptions.push(diagCollection);

  function applyDiagnosticsFromReport(report, ws) {
    try {
      diagCollection.clear();
      if (!report || !report.tasks) return;
      const fileMap = new Map();
      // Iterate tasks and their details
      for (const [taskName, taskVal] of Object.entries(report.tasks)) {
        if (!taskVal) continue;
        const details = taskVal.details || [];
        for (const det of details) {
          if (det.ok) continue; // only report failures
          const rel = det.file || det.filename || det.path || null;
          if (!rel) continue;
          let fp = rel;
          // normalize path: if not absolute, join with workspace
          if (ws && !path.isAbsolute(fp)) fp = path.join(ws, fp);
          fp = path.normalize(fp);
          if (!fs.existsSync(fp)) continue;
          const uri = vscode.Uri.file(fp);
          const msg = det.output || det.message || `${taskName} failed`;
          const range = new vscode.Range(new vscode.Position(0, 0), new vscode.Position(0, 1));
          const severity = vscode.DiagnosticSeverity.Error;
          const diag = new vscode.Diagnostic(range, msg, severity);
          const arr = fileMap.get(uri.toString()) || [];
          arr.push(diag);
          fileMap.set(uri.toString(), arr);
        }
      }

      // Apply diagnostics to the collection
      for (const [key, diags] of fileMap.entries()) {
        const uri = vscode.Uri.parse(key);
        diagCollection.set(uri, diags);
      }
      if (fileMap.size > 0) {
        vscode.window.showInformationMessage(`Agent diagnostics added for ${fileMap.size} file(s).`);
      }
    } catch (e) {
      console.error('Failed to apply diagnostics from report', e);
    }
  }
  function saveReport(content, agentsArg, ws) {
    try {
      if (!ws) return undefined;
      const reportsDir = path.join(ws, '.vscode', 'agent-reports');
      if (!fs.existsSync(reportsDir)) fs.mkdirSync(reportsDir, { recursive: true });
      const ts = new Date().toISOString().replace(/[:.]/g, '-');
      const safeName = (agentsArg || 'agents').replace(/[^a-zA-Z0-9_+-]/g, '_');
      const file = path.join(reportsDir, `${ts}__${safeName}.log`);
      fs.writeFileSync(file, content, { encoding: 'utf8' });
      return file;
    } catch (e) {
      console.error('Failed to save agent report', e);
      return undefined;
    }
  }

  const run = vscode.commands.registerCommand('wordpressThemeAgent.run', () => {
    const ws = (vscode.workspace.workspaceFolders && vscode.workspace.workspaceFolders[0])
      ? vscode.workspace.workspaceFolders[0].uri.fsPath
      : undefined;
    const script = ws ? path.join(ws, 'scripts', 'spec_sync.py') : 'scripts/spec_sync.py';
    const pythonCmd = process.platform === 'win32' ? 'python' : 'python3';
    const agentsArg = manager.getActive().join('+');

    out.show(true);
    out.appendLine(`Running: ${pythonCmd} ${script} --agent wordpress.theme.agent --run-mode manual --agents "${agentsArg}"`);

    const proc = spawn(pythonCmd, [script, '--agent', 'wordpress.theme.agent', '--run-mode', 'manual', '--agents', agentsArg], { cwd: ws || undefined, shell: false });
    let acc = '';
    proc.stdout.on('data', (chunk) => {
      const s = chunk.toString();
      acc += s;
      out.append(s);
    });
    proc.stderr.on('data', (chunk) => {
      const s = chunk.toString();
      acc += s;
      out.append(s);
    });
    proc.on('close', (code) => {
      out.appendLine(`\nProcess exited with code ${code}`);
      const f = saveReport(acc, agentsArg, ws);
      // try to locate JSON report path in output
      const m = acc.match(/REPORT_JSON:\s*(\S+)/);
      if (m && m[1]) {
        let jsonPath = m[1].trim();
        // if relative, resolve against workspace
        if (ws && !path.isAbsolute(jsonPath)) jsonPath = path.join(ws, jsonPath);
        try {
          if (fs.existsSync(jsonPath)) {
            const txt = fs.readFileSync(jsonPath, { encoding: 'utf8' });
            const report = JSON.parse(txt);
            applyDiagnosticsFromReport(report, ws);
            vscode.workspace.openTextDocument(jsonPath).then(doc => vscode.window.showTextDocument(doc, { preview: false }));
          }
        } catch (e) {
          console.error('Failed to read/parse REPORT_JSON', e);
        }
      }
      if (f) {
        vscode.window.showInformationMessage(`Agent run finished (code ${code}). Report: ${path.relative(ws || '', f)}`);
        vscode.workspace.openTextDocument(f).then(doc => vscode.window.showTextDocument(doc, { preview: false }));
      } else {
        vscode.window.showInformationMessage(`Agent run finished (code ${code}).`);
      }
    });
  });

  const runWithArgs = vscode.commands.registerCommand('wordpressThemeAgent.runWithArgs', async () => {
    const agent = await vscode.window.showInputBox({ prompt: 'Agent name', value: 'wordpress.theme.agent' });
    if (typeof agent === 'undefined') {
      return;
    }
    const runMode = await vscode.window.showInputBox({ prompt: 'Run mode', value: 'manual' });
    if (typeof runMode === 'undefined') {
      return;
    }

    const ws = (vscode.workspace.workspaceFolders && vscode.workspace.workspaceFolders[0])
      ? vscode.workspace.workspaceFolders[0].uri.fsPath
      : undefined;
    const script = ws ? path.join(ws, 'scripts', 'spec_sync.py') : 'scripts/spec_sync.py';
    const pythonCmd = process.platform === 'win32' ? 'python' : 'python3';
    const agentsArg = manager.getActive().join('+');

    out.show(true);
    out.appendLine(`Running: ${pythonCmd} ${script} --agent ${agent} --run-mode ${runMode} --agents "${agentsArg}"`);
    const proc = spawn(pythonCmd, [script, '--agent', agent, '--run-mode', runMode, '--agents', agentsArg], { cwd: ws || undefined, shell: false });
    let acc = '';
    proc.stdout.on('data', (chunk) => {
      const s = chunk.toString();
      acc += s;
      out.append(s);
    });
    proc.stderr.on('data', (chunk) => {
      const s = chunk.toString();
      acc += s;
      out.append(s);
    });
    proc.on('close', (code) => {
      out.appendLine(`\nProcess exited with code ${code}`);
      const f = saveReport(acc, agentsArg, ws);
      const m = acc.match(/REPORT_JSON:\s*(\S+)/);
      if (m && m[1]) {
        let jsonPath = m[1].trim();
        if (ws && !path.isAbsolute(jsonPath)) jsonPath = path.join(ws, jsonPath);
        try {
          if (fs.existsSync(jsonPath)) {
            const txt = fs.readFileSync(jsonPath, { encoding: 'utf8' });
            const report = JSON.parse(txt);
            applyDiagnosticsFromReport(report, ws);
            vscode.workspace.openTextDocument(jsonPath).then(doc => vscode.window.showTextDocument(doc, { preview: false }));
          }
        } catch (e) {
          console.error('Failed to read/parse REPORT_JSON', e);
        }
      }
      if (f) {
        vscode.window.showInformationMessage(`Agent run finished (code ${code}). Report: ${path.relative(ws || '', f)}`);
        vscode.workspace.openTextDocument(f).then(doc => vscode.window.showTextDocument(doc, { preview: false }));
      } else {
        vscode.window.showInformationMessage(`Agent run finished (code ${code}).`);
      }
    });
  });

  const processCommand = vscode.commands.registerCommand('wordpressThemeAgent.processCommand', async () => {
    // Try to get selected text from active editor first
    let input = '';
    const editor = vscode.window.activeTextEditor;
    if (editor && !editor.selection.isEmpty) {
      input = editor.document.getText(editor.selection).trim();
    }
    if (!input) {
      input = await vscode.window.showInputBox({ prompt: 'Enter @-command (e.g. @security, @testing, @security.deactivate, @all.deactivate)' });
      if (typeof input === 'undefined') return; // cancelled
    }

    input = input.trim();
    if (!input.startsWith('@')) {
      vscode.window.showInformationMessage('Not an @-command. Start with @');
      return;
    }
    const cmd = input.slice(1).trim();
    if (!cmd) return;

    if (cmd.toLowerCase() === 'all.deactivate' || cmd.toLowerCase() === 'all_deactivate') {
      manager.deactivateAll();
      vscode.window.showInformationMessage('All agents deactivated. Back to wordpress.theme.');
      return;
    }

    if (cmd.endsWith('.deactivate')) {
      const name = cmd.replace(/\.deactivate$/i, '');
      manager.deactivate(name);
      vscode.window.showInformationMessage(`${name} deactivated. Active: ${manager.getActive().join('+')}`);
      return;
    }

    // Activate agent
    manager.activate(cmd);
    vscode.window.showInformationMessage(`${cmd} activated. Active: ${manager.getActive().join('+')}`);
  });

  const showAgents = vscode.commands.registerCommand('wordpressThemeAgent.showAgents', async () => {
    // Provide a small management UI: activate new, deactivate selected, deactivate all
    const config = vscode.workspace.getConfiguration();
    const showKnown = config.get('wordpressThemeAgent.showKnownAgents', false);

    const baseChoices = [
      { label: 'Activate agent', description: 'Add an agent to the active stack' },
      { label: 'Deactivate agent(s)', description: 'Remove one or more active agents (except wordpress.theme)' },
      { label: 'Deactivate all', description: 'Reset to wordpress.theme only' }
    ];

    // If configured to hide known agents, we won't pre-populate quick picks with known ones.
    const choice = await vscode.window.showQuickPick(baseChoices, { placeHolder: 'Manage active agents' });
    if (!choice) return;

    if (choice.label === 'Activate agent') {
      // Build quick pick list from workspace .github/agents and .github/agents/topics
      const ws = (vscode.workspace.workspaceFolders && vscode.workspace.workspaceFolders[0])
        ? vscode.workspace.workspaceFolders[0].uri.fsPath
        : undefined;
      let pickName;
      if (ws) {
        try {
          const agentsDir = path.join(ws, '.github', 'agents');
          const topicsDir = path.join(agentsDir, 'topics');
          const choices = [];
          // Only expose wordpress.theme as the primary custom agent in the dropdown
          if (fs.existsSync(agentsDir)) {
            const topFiles = fs.readdirSync(agentsDir).filter(f => f.toLowerCase().endsWith('.md'));
            const wpFile = topFiles.find(f => f.toLowerCase().includes('wordpress.theme'));
            if (wpFile) choices.push({ label: 'wordpress.theme', description: 'custom agent (primary)' });
          }
          // Add topic agents under topics folder
          if (fs.existsSync(topicsDir)) {
            const topicFiles = fs.readdirSync(topicsDir).filter(f => f.toLowerCase().endsWith('.md'));
            topicFiles.sort().forEach(t => choices.push({ label: path.basename(t, '.md'), description: 'topic' }));
          }
          if (choices.length > 0) {
            const pick = await vscode.window.showQuickPick(choices, { placeHolder: 'Select an agent to activate' });
            if (pick) pickName = pick.label;
          }
        } catch (e) {
          console.error('Failed to build agent choices', e);
        }
      }
      if (!pickName) {
        // fallback to manual input
        pickName = await vscode.window.showInputBox({ prompt: 'Agent name to activate (e.g. security or topic name)' });
      }
      if (typeof pickName === 'undefined' || !pickName) return;
      manager.activate(pickName.trim());
      vscode.window.showInformationMessage(`Activated ${pickName}. Active: ${manager.getActive().join('+')}`);
      return;
    }

    if (choice.label === 'Deactivate agent(s)') {
      const candidates = manager.getActive().filter(a => a !== 'wordpress.theme');
      if (candidates.length === 0) {
        vscode.window.showInformationMessage('No secondary agents active.');
        return;
      }
      const toRemove = await vscode.window.showQuickPick(candidates, { canPickMany: true, placeHolder: 'Select agents to deactivate' });
      if (!toRemove || toRemove.length === 0) return;
      toRemove.forEach(n => manager.deactivate(n));
      vscode.window.showInformationMessage(`Deactivated: ${toRemove.join(', ')}. Active: ${manager.getActive().join('+')}`);
      return;
    }

    if (choice.label === 'Deactivate all') {
      manager.deactivateAll();
      vscode.window.showInformationMessage('All agents deactivated. Back to wordpress.theme.');
      return;
    }
  });

  context.subscriptions.push(run, runWithArgs, processCommand, showAgents, statusBar);
}

function deactivate() {}

module.exports = { activate, deactivate };
