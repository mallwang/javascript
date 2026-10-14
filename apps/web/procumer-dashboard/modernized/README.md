# procumer-dashboard

Energy dashboard for a house: meter readings (electricity, water) imported from an Excel workbook into SQLite, with monthly and yearly consumption. Next.js 16, TypeScript, SQLite (`better-sqlite3`), `read-excel-file`.

## Prerequisites

Node.js 22 and `npm install` at the repository root.

## Run

```sh
cp .env.example .env   # optional, all values have defaults
npm run dev            # http://localhost:3000
```

Open the app and upload your workbook (`.xlsx`) in the "Import" form. The file is parsed in memory and only the readings are stored in `data/app.db`; the workbook itself is never saved. `data/` and `.env` are git-ignored, so private data stays local.

Expected workbook layout: one sheet per meter (`Strombezug` for electricity, `Zaehler Wasser` for water; override with `SHEET_STROM`, `SHEET_WASSER`), column A = date, column B = meter reading. Rows without a date in column A or a number in column B are ignored. Re-uploading replaces the stored readings of every meter whose sheet is found.

The app has no login: run it locally only.

## Build and test

```sh
npm run build
npm test
npm run lint
```

`tests/fixtures/sample.xlsx` is a small synthetic workbook, not real data.
