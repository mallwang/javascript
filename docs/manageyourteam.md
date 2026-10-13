# manageyourteam

Legacy path: `apps/web/manageyourteam/legacy/` (132 files) and `legacy-neu/` (134 files, formerly `myt_neu`). "ManageYourTeam" is a free web application to manage a football club (teams, players, coaches, appointments, matches, tournaments, attendance, line-up). German UI, PHP 5 era, `mysql_*`.

## Diff `legacy` vs `legacy-neu`

Identical content. Differences: six files use CRLF in `legacy` and LF in `legacy-neu` (`css/fullcalendar.css`, `inc/DB.php`, `js/fullcalendar.js`, `js/jquery-ui.custom.min.js`, `js/metro-calendar.js`, `sitemap.xml`), and `legacy-neu` has an Eclipse `.settings/` directory (2 IDE files). Nothing else.

## Inspect

| Path | Purpose |
|------|---------|
| `index.php`, `.htaccess` | Front controller. Rewrite rules map `/p1/p2/p3` to `index.php?p1=&p2=&p3=`; a `switch` picks the page. |
| `inc/base.inc.php`, `templates/main.htm` | Template placeholders (`$base[...]`): title, meta, navigation bar with login form, CSS/JS includes. |
| `inc/DB.php` (3196 lines) | `DB` class: connection, generic query builders, getters/updaters/creators/deleters for 18 tables, `cryptPassword`, `generateMail`, `sendEmail`. Only partly used by the endpoints (see below). |
| `inc/ajaxTest.php` | Despite the name the live generic read endpoint: `?class=db&function=getXByY&id=` dispatches to `DB` getters; used by the page scripts (`mannschaftsuebersicht.js`, `spieleruebersicht.js`, `traineruebersicht.js`, `turniertermine.js`, ...). |
| `inc/ajaxControl.php`, `inc/db_test.php`, `inc/db_test.css` | Unused copy of the dispatcher (not referenced) and a DB test page. |
| `functions/*.php` (41 files) | AJAX endpoints (inline SQL): `login`, `register`, `newPW`, `newBestmail`, `create/save/delete` for Coach, Player, Team, Match, Tournament, TournamentMatch, Appointment, Event, `saveAttendance`, `saveLineup`, `savePassword`, `saveProfil`, `deleteProfil`, `saveVereinsprofil`, `liveSearch`, calendar feeds (`alleTermine`, `alleTermineClub`, `spieltermine`, `trainingstermine`, `turniertermine`), `getSpielplan`, `getTurnierplan`, `getAufstellung`, `getAttendance`, `get*Infos`. |
| `functions/lib/` | PHPMailer (`class.phpmailer.php`, `class.smtp.php`, `PHPMailerAutoload.php`) and `funktionen.php` (`cryptPassword`, `sendEmail`). |
| `calendar.php` | FullCalendar page. |
| `js/` (30 files) | Page scripts (`anwesenheit.js`, `aufstellung.js`, `spielplan.js`, `turnierplan.js`, `vereinProfil.js`, ...) and vendor libs (jQuery, jQuery UI, DataTables, FullCalendar, Metro UI). |
| `css/` (23), `fonts/`, `img/` (11 png) | Metro UI CSS, FullCalendar, 9 `taktik_*.css` line-up formations, `aufstellung.css`. |
| `robots.txt`, `sitemap.xml`, `.buildpath`, `.project` | SEO files and Eclipse project files. |

## Purpose and features

Three roles (`usertab.userRights`): 0 club admin, 1 coach, 2 player.

