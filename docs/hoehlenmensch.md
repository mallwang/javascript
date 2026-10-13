# hoehlenmensch

Legacy path: `apps/games/hoehlenmensch/legacy/` (59 files, 456 KB). Title in the legacy code: "Steinzeitspiel - Reloaded", version 0.2, dated 27.08.2012. Source files are ISO-8859-1 (Latin-1), not UTF-8.

## Inspect

| File | Purpose |
|------|---------|
| `index.php` | Landing page: registration form and login form, imprint. |
| `kontrolle_reg.php`, `kontrolle_login.php`, `logout.php` | Register (creates 4 rows + welcome mail), login (md5), logout. |
| `pw_vergessen.php`, `pw_gesendet.php` | Forgot-password form and handler (sets and mails a new random password). |
| `profil.php`, `email_neu.php`, `passwort_neu.php` | Profile page, change e-mail, change password. |
| `header.php`, `footer.php`, `head.php`, `menu.php`, `home.php`, `status_header.php`, `sessionpruefung.php` | Page layout (table), side menu, character/resource box, session check. |
| `functions.php` | DB getters (`id`, `dauer`, `email`, `arbeitsende`, `skills`, `taetigkeit`, `rohstoffe`, `inventar`, `ep`) and `arbeiten()` which starts an activity. |
| `connect.php` | `mysql_connect` (secrets already placeholders). |
| `Hauptseite.php`, `Umgebung.php`, `Wald.php`, `Lager.php`, `Bett.php`, `Feuer_an.php` | Scenes: a background image with an HTML image map; hotspots start activities or link to other scenes. |
| `dauer.php`, `Arbeiten.php`, `Status.php` | Choose duration, start activity, collect result when the activity is over. |
| `herstellung.php`, `werkzeuge.php`, `kleidung.php`, `produktion.php` | Crafting: choose tool or clothing, start the 60-minute craft job. |
| `inventar.php` | Inventory table. |
| `Uebersichtskarte.php`, `Uebersichtskarte2.php`, `testkarte.php` | Unfinished world map prototypes (static image table; `MyMap` PHP class). Not linked. |
| `Hauptseite.html`, `Fragen.html`, `aufklappen.js` | Static prototype of the main scene with dummy city links; FAQ with placeholder questions and a show/hide script. Not linked. |
| `main.css` | Background image only. |
| `Steinzeitspiel_reloaded.docx` | Game design document ("Browsergame Skript", German, ~14 pages). |
| `images/` (19 jpg/png) | Scenes, banners, map tiles, background. |

## Purpose and features

A turn-less, timer-based browser role-playing game set in the Stone Age (inspired by the existing "Steinzeitspiel"). The player is a caveman with a cave, resources, an inventory and skills.

- **Account:** register with nickname, e-mail, password; login by nickname or e-mail; logout; change e-mail and password; forgot-password mail.
- **Scenes:** cave (`Hauptseite`), surroundings, forest, camp, bed, fire. Clickable hotspots start activities.
- **Activities** (id 1-13, one at a time per character):
  1. hunt, 2. gather fruit, 3. fish, 4. gather mushrooms, 5. fell wood, 6. cut stone, 7. find water (gathering, duration 30/60/120/240 min chosen on `dauer.php`)
  8. axe, 9. hunting spear, 10. water bag, 11. basket, 12. loincloth, 13. shoes (crafting, fixed 60 min)
- **Costs:** starting an activity consumes animal food and water by duration (30 min: 0.4 / 0.25, 60: 0.8 / 0.5, 120: 1.6 / 1, 240: 3.2 / 2). Empty stock is set to 0 (no penalty).
- **Reward** (`Status.php`, once `arbeitsende` has passed): yield `rand` by duration (30: 8-12, 60: 14-22, 120: 26-40, 240: 46-64), skill gain 1/2/4/8, +1 experience point. Food activities draw a random row from a lookup table (small animals 13, plants 21, fish 20, mushrooms 4) and give `nahrungspunkte * factor` (1 / 1.75 / 3 / 4.5). Crafting gives +1 item and skill.
- **Display:** side box with character values (experience, character points, strength, intelligence, speed, dexterity, stamina) and resources (plant food, animal food, water, wood, stone); inventory page; status line with the current activity and end time.
- **Not implemented** (only in the design doc): tools/clothing give no yield bonus, character values never change, fire, bed, chest, tribe, map, potions, bronze/iron age, trade.

