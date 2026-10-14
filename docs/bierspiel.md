# bierspiel

Legacy path: `apps/games/bierspiel/legacy/` with `v5` and `v6` (PHP 5, `mysql_*`, German UI). The browser game was originally one project called "Schankimperium" that ended up spread over several repos and devices. The vision for a rewrite is in [bierspiel-concept.md](bierspiel-concept.md); it is not part of the port.

## Evolution and decision

Two lines of development existed (earlier stages `v1` to `v4` and the GitLab exports were compared and deleted, see `PLAN.md`):

- **A, brewery/pub simulation:** v1 → v2 → v3 → v6. Bootstrap 2, jQuery 1.9, jVectorMap, clean URLs. Buildings, drinks menu, NPC and player markets, bank, settlement. `v6` is the newest game logic.
- **B, labour market:** v4 → v5. Metro UI CSS shell, bids on workers, weekly payroll. `v5` is the newest state.

Decision: modernize `v6` and port the labour market (Part B) into it. Everything in the "Security" and "Secrets" sections below is to be fixed or dropped in the port. Column types are inferred, there is no DDL in the code. Seed data (`preise`, `stadtviertel`, `nachfrage`) is missing in the code and must be invented during the port.

Both parts below are written so that the port works without the legacy code. Email addresses found in the code are not repeated here; the file references show where they are.

# Part A: v6 brewery game

Source: `apps/games/bierspiel/legacy/v6` (PHP 5, `mysql_*`, Bootstrap 2, jQuery 1.9, jVectorMap). Files are Latin-1 (use `iconv`/`grep -a`). Column types below are INFERRED (no DDL in code).

### 1. Purpose and page/request flow

Browser game: each player has one company (`firmendaten`), starts with 200,000 EUR, builds pubs ("Kneipe", "Gaststaette"), configures them (style, target groups, seats, toilets), buys drinks from an NPC market, sets a drinks menu with prices, and a periodic settlement (`abrechnung.php`) computes visitors, sales, interest. Other building types, research, employees, advertising, extensions, labour market, city page are placeholders.

#### Entry points / clean URLs (.htaccess, mod_rewrite)
`Aufbau, Screenshots, Impressum, Kontakt, Logbuch, Support_Ticket, Home, Error, Login, Registrierung`, `Bestaetigung/(key)/(benutzer)/(art)`, `Firma, Landwirtschaft, Neues_Gebaeude, Produktion, Sonstige, Transport, Verkauf, Lager, Forschung`, `Finanzen, Einnahmen, Ausgaben, Bank`, `Stadt, Markt, Eigene_Angebote`, `Onlineuser, Userstatistik`, `Neue_Nachricht, Posteingang, Postausgang`, `Profil, Logout`. 404 -> `/Error`. `.htaccess`/`.htpasswd` denied.
`index.php` = start page (jVectorMap world map is the only "map"); `startHeader.php` (public) vs `geheimHeader.php` (logged in, includes `sessionpruefung.php`).

#### Session / auth
- Session vars set on login: `nickname` (holds the EMAIL), `user`, `userId`, `firmenname`, `firmaId`. `sessionpruefung.php` redirects if unset.
- `validate.php` (POST `art`):
  - `login`: password `md5()`. If input matches `^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$` -> lookup by email (lowercased) else by nickname. Then `SELECT firmenname,id FROM firmendaten WHERE user_id=`. If `aktiviert==1`: `UPDATE user SET anzahlLogins=+1,status=1,letzterLogin=now`, `DELETE FROM loeschen WHERE firma_id=` (cancels pending deletion), set session, echo `ok`. Codes: `nichtAktiviert`, `no`, `fehlerEintragAnzahlLogin`.
  - `regis`: fields nickname, email1, passwort1, passwort2 (never compared server-side), firmenname, standort. Empty -> `nichtAlleAusgefuellt`; duplicates -> `benEmailVorhanden`, `firmaVorhanden`. Key: `$zufallskey=substr(md5(microtime()),mt_rand(0,24),8); $schluessel=md5($zufallskey)`. Inserts `user`, `firmendaten` (konto=200000, rest 0), `finanzen` (konto 200000, kredit 0, kreditrahmen 200000). Mails to webmaster and user (activation link `schankimperium.de/Bestaetigung/$schluessel/$nickname/neuerAccount`). Codes: `fehlerVersandUser, fehlerVersandWebmaster, fehlerFinanzenEintragen, fehlerFirmaEintragen, fehlerUserAnlegen, fehler`.
- `bestaetigung.php`: `neuerAccount` sets `aktiviert=1` by `schluessel`; `neuesPasswort` via `schluessel_pw` generates a new password, mails it, sets `schluessel_pw=0`. `startseite/neuesPasswort.php` = form.
- `datenNeu.php` (POST `art`): `email` (change, needs password, unique, mails old+new), `passwortIntern` (md5), `firmenname` (rename; bug: undefined `$id` in UPDATE), `passwort` (forgot: sets `schluessel_pw`, mail), `best` (resend activation), `accountLoeschen` (`INSERT loeschen(user_id,firma_id,timestamp)` + mail). Codes `ok, pwFalsch, emailFalsch, eMailVorhanden, fehlerUpdate, ...`.
- `loescheAccounts.php`: intended cron to purge accounts in `loeschen`; BROKEN (`$firmaId`, `$userId` undefined; read step empty). Deletes from `finanzen, firmendaten, pns, gebaeude, lager, quellwasser, user`; does NOT delete `getraenkeangebote, gebaeudeerweiterungen, getraenkelager, arbeitnehmer`. No session check.

