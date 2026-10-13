# PLAN.md

Master plan for reviewing and modernizing all apps. Every agentic session works on exactly one app, follows this plan and ticks off the tasks it completes. Update this file in the same commit as the work.

## Session protocol

1. Read `AGENTS.md` and this file. Pick the first app in the order below that is not done (or the one the user names).
2. Work only inside that app's directory (`apps/<category>/<app>/`) plus `docs/<app>.md` and this file.
3. Work through the per-app workflow below. Tick `[x]` for finished tasks, add notes under the app if something is unclear or deferred.
4. Ask the user before every decision marked "user decision" (duplicates, target stack, deleting anything).
5. Commit with Conventional Commits. Legacy code is committed per app, only after the review and duplicate cleanup of that app (step 3 below). Never push without being asked.

## Global rules (summary)

- All files in English; German only in chat.
- Do not remove images or other assets.
- No credentials in the repo; secrets are placeholders (`DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`, `SMTP_USER`, `SMTP_PASSWORD`). Modernized code reads config from `.env` (commit only `.env.example`).
- No GitHub Actions for now.
- Target language is JavaScript, preferably TypeScript with a framework. Stack is decided per app in step 4.
- Monorepo (workspaces under `apps/`) is undecided; see "Open decisions". Until then every app is standalone (see "Shared conventions").

## Shared conventions

All modernized apps use the same runtime, dependency versions and conventions, so a later monorepo switch only adds a root `package.json` with workspaces.

- **Standalone apps:** each `modernized/` has its own `package.json` and lockfile, installs with `npm install` inside its folder and depends on no root files.
- **One Node.js version:** defined once in `.nvmrc` at the repo root (current LTS, chosen by the first modernized app). Every `package.json` sets the same `engines.node`.
- **Same dependency versions:** shared dependencies (TypeScript, ESLint, Prettier, Vitest, and the chosen framework if apps share one) use identical, exact versions in all apps. The baseline is set by the first app and recorded in `docs/conventions.md`. Version bumps are applied to all apps together.
- **Same tooling and scripts:** TypeScript `strict`, ESLint, Prettier, Vitest; every app provides the scripts `dev`, `build`, `test`, `lint`.
- **Same layout:** `modernized/` contains `README.md`, `.env.example`, `src/`, `tests/`, and the app's assets.
- **Deviations** (different framework, extra dependency) are allowed per app but must be stated with a reason in `docs/<app>.md`.

## Per-app workflow

1. **Inspect** the legacy code and list files, entry points, pages, assets, external dependencies.
2. **Analyze (reverse engineer)** and write `docs/<app>.md`: purpose, features, page/request flow, data model (derive DB schema from SQL), external services, security issues (SQL injection, plaintext passwords, `mysql_*`, XSS), dead code.
3. **Clean up legacy**: check for remaining secrets, remove duplicates and junk only after user approval (never assets), then commit legacy (`chore(<app>): add legacy code`).
4. **Decide stack** (user decision): TypeScript framework, backend or static, database (e.g. SQLite instead of MySQL), hosting needs. Record it in `docs/<app>.md`.
5. **Modernize** into `modernized/`: project setup, port features, `.env.example`, secure auth and queries, keep assets.
6. **Test**: unit or e2e tests for the core logic, manual run-through of main flows.
7. **Document**: `modernized/README.md` (prerequisites, run, build, test), update the status table in the root `README.md` and the checkbox here.
8. **Commit** (`feat(<app>): modernize ...`).

## Order (simple to hard)

Sizes are file counts of the legacy code.

### 1. misc/richkidzofupo (3 files)

- [x] Inspect
- [x] Analyze (`docs/richkidzofupo.md`)
- [x] Clean up and commit legacy
- [x] Decide stack
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 2. misc/email-check (5 files)

- [x] Inspect
- [x] Analyze (`docs/email-check.md`)
- [x] Clean up and commit legacy
- [x] Decide stack
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 3. web/verwaltungstool (36 files, 32 after cleanup)

- [x] Inspect
- [x] Analyze (`docs/verwaltungstool.md`)
- [x] Clean up and commit legacy
- [x] Decide stack
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 4. web/greenscreen (54 files, JS dashboard with PHP proxies for stocks, public transport, traffic)

- [ ] Inspect
- [ ] Analyze (`docs/greenscreen.md`)
- [ ] Clean up and commit legacy
- [ ] Decide stack
- [ ] Modernize
- [ ] Test
- [ ] Document
- [ ] Commit

### 5. games/hoehlenmensch (59 files)

- [ ] Inspect
- [ ] Analyze (`docs/hoehlenmensch.md`)
- [ ] Clean up and commit legacy
- [ ] Decide stack
- [ ] Modernize
- [ ] Test
- [ ] Document
- [ ] Commit

### 6. sites/hallindi (164 files, static; similar to kia)

- [ ] Inspect
- [ ] Analyze (`docs/hallindi.md`)
- [ ] Clean up and commit legacy
- [ ] Decide stack (consider sharing it with kia)
- [ ] Modernize
- [ ] Test
- [ ] Document
- [ ] Commit

### 7. sites/kia (159 files, static; similar to hallindi)

- [ ] Inspect
- [ ] Analyze (`docs/kia.md`)
- [ ] Clean up and commit legacy
- [ ] Decide stack (consider reusing hallindi)
- [ ] Modernize
- [ ] Test
- [ ] Document
- [ ] Commit

### 8. web/manageyourteam (`legacy` 132 files, `legacy-neu` 134 files, formerly `myt_neu`)

- [ ] Inspect
- [ ] Diff `legacy` against `legacy-neu`; user decision which to keep
- [ ] Analyze (`docs/manageyourteam.md`; large `inc/DB.php`)
- [ ] Clean up and commit legacy (check the contact address `benutzerverwaltung@...` is intended to stay)
- [ ] Decide stack
- [ ] Modernize
- [ ] Test
- [ ] Document
- [ ] Commit

### 9. web/bootstrap and web/iusemybrain (161 and 167 files, nearly identical)

- [ ] Inspect
- [ ] Diff `bootstrap` against `iusemybrain`; user decision how to merge (one app, one legacy)
- [ ] Analyze (`docs/iusemybrain.md`)
- [ ] Clean up and commit legacy
- [ ] Decide stack
- [ ] Modernize
- [ ] Test
- [ ] Document
- [ ] Commit

### 10. games/bierspiel (v1 to v6, 923 files, 39 MB)

- [ ] Inspect all versions
- [ ] Diff versions; user decision which versions to keep as legacy and which to modernize (likely v6)
- [ ] Analyze (`docs/bierspiel.md`; evolution v1 to v6, game rules, DB model)
- [ ] Clean up and commit legacy (per version)
- [ ] Decide stack
- [ ] Modernize
- [ ] Test
- [ ] Document
- [ ] Commit

## Open decisions

- [x] Baseline for "Shared conventions" (Node version in `.nvmrc`, dependency versions, ESLint/Prettier/TypeScript/Vitest config) defined by the first modernized app and written to `docs/conventions.md`
- [ ] Monorepo with workspaces under `apps/`: decide after the first three apps (richkidzofupo, email-check, verwaltungstool) are modernized
- [ ] Shared components between apps (e.g. auth, mailer, hallindi/kia template)