## Request flow

`index.php` -> `kontrolle_login.php` (session `nickname`, `time`) -> `Hauptseite.php`. Every game page includes `header.php` (session check, functions, DB connect, layout) and `footer.php`. Scene hotspot -> `Arbeiten.php?Arbeit=n` (or `dauer.php?Arbeit=n` from the menu, which submits `dauer` via GET to `Arbeiten.php`) -> `arbeiten()` writes `arbeitsende`, `dauer`, `taetigkeit` and deducts food/water. The result is only credited when the player opens `Status.php` after `arbeitsende` (no background job).

## Data model

No SQL file exists. Derived from the queries (one row per player in each table, keyed by `login_id`):

| Table | Columns |
|-------|---------|
| `login` | `id`, `nickname`, `email`, `passwort` (md5) |
| `charakter` | `login_id`, `erfahrungspunkte`, `charakterpunkte`, `kraft`, `intelligenz`, `schnelligkeit`, `geschicklichkeit`, `ausdauer`, `arbeitsende` (datetime, idle = `9999-00-00 00:00:00`), `taetigkeit` (0 = idle, 1-13), `dauer`, `status` (1 = logged in) |
| `rohstoffe` | `login_id`, `pflanzliche_nahrung`, `tierische_nahrung`, `wasser`, `holz`, `stein` (start 10/10/10/0/0) |
| `inventar` | `login_id`, `axt`, `jagdspeer`, `wasserbeutel`, `korb`, `lendenschurz`, `schuhe` |
| `skills` | `login_id`, `jagen`, `sammeln`, `fischen`, `pilzesuchen`, `holzfaellen`, `steinmetzen`, `wassersuchen`, `axt`, `jagdspeer`, `wasserbeutel`, `korb`, `lendenschurz`, `schuhe` |
| `kleine_tiere`, `pflanzen`, `fische`, `pilze` | `id`, `name`, `nahrungspunkte`. **The contents are not in the repo** (13/21/20/4 rows), so the modernized version needs its own seed data. |

## External services

None besides PHP `mail()` (welcome mail, e-mail change, password mails, sender hardcoded to a private address).

## Security and quality issues

- **SQL injection everywhere:** all POST/GET values go unescaped into `mysql_query` (login, register, profile, `pw_gesendet`). `dauer` from GET is unvalidated.
- `mysql_*` removed in PHP 7; `die()` as error handling.
- **Passwords:** unsalted md5; the new password is mailed in plain text (`passwort_neu.php`) and even echoed on the page in `pw_gesendet.php` (anyone knowing nickname and e-mail can take over the account; the old password is not required to change it).
- **No session expiry:** `sessionpruefung.php` and `header.php` set the expiry to now + 60 min on every request and then compare against it, so it never fires. No session id regeneration, no CSRF protection; state-changing actions are GET links.
- **XSS:** nickname and e-mail echoed unescaped.
- **Login with unknown user** dereferences a null row (notice); registration does not validate e-mail format, uses unescaped input in the SQL and sends a "Test der Mail-Funktion / blablabla" mail.
- **Game logic bugs:**
  - `if ($dauer=30)` in `Status.php` is an assignment (always true).
  - Column and name mismatches: `skills` insert uses `holzf�llen`, `pilzsuchen`, `wasserschlauch` and the `benutzer.` schema prefix, reads use `holzfaellen`, `pilzesuchen`, `wasserbeutel`; `inventar` insert has `wasserschlauch`, reads `wasserbeutel`; `werkzeuge.php` posts `wasserbeutel` but `produktion.php` maps only `wasserschlauch` (water bag crafting yields `$nr` undefined). `inventar.php` and `Status.php` would fail on a DB created from the registration inserts.
  - Crafting calls `arbeiten($nr)` in `Arbeiten.php` without duration (undefined consumption variables).
  - Consumption can drive food/water negative (only checked before subtracting); an activity can be started without any food or water.
  - Results are credited on page view, and two parallel requests can credit the same job twice (read-modify-write without transaction). `Arbeiten.php` accepts any `nr` 8-13 directly, so crafting can be triggered from the URL for free.
  - The date check compares strings; `9999-00-00` is an invalid date.
  - Lookup ranges `rand(1, 13/21/20/4)` are hard coded to the table sizes.