#### Modules
- `firma/neuesGebaeude.php` + `bau.php`: construct buildings (see 2.1). `abreissen.php` (delete, no refund), `verkaufen.php` (refund baukosten/2), `neuerName.php` (rename building), `neueKarte.php` (drinks menu), `speichern.php` (rebuild), `verkauf.php` (3401 lines; pub management UI with tabs: style/target groups/seats/toilets, drinks card, buy goods, extensions A-F static text, advertising/staff placeholders), `lager.php`/`lagerupdate.php` (raw-material warehouse and sell to player market).
- `markt/`: `angebote.php` (market overview, reads `preise`), `bier.php, malz.php, hopfen.php, gerste.php, quellwasser.php` (player offer lists `ORDER BY preis, qualitaet DESC`), `kauf.php` (buy drinks from NPC market), `kaufen.php` (buy from player offer), `suche.php`, `eigeneAngebote.php` (stub).
- `finanzen/`: `uebersicht.php`, `bank.php` + `bankgeschaefte.php`, `einnahmen.php`/`ausgaben.php` (placeholders).
- `nachrichten/`: `eingang.php, ausgang.php, nachrichtSenden.php, nachrichtLoeschen.php` (table `pns`).
- `profil/profil.php`, `profil/logout.php` (`UPDATE user SET status=0 WHERE email`).
- `statistiken/onlineuser.php` (users with status=1 + count), `user.php` (user count).
- `stadt/uebersicht.php`, `arbeitsmarkt/*` (6 files): placeholders.
- AJAX endpoints return plain-text codes (`ok`, `fehlerXyz`, ...). DB wrapper `anfrage()` in `funktionen.php`: SELECT returns FIRST row only (`MYSQL_NUM`); other statements return true if `affected_rows`>0 else `die`.
- `abrechnung.php`: settlement (see 2.7). Not referenced from anywhere; must be called manually/cron. Only includes `sessionpruefung.php`. It is a DRY RUN (UPDATEs commented out or echoed).

### 2. Game rules

#### 2.1 Buildings (client JS in `neuesGebaeude.php`; server trusts client cost/time)
Categories: 1 Verkauf `v`, 2 Produktion `p`, 3 Forschung `f`, 4 Landwirtschaft `l`, 5 Transport `t`, 6 Sonstige `s`. Only Verkauf is enabled in UI (Kneipe, Gaststaette).

| Code | Building | Cost EUR | Build minutes |
|---|---|---|---|
| 11 | Kneipe | 100,000 | 300 |
| 12 | Gaststaette | 300,000 | 600 |
| 13 | Handelszentrum | 1,000,000 | 1200 |
| 21 | Brauerei | 1,000,000 | 1440 |
| 31-36 | Forschung (Inhalt & Geschmack, Verpackung, Technik, Arbeitsablaeufe, Merchandise, Marketing) | 50,000 each | 300 |
| 41/42/43 | Farm / Plantage / Quelle | 100,000 | 600 |
| 51 | LKW Verladestation | 150,000 | 600 |
| 61 | Firmenhauptsitz | 2,500,000 | 1440 |
| 62 | Lagerhaus | 500,000 | 600 |

Construction provider 1-5: cost x [1, 1.2, 1.5, 2, 2.5], time x [1, 0.9, 0.8, 0.6, 0.4].
Locations (standort): 1 Ansbach, 2 Landshut, 3 Muenchen, 4 Bayreuth, 5 Regensburg, 6 Wuerzburg, 7 Augsburg. District (viertel): 1 innenstadt, 2 wohngebiet, 3 gewerbegebiet. Size fixed 250.
`bau.php` POST `gebaeude, kategorie, kosten, dauer, standort, viertel, groesse`: allowed if `COUNT(gebaeude of firma) <= 20` (so max 21; code `maxGebaeude`); `fertigstellung = now + dauer*60 s`; needs `konto >= kosten` (`kontostandZuKlein`). Inserts `gebaeude(firma_id,kategorie,art,fertigstellung,baukosten,groesse,standort,viertel)` (`art` = German name e.g. "Kneipe"), then default rows in `getraenkeangebote`, `gebaeudeerweiterungen`, `getraenkelager` (firma_id, gebaeude_id), then `finanzen`: `konto -= kosten, a_gebaeudebau += kosten, a_gesamt += kosten`. Codes: `ok, maxGebaeude, kontostandZuKlein, fehlerGebaeudeeintrag, fehlerGetraenkeeintrag, fehlerGebaeudeerweiterungeneintrag, fehlerGetraenkelagereintrag, fehlerKontostandeintrag`.
Demolish: ownership check, delete `gebaeude` row, no refund, orphans remain. Sell: `konto += baukosten/2`, delete row; `e_gebaeudeverkauf` not updated; orphans remain.

#### 2.2 Rebuild (`speichern.php`)
POST `id, stilIndex, pzgIndex, szgIndex, spIndex, tmIndex, twIndex`. Allowed only if stil, tm, tw, sp all != "0" (else `keinenIndex`).
- Style: 1 Modern, 2 Rustikal, 3 Edel, 4/5/6 "keinen".
- Target groups pzg/szg: 0 keine, 1 Familie, 2 Teenager, 3 Junge Erwachsene, 4 Erwachsene, 5 Rentner.
- Seats = 20 + 4*index (index 1..25 -> 24..120). Toilets M and W = index 1..6.
- Cost: style change +10,000; seats increase delta*50, decrease delta*20 (refund); toilets increase delta*500, decrease delta*200 (decrease also COSTS, quirk).
- Cost 0 -> `keinWertGeaendert`. `konto < cost` -> `finanzenNichtAusreichend`. Cost>0: `konto -= cost, a_gebaeudeumbau += cost, a_gesamt += cost`. Cost<0: `konto -= cost` (credit), `e_gebaeudeumbau -= cost, e_gesamt -= cost`. Then `UPDATE gebaeude SET stil,pzg,szg,sp,tm,tw` (stores NAMES such as "Teenager", "Modern", "keine"). Other codes: `finanzUpdateKaufFehlgeschlagen, fehlerDateneintrag, nichtBesitzer`.

#### 2.3 Extensions (`gebaeudeerweiterungen`)
UI lists 12 (tooltip productivity/attractivity): Zapfanlage +++/o, Stammtisch ++/+++, Festzelt ++/+, Bar ++/+++, Kaminofen +/++, Happy Hour +/+, Biergarten +++/+++, Kueche +++/+++, Speisekarte +/+, Tageskarte +/++, Servierwagen ++/o, Menage +/+. No server code buys them; only default rows are created. Columns inferred from `abrechnung.php`: [2] Zapfanlage (count, +0.05 each), [3] Stammtisch (+0.1), [4] Festzelt (unused), [5] Bar (+0.15), [6] Kaminofen (+0.1, +0.02 more if Kueche), [7],[8] Happy Hour times (logic commented out), [9] Biergarten (+weather*0.4, weather hardcoded 0), [10] Kueche (+0.2; [11] Speisekarte +0.1, [12] Tageskarte +0.05, [13] Servierwagen +0.05), [14] Menage (unused). Sales factor = `1 + sum(extension factors)`, multiplied onto each good's volume.

