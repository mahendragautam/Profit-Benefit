#!/usr/bin/env python3
import json
import fnmatch
import subprocess
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
PROT = ROOT / '.github' / 'protected-files.json'

def load_patterns():
    if not PROT.exists():
        print('No protected-files.json found')
        return []
    j = json.loads(PROT.read_text(encoding='utf-8'))
    return j.get('protected', [])

def git_ls_files():
    out = subprocess.check_output(['git','ls-files'], cwd=str(ROOT))
    return out.decode().splitlines()

def matches_any(path, patterns):
    p = path.replace('\\','/')
    for pat in patterns:
        if fnmatch.fnmatch(p, pat):
            return True
    return False

def main():
    patterns = load_patterns()
    files = git_ls_files()
    agent_files = sorted([f for f in files if 'agent' in f.lower() or 'agents' in f.lower() or 'agent-' in f.lower() or f.upper().startswith('AGENT')])
    protected = []
    unprotected = []
    for f in agent_files:
        if matches_any(f, patterns):
            protected.append(f)
        else:
            unprotected.append(f)

    print('Protected patterns:')
    for p in patterns:
        print('  -', p)
    print('\nAgent-related tracked files:')
    for f in agent_files:
        print('  -', f)

    print('\nProtected:')
    for f in protected:
        print('  -', f)

    print('\nUnprotected:')
    for f in unprotected:
        print('  -', f)

    print('\nSummary: {} agent-related files, {} protected, {} unprotected'.format(len(agent_files), len(protected), len(unprotected)))
    return 0

if __name__ == '__main__':
    raise SystemExit(main())
