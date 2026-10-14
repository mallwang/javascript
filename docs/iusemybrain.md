# iusemybrain

Legacy path: `apps/web/iusemybrain/legacy/` (167 files). "I use my brain" (i-use-my-brain.com) is a German hobby website built on Twitter Bootstrap 2: a directory of special places in Germany (sights, parks, museums) that users submit, a download page for two Android apps ("Wo ist was?! - Besondere Orte", "MyAnnoOnline"), a media library, a memory game and programming tutorials. German UI, PHP 5 era, `mysql_*`, mixed ISO-8859-1 and UTF-8 sources.

## Diff `bootstrap` vs `iusemybrain`

`web/bootstrap` was an older copy of the same site (161 vs 167 files). 55 of the 61 differing files differed only in line endings (CRLF vs LF). Real differences: `iusemybrain` is the newer version (visitor counter from 2015 instead of 2013, `html1.php`, 7 more images, the `MyAnnoOnline.apk` download, a link to it in `download.php`, `autocomplete` on the login field, `schnecke3.png` on `geheim.php`). `bootstrap` only had `karte.html`, `sitemap.xml`, a Google site verification file, a link to a different image, and two empty directories (`resources/`, `tutorials/android/`). User decision: keep `iusemybrain` only, `bootstrap/` deleted.

## Inspect

| Path | Purpose |
|------|---------|
| `.htaccess` | Rewrite rules for pretty URLs (`/Neuer_Dateneintrag`, `/Download`, `/Login`, ...), custom 404. |
| `header.php`, `footer.php`, `header2.php` | Page layout with Bootstrap navbar (login/register/profile links), includes `analyticstracking.php` (Google Analytics) and `counter.php`. |
| `index.php`, `kontakt.php`, `impressum.php`, `werbung.php`, `support.php`, `referenzen.php`, `logbuch.php`, `error404.php`, `donate_*.php` | Mostly static content pages. `logbuch.php` is disabled ("temporarily out of service"). |
| `registrieren.php`, `login.php`, `logout.php`, `pwNeu.php`, `bestNeu.php`, `bestaetigung.php`, `profil.php`, `sessionpruefung.php`, `geheim.php` | Accounts: register, login, forgot password, resend confirmation, activation link, profile, session guard. |
| `validate.php` | AJAX endpoint for login (`art=login`) and registration (`art=regis`); `namenspruefung.php` checks nickname and e-mail availability; `datenNeu.php` loads place data for editing. |
| `neuerEintrag.php` (2385 lines), `neueintrag.php`, `eintragen.php` | Submit a new place or a change; the webmaster approves via a mailed key link. |
| `getPlaces.php`, `getPlaces2.php`, `holeOrte.php` | XML endpoints that return places by city and category (used by the Android app and the pages). |
| `bibliothek.php`, `resultBibliothek.php` | Searchable media library (DataTables) over `bib_buch`. |
| `support_ticket.php`, `newsletter.php` | Support form (stored and mailed) and a newsletter test script that mails every user. |
| `download.php`, `download/` | Two Android APKs (WoIstWas_BesondereOrte, MyAnnoOnline), QR codes, Play Store logo. |
| `android.php`, `php.php`, `javascript.php`, `html.php`, `html1.php`, `ajax.php`, `tutorials/{html,php,ajax}` | Programming tutorials, shown in the menu only for the owner. |
| `memory.php`, `game.php` | Memory game (`js/memory.js`, `img/memory/`) and a "Brainsnail" game placeholder. |
| `funktionen.php`, `connect.php`, `configuration.php` | DB helper `anfrage()` and connection (placeholders `DB_*`). |
| `counter.php`, `counter.txt`, `statistik.php` | Third-party file based visitor counter (vonderborn.com) and its statistics page. |
| `highlighter/`, `js/`, `css/` | Google code-prettify, Bootstrap 2, jQuery 1.9 and 1.10, DataTables, Lightbox, page scripts (`login.js`, `registrierung.js`, `pwNeu.js`, `bestNeu.js`, `support.js`, `memory.js`). |
| `img/` | 36 images and the memory card set. |

## Purpose and features

- **Places directory:** sights (`sehensw_*`), parks (`parks_*`) and museums (`museen_*`) per German state and city with name, description, latitude and longitude. Anyone (even without an account) can submit a new place or a change. The submission is stored in a `*_neu` table with a random key; the webmaster is mailed and approves via `eintragen.php?schluessel=&entry=&art=`, which moves the row to the live table.
- **Accounts:** register with nickname, e-mail and password; an activation mail links to `bestaetigung.php?key=&benutzer=`; login by nickname or e-mail; forgot password (new password mailed), resend confirmation; profile (change data, delete account with grace period via table `loeschen`).
- **Places API:** `getPlaces*.php` and `holeOrte.php` return XML (`<datensaetze><objekt>...`) consumed by the Android app and the entry form.
- **Library:** filter books by category and file type, DataTables display.
- **Support and contact:** support form stored in `support` and mailed.
- **Misc:** memory game, tutorials (owner only), download page, visitor statistics, donate pages.

## Request flow

