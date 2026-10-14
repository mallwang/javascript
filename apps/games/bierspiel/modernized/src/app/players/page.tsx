import { onlineUsers } from '../../lib/account';
import { getDb } from '../../lib/db';
import { money } from '../flash';

export default function Players() {
  const db = getDb();
  const top = db
    .prepare(
      'SELECT u.nickname, c.name, c.balance - c.loan worth FROM companies c JOIN users u ON u.id = c.user_id ORDER BY worth DESC LIMIT 20',
    )
    .all() as { nickname: string; name: string; worth: number }[];
  return (
    <>
      <h2>Ranking</h2>
      <table>
        <tbody>
          {top.map((t, i) => (
            <tr key={t.nickname}>
              <td>{i + 1}</td>
              <td>{t.nickname}</td>
              <td>{t.name}</td>
              <td>{money(t.worth)}</td>
            </tr>
          ))}
        </tbody>
      </table>
      <h2>Online</h2>
      <p>
        {onlineUsers(db)
          .map((o) => o.nickname)
          .join(', ') || 'Nobody'}
      </p>
    </>
  );
}
