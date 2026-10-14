import { getDb } from '@/lib/db';
import { requireAdmin } from '@/lib/session';
import { listUsers } from '@/lib/users';
import { deleteUserAction, setRoleAction } from '../actions';
import { Notice, type Notices } from '../notice';

export default async function Admin({ searchParams }: { searchParams: Notices }) {
  const me = await requireAdmin();
  return (
    <>
      <h1>Administration</h1>
      <Notice searchParams={searchParams} />
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>E-Mail</th>
            <th>Bestätigt</th>
            <th>Rolle</th>
            <th />
          </tr>
        </thead>
        <tbody>
          {listUsers(getDb()).map((u) => (
            <tr key={u.id}>
              <td>
                {u.firstName} {u.lastName}
              </td>
              <td>{u.email}</td>
              <td>{u.verified ? 'ja' : 'nein'}</td>
              <td>{u.role}</td>
              <td>
                {u.id !== me.id && (
                  <>
                    <form action={setRoleAction}>
                      <input type="hidden" name="id" value={u.id} />
                      <input
                        type="hidden"
                        name="role"
                        value={u.role === 'admin' ? 'user' : 'admin'}
                      />
                      <button>{u.role === 'admin' ? 'Zu User machen' : 'Zu Admin machen'}</button>
                    </form>
                    <form action={deleteUserAction}>
                      <input type="hidden" name="id" value={u.id} />
                      <button>Löschen</button>
                    </form>
                  </>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
}
