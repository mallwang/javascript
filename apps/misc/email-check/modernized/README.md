# email-check (modernized)

Static e-mail address tester (Vite + TypeScript). Validation runs in the browser (`src/email.ts`).

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root).

## Run

```sh
npm install
npm run dev      # dev server
npm run build    # type-check and build to dist/
npm test         # Vitest
npm run lint     # ESLint + Prettier check
```

No configuration is needed (`.env.example` is empty on purpose). Deploy `dist/` to any static host.
