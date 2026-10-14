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
- Monorepo: npm workspaces (`apps/*/*/modernized`) plus Nx for cached task running; see "Shared conventions".

## Shared conventions

All modernized apps use the same runtime, dependency versions and conventions and live in one npm workspaces monorepo with Nx (decided after app 3).

- **Workspaces:** one root `package.json`, one `package-lock.json`, `npm install` at the repo root. Each `modernized/` has its own `package.json` with only app-specific dependencies and extends `tsconfig.base.json`. ESLint and Prettier configs live in the root. Nx (`nx.json`, no generators or plugins) runs the existing scripts with caching: `npm run lint|test|build` at the root or `npx nx <task> <app>`.
- **One Node.js version:** defined once in `.nvmrc` at the repo root (current LTS, chosen by the first modernized app). Every `package.json` sets the same `engines.node`.
- **Same dependency versions:** shared tooling (TypeScript, ESLint, Prettier, Vitest, Nx) is declared once in the root `package.json`; other shared dependencies (and the chosen framework if apps share one) use identical, exact versions in all apps. The baseline is set by the first app and recorded in `docs/conventions.md`. Version bumps are applied to all apps together.
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

- [x] Inspect
- [x] Analyze (`docs/greenscreen.md`)
- [x] Clean up and commit legacy
- [x] Decide stack
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 5. games/hoehlenmensch (59 files)

- [x] Inspect
- [x] Analyze (`docs/hoehlenmensch.md`)
- [x] Clean up and commit legacy
- [x] Decide stack
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 6. sites/hallindi (164 files, static; similar to kia)

- [x] Inspect
- [x] Analyze (`docs/hallindi.md`)
- [x] Clean up and commit legacy
- [x] Decide stack
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 7. sites/kia (159 files, static; similar to hallindi)

- [x] Inspect
- [x] Analyze (`docs/kia.md`)
- [x] Clean up and commit legacy
- [x] Decide stack
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 8. web/manageyourteam (`legacy` 132 files, `legacy-neu` 134 files, formerly `myt_neu`)

- [x] Inspect
- [x] Diff `legacy` against `legacy-neu`; user decision which to keep
- [x] Analyze (`docs/manageyourteam.md`; large `inc/DB.php`)
- [x] Clean up and commit legacy (check the contact address `benutzerverwaltung@...` is intended to stay)
- [x] Decide stack
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 9. web/iusemybrain (`bootstrap/` was a near-identical older copy and was deleted)

- [x] Inspect
- [x] Diff `bootstrap` against `iusemybrain`; user decision how to merge (one app, one legacy)
- [x] Analyze (`docs/iusemybrain.md`)
- [x] Clean up and commit legacy
- [x] Decide stack
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 10. games/bierspiel (v1 to v6, 923 files, 39 MB)

- [x] Inspect all versions
- [x] Diff versions; user decision which versions to keep as legacy and which to modernize

Decision: two lines exist, A = v1 to v2 to v3 to v6 (brewery: buildings, goods markets, bank) and B = v4 to v5 (Metro UI, labour market with bids). Keep `v5` and `v6` as legacy; `v1` to `v4` were deleted (earlier stages or near-identical). Modernize v6 and port the labour market from v5. The v5 labour market must be documented in `docs/bierspiel.md`.

The game was originally one browser game called "Schankimperium" spread over several repos. The GitLab exports were checked: none contained newer game code than `v5`/`v6`, and the two PHP exports were deleted (older copies of `v4` and `v2`). The game design from the Schankimperium monorepo was saved as `docs/bierspiel-concept.md` (vision, not scope of the port) and the empty Nx/Angular scaffold was deleted; a rewrite is a separate later project.

- [x] Analyze (`docs/bierspiel.md`; evolution v1 to v6, game rules, DB model)
- [x] Clean up and commit legacy (per version)
- [x] Decide stack (Next.js + TypeScript + SQLite; port 1:1, title "Schankimperium"; a later rewrite may change the stack)
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 11. web/unser-blog (64 files, PHP blog with subscriptions; source `gitlab-export/unser-blog-main`)

- [x] Inspect
- [x] Analyze (`docs/unser-blog.md`)
- [x] Clean up and commit legacy (secrets replaced, `gruppe1.sql` anonymized, Eclipse junk removed)
- [x] Decide stack (Next.js + TypeScript + SQLite, no mail delivery)
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 12. web/family-adventskalender (145 files, 60 MB, Ionic/Angular 10 PWA without backend; source `gitlab-export/family-adventskalender-master`)

- [x] Inspect
- [x] Analyze (`docs/family-adventskalender.md`)
- [x] Clean up and commit legacy (GitLab templates, CI, Docker, commitlint config and lockfile removed)
- [x] Decide stack (Vite + TypeScript, no framework; hand-written service worker)
- [x] Modernize
- [x] Test
- [x] Document
- [x] Commit

### 13. web/procumer-dashboard (130 files, Nx with Angular 10 frontend and NestJS API reading a private XLSX; source `gitlab-export/procumer-dashboard-master`)

Finding: only the strom part works; wasser/pellet are stubs and the dashboard page is only a heading. The private XLSX and `.env` are not committed; the modernized app imports a user-supplied XLSX locally into SQLite.

- [x] Inspect
- [x] Analyze (`docs/procumer-dashboard.md`)
- [x] Clean up and commit legacy (XLSX, `.env`, lockfile, commitlint config, `.vscode` removed)
- [x] Decide stack (Next.js + TypeScript + SQLite; XLSX supplied locally by the user, never committed)
- [ ] Modernize
- [ ] Test
- [ ] Document
- [ ] Commit

## Open decisions

- [x] Baseline for "Shared conventions" (Node version in `.nvmrc`, dependency versions, ESLint/Prettier/TypeScript/Vitest config) defined by the first modernized app and written to `docs/conventions.md`
- [x] Monorepo: npm workspaces + Nx (decided and set up after the first three apps)
- [x] Shared components between apps (e.g. auth, mailer, hallindi/kia template): **rejected**. Every app stays a self-contained application that can be copied out of the monorepo as a folder. Some apps (e.g. both games) are meant to be extracted into their own repos later, so apps must not import code from each other or from shared packages.
