import { requireUser } from '../../lib/auth';
import { getDb } from '../../lib/db';
import { borrow, repay } from '../actions';
import { Flash, money, type SP } from '../flash';

export default async function Finances({ searchParams }: { searchParams: SP }) {
  const u = await requireUser();
  const c = getDb()
    .prepare('SELECT balance, loan, credit_limit FROM companies WHERE id = ?')
    .get(u.companyId) as {
    balance: number;
    loan: number;
    credit_limit: number;
  };
  return (
    <>
      <Flash sp={searchParams} />
      <h2>Bank</h2>
      <p>
        Cash {money(c.balance)} · Loan {money(c.loan)} (10 % interest per tick) · Available credit{' '}
        {money(c.credit_limit - c.loan)}
      </p>
      <form action={borrow} className="row">
        <input name="amount" type="number" step="0.01" min="0.01" required />
        <button>Borrow</button>
      </form>
      <form action={repay} className="row">
        <input name="amount" type="number" step="0.01" min="0.01" required />
        <button>Repay</button>
      </form>
    </>
  );
}