#### 2.4 Goods (41 drinks) and drinks menu
Categories/goods in `getraenkelager` and `getraenkeangebote` (and `preise` order):
- Beer: pils, helles, lager, weissbier, schwarzbier, altbier
- Spirits: korn, whiskey, wodka, obstbrand, rum, likeur
- Beer mixes: almradler, radler, diesel, goass, colaweizen, russ
- Cocktails: sotb (sexonthebeach), liit (longislandicedtea), zombie, hurricane, caipirinha, mojito
- Non-alcoholic: mineralwasser, cola, zlimo (zitronenlimo), olimo (orangenlimo), apfelschorle, colamix
- Wine: weisswein, rotwein, schaumwein, likeurwein, rosewein
- Juice: asaft (apfelsaft), osaft (orangensaft), ksaft (kirschsaft), bsaft (bananensaft), jsaft (johannisbeersaft), psaft (pfirsichsaft)

`getraenkeangebote` layout (from `abrechnung.php`): [0] firma_id, [1] gebaeude_id, then interleaved pairs flag (even idx) / price (odd idx; columns `preisPils`, `preisHelles`, ...): beer 2-13, spirits 14-25, beer mixes 26-37, cocktails 38-49, non-alc 50-61, wine 62-71, juice 72-83.
`getraenkelager` layout (stock, no prices): [0] firma_id, [1] gebaeude_id, beer 2-7, spirits 8-13, beer mixes 14-19, cocktails 20-25, non-alc 26-31, wine 32-36, juice 37-42.
`neueKarte.php`: POST `id`, `alleGetraenke` (41 flags joined by `|`), `allePreise` (41 prices by `|`); ownership check; update flags+prices; no price validation. Form order: pils, helles, lager, weissbier, schwarzbier, altbier, korn, whiskey, wodka, obstbrand, rum, likeur, almradler, radler, diesel, goass, colaweizen, russ, sotb, liit, zombie, hurricane, caipirinha, mojito, mineralwasser, cola, zlimo, olimo, apfelschorle, colamix, weisswein, rotwein, schaumwein, likeurwein, rosewein, asaft, osaft, ksaft, bsaft, jsaft, psaft.

#### 2.5 NPC market purchase (`markt/kauf.php`, called from `verkauf.php`)
UI genres: 1 Bier, 2 Spirituosen, 3 Biermisch, 4 Alkoholfrei, 5 Saefte, 6 Cocktails, 7 Weine. `gesamtpreis = einzelpreis*menge` (menge in liters). Code -> good: 11-16 beer (pils, helles, lager, weissbier, schwarzbier, altbier [typo `altber` makes query fail]), 21-26 spirits, 31-36 beer mixes, 41-46 non-alc, 51-56 juice, 61-66 cocktails, 71-75 wine. Flow: ownership via COUNT, `cost = preise.<good> * menge`, `konto >= cost`, `UPDATE finanzen konto, a_getraenke(sic; uebersicht uses a_getraenkekauf), a_gesamt`, then add stock. No check that menge is positive/integer (negative amount = exploit). `preise` has ONE row, 41 price columns in the order of 2.4 (`$preis[0]` pils ... `[18]` mineralwasser ... `[40]` rosewein).
Market price drift (in `abrechnung.php` tail, new price = `round(old*(1+s/100),2)` clamped to [min,max]); s drawn per good from array `[0,0,1,-1,1,-1,1,-1,1,-1,1,-1,2,-2,2,-2,2,-2,2,-2,3,-3,3,-3,3,-3,4,-4,4,-4,5,-5]` via `rand(0,31)`. Ranges: beer 40-50, spirits 12-15, beer mix 8-12, non-alc 5-7, juice 6-8, wine 3-5, cocktails 40-50 (units differ from the reference prices used for demand; scale inconsistent, probably per larger unit). The UPDATE of `preise` is only echoed/commented (dry run).

#### 2.6 Raw materials and player market
`lager(firma_id, bier, quellwasser, hopfen, gerste, malz)`. Market tables `bier, malz, hopfen, gerste, quellwasser` (columns id, anbieter_id, menge, qualitaet, preis). `lagerupdate.php` (`art=lagerbestandUpdate`, `verkaufMarkt`): `$ware` from POST used as table/column name; sells raw material as offers with optimistic stock check (`bestandNichtAktuell`). `kaufen.php`: buyer != seller (`anbieterIstEmpfaenger`), `mengeNichtVerfuegbar`, `finanzenNichtAusreichend`; deletes/reduces offer, credits seller, debits buyer, adds to `lager.$ware`; no transaction. `suche.php`: `SELECT * FROM alkGetr WHERE <built from POST a1/a2>` (columns art, anbieter, menge, preis), no session check. Buildings for production/farm are placeholders, so raw materials have no producer in code.

