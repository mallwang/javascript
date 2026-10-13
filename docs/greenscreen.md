# greenscreen

Legacy path: `apps/web/greenscreen/legacy/` (54 files, 1.5 MB).

## Inspect

| File / dir | Purpose |
|------------|---------|
| `index.php` | The dashboard ("evosoft infoscreen"). One page with inline CSS and ~240 lines of inline JS. Includes `getStocks.php` server-side. |
| `getOeffi.php` | Server-side proxy: scrapes departure tables from vgn.de for two stops. Returns an HTML fragment. |
| `getStauinfo.php` | Server-side proxy: scrapes freiefahrt.info for traffic jam reports in Nuremberg. Returns an HTML fragment. |
| `getStocks.php` | Server-side proxy: Yahoo Finance CSV quotes for 30 DAX stocks. Returns a one-line HTML ticker string. |
| `video.html` | Standalone iframe of an IP camera stream (with the camera's control command list as comments). Not linked from `index.php`. |
| `css/`, `js/` | Vendored Foundation 5 (CSS, all component JS), normalize, jQuery, modernizr, fastclick, placeholder, jquery.cookie. |
| `slick/` | Vendored slick carousel (css, scss, js, min.js, fonts, loader gif). |
| `img/` | `background.jpg` (unused), `verkehrslage_nbg.png` (unused). |
| `nbproject/`, `.project` | IDE project files (NetBeans, Eclipse). |
| `humans.txt`, `robots.txt` | Foundation boilerplate. |

Actually used by `index.php`: `foundation.min.css`, `slick.css`, `slick-theme.css`, `jquery.js`, `slick.min.js`, `modernizr.js`, `foundation.min.js`, `foundation.orbit.js` (no orbit markup exists).

## Purpose and features

A wall-mounted info screen for an office (evosoft, Nuremberg). Three columns, all auto-refreshing:

- **Left:** clock (date and time, German weekday, every minute), weather widgets for Marienberg, Budapest and Klausenburg (wetter.com widget scripts, rotated every 10 s by slick), public transport departures (Ernst-Heinkel-Weg buses, Ziegelstein U2).
- **Middle:** three Google Maps with traffic layer (zoom 13, 12, 11, centered on Nuremberg 49.448, 11.080), rotated every 10 s and re-rendered every minute. Below: traffic jam report for Nuremberg.
- **Right:** 1-year Siemens chart (Yahoo chart image), news iframes from nachrichten.de (9 categories, rotated every 20 s, reloaded every minute).
- **Bottom:** `<marquee>` stock ticker with name, price and colored change of the 30 DAX stocks.

## Request flow

`GET /` -> `index.php` (renders the ticker inline) -> browser loads jQuery, slick, Foundation, Google Maps -> on load, jQuery AJAX calls `getStauinfo.php`, `getOeffi.php`, `getStocks.php` and injects the returned HTML via `innerHTML`; `setInterval` repeats every 60 s.

## Data model

None. No database; everything is fetched live.

## External services

| Service | Use | State |
|---------|-----|-------|
| vgn.de Komfortauskunft | departures, HTML scraping, stop ids 3001351 and 3001320 | page layout surely changed since |
| freiefahrt.info | traffic jam table, HTML scraping | site gone/changed |
| Yahoo Finance `download.finance.yahoo.com` CSV and `chart.finance.yahoo.com` | quotes and chart | both APIs were shut down years ago |
| Google Maps JS API v3.exp with `signed_in=true` | traffic maps | no API key, would not load today |
| wetter.com WOYS widgets | weather | widget service discontinued |
| nachrichten.de widget | news iframes | all plain `http` |
| `gartenkamera.dtdns.net` | IP camera in `video.html` | private camera |

Practically every data source of the legacy app is dead, so the modernized version needs new sources.

## Security and quality issues

- **Credentials in the repo:** `video.html` contained the IP camera user and password in clear text in the stream URL. Replaced with the placeholders `CAMERA_USER` / `CAMERA_PASSWORD` in `legacy/`. The password was never committed, but it was shared in plain text, so change it on the camera.
- `file_get_contents` on remote URLs over plain `http`, no timeout, no error handling. If the source fails, `strpos` returns `false` and the parsers produce garbage.
- Scraping by fixed string offsets and hardcoded row indexes (`$teile[2]`..`$teile[6]`); breaks with any layout change; the five departure rows are copy-pasted.
- Upstream HTML is passed through unsanitized and set via `innerHTML` (XSS if the source is compromised or spoofed over http).
- Error handlers use `eval("(" + xhr.responseText + ")")` with an undefined variable `xhr` (the parameter is `jqXHR`), so any failed request throws a ReferenceError instead of showing a message.
- Deprecated or non-standard HTML: `<marquee>`, `align`, custom tags `<green>` / `<red>`, duplicate element ids (`wettercom-*` three times).
- Hand-written weekday `switch` and zero padding; two intervals share the variable name `intervallChart` (works by accident).
- Mixed content: everything is `http`, Google Maps is `https`.
- Google Maps without API key, third-party widgets without consent handling (GDPR).

## Dead code

- `foundation.*.js` component files: accordion, alert, clearing, dropdown, equalizer, interchange, joyride, magellan, offcanvas, orbit, reveal, slider, tab, tooltip, topbar, abide. Only the minified bundle and orbit are loaded, orbit has no markup.
- Non-minified duplicates: `foundation.css` (min used), `slick.js` (min used), `slick.scss`, `slick-theme.scss`, `config.rb`.
- Unused: `fastclick.js`, `jquery.cookie.js`, `placeholder.js`, `normalize.css`, `humans.txt`, `robots.txt`.
- `img/background.jpg`, `img/verkehrslage_nbg.png` (assets, never removed).
- `nbproject/*`, `.project`: IDE files.

## Cleanup (user-approved)

Deleted from `legacy/` (54 -> 21 files): `nbproject/`, `.project`, `humans.txt`, `robots.txt`, `css/foundation.css`, `css/normalize.css`, unused `js/foundation/*.js` (all except `foundation.orbit.js`, which `index.php` loads), `js/vendor/{fastclick,jquery.cookie,placeholder}.js`, `slick/slick.js`, `slick/*.scss`, `slick/config.rb`. Images and fonts kept. Camera credentials in `video.html` replaced by `CAMERA_USER` / `CAMERA_PASSWORD`.

## Stack decision

Next.js (full-stack TypeScript), same baseline as verwaltungstool (user decision). Server components/route handlers replace the PHP proxies, with timeouts and revalidation instead of `setInterval` polling. Dead data sources need replacements, to be chosen during modernization.

## Modernized

`apps/web/greenscreen/modernized/`: `src/lib/parse.ts` (pure parsers, clock), `src/lib/sources.ts` (fetchers with timeout, cache, fail-soft), `src/app/` (server-rendered page, `Live.tsx` with refresh, clock and rotator), `tests/parse.test.ts` (4 tests). Replacement sources are listed in its README. Deviations: no database, no `.env` values (all sources keyless). Dropped: Google Maps (needs key), chart image, `video.html`.
