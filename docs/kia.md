# kia

Legacy path: `apps/sites/kia/legacy/` (159 files). A one-page classified ad for a used car ("KIA Rio 1.4 EX", 3.850 EUR negotiable) on the same "Timber" template as hallindi. Static, German, no backend. Copyright line 2014, owner Markus Allwang (i-use-my-brain.com).

## Inspect

| Part              | Purpose                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| ----------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `index.html`      | The only page: header with logo and menu (Home, Daten, Bilder), slider with four captions, data sections, gallery, contact footer. UTF-8 with BOM, `lang="en"` but German content.                                                                                                                                                                                                                                                                           |
| `css/` (15 files) | Loaded: `bootstrap.css`, `font-awesome.css`, `slicknav.css`, `style.css`, `camera.css`, `prettyPhoto.css`. Not loaded: `bootstrap.min.css`, `bootstrap-theme*`, `*.map`, `font-awesome.min.css`, `easy-pie.css`, `main.min.css`, `normalize.css`, `style.css.bak`.                                                                                                                                                                                           |
| `js/` (22 files)  | Loaded: `jquery-1.8.3.min.js`, `jquery.mobile.customized.min.js`, `jquery.easing.1.3.js`, `camera.min.js`, `myscript.js`, `sorting.js`, `jquery.isotope.js`, `bootstrap.min.js`, `jquery.slicknav.js`, `jquery.prettyPhoto.js`, `jquery.nav.js`. Not loaded: `bootstrap.js`, other jQuery copies (`jquery.js`, `jquery.min.js`, `jquery-1.11.1.min.js`), `current.js`, `easypiechart*`, `jquery.easypiechart.min.js`, `js1.js`, `myscript1.js`, `slider.js`. |
| `fonts/` (9)      | Font Awesome and Glyphicons, referenced from the CSS.                                                                                                                                                                                                                                                                                                                                                                                                        |
| `images/`         | `1.jpg` (hero photo), `logo.png`, `logo2.png` (favicon), 27 photos plus 27 thumbs in `prettyPhotoImages/`, prettyPhoto themes, template backgrounds, `slides/` (blank.gif only, plus Dreamweaver `_notes` files).                                                                                                                                                                                                                                            |

## Purpose and features

- Header summary: model, 87000 km, "8-fach bereift", price.
- Data sections: general data (first registration 03/2009, 87.266 km, petrol, 71 kW, manual, accident-free), technical data (1399 cm3, consumption, Euro 4, 2 owners, HU 06/2016, black metallic), equipment, description (ex-rental car, summer and winter tyres, test drive possible until mid-January).
- Gallery of 27 photos, filter All / Aussen (12) / Innen (15), lightbox with slideshow (prettyPhoto, light_square).
- Contact: "Sie haben Interesse?" with phone numbers and an e-mail address.
- Slider with four captions over a blank image (Camera plugin). Menu with smooth scroll (`jquery.nav`), mobile menu (slicknav).

## Page flow

Single static page. Scripts on load: Camera slider, Isotope filter and a portfolio plugin call in `index.html` that feeds three template demo items ("Foil Mini Badges", ...) into a block that is not on the page, hover handlers on `.heart`, show/hide buttons that match nothing, prettyPhoto on `.pretty a`.

## External services

Google Fonts (Roboto, Open Sans) over `http://`. No analytics, no forms, no API calls.

## Security and quality issues

- jQuery 1.8.3 (known XSS issues), Bootstrap 3, Camera, Isotope and prettyPhoto are unmaintained.
- Mixed content: Google Fonts over http.
- Template demo code in `index.html` (fake portfolio items, `.heart`, `.bhide` handlers) does nothing here.
- Slider slides use a blank gif as image, so it only shows captions.
- Accessibility: gallery thumbs have alt text, but `lang="en"` is wrong, no `<main>`, empty `<i class="fa">` elements.
- The ad is dated: HU valid until 06/2016 and "registered until mid-January".

## Personal data

`index.html` footer contains a private phone number (`+49 9624 2929 / +49 151 4144 9291`) and a private e-mail address, plus the owner's name in the copyright line. The same phone numbers are on hallindi.

## Duplicates and dead code

- Unused CSS and JS listed in the table (including four copies of jQuery, minified duplicates, a `.bak` file and Dreamweaver `_notes`).
- Differs from hallindi only in content and images; shared template assets (bootstrap, camera, prettyPhoto, ...) are copies, not byte-identical.

## Cleanup

Deleted from `legacy/` (159 -> 136 files) by user decision: all CSS and JS files not loaded by `index.html` (10 CSS, 11 JS) and the Dreamweaver `_notes` directories. Fonts and images are untouched. Personal data (phone numbers, e-mail address, owner name) left unchanged by user decision.

## Stack decision

Vite + TypeScript, vanilla (user decision), same as hallindi. No deviations from `docs/conventions.md`.

## Modernized

`apps/sites/kia/modernized/`: `index.html` (German content), `src/main.ts` (caption rotation, filter, `<dialog>` lightbox with previous/next), `src/lib.ts` (gallery data, pure filter and neighbour functions), `src/style.css` (copied from hallindi plus a few rules), `tests/lib.test.ts` (3 tests), `public/images/` (hero photo, logos, all 27 photos and thumbs).

Behavior changes:

- jQuery, Bootstrap, Camera, Isotope, prettyPhoto, slicknav, jquery.nav and Font Awesome are replaced by native code and CSS; system fonts instead of Google Fonts over http. Smooth scrolling is CSS.
- The slider is a plain caption rotation (the legacy slides were blank images).
- Lightbox without autoplay slideshow; has previous/next buttons.
- Dropped: template demo code (fake portfolio items, `.heart`, `.bhide` handlers), mobile slicknav menu (the menu wraps instead).
- Content, including the personal contact data, is unchanged.
- The style sheet is duplicated from hallindi; sharing it is still an open decision in PLAN.md.
