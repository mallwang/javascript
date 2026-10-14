# Unser Blog (modernized)

Multi-user blog with subscriptions: register, write posts, comment, follow other users, profile with photo. Port of the PHP student project "Gruppe1 - Blog" (2012), built with Next.js (App Router, server actions), TypeScript and SQLite (`better-sqlite3`).

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root).

## Run

```sh
cp .env.example .env   # DB_PATH, PHOTO_DIR, APP_URL
npm install            # run once at the repo root
npm run dev            # dev server on http://localhost:3000
npm run build          # type-check and production build
npm start              # run the production build
npm test               # Vitest (accounts, reset, posts, comments, subscriptions, search, photos)
npm run lint           # ESLint + Prettier check
```

The SQLite file is created at `DB_PATH` (default `data/app.db`) on first start; uploaded profile photos are stored in `PHOTO_DIR` (default `data/photos`).

## No mail delivery

The app sends no e-mails. After registering or requesting a password reset, the activation or reset link is printed to the server console (built from `APP_URL`); open it in the browser to continue.

## Notes

Deviations from the legacy app (security fixes, no mail, plain-text posts, public profile without e-mail) are listed in `docs/unser-blog.md`, section "Modernization notes".
