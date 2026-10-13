# hallindi

Legacy path: `apps/sites/hallindi/legacy/` (164 files, 9 MB; 142 after cleanup). Single static page `index.html` (UTF-8 with BOM, CRLF), title "Hubert Allwang Internetdienstleistung". Built on the "Timber" HTML template (the footer still says "Copyright 2014 Timber HTML Template"). Very similar to `sites/kia` (same template, same JS/CSS libraries, differing content).

## Inspect

| File / dir   | Purpose                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| ------------ | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `index.html` | The whole site: menu, slider, about/team, portfolio charts, project gallery, contact form, newsletter, footer. About 38 KB with inline scripts.                                                                                                                                                                                                                                                                                                                                                                                   |
| `css/`       | Used: `bootstrap.css`, `font-awesome.css`, `camera.css`, `slicknav.css`, `prettyPhoto.css`, `style.css` (custom). Unused: `*.min.css` copies, `bootstrap-theme*`, `.map` files, `main.min.css`, `normalize.css`, `easy-pie.css`, `style.css.bak` (whitespace-only differences from `style.css`).                                                                                                                                                                                                                                  |
| `js/`        | Used: `jquery-1.8.3.min.js`, `jquery.mobile.customized.min.js`, `jquery.easing.1.3.js`, `camera.min.js`, `myscript.js` (easyPieChart + onScreen plugins inline), `sorting.js` (Isotope filter + `portfolio_addon`), `jquery.isotope.js`, `jquery.prettyPhoto.js`, `bootstrap.min.js`, `jquery.slicknav.js`. Unused: `jquery.js`, `jquery.min.js`, `jquery-1.11.1.min.js`, `bootstrap.js`, `easypiechart*.js`, `jquery.easypiechart.min.js`, `jquery.nav.js` (commented out), `js1.js`, `myscript1.js`, `current.js`, `slider.js`. |
| `fonts/`     | Font Awesome and Glyphicons (eot, svg, ttf, woff, otf).                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| `images/`    | Slider images, team/about pictures, prettyPhoto gallery images and thumbs, icons, `hand.png`, logo. `picNews/` belongs to the commented-out news section.                                                                                                                                                                                                                                                                                                                                                                         |
| `Logos/`     | `Logo1-11.jpg`, `erde.png`, `erde2.png`; not referenced by `index.html`.                                                                                                                                                                                                                                                                                                                                                                                                                                                          |

## Purpose and features

Business card site of a small web and Android app development shop (German).

- **Home:** fixed top menu (anchors `#home`, `#about`, `#portfolio`, `#projekte`, `#kontakt`, mobile menu via slicknav, menu turns into a fixed transparent bar after 100 px scroll), Camera slider with three captions ("DESIGNEN. VERBESSERN. OPTIMIEREN."), animated counters (2 websites, 3 Android apps, "... viele" satisfied users), intro text.
- **About:** three feature boxes, two team members (one with lorem ipsum text), testimonials (placeholder "Anna Smith / Sarah White, Company Inc.", lorem ipsum).
- **Portfolio:** four animated pie charts (Webdesign, E-Commerce, Android-Programmierung with empty text, Services). A larger skills block (HTML5, CSS3, JavaScript, PHP, AJAX, Java, SQL, Webservices) is commented out.
- **Projects:** Isotope grid with filter (all / websites / apps) and prettyPhoto lightbox. Five entries: two websites (i-use-my-brain.com, photovoltaik-allwang.de) and three Google Play apps (Wo ist was?! PLZ, Wo ist was?! Besondere Orte, MySunPower), each with date.
- **Contact:** form (name, e-mail, subject, message), address, two phone numbers, `info@hallindi.de`, dead social links (Twitter, Facebook, Dribbble, Flickr, YouTube).
- **Newsletter box** and footer menu.

## Page flow

