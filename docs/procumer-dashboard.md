# procumer-dashboard

Source: `gitlab-export/procumer-dashboard-master/` (130 files). Private project by Markus Allwang (MIT): dashboard for the energy consumption of a house ("procumer" = producer + consumer). Nx 10 workspace with an Angular 10 frontend and a NestJS 7 API. Now in `apps/web/procumer-dashboard/`.

## Inspect

| Area     | Files                                                                                                                                                                      |
| -------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| API      | `apps/api/src/main.ts`, `app/{strom,wasser,pellet}/` (controller, service), `app/services/{config,file}/`, `app/decorators/cache.decorator.ts`                             |
| Frontend | `apps/dashboard/src/app/`: `pages/{dashboard,consumption,meter}/` (meter has `strom`, `wasser`, `pellet` subcomponents), `core/` (api service, interceptor, error handler) |
| Shared   | `libs/data/src/lib/{strom,wasser,pellet}.ts` (DTO interfaces), `data.ts` (generated stub)                                                                                  |
| Data     | `apps/api/src/assets/Verbrauchstabellen Haus.xlsx` (private, **not committed**), `apps/api/src/assets/.env` (**not committed**)                                            |
| Tooling  | Nx, Jest, Cypress (`dashboard-e2e`, generated stub), TSLint + ESLint, husky, commitlint                                                                                    |

Removed from the export during legacy cleanup: the XLSX (private consumption data), `apps/api/src/assets/.env` (config incl. possible secrets; content was not read), `package-lock.json`, `commitlint.config.js`, `.vscode/`.

## Purpose and features

Reads meter readings from an Excel workbook and shows consumption.

- **Strom (works):** sheet `Strombezug`, column A = date, column B = meter reading in kWh. Rows without a date in column A are skipped.
  - `GET /api/v1/strom/meterReadings`: all readings, value rounded to 2 decimals.
  - `GET /api/v1/strom/consumption/months`: per reading, `round(reading - previous reading)`; first reading gives 0.
  - `GET /api/v1/strom/consumption/years`: monthly consumptions summed per calendar year.
- **Wasser, Pellet (not implemented):** `WasserService` and `PelletService` are empty stubs, the controllers have no useful endpoints, and the frontend calls are commented out. Only the DTO shapes exist: Wasser = date + meter reading (sheet `Zaehler Wasser`); Pelletbetankung = date, tons, price per ton, currency, pot hours total, consumption kg (sheet `Pellettankung`); Pelletverbrauch = date, pot hours, consumption kg (sheet `Pelletverbrauch`). The column layout of these sheets is unknown (only in the private XLSX).
- **Frontend:** `/meter` shows meter reading tables (newest first) for strom, wasser, pellet; `/consumption` shows sortable year and month tables for strom; `/dashboard` is only a heading ("Energy Dashboard", the skeleton part). Chart.js is a dependency but never used.

## Request flow

Browser (Angular, dev proxy `proxy.conf.json`) -> `ApiService` -> NestJS `/api/v1/...` -> `FileService.read(sheet)` re-reads the XLSX on every request (cache decorator exists but is commented out) -> `sheet_to_json(header: 1)` -> service computes DTO.

## Data model (derived for SQLite)

One table per meter, readings only; consumption is derived.

- `meter_reading(id, meter TEXT CHECK in ('strom','wasser'), date TEXT, reading REAL)`, unique on (`meter`, `date`)
- `pellet_refill(id, date, amount_tons, price_per_ton, currency, pot_hours_total, consumption_total_kg)` and `pellet_usage(id, date, pot_hours_total, consumption_total_kg)` only if the user supplies sheets in that layout (open question, see below).

## External services

None. Config (`.env`): `FILE_PATH`, sheet names, `HOST`, `PORT`, `CORS_WHITELISTED_ORIGINS`, `API_VERSION`, `SWAGGER_API_ENDPOINT`.

## Issues

- Private data and a `.env` were committed in the original repo.
- `xlsx` 0.16 has known vulnerabilities (prototype pollution, ReDoS); the 0.18+ releases are not published on npm.
- `StromService`, `WasserService`, `PelletService` import `Injectable` from `@angular/core` instead of `@nestjs/common` (works only because the decorator is a no-op for DI here).
- `FileService` reads the file synchronously per request, no cache.
- `csurf` (deprecated, and pointless for a read-only GET API) plus rate limiting and helmet are configured; CORS may be `*`.
- Calendar-year grouping uses local time; the "consumption" of the first reading is 0 and counts toward its year.
- Month "consumption" is the difference to the previous reading regardless of the gap between readings (readings are monthly by convention but not enforced).
- Dead code: `data()` stub and its spec, `AppController`/`AppService` hello world, `http-cancel.service`, `cache.decorator`, e2e stubs, `tslint` configs, empty `libs/.gitkeep`, `tools/schematics`.

## Stack decision

Next.js + TypeScript + SQLite (same as `unser-blog`, `bierspiel`). Next.js route handlers replace NestJS, one app instead of three Nx projects.

- The private XLSX is **not part of the repo**. The user places it locally (path from `.env`, ignored by git) and an import step (script and/or upload) loads the readings into SQLite.
- Scope: strom fully ported; wasser as a second meter with the same logic (same shape: date + reading); pellet and charts only if the layout of the sheets is provided. Dashboard page gets a real overview (latest reading, yearly consumption chart).
