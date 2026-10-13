# verwaltungstool

Legacy path: `apps/web/verwaltungstool/legacy/` (36 files, ~1 MB, mostly vendored Bootstrap 2 and jQuery). Subdomain `verwaltung.i-use-my-brain.com`, an extract of the `web/iusemybrain` site (same header, footer, `datenNeu.php`, `connect.php`, ...). Author: Markus Allwang, 2013.

## Inspect

| Area | Files |
|------|-------|
| Pages | `index.php`, `login.php`, `registrieren.php`, `bestaetigung.php`, `verwaltung.php`, `profil.php`, `werbung.php`, `error404.php`, `logout.php` |
| Layout | `header.php` (logged-in aware navbar), `header2.php` (variant, includes missing `analyticstracking.php`, `counter.php`), `footer.php` |
| Ajax endpoints | `validate.php` (login, register), `namenspruefung.php` (name/e-mail availability), `datenNeu.php` (change password, change e-mail, request account deletion) |
| DB | `configuration.php` (placeholders only), `connect.php`, `funktionen.php` |
| Access guard | `geheim.php` (redirect-less "no access" page if no session) |
| Client JS | `js/login.js`, `js/registrierung.js`; vendored `jquery-1.9.0.js`, `jquery-1.10.0.js` (unused), `bootstrap.js` |
| Assets | `css/bootstrap*.css` (+ `.min`), `img/` (`404.png`, `stop.png`, `logo.png`, `loading.gif`, glyphicons) |
| Routing | `.htaccess`: pretty URLs `/Login`, `/Registrieren`, `/Profil`, `/Verwaltung`, `/Werbung`, `/Logout`, `/Kontakt`, `/Impressum`, `/Error` |

Referenced but missing from this folder (exist in `web/iusemybrain`): `pwNeu.php`, `bestNeu.php`, `js/pwNeu.js`, `js/bestNeu.js`, `kontakt.php`, `impressum.php`, `counter.php`, `analyticstracking.php`, `Userkonten/` directory. No SQL schema is shipped.

## Purpose and features

Account management for a planned study-material manager ("Hier kannst du all deine Studienunterlagen verwalten"). The actual file management is unimplemented (`<a>Neuen Ordner anlegen</a>` is a dead link). Implemented:

- **Registration** with live checks (nickname >3 chars and unique, e-mail format and unique, password >=6 chars with strength bar, confirmation match). Creates the user with an activation key, creates `Userkonten/User<id>/Allgemein/`, mails the webmaster and mails the user an activation link.
- **Activation** via `bestaetigung.php?key=...`.
- **Login** by nickname or e-mail; session keys `username`, `nickname` (holds the e-mail), `userId`. Login cancels a pending deletion.
- **Profile**: change e-mail (password required, notification to old and new address), change password (password required, notification mail), request account deletion (row in `loeschen`, mail, "deleted after 3 days" by an external job that is not in the repo).
- Home page, ad page (Amazon affiliate widget `iusemybrain-21`), custom 404.

## Request flow

- Page request: `.htaccess` rewrite -> `*.php` -> `header.php` (starts session) -> content -> `footer.php`.
- Protected pages (`verwaltung.php`, `profil.php`) include `geheim.php` first.
- Forms submit via jQuery `$.ajax` POST; endpoints answer with plain-text status codes (`ok`, `no`, `nichtAktiviert`, `pwFalsch`, `eMailVorhanden`, ...) which the JS maps to Bootstrap alerts.

## Data model (derived from queries)

```
user(id PK, nickname UNIQUE, email UNIQUE, passwort CHAR(32) md5, schluessel, aktiviert 0/1)
loeschen(user_id)          -- pending account deletions
```

Filesystem: `Userkonten/User<id>/Allgemein/` per user (empty folders, never used afterwards).

## External services

