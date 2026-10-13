# AGENTS.md

## Goal

Analyze (reverse engineer) old PHP/JS applications under `apps/**/legacy/` and migrate them to JavaScript/TypeScript in `apps/**/modernized/`. The framework is chosen separately per app.

## Rules

- Language: all Markdown files and all other files (code, comments, commit messages, docs) must be written in English. German is used only in the chat with the user.
- Do not modify `legacy/`, read only. Exception: secrets are replaced by placeholders such as `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`, `SMTP_USER`, `SMTP_PASSWORD`.
- Never remove images or other assets; the apps need them.
- Do not commit credentials: `connect*.php` and `configuration.php` in `legacy/` may contain secrets. In modernized code, use `.env` for configuration.
- Record analyses and status in `docs/`.
- No GitHub Actions for now.
- A possible later switch to a monorepo (workspaces under `apps/`) is still open.

## Commits

Conventional Commits, e.g. `docs: ...`, `feat: ...`, `refactor: ...`, `chore: ...`.
