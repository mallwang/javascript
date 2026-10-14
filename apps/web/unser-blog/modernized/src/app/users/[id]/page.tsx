import { notFound } from 'next/navigation';
import { getProfile } from '../../../lib/account';
import { requireUser } from '../../../lib/auth';
import { isSubscribed } from '../../../lib/blog';
import { getDb } from '../../../lib/db';
import { subscribe, unsubscribe } from '../../actions';
import { Flash, type SP } from '../../flash';

export default async function UserPage({
  params,
  searchParams,
}: {
  params: Promise<{ id: string }>;
  searchParams: SP;
}) {
  const me = await requireUser();
  const id = Number((await params).id);
  const p = Number.isInteger(id) ? getProfile(getDb(), id) : undefined;
  if (!p) notFound();
  const subscribed = isSubscribed(getDb(), me.id, id);
  return (
    <>
      <h2>
        <img className="avatar" src={`/photo/${id}`} alt="" /> {p.name}
      </h2>
      <Flash sp={searchParams} />
      <table>
        <tbody>
          <tr>
            <th>Name</th>
            <td>{`${p.firstName} ${p.lastName}`.trim()}</td>
          </tr>
          <tr>
            <th>Birth date</th>
            <td>{p.birthDate}</td>
          </tr>
          <tr>
            <th>Place</th>
            <td>{p.place}</td>
          </tr>
          <tr>
            <th>Hobbies</th>
            <td>{p.hobbies}</td>
          </tr>
          <tr>
            <th>About</th>
            <td className="text">{p.description}</td>
          </tr>
        </tbody>
      </table>
      {id !== me.id && (
        <form action={subscribed ? unsubscribe : subscribe}>
          <input type="hidden" name="author" value={id} />
          <input type="hidden" name="back" value={`/users/${id}`} />
          <button>{subscribed ? 'Unsubscribe' : 'Subscribe'}</button>
        </form>
      )}
    </>
  );
}
