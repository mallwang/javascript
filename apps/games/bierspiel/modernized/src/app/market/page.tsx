import { requireUser } from '../../lib/auth';
import { getDb } from '../../lib/db';
import { buyOffer } from '../actions';
import { Flash, money, type SP } from '../flash';

type Offer = {
  id: number;
  ware: string;
  amount: number;
  quality: number;
  price: number;
  seller: string;
};

export default async function Market({ searchParams }: { searchParams: SP }) {
  const u = await requireUser();
  const db = getDb();
  const offers = db
    .prepare(
      'SELECT o.id, o.ware, o.amount, o.quality, o.price, c.name seller FROM offers o JOIN companies c ON c.id = o.seller_id WHERE o.seller_id <> ? ORDER BY o.ware, o.price',
    )
    .all(u.companyId) as Offer[];
  const stock = db
    .prepare('SELECT ware, amount FROM warehouse WHERE company_id = ? AND amount > 0')
    .all(u.companyId) as { ware: string; amount: number }[];
  return (
    <>
      <Flash sp={searchParams} />
      <h2>Raw-material market</h2>
      {!offers.length && <p>No offers right now.</p>}
      <table>
        <tbody>
          {offers.map((o) => (
            <tr key={o.id}>
              <td>{o.ware}</td>
              <td>{o.amount}</td>
              <td>quality {o.quality}</td>
              <td>{money(o.price)}</td>
              <td>{o.seller}</td>
              <td>
                <form action={buyOffer} className="inline">
                  <input type="hidden" name="id" value={o.id} />
                  <input name="amount" type="number" step="any" min="0" max={o.amount} required />
                  <button>Buy</button>
                </form>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      <h2>Your warehouse</h2>
      <ul>
        {stock.map((s) => (
          <li key={s.ware}>
            {s.ware}: {s.amount}
          </li>
        ))}
      </ul>
    </>
  );
}
