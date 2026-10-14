# iusemybrain (modernized)

Directory of special places in Germany (sights, parks, museums) built with Next.js (App Router, server actions), TypeScript and SQLite (`better-sqlite3`). Anyone can submit a place; an admin approves it. Also: accounts, a searchable library, a support form, the memory game, APK downloads and an XML places API for the Android app.

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root).

## Run

```sh
cp .env.example .env   # set DB_PATH, ADMIN_EMAIL, ADMIN_PASSWORD, IMPRINT_NAME
npm install            # run once at the repo root
npm run dev            # dev server on http://localhost:3000
npm run build          # type-check and production build
npm start              # run the production build
npm test               # Vitest (accounts, moderation, XML, library, memory deck)
npm run lint           # ESLint + Prettier check
```

The SQLite file is created at `DB_PATH` (default `data/app.db`) on first start. The admin account (`ADMIN_EMAIL` / `ADMIN_PASSWORD`) is seeded if both are set.

## API

`GET /api/places?city=Berlin&category=sight|park|museum` returns approved places as XML (`<datensaetze><objekt>` as before, now escaped).

## Notes

No mail is sent: accounts are active immediately and the admin approves places in `/admin`. The library has no data or editor yet (the legacy database was not part of the repo); add rows to `books` directly. Tutorials, donate pages and the visitor counter were not ported. See `docs/iusemybrain.md`.