#### 2.7 Settlement (`abrechnung.php`, 2037 lines)
Trigger: none (manual/cron, public URL). Dry run: UPDATEs commented.
1. Interest, for every `finanzen` row except `firma_id=1`: `zinsertraege = round(konto*0.01,2)`, `zinsaufwendungen = round(kredit*0.10,2)`; `konto = round(konto + ertraege - aufwendungen, 2)`; `e_zinsen/e_gesamt += ertraege`, `a_zinsen/a_gesamt += aufwendungen`.
2. For each finished building (`fertigstellung <= now`, kategorie v):
 - `besucher = gebaeude.beliebtheit`.
 - District shares from `stadtviertel` row of `viertel`: [1] Teenager, [2] Junge Erwachsene, [3] Familien, [4] Rentner, [5] Erwachsene.
 - pzg factor x1.3, szg x1.2 (if only szg set, it becomes pzg).
 - Style factors: "keinen" all x0.5; Modern x1.1 for Teen/Junge; Rustikal x1.066 for Erwachsene/Rentner/Familie; Edel x1.05 all.
 - Visitors per group = `ceil(share*factor*besucher)`. Toilet influence: empty stub.
 - `nachfrage` table: 5 rows (teen, junge, familie, rentner, erwachsene) x columns [1] biere, [2] biermisch, [3] alkfrei, [4] saefte, [5] spirituosen, [6] cocktails, [7] weine. Category demand = sum(factor * group visitors).
 - Offer count per category = sum of flag columns.
 - `kauffaktor = preisFaktor(price / referencePrice)`; reference prices: beer 2.3, beer mix 2.1, non-alc 1.8, juice 1.5, spirits 1.1, cocktails 5.2, wine 1.6.
 - preisFaktor table (ratio r): r==1 ->1; (1,1.1] .95; (1.1,1.15] .90; (1.15,1.2] .80; (1.2,1.25] .70; (1.25,1.3] .60; (1.3,1.35] .50; (1.35,1.5] .35; (1.5,1.75] .25; (1.75,2] .15; >2 ->0; [.95,1) 1.1; [.90,.95) 1.2; [.80,.90) 1.3; [.70,.80) 1.35; [.60,.70) 1.5; [.50,.60) 1.7; [.35,.50) 2; [.25,.35) 2.6; [.15,.25) 3.5; [.10,.15) 4.5; [.05,.10) 7; <.05 -> 10.
 - `berechneAbsatz`: if flag==1: `volume = ceil(((totalDemand*15)*verteilung*kauffaktor)/unit)*unit`. Units (liters): beer, beer mix, non-alc, juice 0.5; spirits 0.02; cocktails 0.4; wine 0.1.
 - `verteilung(6, n)`: n offered goods; random split of 100 %: loop from last slot, `w=(100-abzug)/anzahl; z=rand(w-8,w+8); arr[anzahl-1]=z/100; abzug+=z`, final slot = rest; then shuffle 5-10 times. n==0 -> zeros. The index advances per offered good.
 - Known bugs: goass/colaweizen/russ use the almradler result; `$$colaArray[1]` for zlimo; juice uses non-alc base, wine uses cocktail base.
 - Extension factor applied; stock check per good: if `stock < demand`, demand capped at stock and the excess x0.6 redistributed to the next goods of the category with `excess/(offers-used)` (only if `offers-used>1`; first pils step divides by `offers-1`).
 - Revenue per good = `round(liters*price,2)`. `konto += total`, `e_umsatz`, `e_gesamt += total`. New stock = stock - sold (sold rounded up to unit; wine uses 0.7, inconsistent with 0.1).
3. New market prices as in 2.5. Output: HTML table with visitors/extensions/goods per building.

#### 2.8 Bank
`finanzen.kreditrahmen` default 200,000. Borrow: amount <= kreditrahmen - kredit; repay: amount <= kredit and <= konto. Checked ONLY client-side; `bankgeschaefte.php` runs `UPDATE finanzen SET konto='$kontostandNeu', kredit='$kreditNeu' WHERE firma_id=` with client values (`ok`/`fehlerEintrag`). Interest: 1 % on balance, 10 % on loan per settlement run (2.7).
`finanzen/uebersicht.php` index map of `SELECT * FROM finanzen`: [2] konto, [3] unknown (start-of-year balance?), [4] kredit, [5] kreditrahmen (inferred), [6] e_gebaeudeverkauf, [7] e_gebaeudeumbau, [8] e_umsatz, [9] e_zinsen, [10] e_gesamt, [11] a_gebaeudebau, [12] a_gebaeudeumbau, [13] a_getraenkekauf, [14] a_gehaelter, [15] a_zinsen, [16] a_gesamt.

#### 2.9 Employees (`funktionen.php`, no UI; arbeitsmarkt pages empty)
- Generation needs missing txt files (`vornamen.txt` etc.): `erstelleName, erstelleAuszubildenen, erstelleUngelernteArbeitskraft, erstelleAngelernteArbeitskraft, fuegeANHinzu`.
- Satisfaction factor: 91-100 x1.1, 81-90 x1.05, 71-80 x1, 51-70 x0.95, 31-50 x0.75, 1-30 x0.5, 0 -> 0. Caps by art: 30, 50, 75, 100.
- Base salary: art0 800; art1 1300+(gw-34)*20; art2 1700+(gw-44)*45; art3 3200+(gw-69)*100.
- New satisfaction: `faktor=(int)(gehaltNeu/grundgehalt*100-100)`; faktor>=0: change=(int)(faktor/2); faktor<-50: change=71; else (int)(faktor/0.71). Satisfaction = 71 + change, clamp 0..100.
- Others: `berechneArbeitswert, grundgehaltBerechen(grundgehaltBerechnen), neueZufr, getNeueZufr, decimalUmwandeln`. Table `arbeitnehmer` (also `bierspiel.arbeitnehmer`, schema hardcoded `bierspiel` at funktionen.php:207).

#### 2.10 Messages (`pns`)
Columns: id, betreff, von_id, von_user, zu_id, zu_user, text, versendet (datetime), sender_geloescht, empfaenger_geloescht. Limits: inbox <= 20 per recipient, outbox <= 20 undeleted. Recipient lookup `SELECT COUNT(id), id FROM user WHERE nickname`. Delete marks per side; row removed when both deleted (`DELETE FROM pns WHERE id`, no ownership check).

#### 2.11 Map
No game map. `index.php` shows jVectorMap (world/germany/bayern) as decoration; `firma/neueKarte.php` is the drinks menu, not a map. "Stadt" page is a placeholder.

### 3. Derived DB schema (types inferred)