Pure client side, no backend, no database, no build step. Scroll navigation animates to the section anchors (`calculateScroll()` highlights the active menu entry). Jobs on load: counters count up after 1 s, charts animate when scrolled into view (`onScreen`), Isotope lays out the grid on `window.load`.

## External services

- Google Fonts (Roboto, Open Sans) over plain `http://`.
- Links to the two project websites and Google Play.
- No analytics, no map (Google Maps iframe commented out).

## Security and quality issues

- **The contact form and newsletter do nothing:** `leereForm()` only shows `alert("Form leeren")` and writes "absce" into the textarea; `eintragNewsletter()` only shows `alert(...)`. "Nachricht absenden" links to `#`. No form action, no mail handler.
- jQuery 1.8.3 (2012, known XSS issues), unmaintained plugins (Isotope 1.x, prettyPhoto, Camera, jquery.mobile customized), Bootstrap 3, `window.load` shorthand removed in jQuery 3.
- Mixed content: Google Fonts over http.
- Dead and broken markup: unclosed `</a>` order (`<a><i></i>text</li></a>`), invalid `<link ...></link>`, duplicate `class` attributes, `<span name=...>`, `portfolio_addon` is called with demo items from the template ("Foil Mini Badges", "Darko - Business Card Mock Up") that only load with the commented-out "Load more" button.
- `calculateScroll()` reads `.navmenu` which does not exist on the page (the menu is `#menu`), so the active-state highlight and the click handler there never run.
- Missing `lang`, `viewport`-related and accessibility basics (no alt on some images, links with `#`).
- Template leftovers: "Copyright 2014 Timber HTML Template", lorem ipsum, fake testimonials, a commented-out news section with six images (`images/picNews/`), "Search the Site" input without function, eight empty social icons in the footer.
- 10 MB of which most is unused libraries and duplicates (see below).

## Personal data

- Names of two persons in the team section and the title.
- Private postal address (`Hütberg 3, 92266 Ensdorf`), two phone numbers and `info@hallindi.de` in the contact block.

## Duplicates and dead code

- CSS: the unused files listed above, including `style.css.bak`.
- JS: the unused files listed above (about 700 KB, `current.js`, `js1.js`, `myscript1.js` are older template bundles).
- Assets are never removed: `Logos/`, `images/picNews/`, slider and team images stay.
- `sites/kia` repeats the same libraries and template; sharing the modernized template is a decision for step 4.

## Cleanup

Deleted from `legacy/` (164 -> 142 files) by user decision: all CSS and JS files not loaded by `index.html` (listed under Inspect), including `style.css.bak`. All images, fonts and `Logos/` kept. Personal data (names, address, phones, `info@hallindi.de`) left unchanged by user decision, as it is the public business card of the site.

## Stack decision

Vite + TypeScript, vanilla: no framework, no backend, no jQuery (user decision). Same baseline as richkidzofupo. Deviation: none; `.env.example` is empty because no configuration is needed.

## Modernized

`apps/sites/hallindi/modernized/`: `index.html` (German content), `src/main.ts` (caption rotation, count-up counters, project filter, `<dialog>` lightbox, mailto form), `src/lib.ts` (project data, pure filter and counter functions), `src/style.css` (plain CSS grid), `tests/lib.test.ts` (3 tests), `public/images/` (only the used assets).

Behavior changes:

- jQuery, Bootstrap, Camera, Isotope, prettyPhoto, easyPieChart, slicknav and Font Awesome are replaced by native code and CSS; system fonts instead of Google Fonts over http.
- The contact form opens the mail client (`mailto:info@hallindi.de`); the legacy form had no working backend.
- Dropped: newsletter box (did nothing), dead social links, lorem ipsum testimonials and CEO bio, the template footer text, the empty Android and Services portfolio texts, the pie charts (all 100%).
- App store links use https; the third counter shows "... viele" after counting up.
- Unused legacy images stay in `legacy/`; only used assets were copied.
