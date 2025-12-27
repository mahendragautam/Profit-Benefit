#!/usr/bin/env python3
import sys
import json
import fnmatch
from pathlib import Path

def load_protected(path):
    j = json.loads(Path(path).read_text(encoding='utf-8'))
    return j.get('protected', [])

def matches_any(path, patterns):
    p = path.replace('\\', '/')
    for pat in patterns:
        if fnmatch.fnmatch(p, pat):
            return True
    return False

def main():
    prot_path = '.github/protected-files.json'
    if not Path(prot_path).exists():
        print('No protected-files.json found; skipping check')
        return 0
    patterns = load_protected(prot_path)
    # read changed files from stdin
    changed = [l.strip() for l in sys.stdin.read().splitlines() if l.strip()]
    if not changed:
        print('No changed files detected')
        return 0
    blocked = [f for f in changed if matches_any(f, patterns)]
    if blocked:
        print('ERROR: Changes to protected files detected:')
        for b in blocked:
            print('  -', b)
        print('\nProtected patterns:')
        for p in patterns:
            print('  -', p)
        return 2
    print('OK: No protected files changed')
    return 0

if __name__ == '__main__':
    sys.exit(main())
