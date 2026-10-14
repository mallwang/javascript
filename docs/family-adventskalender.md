# family-adventskalender

Source: `gitlab-export/family-adventskalender-master/` (157 files, 60 MB, mostly photos). Private family project by Markus Allwang (MIT), Advent 2020. PWA built with Ionic 5 / Angular 10.1, no backend. Now in `apps/web/family-adventskalender/`.

## Inspect

| Area    | Files                                                                                                                                                                                                                          |
| ------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Entry   | `src/main.ts`, `src/index.html`, `src/app/app.module.ts`, `app-routing.module.ts`, `app.component.*`                                                                                                                           |
| Page    | `src/app/home/` (`home.page.ts/html/scss`, module): the calendar grid                                                                                                                                                          |
| Modals  | `src/app/image-modal/` (photo of a day), `src/app/qr-code-modal/` (share QR code)                                                                                                                                              |
| Guard   | `src/app/can-deactivate.guard.ts` (back button closes the open modal first)                                                                                                                                                    |
| Config  | `.env` (`NODE_ENV`, `REQUIRED_MONTH`), `scripts/setup-angular-environment.js` (generates `src/environments/*.ts`), `ngsw-config.json`                                                                                          |
| PWA     | `src/manifest.webmanifest`, `src/dev.manifest.webmanifest`, Angular service worker                                                                                                                                             |
| Tooling | `angular.json`, Karma/Jasmine unit tests (`*.spec.ts`, mostly generated stubs), Protractor e2e (`e2e/`), ESLint, Prettier, husky, commitlint                                                                                   |
| Assets  | `src/assets/images/full/1..24.jpg` (43 MB), `preview/1..24.jpg` (16 MB), `bg.jpg`, `qr-code.jpg`, `dev.qr-code.jpg`, icons (prod + `dev/`), font `MountainsofChristmas.ttf`, `images/{background,no-background}/` icon sources |

Removed from the export during legacy cleanup (user approved): `.gitlab/` (issue/MR templates), `docker/` (CI images), `.gitlab-ci.yml`, `commitlint.config.js`, `package-lock.json`. `.env` is non-secret config but ignored by the root `.gitignore`, so it is not committed.

## Purpose and features

Advent calendar for the family: 24 doors in a 3-column grid, door order shuffled on every page load.

- A door can be opened only when the current month equals `REQUIRED_MONTH` (0-based, default 11 = December) and today's day of month is >= the door number.
- Locked door: toast. Wrong month: "wait until <month>", right month: "just a few more sleeps".
- Open door: modal with the preview photo; click opens the full-size photo in a new tab.
- Share button: modal with a QR code image (the QR code points to the hosted app).
- Pull-to-refresh re-reads the current date.
- Falling snowflakes (12 CSS-animated glyphs), custom Christmas font, red theme.
- Back button / hardware back closes an open modal instead of navigating (`CanDeactivateGuard`, `ionBackButton`); `popstate` is hijacked to disable back navigation completely.
- PWA: installable, service worker with lazy asset caching; update alert asks the user to reload.

## Request flow

Static single page: `index.html` -> `app-root` -> route `home` (every other URL redirects to `home`) -> `HomePage`. No HTTP requests except static files from `assets/`. The "backend" is the FTP deploy of `www/` (see CI).

## Data model

None. Photos are addressed by day number: `assets/images/{preview,full}/<day>.jpg`.

## Configuration and deployment

- `REQUIRED_MONTH` (default 11) and `NODE_ENV` via `.env`; `setup-angular-environment.js` writes `src/environments/environment*.ts` before every start/build (the README tells users to `git update-index --assume-unchanged` them). `USE_HASH` (hash location strategy) is read but not documented.
- GitLab CI: dependency check, lint, Karma + Protractor tests in a Puppeteer image, build, FTP deploy via `lftp` (credentials only as CI variables, none in the repo). A dev variant swaps in a green manifest and `dev.qr-code.jpg`.

## External services

None at runtime (no analytics, no CDN). Only the share QR code image encodes a hosted URL.

## Security and quality issues

- No credentials in the code; `.env` holds only `NODE_ENV` and `REQUIRED_MONTH`.
- The unlock check is client-side only: everyone can open `assets/images/full/<n>.jpg` directly or change the device clock. Acceptable for a family calendar; a real fix needs a server that serves the photos by date.
- `shuffle()` uses `sort(() => Math.random() - 0.5)`, which is biased.
- Typo in the toast text ("ehe due das Päckchen").
- `popstate` hijack traps the user (back button never leaves the page).
- `ImageModalComponent` has a `TODO` for individual titles per day; `qr-code-modal` reuses the selector `app-image-modal`.
- Outdated stack: Angular 10, Ionic 5, RxJS 6, Protractor (deprecated), Karma, Puppeteer 5, TypeScript 4.0, Node 12 in CI; many known npm advisories.
- `meta http-equiv="Cache-control: no-cache"` and `user-scalable=no` (accessibility).
- `lang="en"` for a German UI; manifest and title hard-code "2020".

## Dead code

- `src/test.ts`, `karma.conf.js`, `e2e/`, most `*.spec.ts` (generated stubs).
- `@ionic-native/*` splash screen / status bar (cordova plugins, unused), `USE_HASH`.
- `dev.manifest.webmanifest`, `dev.qr-code.jpg`, `assets/icons/dev/` (dev deployment variant only).
- `images/{background,no-background}/` icon sources (not referenced by the app; kept as assets).

## Stack decision

Vite + TypeScript without a framework, static build, no backend (user decision). One page with 24 buttons and two `<dialog>` modals does not justify Angular/Ionic; `<dialog>` gives Esc/backdrop closing and focus handling natively. Hand-written `public/sw.js` (lazy cache, about 25 lines) replaces the Angular service worker instead of adding `vite-plugin-pwa`.

## Modernized

`apps/web/family-adventskalender/modernized/`: `index.html`, `src/` (`calendar.ts` unlock logic, `main.ts` UI, `style.css`), `public/` (all assets, manifest, `sw.js`), `tests/calendar.test.ts` (unlock rules, messages, shuffle is a permutation). `npm run lint`, `test` and `build` pass.

Changes against the legacy app:

- `REQUIRED_MONTH` is now `VITE_REQUIRED_MONTH` (build time, no generated environment files).
- Unbiased Fisher-Yates shuffle; typo fixed; month name via `Intl` instead of a 12-case switch.
- The date is read on every click and on `visibilitychange` (replaces pull-to-refresh).
- Dropped: back-button hijack (`popstate`), update alert, dev variant (green manifest, dev QR code; dev assets are still copied), "2020" in title and manifest, `user-scalable=no`, no-cache meta tags. The page is `lang="de"`.
- Snowflakes are disabled with `prefers-reduced-motion`.

Deviation from the shared conventions: none; the app only adds `jsdom` and `vite` like richkidzofupo.
