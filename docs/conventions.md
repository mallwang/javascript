# Conventions baseline

Defined by the first modernized app (richkidzofupo, 2026-10-13). All apps use identical exact versions; bump them together.

- Node.js: 22 (`.nvmrc`, `engines.node: ">=22"`).
- vite 8.3.4, typescript 6.0.3 (typescript-eslint 8.71.2 does not support 7.x yet), vitest 5.0.4, jsdom 30.1.2, eslint 10.12.0, @eslint/js 10.0.1, typescript-eslint 8.71.2, prettier 3.9.10.
- Scripts: `dev`, `build` (`tsc --noEmit && vite build`), `test` (`vitest run`), `lint` (`eslint . && prettier --check .`).
- Configs: `tsconfig.json` (strict), `eslint.config.js` (recommended + typescript-eslint recommended), `.prettierrc.json` (`singleQuote`, `printWidth: 100`).
- Frameworks and runtime dependencies are decided per app.
