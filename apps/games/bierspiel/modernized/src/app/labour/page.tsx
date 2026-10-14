import { requireUser } from '../../lib/auth';
import { getDb } from '../../lib/db';
import { AREA_LABEL, ART_LABEL } from '../../lib/labour';
import { bid, hire } from '../actions';
import { Flash, money, type SP } from '../flash';

type W = {
  id: number;
  art: number;
  area: number;
  first_name: string;
  last_name: string;
  age: number;
  talent: number;
  salary: number;
  work_value: number;
  my_bid: number | null;
};

export default async function Labour({ searchParams }: { searchParams: SP }) {
  const u = await requireUser();
  const list = getDb()
    .prepare(
      `SELECT w.*, (SELECT MAX(amount) FROM bids WHERE worker_id = w.id AND company_id = ?) my_bid
       FROM workers w WHERE employer IS NULL ORDER BY art, id`,
    )
    .all(u.companyId) as W[];
  return (
    <>
      <Flash sp={searchParams} />
      <h2>Labour market</h2>
      <p>
        Apprentices and unskilled workers can be hired directly. Bids for skilled workers and
        managers are decided at the next tick.
      </p>
      <table>
        <tbody>
          {list.map((w) => (
            <tr key={w.id}>
              <td>
                {w.first_name} {w.last_name}
              </td>
              <td>{ART_LABEL[w.art]}</td>
              <td>{AREA_LABEL[w.area]}</td>
              <td>age {w.age}</td>
              <td>talent {w.talent}</td>
              <td>value {w.work_value}</td>
              <td>{money(w.salary)}</td>
              <td>
                {w.art <= 1 ? (
                  <form action={hire} className="inline">
                    <input type="hidden" name="id" value={w.id} />
                    <button>Hire</button>
                  </form>
                ) : (
                  <form action={bid} className="inline">
                    <input type="hidden" name="id" value={w.id} />
                    <input
                      name="amount"
                      type="number"
                      step="0.01"
                      min={w.salary}
                      placeholder={w.my_bid ? `your bid ${money(w.my_bid)}` : 'Bid'}
                      required
                    />
                    <button>Bid</button>
                  </form>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
}
