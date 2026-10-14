# family-library

Shared family book shelf with users: open registration with e-mail verification, book inventory with owners, admin user management. Next.js 16, TypeScript, SQLite (`better-sqlite3`), nodemailer. The UI is German.

## Prerequisites

Node.js 22 and `npm install` at the repository root.

## Run

```sh
cp .env.example .env   # all values are optional in development
npm run dev            # http://localhost:3000
```

Register, then open the verification link from the mail. Without `SMTP_HOST` mails are printed to the console of `npm run dev`. Set `ADMIN_EMAIL` to the address of the first admin: that account becomes admin when it verifies its address. Data lives in `data/app.db` (git-ignored).

## Environment

`DB_PATH`, `BASE_URL` (used in mail links), `ADMIN_EMAIL`, `SMTP_HOST`, `SMTP_PORT`, `SMTP_SECURE`, `SMTP_USER`, `SMTP_PASSWORD`, `MAIL_FROM`. In production set `BASE_URL` to the public https URL (the session cookie is `Secure` there).

## Build and test

```sh
npm run build
npm test
npm run lint
```

The tests cover registration, verification, sessions, password reset, rate limiting, validation, book ownership and user administration. See `docs/family-library.md` for the analysis and the security fixes.
