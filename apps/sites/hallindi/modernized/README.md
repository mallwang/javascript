# hallindi (modernized)

One-page company site (Vite + TypeScript, vanilla): rotating hero caption, count-up numbers, team, portfolio, filterable project grid with lightbox, and a contact form that opens the visitor's mail client (`mailto:`).

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root).

## Run

```sh
npm install      # run once at the repo root
npm run dev      # dev server
npm run build    # type-check and build to dist/
npm test         # Vitest
npm run lint     # ESLint + Prettier check
```

No configuration is needed (`.env.example` is empty on purpose). Deploy `dist/` to any static host.
