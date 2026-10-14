# unser-blog

Source: `gitlab-export/unser-blog-main/` (64 files, ~800 KB, not yet moved to `apps/`). Student group project "Gruppe1 - Blog", version 1.0, 25.10.2012 (DHBW Stuttgart). Authors: Markus Allwang, Sophia Harland, Lena Pinkerneil. Originally hosted at `www.unser-source.co.de`. Eclipse PHP project (`.project`, `.buildpath`, `.settings/`).

## Inspect

| Area | Files |
|------|-------|
| Entry | `index.php` (registration, login, "forgot password" form on one page) |
| Auth | `reg_control.php`, `log_control.php`, `bestaetigung.php` (activation), `pw_gesendet.php` (reset), `logout.php`, `sessionpruefung.php` (guard) |
| Blog | `home.php` (posts of subscribed users), `blog.php` (own posts), `new_blog.php`, `send_blog.php`, `delete_blog_confirm.php` |
| Comments | `send_comment_blog.php`, `send_comment_home.php`, `delete_comment_confirm_blog.php`, `delete_comment_confirm_abbonnement.php` |
| Users | `suche.php`, `usersuche.php`, `user.php` (public profile), `abbonieren.php`, `stop_abbonieren_confirm.php` |
| Profile | `profil.php`, `persDaten_aendern.php`, `persDaten_neu.php`, `fotoHochladen.php`, `bildPruefen2.php`, `email_aendern.php`, `email_neu.php`, `pw_aendern.php`, `pw_neu.php` |
| Layout | `header.php` (logged in), `header_outside.php`, `footer.php`, `footer_outside.php`, `impressum.php` |
| DB | `connect.php` (**real credentials**), `functions.php` (query helpers, e-mail regex, password strength), `gruppe1.sql` (gzip-compressed dump despite the extension in the export, plain text and anonymized in `legacy/`) |
| Client | `pruef.js` (form checks, delete confirms), `jQuery.js` (v1.8.2), `modern.css` (Metro UI CSS 0.1.1, MIT) |
| Assets | `mail.ico`, `security.ico`, `images/` (4 pager arrows), `images/Userbilder/` (12 JPEGs: `Dummyuser.jpg` plus profile photos named `<user id>.jpg`) |

Missing: `images/menu_*.jpg`, `images/hintergrund.jpg` (only in commented-out header code and a reset-mail style), `delete_comment_confirm.php` (called from a dead `deleteMe()` in `header.php`).

Encoding: PHP files are ISO-8859-1 (umlauts), `readMe.txt` too; the SQL dump is latin1 tables.

## Purpose and features

Multi-user blog with subscriptions ("Abonnements"). Per `readMe.txt`:

- **Register** with nickname, e-mail, password; activation by mailed link (`bestaetigung.php?key=`).
- **Login** by nickname or e-mail, session key `nickname`.
- **Home**: posts of subscribed users, newest first, 5 per page, with comments and a comment form.
- **Blog**: own posts (5 per page), new post (subject max 50, text), delete own post (cascades to comments).
- **Comments** on any post shown in Home or Blog (subject prefilled `Re: <subject>`), delete own comment.
- **Profile**: first name, last name, birth date, place, hobbies, description, profile photo (JPEG, max 100x100 px, 100 KB); list of own subscriptions with "stop subscription".
- **Search** users by nickname substring (`*` = all) with post/comment counts; open public profile in a popup (shows the e-mail address of the user!); subscribe.
- **Options**: change e-mail (notification to old and new address), change password (mail), "forgot password" (new random password by mail).
- Static pages: impressum, contact menu (hard-coded university e-mail).

## Request flow

- Public: `index.php` -> `header_outside.php` -> forms POST to `reg_control.php` / `log_control.php` / `pw_gesendet.php`; these render the result inside the outside layout. Links use the absolute production URL `http://www.unser-source.co.de/...`.
- Protected: every page includes `header.php` which does `session_start`, `connect.php`, `functions.php`, then `sessionpruefung.php` (prints "please log in" and `exit` if `$_SESSION['nickname']` is missing).
- Plain server-rendered PHP, no framework, HTML tables for layout, jQuery only for show/hide toggles (custom tags `<z>`, `<abc>`, `<neuesPasswort>`).
- Pagination: `?beginn=<offset>`, page size 5, links first/prev/next/last. Detail pages are rendered with `<meta http-equiv="refresh">` redirects after actions.
- Actions are GET links with ids (`delete_blog_confirm.php?blog_id=`, `abbonieren.php?user_id=`), confirmation via JS `confirm()`.

## Data model (from `gruppe1.sql`)

All tables InnoDB, latin1, no foreign keys.

```
user(id PK, schluessel varchar(60)  -- activation key, '' = active
     name UNIQUE varchar(30), email UNIQUE varchar(50), passwort varchar(60) md5,
     vorname, nachname, gebDatum date, wohnort, hobbys, beschreibung text)
blogs(id PK, user_id, betreff varchar(50), inhalt text, datum timestamp default now on update now)
kommentare(id PK, blog_id, user_id, betreff varchar(50), inhalt text, datum datetime)
abbonnements(id PK, user_id, abbonierter_id)   -- subscriber -> author
```

