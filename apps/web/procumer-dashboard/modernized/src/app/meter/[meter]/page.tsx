import Link from 'next/link';
import { notFound } from 'next/navigation';
import { monthly } from '../../../lib/consumption';
import { getDb } from '../../../lib/db';
import { getReadings, isMeter, meters } from '../../../lib/importer';

export default async function MeterPage({ params }: { params: Promise<{ meter: string }> }) {
  const { meter } = await params;
  if (!isMeter(meter)) notFound();
  const { label, unit } = meters[meter];
  const asc = getReadings(getDb(), meter);
  const consumption = new Map(monthly(asc).map((c) => [c.period, c.consumption]));
  const readings = [...asc].reverse();
  return (
    <>
      <p>
        <Link href="/">← Overview</Link>
      </p>
      <h2>{label}</h2>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Reading ({unit})</th>
            <th>Consumption ({unit})</th>
          </tr>
        </thead>
        <tbody>
          {readings.map((r) => (
            <tr key={r.date}>
              <td>{r.date}</td>
              <td>{r.reading}</td>
              <td>{consumption.get(r.date.slice(0, 7)) ?? '–'}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
}
