```markdown
# Usage Guide — How to use these agent specifications


Purpose: Explain when to consult `machine-spec` vs `docs` and how to make changes that keep automation happy.

When to change `machine-spec`

- Add or update a file in `machine-spec/` when you need the automation to load a concise, machine-focused rule set.
- Any structural change requires updating `machine-spec/index.md` and ensuring `scripts/spec_sync.py` passes.

When to change `docs/`

- Use `docs/` for long-form guides, examples, migration instructions, and troubleshooting steps intended for humans.

Making contributions

1. Update or add a `machine-spec` file with concise, testable rules.
2. Update `machine-spec/index.md` to include the new filename and a one-line description.
3. Run `python3 scripts/spec_sync.py` locally to confirm consistency.
4. Add detailed examples to `Theme spec/docs/` if needed.

Example PR checklist

 - `machine-spec` updated (if required)
 - `machine-spec/index.md` updated
- `scripts/spec_sync.py` passes
- Tests: PHPCS, Theme Check, basic Lighthouse/Axe run

```