Single MySQL schema (`bierspiel`), MyISAM/InnoDB unknown.
- `user`: id INT PK AI, schluessel VARCHAR(32) (md5), schluessel_pw VARCHAR(32)/0, nickname VARCHAR, email VARCHAR, passwort CHAR(32) md5, aktiviert TINYINT 0/1, anzahlLogins INT, anmeldedatum DATE/DATETIME, letzterLogin DATETIME, status TINYINT 0/1 (online).
- `firmendaten`: id PK, user_id FK user.id, hauptsitz (standort 1-7), konto DECIMAL, kredit DECIMAL, gehaltskonto DECIMAL, woche INT, mitarbeiter INT, bekanntheit INT, beliebtheit INT, firmenname VARCHAR (unique by code).
- `finanzen`: id?, firma_id FK, konto DECIMAL(.,2), [3] unknown, kredit, kreditrahmen, e_gebaeudeverkauf, e_gebaeudeumbau, e_umsatz, e_zinsen, e_gesamt, a_gebaeudebau, a_gebaeudeumbau, a_getraenkekauf (kauf.php says `a_getraenke`: mismatch), a_gehaelter, a_zinsen, a_gesamt. firma_id=1 is a special row (excluded from interest).
- `loeschen`: user_id, firma_id, timestamp.
- `gebaeude`: id PK, firma_id FK, kategorie CHAR(1), art VARCHAR (name), name VARCHAR, fertigstellung DATETIME, baukosten DECIMAL, groesse INT, standort INT, viertel INT, beliebtheit INT (visitor base), stil VARCHAR, pzg VARCHAR, szg VARCHAR, sp INT (seats), tm INT, tw INT (toilets). Exact order unknown (about 17 columns by SELECT *).
- `getraenkeangebote`: firma_id, gebaeude_id, 41 flag columns (TINYINT, named like goods) + 41 price columns (DECIMAL, `preisPils`...), interleaved (see 2.4).
- `getraenkelager`: firma_id, gebaeude_id, 41 stock columns (DECIMAL liters, named like goods; real names e.g. `sexonthebeach, longislandicedtea, zitronenlimo, orangenlimo, apfelsaft, orangensaft, kirschsaft, bananensaft, johannisbeersaft, pfirsichsaft`).
- `gebaeudeerweiterungen`: firma_id, gebaeude_id, 13 extension columns (2.3).
- `preise`: single row, 41 DECIMAL price columns.
- `stadtviertel`: viertel id + 5 share columns (teen, junge, familie, rentner, erwachsene).
- `nachfrage`: 5 rows x 7 factor columns (2.7).
- `lager`: firma_id, bier, quellwasser, hopfen, gerste, malz. `quellwasser` also referenced as separate table in `loescheAccounts.php`.
- Market tables `bier, malz, hopfen, gerste, quellwasser`: id PK, anbieter_id, menge, qualitaet, preis. `alkGetr`: art, anbieter, menge, preis.
- `pns`: see 2.10. `arbeitnehmer`: columns unknown beyond funktionen.php use (art, gw/arbeitswert, zufriedenheit, gehalt).
Relations: user 1-1 firmendaten 1-1 finanzen; firma 1-n gebaeude; gebaeude 1-1 getraenkeangebote/-lager/gebaeudeerweiterungen (no FK constraints visible).
Seed data expected but ABSENT in code: `preise` row (within ranges of 2.5), `stadtviertel` rows for viertel 1-3, `nachfrage` 5 rows, `finanzen`/`firmendaten` row with firma_id/id 1 (NPC/system company).

### 4. Security issues

- SQL injection: raw `$_POST`/`$_SESSION` in nearly all queries: `validate.php`, `datenNeu.php`, `finanzen/bankgeschaefte.php`, `firma/bau.php`, `firma/speichern.php`, `markt/kauf.php`, `nachrichten/*`. Table/column names from POST: `firma/lagerupdate.php`, `markt/kaufen.php` (`$ware`). `markt/suche.php` builds WHERE from POST. `funktionen.php anfrage()` calls `mysql_escape_string` on a copy that is never used.
- MD5 without salt for passwords (`validate.php`, `datenNeu.php`, `bestaetigung.php`); activation/reset keys = md5 of 8 pseudo-random chars (`validate.php`).
- XSS: unescaped output of nickname, company name, messages (`nachrichten/eingang.php`, `statistiken/onlineuser.php`, `profil/profil.php`, `markt/*.php`).
- No CSRF protection anywhere (all POST endpoints rely on session cookie only).
- Client-trusted values: building cost/time (`bau.php`), balance and loan (`bankgeschaefte.php`), drink prices (`neueKarte.php`), negative amounts (`kauf.php`), rebuild cost client-side check (`verkauf.php`).
- Public maintenance scripts: `abrechnung.php` (no auth), `loescheAccounts.php` (no auth), `markt/suche.php` (no session check).
- Authorization gaps: `nachrichtLoeschen.php` no ownership check; password confirmation not checked server-side (`validate.php` regis).
- Race conditions: no transactions (`kaufen.php`, `kauf.php`, `bau.php`).
- Hardcoded credentials: only placeholders now (`connect.php`, `configuration.php` lines 2-5 commented / 7-10 active, `abrechnung.php` lines 4 and 9).
- `mysql_*` API removed in PHP 7; PHP 5 EOL. `mail()` with user-controlled values.

### 5. Dead code, junk, duplicates, libs, assets

- Duplicates/backups: `firma/Copy (2) of verkauf.php`, `firma/Copy of verkauf.php`, `firma/Copy of lager.php`, `firma/firmaVerkaufAlt.php`, `firma/neuesGebaeudeAlt.php`, `firma/firmaNeuesGebaeudeNeu.php`, `firma/firmaArbeitnehmer.php`, `markt/angeboteAlt.php`.
- Placeholder pages: `firma/forschung.php, landwirtschaft.php, produktion.php, sonstige.php, transport.php`, `stadt/uebersicht.php`, `arbeitsmarkt/*` (6), `finanzen/einnahmen.php, ausgaben.php`, `markt/eigeneAngebote.php`.
- Other: `firma/gaststaetten/` (dir), `namenspruefung.php`, `error404.php`, `intern/`; commented-out code throughout `abrechnung.php`; unused variable `$festgeld` in `validate.php`; unused extension columns (Festzelt, Menage, Happy Hour).
- Missing files: `vornamen.txt` etc. (name generator), `js/jvectormap-1.2.2.js`.
- Libraries/assets (listed only): Bootstrap 2, jquery-1.9.0, jVectorMap 1.2.2 (world, germany, bayern maps), `metro-dialog.js`, `bootstrap-tooltip.js`, `support.js`, `css/`, `img/`.
- Directories: `arbeitsmarkt, css, finanzen, firma, footer, img, intern, js, markt, nachrichten, profil, stadt, startseite, statistiken`.

### 6. Secrets / personal data

Scrubbed. DB credentials are placeholders (`DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`). Sender/webmaster/contact addresses are `MAIL_SENDER@example.com`, `MAIL_WEBMASTER@example.com`, `MAIL_CONTACT@example.com`; the domain `schankimperium.de` and `i-use-my-brain.com` became `example.com`; the author name became `AUTHOR_NAME`. The game name "Schankimperium" is kept as a title.

