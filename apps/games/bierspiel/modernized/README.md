# Schankimperium (modernized)

Browser game (formerly "Bierspiel"): build and run pubs, buy drinks, set menu prices, take loans, hire staff on the labour market. Port of legacy `v6` (brewery game) plus the `v5` labour market, built with Next.js (App Router, server actions), TypeScript and SQLite (`better-sqlite3`).

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root).

## Run

```sh
cp .env.example .env   # set DB_PATH and TICK_SECRET
npm install            # run once at the repo root
npm run dev            # dev server on http://localhost:3000
npm run build          # type-check and production build
npm start              # run the production build
npm test               # Vitest (accounts, buildings, bank, market, settlement, labour, messages)
npm run lint           # ESLint + Prettier check
```

The SQLite file is created at `DB_PATH` (default `data/app.db`) on first start and seeded with invented prices, districts and workers.

## Weekly tick

Settlement (interest, sales, price drift, labour market bids, payroll, account deletion purge) runs in one transaction when the tick endpoint is called, e.g. from cron:

```sh
curl -X POST -H "Authorization: Bearer $TICK_SECRET" http://localhost:3000/api/tick
```

Without the secret the endpoint answers 403; an unset or `change-me` secret disables it.

## Notes

Deviations from the legacy game (no mail, 20-liter market unit, invented seeds, building cap, fixed bugs) are listed in `docs/bierspiel.md`, section "Modernization notes".
