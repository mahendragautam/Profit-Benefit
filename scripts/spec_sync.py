#!/usr/bin/env python3
"""Simple sync checker for Theme spec machine-spec index.

This script optionally accepts `--agent` and `--run-mode` CLI arguments and
will also respect `AGENT_NAME` and `RUN_MODE` environment variables. Those
values are currently informational for logging and future integrations.
"""
from pathlib import Path
import re
import sys
import os
import datetime
from typing import Optional
import json

ROOT = Path(__file__).resolve().parents[1]
MACHINE_SPEC = ROOT / 'Theme spec' / 'machine-spec'
INDEX = MACHINE_SPEC / 'index.md'


def parse_index(index_path: Path):
    text = index_path.read_text(encoding='utf-8')
    # find backticked filenames like `foo.md`
    names = re.findall(r'`([^`]+\.md)`', text)
    return sorted(set(names))


def list_files(folder: Path):
    return sorted([p.name for p in folder.glob('*.md') if p.is_file()])


def get_inputs(argv) -> tuple[Optional[str], Optional[str], Optional[str]]:
    """Return (agent_name, run_mode, agents_stack) extracted from CLI args or environment.

    CLI args take precedence. Supported flags:
      --agent AGENT_NAME
      --run-mode RUN_MODE
      --agents AGENT+STACK (e.g. wordpress.theme+security)
    """
    agent = None
    run_mode = None
    agents_stack = None
    args = list(argv)
    while args:
        a = args.pop(0)
        if a == '--agent' and args:
            agent = args.pop(0)
        elif a == '--run-mode' and args:
            run_mode = args.pop(0)
        elif a == '--agents' and args:
            agents_stack = args.pop(0)

    if not agent:
        agent = os.environ.get('AGENT_NAME')
    if not run_mode:
        run_mode = os.environ.get('RUN_MODE')
    if not agents_stack:
        agents_stack = os.environ.get('AGENTS')
    return agent, run_mode, agents_stack


def run_agent_tasks(agents_stack: Optional[str]) -> tuple:
    """Map agent names to test tasks and run them.

    Returns a tuple (exit_code:int, tasks_results:dict). exit_code is 0 for success,
    non-zero for failures. `tasks_results` contains per-agent results.

    Current built-in mappings:
      - security: run `php -l` on PHP files (if `php` available)
      - testing: run `vendor/bin/phpunit` if present, otherwise `npm test` if package.json exists
    """
    if not agents_stack:
        return 0, {}

    agents = [a.strip() for a in agents_stack.split('+') if a.strip()]
    if not agents:
        return 0, {}

    import subprocess
    from shutil import which

    overall_ok = True
    tasks_results = {}
    for ag in agents:
        if ag == 'wordpress.theme':
            # baseline agent — no extra tasks
            print('Agent: wordpress.theme (baseline) — no additional tasks')
            continue

        print(f"Running tasks for agent: {ag}")
        if ag == 'security':
            task_result = {"ok": True, "details": []}
            php = which('php')
            if php:
                # run php -l on all PHP files
                php_files = list(Path('.').glob('**/*.php'))
                if not php_files:
                    print('No PHP files found to lint.')
                else:
                    for p in php_files:
                        try:
                            r = subprocess.run([php, '-l', str(p)], capture_output=True, text=True)
                            detail = {"file": str(p).replace('\\', '/'), "ok": r.returncode == 0, "output": (r.stdout + r.stderr).strip()}
                            task_result["details"].append(detail)
                            if r.returncode != 0:
                                overall_ok = False
                                print(f"PHP lint failed for {p}:\n{r.stdout}{r.stderr}")
                            else:
                                print(f"OK: {p}")
                        except Exception as e:
                            overall_ok = False
                            task_result["details"].append({"file": str(p).replace('\\', '/'), "ok": False, "output": str(e)})
                            print(f"Error running php -l on {p}: {e}")
            else:
                print('php not found in PATH — skipping PHP lint for security agent')
                task_result["note"] = 'php not found'
            tasks_results["security"] = task_result

        elif ag == 'testing':
            task_result = {"ok": True, "details": []}
            # prefer PHPUnit if present
            phpunit = Path('vendor') / 'bin' / 'phpunit'
            if phpunit.exists():
                try:
                    r = subprocess.run([str(phpunit)], capture_output=True, text=True)
                    print(r.stdout)
                    if r.returncode != 0:
                        overall_ok = False
                        task_result["ok"] = False
                        task_result["output"] = r.stdout + r.stderr
                        print('PHPUnit tests failed')
                except Exception as e:
                    overall_ok = False
                    task_result["ok"] = False
                    task_result["output"] = str(e)
                    print(f'Error running PHPUnit: {e}')
            else:
                # fallback to npm test if package.json exists
                if Path('package.json').exists() and which('npm'):
                    try:
                        r = subprocess.run(['npm', 'test'], capture_output=True, text=True)
                        print(r.stdout)
                        if r.returncode != 0:
                            overall_ok = False
                            task_result["ok"] = False
                            task_result["output"] = r.stdout + r.stderr
                            print('npm test failed')
                    except Exception as e:
                        overall_ok = False
                        task_result["ok"] = False
                        task_result["output"] = str(e)
                        print(f'Error running npm test: {e}')
                else:
                    print('No test runner found for testing agent (no vendor/bin/phpunit and no npm)')
                    task_result["note"] = 'no test runner found'
            tasks_results["testing"] = task_result

        else:
            print(f'No mapped tasks for agent: {ag} — skipping')
            tasks_results.setdefault(ag, {"ok": True, "note": "no tasks mapped"})

    return (0 if overall_ok else 2), tasks_results


