# richkidzofupo

Legacy path: `apps/misc/richkidzofupo/legacy/` (3 files, 2.5 KB).

## Inspect

| File | Purpose |
|------|---------|
| `index.php` | Only page. Hosting "under construction" placeholder. |
| `analyticstracking.php` | Google Analytics (Universal Analytics) snippet, included by `index.php`. |

No assets in the repo; all images are hotlinked from external hosts. No database, no forms handled locally, no JS beyond the analytics snippet.

## Purpose and features

Placeholder page of a hosting provider (WIRCON Interservices GmbH) for the domain `richkidzofupo`:

- German text "Hier entsteht die Internet-Seite" (website under construction).
- Shows the requested host name (`$_SERVER["HTTP_HOST"]`) in the `<title>` and in the footer.
- PayPal donate button (hosted button id `Z4QUV7A2YAMFA`, form posts to `paypal.com`).
- Hotlinked "100% CO2-neutral hosting" image and link to `wint.global`.
- Google Analytics page view (`UA-38611454-2`).

## Request flow

`GET /` -> `index.php` -> includes `analyticstracking.php` -> static HTML with host name interpolated. No other routes.

## Data model

None.

## External services

- PayPal hosted donate button (button id, not a secret).
- `paypalobjects.com` (button image, pixel).
- `wint.global` (CO2 image, link).
- Google Analytics `UA-38611454-2` (property id, not a secret). Universal Analytics was shut down by Google in 2023, so the tracking is dead.

## Security and quality issues

- **XSS:** `HTTP_HOST` is echoed unescaped in `<title>` and body (Host header injection).
- Short open tags `<? ... ?>` (needs `short_open_tag`).
- Encoding `windows-1252` declared; file contains non-UTF-8 bytes (`�` in the PayPal alt text).
- Invalid/obsolete HTML: `<script>` after `</html>`, `<font>`, `align`, unclosed `<p>` nesting (`<form>` inside `<p>`), HTML comment in `<style>`.
- Hotlinked third-party images and a tracker with no consent handling (GDPR).
- No credentials found in the code.

## Dead code

- Google Analytics snippet (UA is discontinued).
- `.project` (IDE file, removed in legacy cleanup with user approval).
