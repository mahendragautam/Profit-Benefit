#!/usr/bin/env node
// Minimal agent template-based manager (replaced by `.github/agent-management/agent-template.js`).
// This replacement intentionally uses the agent template implementation as the new
// manager entrypoint. It exposes simple CLI commands: `activate`, `deactivate`, `status`, `list`.

const fs = require('fs');
const path = require('path');

// Load the agent template module from the repository agent-management folder.
const TEMPLATE_PATH = path.join(__dirname, '..', '.github', 'agent-management', 'agent-template.js');
let agentTemplate = null;
try {
  agentTemplate = require(TEMPLATE_PATH);
} catch (e) {
  console.error('Failed to load agent template from', TEMPLATE_PATH, e.message || e);
}

const AGENTS_DIR = path.join(__dirname, '..', '.github', 'agents');
const TOPICS_DIR = path.join(AGENTS_DIR, 'topics');
const ACTIVE_PATH = path.join(AGENTS_DIR, 'active.json');
const ACTIVE_STATUS_PATH = path.join(AGENTS_DIR, 'active-status.json');
// Fallback protected set (kept for backward compatibility). Primary protection
// is controlled via YAML frontmatter `protected: true` in the agent markdown.
const PROTECTED_AGENTS = new Set();

function readFrontmatter(filePath) {
  try {
    if (!fs.existsSync(filePath)) return {};
    const raw = fs.readFileSync(filePath, 'utf8');
    const parts = raw.split(/\r?\n/);
    // find the first '---' that starts a frontmatter block
    let start = -1;
    for (let i = 0; i < parts.length; i++) {
      if (parts[i].trim() === '---') { start = i; break; }
    }
    if (start === -1) return {};
    const fm = {};
    for (let i = start + 1; i < parts.length; i++) {
      const line = parts[i];
      if (line.trim() === '---') break;
      const m = line.match(/^([A-Za-z0-9_-]+):\s*(.*)$/);
      if (m) {
        let val = m[2].trim();
        if ((val.startsWith("'") && val.endsWith("'")) || (val.startsWith('"') && val.endsWith('"'))) {
          val = val.slice(1, -1);
        }
        // try to coerce booleans/numbers
        if (val.toLowerCase() === 'true') fm[m[1]] = true;
        else if (val.toLowerCase() === 'false') fm[m[1]] = false;
        else if (!isNaN(Number(val))) fm[m[1]] = Number(val);
        else fm[m[1]] = val;
      }
    }
    return fm;
  } catch (e) { return {}; }
}

function isAgentProtected(agentKey) {
  // agentKey might be 'topics/name.md' or 'name.md' or bare basename
  let filename = agentKey;
  if (agentKey.startsWith('topics/')) filename = agentKey.slice('topics/'.length);
  // try locate file in topics then top-level
  const topicPath = path.join(TOPICS_DIR, filename);
  const topPath = path.join(AGENTS_DIR, filename);
  const fm = fs.existsSync(topicPath) ? readFrontmatter(topicPath) : (fs.existsSync(topPath) ? readFrontmatter(topPath) : {});
  if (fm && (fm.protected === true || String(fm.protected).toLowerCase() === 'true' || String(fm.protected).toLowerCase() === 'yes')) return true;
  // fallback: check PROTECTED_AGENTS set by filename or basename
  if (PROTECTED_AGENTS.has(filename) || PROTECTED_AGENTS.has(path.basename(filename, '.md'))) return true;
  return false;
}

function isAgentUndeletable(agentKey) {
  let filename = agentKey;
  if (agentKey.startsWith('topics/')) filename = agentKey.slice('topics/'.length);
  const topicPath = path.join(TOPICS_DIR, filename);
  const topPath = path.join(AGENTS_DIR, filename);
  const fm = fs.existsSync(topicPath) ? readFrontmatter(topicPath) : (fs.existsSync(topPath) ? readFrontmatter(topPath) : {});
  if (fm && (fm.undeletable === true || String(fm.undeletable).toLowerCase() === 'true' || String(fm.undeletable).toLowerCase() === 'yes')) return true;
  return false;
}

