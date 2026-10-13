import { describe, expect, it } from 'vitest';
import {
  formatClock,
  parseDepartures,
  parseQuote,
  parseWarnings,
  weatherText,
} from '../src/lib/parse';

describe('parse', () => {
  it('maps departures with delay', () => {
    const [d] = parseDepartures({
      Abfahrten: [
        {
          Linienname: '30',
          Richtungstext: 'Nordostbahnhof',
          AbfahrtszeitSoll: '2026-10-13T19:03:00+02:00',
          AbfahrtszeitIst: '2026-10-13T19:08:03+02:00',
        },
      ],
    });
    expect(d).toEqual({ line: '30', to: 'Nordostbahnhof', time: '19:08', delay: 5 });
  });

  it('computes quote change in percent', () => {
    const q = parseQuote('SAP', {
      chart: { result: [{ meta: { regularMarketPrice: 110, chartPreviousClose: 100 } }] },
    });
    expect(q.change).toBeCloseTo(10);
  });

  it('limits warnings and maps weather codes', () => {
    const w = { title: 'A3', subtitle: 'x' };
    expect(parseWarnings({ warning: [w, w, w] }, 2)).toHaveLength(2);
    expect(weatherText(0)).toBe('Clear');
    expect(weatherText(95)).toBe('Thunderstorm');
  });

  it('formats the clock in German', () => {
    expect(formatClock(new Date('2026-10-13T17:05:00Z'))).toContain('Dienstag');
  });
});
