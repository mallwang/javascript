export type Departure = { line: string; to: string; time: string; delay: number };
export type Quote = { name: string; price: number; change: number };
export type Warning = { title: string; subtitle: string };

type VagDeparture = {
  Linienname: string;
  Richtungstext: string;
  AbfahrtszeitSoll: string;
  AbfahrtszeitIst: string;
};

// The ISO strings carry the local offset, so the clock time is a plain slice.
const hhmm = (iso: string) => iso.slice(11, 16);

export function parseDepartures(json: { Abfahrten: VagDeparture[] }, limit = 5): Departure[] {
  return json.Abfahrten.slice(0, limit).map((d) => ({
    line: d.Linienname,
    to: d.Richtungstext,
    time: hhmm(d.AbfahrtszeitIst),
    delay: Math.round((Date.parse(d.AbfahrtszeitIst) - Date.parse(d.AbfahrtszeitSoll)) / 60000),
  }));
}

export function parseQuote(
  name: string,
  json: {
    chart: { result: { meta: { regularMarketPrice: number; chartPreviousClose: number } }[] };
  },
): Quote {
  const { regularMarketPrice: price, chartPreviousClose: prev } = json.chart.result[0].meta;
  return { name, price, change: ((price - prev) / prev) * 100 };
}

export function parseWarnings(json: { warning: Warning[] }, limit = 8): Warning[] {
  return json.warning.slice(0, limit).map(({ title, subtitle }) => ({ title, subtitle }));
}

// Subset of WMO weather codes used by Open-Meteo.
export function weatherText(code: number): string {
  if (code === 0) return 'Clear';
  if (code <= 3) return 'Cloudy';
  if (code <= 48) return 'Fog';
  if (code <= 57) return 'Drizzle';
  if (code <= 67) return 'Rain';
  if (code <= 77) return 'Snow';
  if (code <= 82) return 'Showers';
  if (code <= 86) return 'Snow showers';
  return 'Thunderstorm';
}

export function formatClock(now: Date): string {
  return new Intl.DateTimeFormat('de-DE', {
    weekday: 'long',
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    timeZone: 'Europe/Berlin',
  }).format(now);
}
