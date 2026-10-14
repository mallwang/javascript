import Link from 'next/link';
import { yearly } from '../lib/consumption';
import { getDb } from '../lib/db';
import { getReadings, meters, type Meter } from '../lib/importer';
import { upload } from './actions';
import { BarChart } from './bar-chart';

type Props = { searchParams: Promise<{ ok?: string; error?: string }> };

export default async function Page({ searchParams }: Props) {
  const { ok, error } = await searchParams;
  const db = getDb();
  return (
    <>
      {ok && <p className="ok">{ok}</p>}
      {error && <p className="error">{error}</p>}
      {(Object.keys(meters) as Meter[]).map((meter) => {
        const readings = getReadings(db, meter);
        const last = readings.at(-1);
        const { label, unit } = meters[meter];
        return (
          <section key={meter}>
            <h2>
              <Link href={`/meter/${meter}`}>{label}</Link>
            </h2>
            {last ? (
              <>
                <p>
                  Latest reading: {last.reading} {unit} ({last.date})
                </p>
                <BarChart data={yearly(readings)} unit={unit} />
              </>
            ) : (
              <p>No data yet.</p>
            )}
          </section>
        );
      })}
      <h2>Import</h2>
      <form action={upload}>
        <input type="file" name="file" accept=".xlsx" required />
        <button>Import workbook</button>
      </form>
      <p>Replaces the stored readings of every meter whose sheet is found in the workbook.</p>
    </>
  );
}