### Gaps / uncertainty
Seed values (`preise`, `stadtviertel`, `nachfrage`), exact column types/order, how `gebaeude.beliebtheit` is raised (no code updates it), and `finanzen` column [3] are not derivable from code. Employee system has no UI. Price ranges in 2.5 are not tied to the reference prices in 2.7.

# Part B: v5 labour market

Source: `apps/games/bierspiel/legacy/v5` (PHP 5, procedural, `mysql_*`, Metro UI CSS shell, jQuery 1.8.2). Files are ISO-8859-1 (use `LC_ALL=C grep -a`). DB name `bierspiel`. Read-only analysis; nothing under legacy/ was changed.

### 1. Request flow and file map

#### Conventions
- Every query goes through `anfrage($query)` (`funktionen/funktionen.php`): SELECT returns the first row as a numeric array (MYSQL_NUM), anything else returns true/false (affected rows > 0). `doQuery` dies with `mysql_error()`. No prepared statements anywhere.
- Auth: `$_SESSION['nickname']`. Each page looks up user and company by nickname.
- Shell: `huelle/header.php` includes `sessionpruefung.php`, `connect.php`, `funktionen/funktionen.php`, renders the Metro nav and a money bar (bargeld, festgeld, kredit via `decimalUmwandeln`, 3 queries per request); `huelle/footer.php` closes it.
- Ajax endpoints (jQuery `$.post`, `confirm()`, `unescape()`) echo plain status strings.
- Codes: arbeitnehmer.art 0 Azubi, 1 Hilfskraft (ungelernt), 2 Fachkraft (angelernt), 3 Fuehrungskraft (no generator). arbeitsgebiet 1 Verwaltung, 2 Produktion, 3 Gastsaette (generators emit 2..4; 4 renders blank). `arbeitgeber = -1` = on market, else `firmendaten.id`.

#### Landing and auth
- `index.php` (landing, Metro copy), `index2.php` (variant). `js/index.js` posts ajax to `startseite/validate.php` (`art=login|reg`). `startseite/index.php` forms target non-existent `kontrolle_login.php` / `kontrolle_reg.php`.
- `startseite/bestaetigung.php?key=` activates the account. `startseite/header.php|footer.php|screenshots.php|bierspiel_aufbau.php|support_ticket*.php`.

#### Internal shell
- `intern/startseite.php`: includes `../produktion.php` if the company exists, else `neugruendung.php` (city + company name form). `js/stadtsuche.js` uses `intern/stadtsuche.php?q=` (standorte list, `*` = all) and `intern/firmensuche.php?q=` (name uniqueness), then posts to `intern/firma_gruenden.php`. `intern/logout.php` sets user.status=0, destroys session.
- `huelle/header.php` menu: Home, Die Firma, Stadt, Finanzen, Markt, Arbeitsmarkt, Vertraege, Nachrichten, Statistiken, Profil, Kontakt, Logout. Many are placeholders (`Punkt 3..8`, `href="#"`). Dead links: `../bank/bank_infos_hilfe.php`, `../markt/neuesAngebot.php`, `new_land.php`, `ausbau.php`, `email_aendern.php`, `pw_aendern.php`, `persDaten_aendern.php`; `site.css` is linked but missing.
- Stubs ("IM AUFBAU", bauarbeiter.png): `*_uebersicht.php` and `*_infos_hilfe.php` for firma, stadt, markt, vertraege, statistiken, profil, nachricht, arbeitsmarkt (help pages = stubs). Contracts and friends (`nachricht/freundesliste.php`) = stubs, no tables.
- `finanzen/finanzen_uebersicht.php`: shows bargeld/konto/kredit plus a hard-coded name tile (l.30-33).

#### Labour market
- `arbeitsmarkt/arbeitsmarkt.php`: art IN (2,3), arbeitgeber=-1, ORDER BY grundwert DESC; JS `arbeitsmarkt.js` -> `verhandeln.php`.
- `arbeitsmarkt/hilfsarbeitermarkt.php` (art=1) and `ausbildungsmarkt.php` (art=0): both post to `einstellen.php`.
- `verhandeln.php` (bid), `einstellen.php` (direct hire), `verarbeitung.php` (the tick).
- `stellenangebote.php` reads non-existent/legacy table `arbeitsmarktausbildung`. `arbeitnehmersuche.php`: stub with commented `fuegeANHinzu(0|1|2,30)` seed calls (this is how the market was seeded).

#### Company
- `firma/arbeitnehmer.php` + `arbeitnehmer.js` (list: Name, Art, Arbeitsgebiet, Alter, Talent, Grundwert, Zufriedenheit, Arbeitswert, Gehalt; ordered by arbeitswert DESC via N+1 `LIMIT $i,1`; "Fortbildung" link has no handler; label typo `F%uuml;hrungskraft`).
- `firma/gehaltserhoehung.php` (raise/cut), `firma/entlassen.php` (fire), `firma/grundstuecke.php` (plots; images brachland/kneipe/gaststaette.jpg).
- `markt/angebote.php`: reads `marktplatz`; `oeffneFenster(neuesAngebot.php)` is broken (no offer creation exists).
- `nachricht/posteingang.php|postausgang.php|nachricht_senden.php|neue_nachricht.php|suche.php` (table `pns`).
- `kontakt/support_ticket.php` + `support_ticket_eintragen.php` (needs session); duplicate in `startseite/` (no session).
- `statistiken/useruebersicht.php?a&b&suche&richtung`: ranking (user JOIN firmendaten).
- `produktion.php`: bcmath gold demo (START 1353407799, PPS 5, MAX 5000), uses undefined `$GOLD_UPDATE_TIME`/`$GOLD_VALUE`.
- Registration `startseite/validate.php`: md5 password, unique nickname/email (email lowercased), `schluessel = md5(substr(md5(microtime()), rand(0,24), 8))`, INSERT user, INSERT firmendaten (hauptsitz '', bargeld 100000, konto 0, kredit 0, mitarbeiter 0, bekanntheit 0, beliebtheit 0), HTML mail with activation link. Responses: ok/no/nichtAktiviert/pwUngleich/eintragOk/eintragNichtOk. Login requires aktiviert=1, sets session nickname and user.status=1.
- `intern/firma_gruenden.php`: bug, `$anzahl_firmen + 1` uses a mysql resource, so `standorte.firmenanzahl` is wrong.

