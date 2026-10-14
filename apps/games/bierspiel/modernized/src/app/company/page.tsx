import { requireUser } from '../../lib/auth';
import { getDb } from '../../lib/db';
import { LOCATIONS } from '../../lib/goods';
import { Flash, money, type SP } from '../flash';

export default async function CompanyPage({ searchParams }: { searchParams: SP }) {
  const u = await requireUser();
  const c = getDb().prepare('SELECT * FROM companies WHERE id = ?').get(u.companyId) as Record<
    string,
    number | string
  >;
  const rows = [
    ['Cash', c.balance],
    ['Loan', c.loan],
    ['Credit limit', c.credit_limit],
    ['Salary account', c.salary_account],
    ['Income: sales', c.e_sales],
    ['Income: building sales', c.e_building_sale],
    ['Income: rebuild refunds', c.e_rebuild],
    ['Income: interest', c.e_interest],
    ['Expense: buildings', c.a_building],
    ['Expense: rebuilds', c.a_rebuild],
    ['Expense: goods', c.a_goods],
    ['Expense: wages', c.a_wages],
    ['Expense: interest', c.a_interest],
  ] as [string, number][];
  return (
    <>
      <Flash sp={searchParams} />
      <h2>{u.companyName}</h2>
      <p>Headquarters: {LOCATIONS[Number(c.hq) - 1]}</p>
      <table>
        <tbody>
          {rows.map(([k, v]) => (
            <tr key={k}>
              <td>{k}</td>
              <td>{money(v)}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
}
