# email-check

Legacy path: `apps/misc/email-check/legacy/` (5 files, 6 KB at inspection; 3 after cleanup).

## Inspect

| File | Purpose |
|------|---------|
| `index.php` | Form "Regex-Uebung": one text field, posts to `regex.php`. |
| `regex.php` | Checks the input against an e-mail regex and prints a yes/no message. |
| `user_select.php` | Foreign code: user profile lookup from a blog/community app. |

## Purpose and features

Exercise page for regular expressions. Only `index.php` + `regex.php` belong to the actual app: enter a string, get "Das ist eine E-Mail Adresse!" or "Das ist leider keine E-Mail Adresse!".

Regex: `/([a-zA-Z0-9]+)@{1}(([a-zA-Z]+)\.+)([a-zA-Z]+)/`

- Not anchored (`^`/`$`): `"foo a@b.c bar"` and `"x!!a@b.cZZZ"` match.
- Rejects valid addresses: `.`, `_`, `-`, `+` in the local part, digits or hyphens in the domain, multiple subdomain labels are only partly handled.
- `\.+` accepts `a@b...c`.

## Request flow

`GET index.php` -> form -> `POST regex.php` (`test`) -> message + link back. No state, no DB.

## Data model

None for the actual app. `user_select.php`/`zwischenusersuche.php` belong to a community app and use tables `user` (`id`, `name`, `vorname`, `nachname`, `gebDatum`, `wohnort`, `hobbys`, `beschreibung`), `blogs` (`user_id`) and `kommentare` (`user_id`); schema not available.

## External services

None.

## Security and quality issues

- `regex.php`: `$_POST["test"]` read without `isset` (notice if missing); output does not echo input, so no XSS.
- `user_select.php`, `zwischenusersuche.php`: SQL injection (`$user_id` interpolated into `mysql_query`), removed `mysql_*` API, XSS (`$name`, profile fields echoed unescaped), `<h2>...</h3>` mismatch, depend on missing `header.php`, `footer.php`, `id()`, `getProperty1/6()`, link to missing pages. Unusable on their own.
- `zwischenusersuche.php` differs from `user_select.php` only in the "show blogs" link (no `user_id` param) and a blank line; `user_select.php` is the newer one.
- Invalid HTML (`<form>` in `<p>`, `font-style: bold`).

## Dead code

- `test.html` (`window.status` is ignored by browsers; unrelated) and `zwischenusersuche.php` (duplicate) were removed in the legacy cleanup with user approval.
- `user_select.php` (unrelated to the regex exercise, cannot run) stays in legacy and is not ported.

## Inspected files removed in cleanup

`zwischenusersuche.php` (duplicate of `user_select.php`) and `test.html` (clock experiment), see git history of the original import for content: not preserved, both trivial.
