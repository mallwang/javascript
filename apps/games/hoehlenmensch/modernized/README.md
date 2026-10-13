# hoehlenmensch (modernized)

Timer-based Stone Age browser game ("Steinzeitspiel - Reloaded") built with Next.js (App Router, server actions), TypeScript and SQLite (`better-sqlite3`). Register, walk through the image-map scenes, hunt, gather, fish, fell wood, cut stone, find water and craft tools. Each job takes real time and costs food and water.

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root).

## Run

```sh
cp .env.example .env   # optional, only sets DB_PATH
npm install            # run once at the repo root
npm run dev            # dev server on http://localhost:3000
npm run build          # type-check and production build
npm start              # run the production build
npm test               # Vitest (rules, jobs, registration)
npm run lint           # ESLint + Prettier check
```

The SQLite file is created at `DB_PATH` (default `data/app.db`) on first start and seeded with the food tables.

## Notes

Finished jobs are credited when a page is loaded after the end time (no background job). Seed data for the food tables is invented. See `docs/hoehlenmensch.md` for the list of fixes and what was not ported (e-mail features, profile page).
