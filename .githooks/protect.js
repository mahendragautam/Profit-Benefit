#!/usr/bin/env node
const fs = require('fs');
const { execSync } = require('child_process');
const path = require('path');

const repoRoot = path.resolve(__dirname, '..');
const protectedFiles = [
  '.github/agents/wordpress.theme.agent.md',
  'tools/agent-manager.js',
  '.github/agents/topics/accessibility.md',
  '.github/agents/topics/block.md',
  '.github/agents/topics/classic.md',
  '.github/agents/topics/demo-content.md',
  '.github/agents/topics/marketplace-marketing.md',
  '.github/agents/topics/performance.md',
  '.github/agents/topics/security.md',
  '.github/agents/topics/testing.md',
  '.github/agents/topics/woocommerce.md'
];

function protectFile(rel) {
  const p = path.join(repoRoot, rel);
  if (!fs.existsSync(p)) return console.warn('Missing:', rel);
  try {
    if (process.platform === 'win32') {
      execSync(`attrib +R "${p}"`);
    } else {
      // set read-only for owner/group/others
      fs.chmodSync(p, 0o444);
    }
    console.log('Protected:', rel);
  } catch (e) {
    console.warn('Failed to protect', rel, e && e.message ? e.message : e);
  }
}

protectedFiles.forEach(protectFile);
