# Git status symbols and VS Code Source Control quick guide

This file explains the common status letters and words you see in Visual Studio Code's Explorer and Source Control view (the small letters like `U`, `M`, etc.). It also lists quick commands and UI actions to act on those files.

## Meaning of common symbols

- `U` — Untracked: the file is new in the working directory and is not yet added to Git. (Equivalent to `git status` showing an untracked file.)
- `M` — Modified: the tracked file has changes in the working tree that are not yet staged.
- `A` — Added / Staged: the file has been staged for commit (added to the index).
- `D` — Deleted: the file was removed from the working tree (or staged for deletion).
- `R` — Renamed: Git detected a rename (old → new path).
- `C` — Copied: Git detected a copy operation.
- `U` (in merge contexts) — Unmerged / conflict state: during a merge conflict you may also see conflict markers; VS Code shows these under Source Control as conflicted files. (Context-dependent; in Explorer `U` normally means untracked.)

Note: Git's short status uses two-letter codes (`XY`) where `X` is the staged state and `Y` is the working-tree state. VS Code surface-level letters are a simplified view.

## How it maps to VS Code UI

- Explorer gutter / File decorations: a small icon or letter next to a file indicates its Git state (e.g., a green `+` or `A` for added, `M` for modified). Hovering the icon sometimes shows the full status tooltip.
- Source Control view (side bar): lists changed files grouped by status. Clicking a file shows a diff.

## Useful Git commands

- Show status (short):
  ```bash
  git status --short
  # Example output:
  # U  .github/agents/topics/api.md   # untracked
  # M  README.md                      # modified
  # A  src/newfile.js                 # staged
  ```
- View diff (unstaged): `git diff <path>`
- View diff (staged): `git diff --staged <path>`
- Stage a file: `git add <path>`
- Commit staged changes: `git commit -m "message"`
- Revert unstaged changes: `git restore <path>` (or `git checkout -- <path>` for older Git)
- Remove untracked files (careful): `git clean -f <path>`

## Quick VS Code actions

- Stage a file: open Source Control view, click the `+` (Stage Changes) next to the file.
- View diff: click a file in Source Control or Explorer to see the inline diff editor.
- Discard changes: right-click the file → *Discard Changes* (reverts unstaged changes).
- Commit: fill the commit message atop Source Control and press the checkmark.
- Add to `.gitignore`: right-click an untracked file and choose *Add to .gitignore* if you do not want Git to track it.

## When a file is untracked (`U`) — what it means for your workflow

- The file exists on disk and any local scripts or tools (for example `node tools/agent-manager.js`) can read and use it immediately.
- `U` simply means Git hasn't recorded the file in the repository history; it does not affect runtime behavior.
- If you want the file included in the repo history, stage and commit it:
  ```bash
  git add <path>
  git commit -m "Add <path>"
  ```

## Summary

- `U` = untracked (new file) — stage with `git add` to remove the `U` marker.
- `M` = modified — stage and commit, or discard to revert.
- Use VS Code Source Control for convenient staging, diffing, and committing.

If you want, I can add this file to the repository index (commit it) so it stops appearing as untracked, or place it under a `docs/` folder instead. Which do you prefer?
