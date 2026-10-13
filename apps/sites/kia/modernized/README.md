# kia (modernized)

One-page classified ad for a used KIA Rio 1.4 EX (Vite + TypeScript, vanilla): rotating hero caption, vehicle data, and a filterable photo gallery with a lightbox (previous/next).

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
