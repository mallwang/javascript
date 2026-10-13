import { requireUser } from '../../lib/auth';
import { deleteAccount, updateEmail, updatePassword } from '../actions';
import { Msg, type Search } from '../Msg';

export default async function Profile({ searchParams }: { searchParams: Search }) {
  const user = await requireUser();
  return (
    <>
      <h1>Profile</h1>
      <p>
        {user.nickname} ({user.email})
      </p>
      <Msg search={searchParams} />
      <h2>Change password</h2>
      <form action={updatePassword}>
        <label>
          Current password
          <input name="current" type="password" required />
        </label>
        <label>
          New password
          <input name="new" type="password" required minLength={6} />
        </label>
        <label>
          Repeat new password
          <input name="new2" type="password" required minLength={6} />
        </label>
        <button>Change password</button>
      </form>
      <h2>Change e-mail</h2>
      <form action={updateEmail}>
        <label>
          New e-mail
          <input name="email" type="email" required />
        </label>
        <label>
          Password
          <input name="password" type="password" required />
        </label>
        <button>Change e-mail</button>
      </form>
      <h2>Delete account</h2>
      <p>The account is deleted after 3 days. Logging in again cancels the deletion.</p>
      <form action={deleteAccount}>
        <button>Request deletion</button>
      </form>
    </>
  );
}
