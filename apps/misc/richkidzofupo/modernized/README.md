# richkidzofupo (modernized)

Static "under construction" placeholder page (Vite + TypeScript). Shows the current host name, a PayPal donate button and the hosting provider credit.

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

No configuration is needed (`.env.example` is empty on purpose). Deploy `dist/` to any static host.
