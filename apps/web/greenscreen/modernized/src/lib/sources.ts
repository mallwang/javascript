import {
  parseDepartures,
  parseQuote,
  parseWarnings,
  weatherText,
  type Departure,
  type Quote,
  type Warning,
} from './parse';

async function getJson<T>(url: string, revalidate = 60): Promise<T> {
  const res = await fetch(url, { next: { revalidate }, signal: AbortSignal.timeout(8000) });
  if (!res.ok) throw new Error(`${url}: ${res.status}`);
  return res.json() as Promise<T>;
}

// Each source degrades to null/empty so one dead upstream never breaks the page.
const safe = async <T>(p: Promise<T>, fallback: T): Promise<T> => p.catch(() => fallback);

const STOPS = [
  { name: 'Ernst-Heinkel-Weg', id: 1351, line: '' },
  { name: 'Ziegelstein', id: 1320, line: '/U2' },
];

export const departures = (): Promise<{ stop: string; rows: Departure[] }[]> =>
  Promise.all(
    STOPS.map(async (s) => ({
      stop: s.name,
      rows: await safe(
        getJson<Parameters<typeof parseDepartures>[0]>(
          `https://start.vag.de/dm/api/v1/abfahrten/VGN/${s.id}${s.line}?limitcount=5`,
        ).then((j) => parseDepartures(j)),
        [],
      ),
    })),
  );

const CITIES = [
  { name: 'Marienberg', lat: 50.65, lon: 13.16 },
  { name: 'Budapest', lat: 47.5, lon: 19.04 },
  { name: 'Cluj-Napoca', lat: 46.77, lon: 23.59 },
];

export const weather = () =>
  Promise.all(
    CITIES.map(async (c) => {
      const j = await safe(
        getJson<{ current: { temperature_2m: number; weather_code: number } }>(
          `https://api.open-meteo.com/v1/forecast?latitude=${c.lat}&longitude=${c.lon}&current=temperature_2m,weather_code`,
        ),
        null,
      );
      return {
        name: c.name,
        temp: j?.current.temperature_2m ?? null,
        text: j ? weatherText(j.current.weather_code) : '',
      };
    }),
  );

const DAX: [string, string][] = [
  ['ADS.DE', 'ADIDAS'],
  ['ALV.DE', 'ALLIANZ'],
  ['BAS.DE', 'BASF'],
  ['BAYN.DE', 'BAYER'],
  ['BEI.DE', 'BEIERSDORF'],
  ['BMW.DE', 'BMW'],
  ['CBK.DE', 'COMMERZBANK'],
  ['CON.DE', 'CONTINENTAL'],
  ['DBK.DE', 'DEUTSCHE BANK'],
  ['DB1.DE', 'DEUTSCHE BÖRSE'],
  ['DTE.DE', 'TELEKOM'],
  ['EOAN.DE', 'E.ON'],
  ['FME.DE', 'FRESENIUS MED.'],
  ['FRE.DE', 'FRESENIUS'],
  ['HEI.DE', 'HEIDELBERG MATERIALS'],
  ['HEN3.DE', 'HENKEL'],
  ['IFX.DE', 'INFINEON'],
  ['LIN.DE', 'LINDE'],
  ['MRK.DE', 'MERCK'],
  ['MUV2.DE', 'MUNICH RE'],
  ['RWE.DE', 'RWE'],
  ['SAP.DE', 'SAP'],
  ['SIE.DE', 'SIEMENS'],
  ['VOW3.DE', 'VOLKSWAGEN'],
];

// ponytail: one unofficial Yahoo chart call per stock (cached 5 min); rate limits just drop quotes.
export const stocks = async (): Promise<Quote[]> => {
  const all = await Promise.all(
    DAX.map(([sym, name]) =>
      getJson<Parameters<typeof parseQuote>[1]>(
        `https://query1.finance.yahoo.com/v8/finance/chart/${sym}?range=1d`,
        300,
      )
        .then((j) => parseQuote(name, j))
        .catch(() => null),
    ),
  );
  return all.filter((q): q is Quote => q !== null);
};

export const warnings = async (): Promise<Warning[]> => {
  const all = await Promise.all(
    ['A3', 'A6', 'A9', 'A73'].map((r) =>
      safe(
        getJson<{ warning: Warning[] }>(
          `https://verkehr.autobahn.de/o/autobahn/${r}/services/warning`,
        ).then((j) => parseWarnings(j, 3)),
        [],
      ),
    ),
  );
  return all.flat();
};

export const news = async (): Promise<{ title: string; topline: string }[]> =>
  safe(
    getJson<{ news: { title: string; topline?: string }[] }>(
      'https://www.tagesschau.de/api2u/news?regions=9',
    ).then((j) => j.news.slice(0, 6).map((n) => ({ title: n.title, topline: n.topline ?? '' }))),
    [],
  );