Notes: `blogs.datum` has `ON UPDATE CURRENT_TIMESTAMP`, only inserts occur. `gebDatum` uses `0000-00-00` for "unset". Text is stored HTML-escaped (`htmlentities`) with newlines converted to `<br>`; the dump holds 12 users, ~35 posts, ~30 comments, 18 subscriptions (demo content: fruit articles, news excerpts, test posts).

## External services

- PHP `mail()` for activation, new password, password change, e-mail change (sender `MarkusAllwang@gmx.de`).
- No third-party APIs. Everything else is local (jQuery 1.8.2, Metro UI CSS).

## Security issues

- **Credentials committed:** `connect.php` has the real MySQL host user, password and database name of the hosting account. Must become `.env` / placeholders.
- **Personal data committed:** `gruppe1.sql` contains real e-mail addresses (private and university) and names of students/relatives plus md5 hashes; at least four hashes are the unsalted md5 of trivial passwords (identical hash for several users) and are crackable in seconds.
- **SQL injection everywhere:** `mysql_*` with string concatenation. `functions.php` builds `SELECT/INSERT/UPDATE` from `func_get_args()` with unescaped values and table/column names. GET ids (`blog_id`, `user_id`, `id`, `beginn`) go straight into SQL (`DELETE FROM blogs WHERE id = $blog_id`, `LIMIT $beginn, ...`). `htmlentities` on POST input is the only (HTML, not SQL) escaping and does not escape single quotes by default.
- **Stored/reflected XSS:** post and comment text is stored as HTML and echoed raw; `$name`, profile fields (`vorname`, `hobbys`, ...) are saved unescaped in `persDaten_neu.php` and printed raw; `$_GET["key"]` etc. are echoed.
- **Passwords:** unsalted md5; the new password is mailed in plaintext after a reset, and the **new password after a change is mailed in plaintext** too (`pw_neu.php`). Passwords limited to 15/20 characters in the form.
- **Account takeover/DoS:** `pw_gesendet.php` overwrites the password immediately for anyone who knows nickname and e-mail (no confirm token). The reset code uses `md5(microtime())`, which is predictable; the activation key is only `md5(8 hex chars)` (32 bits).
- **Activation bypass:** `reg_control.php` sets `$_SESSION['nickname']` right after registering, and the guard only checks that key, so an unconfirmed account is already logged in.
- **No CSRF protection;** all state changes are GET links. No `session_regenerate_id`.
- **Broken access control:** `stop_abbonieren_confirm.php` runs `DELETE FROM abbonnements WHERE abbonierter_id = ...` without `user_id`, so it deletes every subscriber's subscription to that author. `delete_comment_confirm_abbonnement.php` and others only check ownership via an unvalidated GET id; `send_comment_*` accepts any `blog_id`, even one the user cannot see. `usersuche.php`/`user.php` expose every user's e-mail address.
- **Upload:** `bildPruefen2.php` moves the file to `images/Userbilder/<id>.jpg` *before* validating width/height, relies on `getimagesize` type 2, no re-encoding.
- **Info leaks:** `die(mysql_error())` messages, hard-coded production URLs in links, error messages revealing that an e-mail/nickname exists.
- **Obsolete APIs:** `mysql_*` and `ereg_replace` (removed in PHP 7), `mail()` with hand-built headers (header injection via nickname/e-mail), custom HTML tags.

## Dead code and quirks

- Large commented-out blocks (empty-field checks moved into `pruef.js`, an old image menu in `header.php`).
- `deleteMe()` in `header.php` points to a missing file; `getPropertyLike()`, `pwPruefung()` (password strength, never used on the server) are unused.
- `home.php`/`blog.php` fetch with `LIMIT $beginn, $rest` first (all rows) only to count; `usersuche.php` does N+1 queries per user.
- `blog.php` and `home.php` show "no entries" using inverted conditions (`$anzahl == 0` guarding the loop in `profil.php`) that look buggy; verify before porting.
- `.project`, `.buildpath`, `.settings/` are Eclipse metadata, no value in the repo.

## Decisions

- **Location:** `apps/web/unser-blog/legacy`.
- **Legacy cleanup:** `.project`, `.buildpath`, `.settings/` removed (Eclipse metadata). `connect.php` secrets replaced by `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`. `gruppe1.sql` was decompressed (plain text now) and anonymized: e-mails are `userN@example.com`, all password hashes are the md5 of `password`, one real surname replaced. Blog texts and nicknames are unchanged demo content. Images, jQuery and Metro UI CSS are kept.
- **Stack:** Next.js + TypeScript + SQLite (same as bierspiel). No mail delivery: activation and reset links are only logged to the server console; no SMTP configuration.
