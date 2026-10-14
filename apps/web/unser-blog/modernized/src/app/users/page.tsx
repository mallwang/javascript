import Link from 'next/link';
import { requireUser } from '../../lib/auth';
import { searchUsers } from '../../lib/blog';
import { getDb } from '../../lib/db';
import { subscribe, unsubscribe } from '../actions';
import { Flash, type SP } from '../flash';

export default async function Users({
  searchParams,
}: {
  searchParams: SP & Promise<{ q?: string }>;
}) {
  const user = await requireUser();
  const { q = '' } = await searchParams;
  const found = searchUsers(getDb(), user.id, q);
  const back = `/users${q ? `?q=${encodeURIComponent(q)}` : ''}`;
  return (
    <>
      <h2>Find users</h2>
      <Flash sp={searchParams} />
      <form className="col">
        <input name="q" defaultValue={q} placeholder="Nickname (empty or * = all)" />
        <button>Search</button>
      </form>
      <table>
        <thead>
          <tr>
            <th>Nickname</th>
            <th>Posts</th>
            <th>Comments</th>
            <th />
          </tr>
        </thead>
        <tbody>
          {found.map((u) => (
            <tr key={u.id}>
              <td>
                <Link href={`/users/${u.id}`}>{u.name}</Link>
              </td>
              <td>{u.posts}</td>
              <td>{u.comments}</td>
              <td>
                {u.id !== user.id && (
                  <form action={u.subscribed ? unsubscribe : subscribe} className="inline">
                    <input type="hidden" name="author" value={u.id} />
                    <input type="hidden" name="back" value={back} />
                    <button>{u.subscribed ? 'Unsubscribe' : 'Subscribe'}</button>
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
