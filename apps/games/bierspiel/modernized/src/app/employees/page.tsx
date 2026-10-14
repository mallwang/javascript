import { requireUser } from '../../lib/auth';
import { getDb } from '../../lib/db';
import { AREA_LABEL, ART_LABEL } from '../../lib/labour';
import { fire, setSalary } from '../actions';
import { Flash, type SP } from '../flash';

type W = {
  id: number;
  art: number;
  area: number;
  first_name: string;
  last_name: string;
  salary: number;
  satisfaction: number;
  work_value: number;
  tenure: number;
};

export default async function Employees({ searchParams }: { searchParams: SP }) {
  const u = await requireUser();
  const list = getDb()
    .prepare('SELECT * FROM workers WHERE employer = ? ORDER BY art, id')
    .all(u.companyId) as W[];
  return (
    <>
      <Flash sp={searchParams} />
      <h2>Employees</h2>
      {!list.length && <p>You have no employees.</p>}
      <table>
        <tbody>
          {list.map((w) => (
            <tr key={w.id}>
              <td>
                {w.first_name} {w.last_name}
              </td>
              <td>{ART_LABEL[w.art]}</td>
              <td>{AREA_LABEL[w.area]}</td>
              <td>satisfaction {w.satisfaction} %</td>
              <td>value {w.work_value}</td>
              <td>{w.tenure} ticks</td>
              <td>
                <form action={setSalary} className="inline">
                  <input type="hidden" name="id" value={w.id} />
                  <input
                    name="salary"
                    type="number"
                    step="0.01"
                    min="0.01"
                    defaultValue={w.salary}
                    required
                  />
                  <button>Set salary</button>
                </form>
                <form action={fire} className="inline">
                  <input type="hidden" name="id" value={w.id} />
                  <button>Fire (severance 10 × salary)</button>
                </form>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
}
