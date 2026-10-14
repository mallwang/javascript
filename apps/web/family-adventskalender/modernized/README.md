# family-adventskalender (modernized)

Family Advent calendar PWA (Vite + TypeScript, no framework): 24 shuffled doors, photo modal, share QR code, snowflakes, offline cache via a small service worker.

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root).

## Run

```sh
npm install          # run once at the repo root
npm run dev      # dev server
npm run build    # type-check and build to dist/
npm test         # Vitest
npm run lint     # ESLint + Prettier check
```

## Configuration

`VITE_REQUIRED_MONTH` (see `.env.example`, default 11 = December, 0-based) is the month in which doors can be opened. It is read at build time. To test the calendar outside December, set it to the current month (e.g. `VITE_REQUIRED_MONTH=9 npm run dev`).

Deploy `dist/` (about 58 MB, mostly photos) to any static host at the site root. The unlock check is client-side only; the photos are public files.