function startupSync() {
  // Ensure agents with `always_on: true` are present in active.json at startup
  try {
    const activeSet = new Set(safeReadActive());
    const files = fs.existsSync(AGENTS_DIR) ? fs.readdirSync(AGENTS_DIR).filter(f => f.toLowerCase().endsWith('.md')) : [];
    const topics = fs.existsSync(TOPICS_DIR) ? fs.readdirSync(TOPICS_DIR).filter(f => f.toLowerCase().endsWith('.md')) : [];
    files.forEach(f => {
      const fm = readFrontmatter(path.join(AGENTS_DIR, f));
      if (fm && (fm.always_on === true || String(fm.always_on).toLowerCase() === 'true' || String(fm.always_on).toLowerCase() === 'yes')) activeSet.add(f);
    });
    topics.forEach(t => {
      const fm = readFrontmatter(path.join(TOPICS_DIR, t));
      if (fm && (fm.always_on === true || String(fm.always_on).toLowerCase() === 'true' || String(fm.always_on).toLowerCase() === 'yes')) activeSet.add(`topics/${t}`);
    });
    if (activeSet.size) {
      safeWriteActive(Array.from(activeSet));
      console.log('Startup sync: ensured always_on agents are active');
    }
  } catch (e) { /* ignore startup sync errors */ }
}

function safeReadActive() {
  try {
    if (!fs.existsSync(ACTIVE_PATH)) return [];
    const raw = fs.readFileSync(ACTIVE_PATH, 'utf8');
    const j = JSON.parse(raw || '[]');
    if (Array.isArray(j)) return j;
  } catch (e) {}
  return [];
}

function safeWriteActive(arr) {
  try {
    fs.mkdirSync(AGENTS_DIR, { recursive: true });
    fs.writeFileSync(ACTIVE_PATH, JSON.stringify(Array.from(new Set(arr)), null, 2), 'utf8');
    try { writeActiveStatus(Array.from(new Set(arr))); } catch (e) { /* ignore */ }
  } catch (e) { console.error('Failed to write active.json', e.message || e); }
}

function writeActiveStatus(activeArr) {
  // Build status map for all agents (top-level and topics)
  const status = {};
  const files = fs.existsSync(AGENTS_DIR) ? fs.readdirSync(AGENTS_DIR).filter(f => f.toLowerCase().endsWith('.md')) : [];
  const topics = fs.existsSync(TOPICS_DIR) ? fs.readdirSync(TOPICS_DIR).filter(f => f.toLowerCase().endsWith('.md')) : [];
  const topicKeys = topics.map(t => `topics/${t}`);
  files.forEach(f => { status[f] = (activeArr.includes(f) || activeArr.includes(path.basename(f, '.md')) ) ? 'active' : 'offline'; });
  topics.forEach(t => { const key = `topics/${t}`; status[key] = (activeArr.includes(key) || activeArr.includes(t) || activeArr.includes(path.basename(t, '.md')) ) ? 'active' : 'offline'; });
  try {
    fs.writeFileSync(ACTIVE_STATUS_PATH, JSON.stringify(status, null, 2), 'utf8');
  } catch (e) { console.error('Failed to write active-status.json', e.message || e); }
}

function listAgents() {
  const active = safeReadActive();
  if (!fs.existsSync(AGENTS_DIR)) {
    console.log('No .github/agents directory found');
    return;
  }
  const files = fs.readdirSync(AGENTS_DIR).filter(f => f.toLowerCase().endsWith('.md'));
  const topics = (fs.existsSync(TOPICS_DIR) ? fs.readdirSync(TOPICS_DIR).filter(f => f.toLowerCase().endsWith('.md')) : []);
  console.log('Available agents:');
  // If a topic with the same basename exists, prefer showing it under Topic agents
  const topicBasenames = new Set(topics.map(t => path.basename(t, '.md')));
  files.sort().forEach(name => {
    const base = path.basename(name, '.md');
    if (topicBasenames.has(base)) return; // skip top-level when a topic with same basename exists
    const activeFlag = active.includes(name) || active.includes(base) || active.includes(`topics/${name}`);
    console.log(`- ${name} ${activeFlag ? '(active)' : '(offline)'}`);
  });
  if (topics.length) {
    console.log('\nTopic agents:');
    topics.sort().forEach(t => {
      const key = `topics/${t}`;
      const activeFlag = active.includes(key) || active.includes(t) || active.includes(path.basename(t, '.md'));
      console.log(`- ${t} ${activeFlag ? '(active)' : '(offline)'}`);
    });
  }
}