- Deprecated HTML (`<center>`, `border` attribute, `align` on `<a>`, tables for layout, `<link>` outside `<head>`), no charset declaration, Latin-1 sources with umlauts in file names (`Umgebung_gemaeßigt.jpg`).
- Mail sender, a private e-mail address, is hardcoded in four files; the imprint in `index.php` and `pw_vergessen.php` contains a private postal address.

## Dead code

- `Uebersichtskarte.php`, `Uebersichtskarte2.php`, `testkarte.php` (map prototypes, `Uebersichtskarte2` references the non-existing `Wald.html`).
- `Hauptseite.html` (prototype with links to German city websites), `Fragen.html` + `aufklappen.js` (placeholder FAQ).
- `menu.php`: function `href()` unused. `functions.php`: `arbeiten()` computes unused `$name`.
- Images used only by the map prototypes: `Karte*.jpg` (assets, never removed). `Banner_links.jpg` and `Banner_mitte.jpg` are not referenced (only `Banner.jpg` is).

## Cleanup

Deleted from `legacy/` (59 -> 57 files): `Fragen.html`, `aufklappen.js` (placeholder FAQ). Kept by user decision: map prototypes, `Hauptseite.html`, the design document and all images. Personal data replaced by placeholders: sender `MAIL_FROM_NAME <MAIL_FROM_ADDRESS>` in `kontrolle_reg.php`, `email_neu.php`, `passwort_neu.php`, `pw_gesendet.php`; imprint `IMPRESSUM_NAME`, `IMPRESSUM_STREET`, `IMPRESSUM_CITY` in `index.php`, `pw_vergessen.php`. `connect.php` already used `DB_*` placeholders.

## Stack decision

Next.js (App Router, server actions) + TypeScript + SQLite via `better-sqlite3`, same baseline as verwaltungstool (user decision). No deviations from `docs/conventions.md`.

## Modernized

`apps/games/hoehlenmensch/modernized/`: `src/lib/game.ts` (pure rules: activities, consumption, yield, skill gain), `src/lib/player.ts` (register, login, sessions, start and settle jobs in transactions), `src/lib/db.ts` (schema, invented seed data for the food tables), `src/lib/scenes.ts` (image-map hotspots), `src/app/` (landing with login and register, scene, work, status, crafting, inventory), `tests/game.test.ts` (7 tests).

Behavior changes and fixes:

- Prepared statements, scrypt-hashed passwords, hashed DB-backed session tokens (7 days, really expire), React-escaped output, no password mailed or echoed.
- Resources, items and skills are `(kind, name, qty)` rows in one `stock` table, which removes the legacy column-name mismatches.
- A job is credited exactly once (clearing `activity` in the same transaction); the result is credited on the next page load after the end time, as in legacy.
- Starting a job needs enough food and water (legacy let stock go negative). Animal food is used first; plant food covers the rest (legacy used animal food only).
- Crafting is always 60 minutes, costs only food and water, and can only be started through the server action. `if ($dauer=30)` is gone (one yield table per duration).
- Dropped: e-mail address, e-mail change, forgot-password mail, profile page (no mail service; login by nickname only), map prototypes, the unused Hauptseite hotspots without target (chest, character, tribe).
- Seed data for small animals, plants, fish and mushrooms is invented (8/8/8/4 rows), because the legacy tables are not in the repo. English names replace the German ones.