- **Registration:** a club admin registers with first and last name, club name, e-mail and password. A confirmation mail with a link `/Bestaetigung/<email>/<key>` activates account and club. The webmaster address gets a "new club" mail. Resend confirmation (`newBestmail`) and forgot password (`newPW`, mails a new 6-character password in plain text).
- **Login:** by e-mail and password; the session stores `userID`, `email`, `clubID`, `clubName`, role and, for coaches and players, `coachID`, `playerID`, `teamID`, `teamBez`.
- **Admin:** club profile (short text, long text, history), create/edit/delete teams (team type, name), coaches (account is created and a mail with the password is sent) and players (account, birth date, team, position), team-to-coach assignment, delete own profile.
- **Calendar:** appointments (`eventType`: training, match, tournament, other) with start/end date and time, info and location; club calendar via FullCalendar; separate lists for trainings, matches and tournaments.
- **Matches and tournaments:** match per team with opponent and location, goals and opponent goals; tournaments with several matches per team (`teamtournamenttab`).
- **Attendance:** per training and player an `attendanceValue`.
- **Line-up:** formation per team (`lineupID`, nine layouts `taktik_1..9.css`) with players dragged onto positions.
- **Profile:** change e-mail and password; `liveSearch` for player and coach search.
- **404 pages** for guests and logged-in users; public pages: Home, imprint-like pages from the template.

## Request flow

Browser -> `.htaccess` rewrite -> `index.php` (reads `p1/p2/p3`, checks `$_SESSION['email']`, builds the page from the template). The page scripts then call the `functions/*.php` endpoints with jQuery AJAX (POST) and receive plain strings (`ok`, `fehler`, `emailSchonVorhanden`, ...) or HTML/JSON. Role and club come from `$_SESSION`, entity ids from POST.

## Data model

There is no SQL file. Derived from `functions/*.php` (the live code) and `inc/DB.php` (older class; its table helpers use columns and tables that the endpoints no longer use, e.g. `locationtab`, `matchtab`, `parenttab`, `playermatchtab`, `playerpositiontab`, `positiontab`, `teammatchtab` with goals/points, `tournamenttab`, `trainingtab`).

Tables used by the endpoints:

| Table | Columns |
|-------|---------|
| `usertab` | `userID`, `userEmail`, `userPassword` (bcrypt `$2y$09`), `userKey` (activation key), `userActive`, `userRights` (0/1/2), `userDelete` |
| `clubtab` | `clubID`, `userID`, `clubName`, `clubAdminFirstName`, `clubAdminSecondName`, `clubShort`, `clubLong`, `clubHistory` |
| `teamtab` | `teamID`, `clubID`, `teamName` (-1 = no name), `teamTypeID`, `lineupID` |
| `coachtab` | `coachID`, `userID`, `clubID`, `coachFirstName`, `coachSecondName` |
| `teamcoachtab` | `coachID`, `teamID` |
| `playertab` | `playerID`, `userID`, `teamID`, `clubID`, `playerFirstName`, `playerSecondName`, `playerBirthdate`, `positionID` |
| `eventtab` | `eventID`, `teamID`, `clubID`, `eventType`, `eventInfo`, `location`, `eventStartDate`, `eventEndDate`, `eventStartTime`, `eventEndTime` |
| `teammatchtab` | `eventID`, `teamID`, `opponent`, `location`, `teamGoals`, `opponentGoals` |
| `teamtournamenttab` | `teamTournamentID`, `eventID`, `teamID`, `matchStartDate`, `matchEndDate`, `matchStartTime`, `matchEndTime`, `opponent`, `teamGoals`, `opponentGoals` |
| `attendancetab` | `playerID`, `eventID`, `attendanceValue` |

Team types come from the PHP function `convertTeamTypeID` (age groups), not from a table.

## External services

SMTP via PHPMailer (SSL, port 465) for confirmation, welcome, password and profile mails. Sender and webmaster address `benutzerverwaltung@manageyourteam.de` are hardcoded in 9 files. Hosting was STRATO (`.htaccess` header). No third-party APIs; all JS and CSS are local.

## Security and quality issues

