import { notFound } from 'next/navigation';
import { requireUser } from '../../../lib/auth';
import type { Building } from '../../../lib/company';
import { getDb } from '../../../lib/db';
import { CATEGORIES, GOODS, GROUPS, STYLES } from '../../../lib/goods';
import { buyGoods, rebuild, saveMenu } from '../../actions';
import { Flash, money, type SP } from '../../flash';

const range = (n: number) => Array.from({ length: n }, (_, i) => i + 1);

export default async function BuildingPage({
  params,
  searchParams,
}: {
  params: Promise<{ id: string }>;
  searchParams: SP;
}) {
  const u = await requireUser();
  const db = getDb();
  const b = db
    .prepare('SELECT * FROM buildings WHERE id = ? AND company_id = ?')
    .get(Number((await params).id), u.companyId) as Building | undefined;
  if (!b) notFound();
  const goods = new Map(
    (
      db.prepare('SELECT * FROM building_goods WHERE building_id = ?').all(b.id) as {
        good: string;
        offered: number;
        price: number;
        stock: number;
      }[]
    ).map((g) => [g.good, g]),
  );
  const market = new Map(
    (
      db.prepare('SELECT good, price FROM market_prices').all() as { good: string; price: number }[]
    ).map((p) => [p.good, p.price]),
  );
  return (
    <>
      <Flash sp={searchParams} />
      <h2>{b.name}</h2>
      <h3>Rebuild</h3>
      <form action={rebuild} className="row">
        <input type="hidden" name="id" value={b.id} />
        <select name="style" defaultValue={b.style}>
          {STYLES.map((s) => (
            <option key={s}>{s}</option>
          ))}
        </select>
        <label>
          Seats{' '}
          <select name="seatIndex" defaultValue={(b.seats - 20) / 4}>
            {range(25).map((i) => (
              <option key={i} value={i}>
                {20 + 4 * i}
              </option>
            ))}
          </select>
        </label>
        <label>
          Toilets (m){' '}
          <select name="wcM" defaultValue={b.wc_m}>
            {range(6).map((i) => (
              <option key={i}>{i}</option>
            ))}
          </select>
        </label>
        <label>
          Toilets (f){' '}
          <select name="wcW" defaultValue={b.wc_w}>
            {range(6).map((i) => (
              <option key={i}>{i}</option>
            ))}
          </select>
        </label>
        <label>
          Primary group{' '}
          <select name="pzg" defaultValue={b.pzg}>
            {GROUPS.map((g) => (
              <option key={g}>{g}</option>
            ))}
          </select>
        </label>
        <label>
          Secondary group{' '}
          <select name="szg" defaultValue={b.szg}>
            {GROUPS.map((g) => (
              <option key={g}>{g}</option>
            ))}
          </select>
        </label>
        <button>Rebuild</button>
      </form>
      <h3>Drinks menu</h3>
      <form action={saveMenu}>
        <input type="hidden" name="id" value={b.id} />
        <table>
          <tbody>
            {GOODS.map((g) => {
              const x = goods.get(g.key)!;
              return (
                <tr key={g.key}>
                  <td>{CATEGORIES[g.cat].label}</td>
                  <td>{g.label}</td>
                  <td>
                    <input type="checkbox" name={`o_${g.key}`} defaultChecked={!!x.offered} />
                  </td>
                  <td>
                    <input
                      name={`p_${g.key}`}
                      type="number"
                      step="0.01"
                      min="0.01"
                      max="99.99"
                      defaultValue={x.price}
                    />
                  </td>
                  <td>stock {x.stock} l</td>
                </tr>
              );
            })}
          </tbody>
        </table>
        <button>Save menu</button>
      </form>
      <h3>Buy drinks</h3>
      {GOODS.map((g) => (
        <form key={g.key} action={buyGoods} className="row">
          <input type="hidden" name="id" value={b.id} />
          <input type="hidden" name="good" value={g.key} />
          <span>
            {g.label}: {money(market.get(g.key)!)} per 20 l
          </span>
          <input name="liters" type="number" step="any" min="1" placeholder="Liters" required />
          <button>Buy</button>
        </form>
      ))}
    </>
  );
}