def main(argv=None):
    if argv is None:
        argv = sys.argv[1:]

    agent_name, run_mode, agents_stack = get_inputs(argv)

    if agent_name:
        print(f"Agent: {agent_name}")
    if run_mode:
        print(f"Run mode: {run_mode}")
    if agents_stack:
        print(f"Agents stack: {agents_stack}")

    if not MACHINE_SPEC.exists():
        print(f"ERROR: machine-spec folder not found at {MACHINE_SPEC}")
        return 2

    if not INDEX.exists():
        print(f"ERROR: index file not found at {INDEX}")
        return 2

    index_files = parse_index(INDEX)
    actual_files = list_files(MACHINE_SPEC)

    # Ignore the index file itself when comparing
    actual_files_no_index = [f for f in actual_files if f != 'index.md']

    # Allow for older-style names in the index (e.g. 'security.md') by
    # mapping them to the new 'theme-security.md' if present on disk.
    adjusted_index = []
    for f in index_files:
        if f in actual_files_no_index:
            adjusted_index.append(f)
        else:
            theme_name = f"theme-{f}"
            if theme_name in actual_files_no_index:
                adjusted_index.append(theme_name)
            else:
                adjusted_index.append(f)

    missing_in_index = [f for f in actual_files_no_index if f not in adjusted_index]
    missing_on_disk = [f for f in adjusted_index if f not in actual_files_no_index]

    ok = True
    if missing_in_index:
        ok = False
        print("Files present in machine-spec but missing from index.md:")
        for f in missing_in_index:
            print("  -", f)

    if missing_on_disk:
        ok = False
        print("Files listed in index.md but not found in machine-spec folder:")
        for f in missing_on_disk:
            print("  -", f)

    if ok:
        print("machine-spec index is consistent with files.")
        spec_code = 0
    else:
        spec_code = 1

    # run any agent-specific tasks
    task_code, tasks_results = run_agent_tasks(agents_stack)

    # Build JSON report and write to .vscode/agent-reports
    report = {
        "timestamp": datetime.datetime.utcnow().isoformat() + 'Z',
        "agents": agents_stack,
        "spec": {
            "ok": bool(ok),
            "missing_in_index": missing_in_index,
            "missing_on_disk": missing_on_disk
        },
        "tasks": tasks_results
    }

    try:
        reports_dir = Path('.') / '.vscode' / 'agent-reports'
        reports_dir.mkdir(parents=True, exist_ok=True)
        safe_name = (agents_stack or 'agents').replace('/', '_').replace('\\', '_')
        ts = datetime.datetime.utcnow().isoformat().replace(':', '-').replace('.', '-')
        json_path = reports_dir / f"{ts}__{safe_name}.json"
        json_path.write_text(json.dumps(report, indent=2), encoding='utf-8')
        print(f"REPORT_JSON: {str(json_path)}")
    except Exception as e:
        print(f"Failed to write report JSON: {e}")

    if spec_code == 0 and task_code == 0:
        return 0
    # non-zero if either failed
    return 1


if __name__ == '__main__':
    sys.exit(main())