- PHP `mail()` (senders `kontoverwaltung@`/`kontenverwaltung@`/`info@i-use-my-brain.com`, webmaster notification to `kontenverwaltung@i-use-my-brain.com`). No SMTP credentials in code.
- Amazon affiliate widget (`ws.amazon.de`, id `iusemybrain-21`, not a secret).
- Google Analytics via the missing `analyticstracking.php`.
- Hard-coded host `verwaltung.i-use-my-brain.com` in the activation link and `logout.php`.

## Security and quality issues

- **SQL injection everywhere**: `$nickname`, `$email`, `$schluessel` interpolated into queries (`validate.php`, `namenspruefung.php`, `bestaetigung.php`, `datenNeu.php`, `profil.php`). `anfrage()` computes `mysql_escape_string` and then discards it.
- Removed `mysql_*` API (PHP 7+).
- **Passwords hashed with unsalted MD5.**
- **The new password is sent in plaintext by e-mail** (`datenNeu.php`, "Es lautet nun: ...").
- Activation key `substr(md5(microtime()), mt_rand(0,24), 8)` hashed once: only 32 bits of weak entropy, guessable; same key is never invalidated.
- **Stored/reflected XSS**: nickname, e-mail, `benutzer` GET parameter and `$_SESSION['nickname']` echoed unescaped (also into inline JS in `profil.php`); activation JS injected from PHP.
- Server-side validation missing: password length, nickname length, e-mail format on registration (client-only); `$_POST["art"]` read without `isset`.
- Account enumeration through `namenspruefung.php` and different login errors; no rate limiting, no CSRF protection, no `session_regenerate_id` after login.
- `bestaetigung.php` activates by `nickname`, not by key; `$nicknameNeu` undefined for invalid keys.
- `mkdir("Userkonten/User$id")` without error handling or permissions.
- Mail header/HTML injection through nickname in mails.
- Mixed `username`/`nickname` session semantics (`index.php` checks `username`, `header.php` checks `nickname`).
- `geheim.php` includes `header2.php` which needs missing files; `logout.php` redirects to a hard-coded host.
- Legacy `windows-1252` encoding in several files.

## Dead code

- `js/jquery-1.10.0.js` (never included), non-minified or minified duplicates of Bootstrap/CSS depending on page (`*.min.*` never referenced).
- `header2.php` (depends on missing files), commented-out dropdown and login-button logic in `header.php`/`login.php`.
- `verwaltung.php` "Neuen Ordner anlegen" (no implementation), `Userkonten` folders.

## Legacy cleanup (user approved)

Deleted unreferenced duplicates: `js/jquery-1.10.0.js`, `css/bootstrap.min.css`, `css/bootstrap-responsive.min.css`, `js/bootstrap.min.js`. Images untouched. No secrets left (`configuration.php` holds placeholders only). Legacy folder is now 32 files.

## Stack decision

Next.js (App Router, full-stack TypeScript) + SQLite (user decision). Server actions or route handlers replace the Ajax endpoints; SQLite via `better-sqlite3` with prepared statements; passwords hashed with scrypt (`node:crypto`); mail via nodemailer using `SMTP_*` from `.env`. Fixes all issues listed above by design.

## Modernized

`apps/web/verwaltungstool/modernized/`: Next.js 16 (App Router, server actions, no client JS), `better-sqlite3`, nodemailer. `src/lib/users.ts` holds all logic (testable with an in-memory DB), `src/app/` the pages and `actions.ts`. Images copied to `public/img`. `npm run lint`, `test` (6 tests) and `build` pass; register -> activate -> login -> protected page and a wrong login were also run against `next start` with curl.

Behavior changes: nicknames are limited to letters, digits and `_ . -` (so they cannot be confused with e-mail addresses); UI texts are English; the activation key is cleared on use; password change invalidates all sessions; the app UI uses plain CSS instead of Bootstrap 2.

Deviations from the conventions: Next.js/React instead of Vite; `build` runs `tsc --noEmit && next build`; `next-env.d.ts` is committed; no jsdom/vite dependencies.
