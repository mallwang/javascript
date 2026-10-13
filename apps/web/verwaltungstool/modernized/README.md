# verwaltungstool (modernized)

Account management (register, e-mail activation, login, profile, account deletion) built with Next.js (App Router, server actions), TypeScript and SQLite (`better-sqlite3`).

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root).

## Run

```sh
cp .env.example .env   # adjust values
npm install          # run once at the repo root
npm run dev            # dev server on http://localhost:3000
npm run build          # type-check and production build
npm start              # run the production build
npm test               # Vitest (users, passwords, validation)
npm run lint           # ESLint + Prettier check
```

Without `SMTP_HOST`, mails (activation link) are printed to the server console. The SQLite file is created at `DB_PATH` on first start.

## Security changes compared to legacy

Prepared statements, scrypt-salted passwords, no password sent by mail, random 128-bit activation key (single use), server-side validation, hashed DB-backed session tokens in an `httpOnly` cookie (all sessions dropped on password change), React-escaped output, Next.js origin check for server actions. Deletion requests are purged lazily after 3 days (no external job).

## Not ported

Live availability checks (server validates instead), dropped dead pages (`werbung`, `header2`, Amazon widget), `Userkonten/` folders, the webmaster notification mail, rate limiting.