### 2. Labour market mechanics

#### 2.1 Worker generation (`funktionen.php`)
- `erstelleName()`: `vornamen.txt` line `rand(0,353)`, `nachnamen.txt` line `rand(0,4804)`. Files have 353 / 4804 lines and CRLF (not trimmed), so index can be out of range/null.
- Azubi (art 0): alter `rand(16,22)`; arbeitswert = random line of `arbeitswertAzubi.txt` (`rand(0,29)`, 29 lines); talent = line of `talent.txt` (`rand(0,19)`, 19 lines); gehalt 800.00; arbeitsgebiet 1.
- Hilfskraft (art 1): alter `rand(23,35)`; arbeitswert line of `arbeitswertUngelernteAN.txt` (`rand(0,79)`); gehalt = 1300 + (aw-34)*20; arbeitsgebiet `rand(2,4)`.
- Fachkraft (art 2): alter line of `alterAngelernte.txt` (`rand(0,152)`, 152 lines); arbeitswert line of `arbeitswertAngelernteAN.txt` (`rand(0,179)`, 184 lines); gehalt = 1700 + (aw-44)*45; arbeitsgebiet `rand(2,4)`.
- `fuegeANHinzu($art,$menge)` INSERTs `(NULL, art, arbeitsgebiet, vorname, nachname, alter, arbeitswert, grundwert(=arbeitswert), talent, gehalt, arbeitgeber=-1)`. Art 3 unhandled (`$name` undefined).
- Weighted distributions (value:count in file, duplicates = weights):
  - Azubi: 1:5 2:5 3:4 4:4 5:3 6:3 7:2 8:2 9:1 10:1
  - Hilfskraft: 35-38:8 each, 39-42:6, 43-46:4, 47-50:2
  - Fachkraft aw: 45-50:5, 51-55:7, 56-60:9, 61-65:7, 66-70:5, 71-74:3, 75:3
  - Fachkraft alter: 23-26:10, 27-30:7, 31-35:5, 36-40:7, 41-45:3, 46-55:1
  - talent: 1 x7, 2 x5, 3 x4, 4 x2, 5 x1

#### 2.2 Base salary (`grundgehaltBerechnen($id,$grundwert)`)
`base + (grundwert - start) * bonus`: art0 800/0; art1 1300, +20/pt, start 34; art2 1700, +45/pt, start 44; art3 3200, +100/pt, start 69.

#### 2.3 Satisfaction
- `neueZufr($id,$gehaltNeu,$grundwert)`: `faktor = (int)((gehaltNeu/grundgehalt)*100-100)`. faktor >= 0: change = `(int)(faktor/2)`. faktor < 0: if faktor < -50 change = 71 (bug: positive), else `(int)(faktor/0.71)`. Has stray `echo "Grundgehalt : "` that corrupts ajax output.
- `getNeueZufr($id,$change,$akt)`: `71 + change` clamped 0..100 (`$akt` ignored; the old cumulative logic is commented out). So satisfaction is absolute: 71 at base pay, +1 per 2% raise.

#### 2.4 Work value (`berechneArbeitswert($id,$zufr)`)
`(int)(grundwert * m)`, m by satisfaction: 91-100 x1.1; 81-90 x1.05; 71-80 x1.0; 51-70 x0.95; 31-50 x0.75; 1-30 x0.5; 0 -> 0. Cap by art: 0->30, 1->50, 2->75, 3->100.

#### 2.5 Bidding (`verhandeln.php`, POST id, gehalt)
- Not numeric: `nichtGueltig`. gehalt <= worker.gehalt: `gehaltZuKlein`. gehalt <= own MAX(gebot): `gebotGroesser`. Else INSERT `gebote(arbeitnehmer_id, arbeitgeber_id, gebot)`, echo `ok`. UI pre-fills own max bid+1 or gehalt+1, input maxlength 5. No money check, no session check.

#### 2.6 Direct hire (`einstellen.php`, POST id; Azubi and Hilfskraft)
If arbeitgeber == -1: set arbeitgeber = firm id, spawn `fuegeANHinzu(1,1)` (always a Hilfskraft replacement, even for Azubi: bug), echo `ok`; else `schonEingestellt`. No money/ownership check, gehalt stays as listed, zufriedenheit not initialised, not atomic.

#### 2.7 Tick (`arbeitsmarkt/verarbeitung.php`)
Manual GET page (menu link). No cron, no idempotency, no lock; any logged-in user can call it repeatedly. Three phases per call:
1. Bids: N = COUNT(DISTINCT arbeitnehmer_id) in gebote. Loop N times: take arbeitnehmer_id of MIN(gebote.id); maxGebot = MAX(gebot); winner = arbeitgeber_id with that gebot (ties: first row). `zufr = getNeueZufr(.., neueZufr(id,maxGebot,grundwert), 71)`; `arbeitswert = berechneArbeitswert`. UPDATE arbeitnehmer SET gehalt=maxGebot, zufriedenheit, arbeitswert, arbeitgeber=winner. DELETE all bids of that worker. `fuegeANHinzu(2,1)` (replacement Fachkraft).
2. Employment duration: for every employed worker (arbeitgeber != -1) `dauer += 1`.
3. Payroll: for every employed worker (`LIMIT $i,1`): weekly = gehalt/4; `gehaltskonto += weekly`; `woche += 1` (per worker, so week counter advances N times per tick: bug). If `woche == 4` (strict): `bargeld -= gehaltskonto` (using bargeld read at iteration start), gehaltskonto=0, woche=0. Negative cash allowed.

#### 2.8 Firing (`entlassen.php`, POST id, arbeitswert(=grundwert), abfindung)
Client sends `abfindung = gehalt*10`; server trusts it. If abfindung < bargeld: bargeld -= abfindung, arbeitgeber=-1, gehalt = `grundgehaltBerechnen(id, grundwert)`; echo `ok`/`nichtOk`; else `noBargeld`. zufriedenheit/arbeitswert not reset, no ownership check.

#### 2.9 Raise/cut (`gehaltserhoehung.php`, POST id, grundwert, gehalt)
Non-numeric: `keineZahl`; <=0: `gehaltZuKlein`. Else neueZufr -> getNeueZufr -> berechneArbeitswert, UPDATE gehalt/zufriedenheit/arbeitswert, `ok`. The leaked echo breaks JS `data=='ok'`; `arbeitnehmer.js` has debug `alert("inAjax"); alert(data);`. grundwert is client-supplied, no ownership check.

