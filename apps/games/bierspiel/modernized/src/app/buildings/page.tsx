import Link from 'next/link';
import { requireUser } from '../../lib/auth';
import type { Building } from '../../lib/company';
import { getDb } from '../../lib/db';
import { BUILDINGS, DISTRICTS, LOCATIONS, PROVIDER_COST, PROVIDER_TIME } from '../../lib/goods';
import { build, demolish, sell } from '../actions';
import { Flash, money, type SP } from '../flash';

export default async function Buildings({ searchParams }: { searchParams: SP }) {
  const u = await requireUser();
  const list = getDb()
    .prepare('SELECT * FROM buildings WHERE company_id = ? ORDER BY id')
    .all(u.companyId) as Building[];
  const now = Date.now();
  return (
    <>
      <Flash sp={searchParams} />
      <h2>Your buildings</h2>
      <table>
        <tbody>
          {list.map((b) => (
            <tr key={b.id}>
              <td>
                {b.finished_at <= now ? <Link href={`/buildings/${b.id}`}>{b.name}</Link> : b.name}
              </td>
              <td>{BUILDINGS[b.kind]?.name}</td>
              <td>
                {LOCATIONS[b.location - 1]}, {DISTRICTS[b.district - 1]}
              </td>
              <td>
                {b.finished_at <= now
                  ? 'open'
                  : `ready ${new Date(b.finished_at).toLocaleString('en-GB')}`}
              </td>
              <td>
                <form action={sell} className="inline">
                  <input type="hidden" name="id" value={b.id} />
                  <button>Sell for {money(b.cost / 2)}</button>
                </form>
                <form action={demolish} className="inline">
                  <input type="hidden" name="id" value={b.id} />
                  <button>Demolish</button>
                </form>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      <h2>Build</h2>
      <form action={build} className="row">
        <input name="name" placeholder="Name" required />
        <select name="kind">
          {Object.entries(BUILDINGS).map(([k, b]) => (
            <option key={k} value={k}>
              {b.name} ({money(b.cost)})
            </option>
          ))}
        </select>
        <select name="location">
          {LOCATIONS.map((l, i) => (
            <option key={l} value={i + 1}>
              {l}
            </option>
          ))}
        </select>
        <select name="district">
          {DISTRICTS.map((d, i) => (
            <option key={d} value={i + 1}>
              {d}
            </option>
          ))}
        </select>
        <select name="provider">
          {PROVIDER_COST.map((c, i) => (
            <option key={i} value={i + 1}>
              Provider {i + 1} (cost ×{c}, time ×{PROVIDER_TIME[i]})
            </option>
          ))}
        </select>
        <button>Build</button>
      </form>
    </>
  );
}
