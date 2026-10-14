export type Reading = { date: string; reading: number }; // date: YYYY-MM-DD
export type Consumption = { period: string; consumption: number };

const round2 = (n: number) => Math.round(n * 100) / 100;

/** Consumption between two consecutive readings, labelled with the later date. The first reading has none. */
export const monthly = (readings: Reading[]): Consumption[] =>
  readings.slice(1).map((r, i) => ({
    period: r.date.slice(0, 7),
    consumption: round2(r.reading - readings[i].reading),
  }));

/** Monthly consumptions summed per calendar year. */
export function yearly(readings: Reading[]): Consumption[] {
  const sums = new Map<string, number>();
  for (const { period, consumption } of monthly(readings)) {
    const year = period.slice(0, 4);
    sums.set(year, (sums.get(year) ?? 0) + consumption);
  }
  return [...sums].map(([period, consumption]) => ({ period, consumption: round2(consumption) }));
}