async function activate(name) {
  if (!name) return console.error('Specify agent filename or basename to activate');
  const top = fs.existsSync(AGENTS_DIR) ? fs.readdirSync(AGENTS_DIR).filter(f => f.toLowerCase().endsWith('.md')) : [];
  const topics = fs.existsSync(TOPICS_DIR) ? fs.readdirSync(TOPICS_DIR).filter(f => f.toLowerCase().endsWith('.md')) : [];
  // Accept exact filename, basename, or topics/<file>
  let found = top.find(c => c.toLowerCase() === name.toLowerCase() || path.basename(c, '.md').toLowerCase() === name.toLowerCase());
  let key = null;
  if (found) key = found;
  else {
    const tmatch = topics.find(t => t.toLowerCase() === name.toLowerCase() || path.basename(t, '.md').toLowerCase() === name.toLowerCase());
    if (tmatch) { found = tmatch; key = `topics/${tmatch}`; }
  }
  if (!found) return console.error('Agent not found:', name);
  const active = safeReadActive();
  if (!active.includes(key || found)) active.push(key || found);
  safeWriteActive(active);
  // If the template exports activate, call it for lifecycle logging
  if (agentTemplate && typeof agentTemplate.activate === 'function') {
    try { await agentTemplate.activate({ agent: found }); } catch (e) { /* ignore */ }
  }
  console.log('Activated', found);
}

async function deactivate(name) {
  const active = safeReadActive();
  const force = process.argv.includes('--force') || process.argv.includes('-f');
  if (!name) {
    // clear all — but refuse if any undeletable agents would be removed (never allow),
    // or if protected agents would be removed (allow only with --force)
    const undeletablePresent = active.some(a => isAgentUndeletable(a));
    if (undeletablePresent) return console.error('Cannot clear active agents: an undeletable agent is active');
    const protectedPresent = active.some(a => isAgentProtected(a));
    if (protectedPresent && !force) return console.error('Cannot clear active agents: a protected agent is active (use --force to override)');
    if (protectedPresent && force) console.log('Force override: clearing active agents including protected ones');
    safeWriteActive([]);
    console.log('Cleared all active agents');
    return;
  }
  // Accept topics/<file>, basename, or filename
  const idx = active.findIndex(x => x.toLowerCase() === name.toLowerCase() || path.basename(x, '.md').toLowerCase() === name.toLowerCase() || x.toLowerCase() === (`topics/${name.toLowerCase()}`));
  if (idx === -1) return console.error('Agent not active:', name);
  const candidate = active[idx];
  // Prevent deactivating undeletable agents (never allow), and protected agents unless forced
  if (isAgentUndeletable(candidate)) return console.error('Agent is undeletable and cannot be deactivated:', candidate);
  if (isAgentProtected(candidate) && !force) return console.error('Agent is protected and cannot be deactivated:', candidate, '(use --force to override)');
  if (isAgentProtected(candidate) && force) console.log('Force override: deactivating protected agent', candidate);
  const removed = active.splice(idx, 1)[0];
  safeWriteActive(active);
  if (agentTemplate && typeof agentTemplate.deactivate === 'function') {
    try { await agentTemplate.deactivate({ agent: removed }); } catch (e) { /* ignore */ }
  }
  console.log('Deactivated', removed);
}

function status() {
  const active = safeReadActive();
  console.log('Active agents:', active.length ? active.join(', ') : '(none)');
}

function help() {
  console.log('Usage: node tools/agent-manager.js <command> [agent]');
  console.log('Commands: list, status, activate <agent>, deactivate [agent] [--force]');
}

async function main() {
  // perform startup sync to ensure always_on agents are active
  try { startupSync(); } catch (e) { /* ignore */ }
  const argv = process.argv.slice(2);
  const cmd = argv[0];
  if (!cmd) return help();
  if (cmd === 'list') return listAgents();
  if (cmd === 'status') return status();
  if (cmd === 'activate') return activate(argv[1]);
  if (cmd === 'deactivate') return deactivate(argv[1]);
  return help();
}

main().catch(e => { console.error(e && e.message ? e.message : e); process.exit(1); });
