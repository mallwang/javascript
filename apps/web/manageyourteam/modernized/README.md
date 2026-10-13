# manageyourteam (modernized)

Football club management built with Next.js (App Router, server actions), TypeScript and SQLite (`better-sqlite3`). Register a club, create teams, coaches and players, plan trainings, matches and tournaments, record results and attendance, and set the line-up per team.

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root).

## Run

```sh
cp .env.example .env   # optional, only sets DB_PATH
npm install            # run once at the repo root
npm run dev            # dev server on http://localhost:3000
npm run build          # type-check and production build
npm start              # run the production build
npm test               # Vitest (rules, authorization, events, attendance)
npm run lint           # ESLint + Prettier check
```

The SQLite file is created at `DB_PATH` (default `data/app.db`) on first start.

## Notes

No mail is sent: the club admin sets the initial password for coaches and players. See `docs/manageyourteam.md` for fixes and what was not ported.