#### 2.10 Education market
`ausbildungsmarkt.php` lists art=0, hiring via `einstellen.php` (gehalt 800). No training/"Fortbildung" logic, no apprenticeship progression, `arbeitsmarktausbildung` is legacy.

#### 2.11 Finances
`firmendaten`: bargeld (start 100000), konto (0), festgeld, kredit; only bargeld is changed (payroll, severance). konto/festgeld/kredit are display-only (no bank logic; `bank_infos_hilfe` missing). `decimalUmwandeln("12345.00")` -> `12.345,00` (brittle for short values).

#### 2.12 Plots and marketplace
`grundstuecke(user_id, art 0 Brachland/1 Kneipe/2 Gasthaus, parkplatz, spielplatz)` is read-only display. `marktplatz(bezirk, anbieter_id, sorte, menge, einstelldatum)` read-only listing, no create/buy.

### 3. Derived DB schema (inferred)

- `user`: id PK, nickname (unique by check), email, passwort (md5), schluessel, aktiviert (0/1), status (0/1), vorname, nachname, geburtsdatum, wohnort, hobbys, beschreibung.
- `firmendaten`: id PK, user_id FK->user, firmenname, hauptsitz (-> standorte.name), bargeld, konto, festgeld, kredit (decimal(x,2)), mitarbeiter int, bekanntheit, beliebtheit, gehaltskonto decimal, woche int.
- `standorte`: name, firmenanzahl.
- `grundstuecke`: id, user_id, art (0/1/2), parkplatz, spielplatz (0/1).
- `arbeitnehmer`: id PK auto, art 0-3, arbeitsgebiet, vorname, nachname, alter, arbeitswert, grundwert, talent (1-5), gehalt decimal(.,2), arbeitgeber int (-1 or firmendaten.id), zufriedenheit 0-100, dauer int.
- `gebote`: id PK auto, arbeitnehmer_id FK, arbeitgeber_id FK->firmendaten, gebot.
- `arbeitsmarktausbildung` (legacy): id, Vorname, Nachname, Alter, Arbeitswert, Talent.
- `marktplatz`: bezirk, anbieter_id FK->firmendaten, sorte, menge, einstelldatum.
- `support_ticket`: betreff, inhalt.
- `pns`: betreff, von_id, von_user, zu_id, zu_user, text, versendet (timestamp).
- No tables for friends or contracts.

### 4. Security issues

- SQL injection everywhere (string interpolation of POST/GET/session): `startseite/validate.php` (login/reg), `startseite/bestaetigung.php` (`$_GET['key']`), `statistiken/useruebersicht.php:8` (`ORDER BY $suche $richtung LIMIT $a,$b` from GET), `arbeitsmarkt/verhandeln.php`, `einstellen.php`, `firma/entlassen.php`, `firma/gehaltserhoehung.php`, `intern/firma_gruenden.php`, `kontakt/support_ticket_eintragen.php`, `nachricht/nachricht_senden.php` (nickname in SELECT/INSERT). `doQuery` calls `mysql_escape_string` and discards the result.
- Broken access control: `verhandeln.php`, `einstellen.php`, `entlassen.php`, `gehaltserhoehung.php` start a session but never require a login; worker ids are not checked against the owner (anyone can fire/alter any firm's workers).
- Client-trusted values: `abfindung`, `grundwert`.
- `verarbeitung.php` is callable by anyone logged in, repeatable, no CSRF, no locking (race conditions; hiring check-then-update is not atomic).
- Unsalted md5 passwords; weak activation key (8 chars of md5(microtime)).
- No CSRF tokens anywhere; stored XSS via unescaped names, firmenname, tickets and messages; `intern/stadtsuche.php` echoes values into a JS href.
- Information leaks: `neueZufr` echo, `posteingang.php` echoes the query, `verbindung/MySQL.class.php` echoes queries, `die(mysql_error())`.
- Mixed content (http pluso.ru, Google Analytics). `mysql_*` removed in PHP 7.

### 5. Dead code, junk, duplicates

- Metro UI demo: `public/` (1.3M, ~30 demo PHP pages, js, prettify, css), `less/` (184K), `README` (Metro UI readme), `sonstiges/` (duplicate css), `css/`, `js/` vs `public/js/` duplicates, `images/screenshots`, `close.gif`.
- Duplicates: `index.php` vs `index2.php`; `startseite/support_ticket*` vs `kontakt/`; `huelle/configuration.php` vs `verbindung/configuration.php`; `startseite/header|footer` vs `huelle/`.
- Test/junk: `test.php`, `test.js`, `test.css`, `textboxlist.js`, `textboxlist.compressed.js`, `mootools-beta-1.2b1.js` (TextboxList demo), `produktion.php`, `huelle/firmenpruefung.php` (included nowhere), `arbeitsmarkt/stellenangebote.php`, `arbeitnehmersuche.php`.
- Eclipse: `.project`, `.buildpath`, `.settings/*`; `.gitignore` only has `.idea`.
- `verbindung/` OO DB layer is UNUSED: `verbindung/dbAbfragen.php` includes `MySQL.class.php`, but nothing includes `dbAbfragen.php` (only a commented reference in `startseite/header.php:1`, pointing at `../sonstiges/dbAbfragen.php`). `AbstractSQL.class.php` is fully commented out; `MySQL.class.php` holds global functions that echo queries, its `doSelect` returns nothing; `dataexception.class.php` declares class `dataexeception` (typo).
- Placeholder menu items, empty anchors, commented-out code throughout.

### 6. Secrets and personal data

Scrubbed. DB credentials are placeholders. Author names are `AUTHOR_NAME`, `AUTHOR_NAME_2`, the hard-coded finance tile uses `FIRST_NAME`/`LAST_NAME`, addresses are `MAIL_*@example.com`, the domain `www.dein-source.co.de` became `example.com`, the test nickname is `NICKNAME`, Google Analytics is `GA_TRACKING_ID` and the Pluso key `PLUSO_KEY`. Non-image files of `public/` (Metro UI demo) were deleted; only the demo images in `public/images` remain.