- **SQL injection:** `mysql_real_escape_string` is used on most POST values, but ids from `$_SESSION` and some GET values go in unescaped; the escape function does not protect numeric contexts or charset edge cases. `mysql_*` was removed in PHP 7.
- **Broken access control:** 22 of the 41 endpoints never reference the session (`deleteTeam`, `deletePlayer`, `deleteCoach`, `saveAttendance`, `saveLineup`, `liveSearch`, ...); entity ids come from POST without checking that they belong to the user's club. Anyone can delete or modify other clubs' data. `inc/ajaxTest.php` exposes `DB` getters by name to anyone (and references `$id` before assigning it).
- **Passwords:** bcrypt (`crypt` with `$2y$09`) is fine, but the salt comes from `array_rand` (not cryptographically secure), a new password is mailed in plain text (`newPW`, `savePassword`, coach/player creation), and `newPW` lets anyone reset any account's password by e-mail address (no token; DoS and account lockout).
- **Activation key:** `md5(substr(md5(microtime()), ...))` is predictable; the link contains the e-mail address.
- **Mail:** sends HTML with unescaped user input (club name, names) -> HTML injection in mails; `sendEmail` calls `exit` on failure, after the DB has been changed.
- **XSS:** names, info and location are echoed into HTML without escaping in the endpoints and in the page scripts.
- **No CSRF protection;** no session id regeneration at login; session stores display data.
- `DB::generateMail` echoes the confirmation link instead of mailing it (debug code).
- The test page `inc/db_test.php` is deployed. Source files mix ISO-8859-1 and UTF-8 (broken umlauts in comments, `�`).
- `each()` in `sendEmail` was removed in PHP 8.

## Dead code

- `inc/DB.php`: most of it. The endpoints use inline SQL; these tables of the class have no counterpart in them (`locationtab`, `matchtab`, `parenttab`, `playermatchtab`, `playerpositiontab`, `positiontab`, `tournamenttab`, `trainingtab`, `teamtypetab`).
- `inc/ajaxControl.php` (unreferenced), `inc/db_test.php`, `inc/db_test.css`. (`inc/ajaxTest.php` is live, see Inspect.)
- `.buildpath`, `.project` (Eclipse), `legacy-neu/.settings`.

## Cleanup

`legacy-neu/` deleted by user decision (identical to `legacy`). Deleted from `legacy/` (132 -> 127 files): `inc/db_test.php`, `inc/db_test.css`, `.buildpath`, `.project`. `inc/ajaxTest.php` was removed by mistake at first and restored from the original export; it is live code and stays. `inc/ajaxControl.php` stays (no deletion approved for it). Contact address `benutzerverwaltung@manageyourteam.de` replaced by the placeholder `MAIL_FROM_ADDRESS` in 9 files (user decision). SMTP settings were already placeholders (`SMTP_HOST`, `SMTP_USER`, `SMTP_PASSWORD`). All images, fonts and CSS kept.

## Stack decision

Next.js (App Router, server actions) + TypeScript + SQLite via `better-sqlite3`, same baseline as verwaltungstool and hoehlenmensch (user decision). No deviations from `docs/conventions.md`.

## Modernized

`apps/web/manageyourteam/modernized/`: `src/lib/rules.ts` (pure rules: roles, formations, line-up and event validation), `src/lib/club.ts` (data and authorization layer), `src/lib/db.ts` (schema), `src/lib/auth.ts` and `src/lib/password.ts` (sessions, scrypt), `src/app/` (login and club registration, club, team line-up, calendar, event, profile), `tests/club.test.ts`.

Behavior changes and fixes:

- Prepared statements, scrypt-hashed passwords, hashed DB-backed sessions (7 days), React-escaped output.
- Every mutation checks role and club scope (legacy had none for 22 endpoints). Admins manage the club, coaches only their assigned teams, players see only their own team and attendance.
- Users, coaches and players are one `users` table (role admin/coach/player); a match is an `events` row with opponent and goals; start and end are `YYYY-MM-DDTHH:MM`.
- Line-up: 7 formations (legacy layouts 8 and 9 duplicated 5-3-2 and 5-4-1); positions are chosen with dropdowns instead of drag and drop.
- Dropped: all mail (activation, welcome, forgot password), so accounts are active immediately and the admin sets initial passwords; the 3-day grace period for profile deletion (deletion is immediate with password confirmation; deleting the admin deletes the club); live search; the unused `DB.php` class.
- Changing the password ends all sessions of that user.