Browser -> `.htaccess` rewrite -> PHP page (includes `header.php`: session start, analytics, counter) -> page scripts call `validate.php`, `namenspruefung.php`, `eintragen.php`, `getPlaces*.php` and others via jQuery AJAX. Endpoints answer with plain strings (`ok`, `no`, `fehler`, `nichtAktiviert`), HTML fragments or XML. Session keys: `username`, `nickname` (the e-mail), `userId`.

## Data model

No SQL file. Derived from the queries:

| Table | Columns |
|-------|---------|
| `user` | `id`, `nickname`, `email`, `passwort` (md5), `schluessel` (activation key), `aktiviert` |
| `loeschen` | `user_id` (pending deletion) |
| `staedte` | `id`, `name`, `bl` (state code), `kreis` |
| `sehensw_neu`, `parks_neu`, `museen_neu` (pending) and the live counterparts | `id`, `name`, `ort`, `bundesland`, `beschreibung`, `lng`, `lat`, `key` |
| `bib_buch`, `bib_kategorie`, `bib_art` | library entries with `KatID`, `ArtID`, `Bez` |
| `support` | `betreff`, `email`, `text` |

## External services

PHP `mail()` for activation, welcome, password, webmaster notifications and support. Google Analytics. Third-party PHP counter script. Google Maps is commented out. Hosting: own domain `i-use-my-brain.com`.

## Security and quality issues

- **SQL injection:** `anfrage()` applies `mysql_escape_string` to a variable it then ignores and runs the raw query. Most POST values are not escaped at all (`validate.php` login and registration, `support_ticket.php`, `namenspruefung.php`); GET values in `getPlaces*.php`, `holeOrte.php`, `eintragen.php` go directly into SQL and even into table names (`tabelle` parameter). `mysql_*` was removed in PHP 7.
- **Passwords:** unsalted md5; a new password is mailed; the activation key is `md5(substr(md5(microtime()), rand, 8))` (predictable).
- **Broken access control:** `eintragen.php` approves places knowing only a key from a mail link (guessable, in the GET string); `newsletter.php` mails all users to anyone who calls it; the owner menu is gated by a hardcoded e-mail address in `header.php`.
- **XSS and HTML injection:** nickname, place data and support text are echoed unescaped into pages, XML and mails; the XML responses are built by string concatenation.
- **Mail header injection** via user-supplied values in `mail()` calls; no CSRF protection, no session id regeneration.
- **Visitor counter** stores IP addresses and user agents in `counter.txt` (personal data in the repo).
- Encoding mix (broken umlauts `�` in comments and texts), deprecated HTML, jQuery 1.9/1.10 and Bootstrap 2, short open tags `<?`.

## Dead code

- `html1.php` and `ajax.php` (tutorial leftovers), `header2.php`, `game.php` (placeholder), `logbuch.php` (disabled), `newsletter.php` (test script), `getPlaces.php` vs `getPlaces2.php` vs `holeOrte.php` (overlapping endpoints), unused `js/jquery-1.9.0.js` (header loads 1.10.0), `css/docs.css`.
- Leftover credentials-style blocks: DB placeholders are repeated at the top of `eintragen.php`, `neueintrag.php`, `getPlaces.php`, `getPlaces2.php`, `holeOrte.php`, `resultBibliothek.php` instead of using `connect.php`.

## Cleanup

- Placeholders: `ADMIN_EMAIL` (`header.php`), `MAIL_WEBMASTER_ADDRESS` and `MAIL_FROM_ADDRESS` (`neueintrag.php`), `GA_TRACKING_ID` (`analyticstracking.php`); the visitor IP in `counter.txt` is replaced by `0.0.0.0`.
- Deleted dead files (user decision): `html1.php`, `ajax.php`, `header2.php`, `game.php`, `newsletter.php`, `js/jquery-1.9.0.js`, `css/docs.css`. No images or other assets were removed.

## Stack decision

Next.js 16 (App Router, server actions) + TypeScript + SQLite (`better-sqlite3`), as for hoehlenmensch and manageyourteam. User decision.

## Modernized

`apps/web/iusemybrain/modernized/`. Ported: places directory with filters, submission (anonymous or logged in) and admin approval, accounts (nickname or e-mail login), library search, support form, memory game (client component), download page, imprint, XML places API `/api/places`. All images and both APKs are kept in `public/`.

Fixes versus legacy:

- All SQL uses prepared statements; the `tabelle` parameter is replaced by a fixed category list (`sight`, `park`, `museum`).
- scrypt password hashes and hashed, expiring DB sessions replace md5 and the unsalted session.
- Approval by guessable mail key is replaced by an admin page; the admin is seeded from `ADMIN_EMAIL` and `ADMIN_PASSWORD` instead of a hardcoded address in the menu code (no mail verification, so "first registered" would be unsafe).
- XML and HTML output is escaped; the support form is stored in `support` instead of mailed.

Not ported: mail (activation, password reset, notifications; accounts are active immediately), tutorials, donate pages, visitor counter and statistics, newsletter, profile editing and account deletion, Google Analytics. The library tables have no data and no editor because the legacy database was not part of the repo. The memory game uses 10 pairs (legacy used 20 cards as well) with a deterministic Fisher-Yates shuffle instead of the legacy retry loop.
