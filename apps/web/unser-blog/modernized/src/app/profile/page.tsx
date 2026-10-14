import { getEmail, getProfile } from '../../lib/account';
import { requireUser } from '../../lib/auth';
import { subscriptions } from '../../lib/blog';
import { getDb } from '../../lib/db';
import { changeEmail, changePassword, saveProfile, unsubscribe, uploadPhoto } from '../actions';
import { Flash, type SP } from '../flash';
import Link from 'next/link';

export default async function Profile({ searchParams }: { searchParams: SP }) {
  const user = await requireUser();
  const db = getDb();
  const p = getProfile(db, user.id)!;
  return (
    <>
      <h2>Profile of {user.name}</h2>
      <Flash sp={searchParams} />
      <form action={saveProfile} className="col">
        <input
          name="firstName"
          defaultValue={p.firstName}
          placeholder="First name"
          maxLength={30}
        />
        <input name="lastName" defaultValue={p.lastName} placeholder="Last name" maxLength={30} />
        <input name="birthDate" type="date" defaultValue={p.birthDate} />
        <input name="place" defaultValue={p.place} placeholder="Place" maxLength={50} />
        <input name="hobbies" defaultValue={p.hobbies} placeholder="Hobbies" maxLength={200} />
        <textarea
          name="description"
          defaultValue={p.description}
          placeholder="About me"
          maxLength={2000}
        />
        <button>Save</button>
      </form>

      <h3>Photo</h3>
      <img className="avatar" src={`/photo/${user.id}`} alt="" />
      <form action={uploadPhoto} className="col">
        <input name="photo" type="file" accept="image/jpeg" required />
        <small>JPEG, at most 100x100 pixels and 100 KB.</small>
        <button>Upload</button>
      </form>

      <h3>Subscriptions</h3>
      <ul>
        {subscriptions(db, user.id).map((s) => (
          <li key={s.id}>
            <Link href={`/users/${s.id}`}>{s.name}</Link>{' '}
            <form action={unsubscribe} className="inline">
              <input type="hidden" name="author" value={s.id} />
              <input type="hidden" name="back" value="/profile" />
              <button>Stop</button>
            </form>
          </li>
        ))}
      </ul>

      <h3>Change e-mail</h3>
      <p className="meta">Current: {getEmail(db, user.id)}</p>
      <form action={changeEmail} className="col">
        <input name="email" type="email" placeholder="New e-mail" maxLength={50} required />
        <input name="password" type="password" placeholder="Password" required />
        <button>Change e-mail</button>
      </form>

      <h3>Change password</h3>
      <form action={changePassword} className="col">
        <input name="current" type="password" placeholder="Current password" required />
        <input name="password" type="password" placeholder="New password" required />
        <input name="password2" type="password" placeholder="Repeat new password" required />
        <button>Change password</button>
      </form>
    </>
  );
}
