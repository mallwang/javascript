# greenscreen (modernized)

Office info screen built with Next.js (App Router, server components) and TypeScript: clock, weather, public transport departures, motorway traffic warnings, news headlines and a DAX ticker. The page revalidates every minute and refreshes itself in the browser.

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root). No API keys or secrets needed.

## Run

```sh
npm install          # run once at the repo root
npm run dev          # dev server on http://localhost:3000
npm run build        # type-check and production build
npm start            # run the production build
npm test             # Vitest (parsers, clock)
npm run lint         # ESLint + Prettier check
```

## Data sources (replacing the dead legacy ones)

| Block | Legacy | Now |
|-------|--------|-----|
| Departures | vgn.de scraping | VAG Puls API (stops 1351 bus, 1320 U2) |
| Weather | wetter.com widgets | Open-Meteo |
| Traffic | freiefahrt.info scraping | autobahn.de API (A3, A6, A9, A73) |
| News | nachrichten.de iframes | tagesschau API |
| Stocks | Yahoo CSV | Yahoo chart endpoint (unofficial, may be rate limited) |

Every source fails soft: a dead upstream shows an empty block, not an error page. Fetches use an 8 s timeout and Next.js caching.

## Not ported

Google Maps traffic maps (need an API key), the Siemens chart image, `video.html` (IP camera iframe with credentials), Foundation, slick and jQuery (replaced by CSS grid and a small React rotator).
