import { requireUser } from '../../lib/auth';
import { deleteAccount, email, password } from '../actions';

export default async function Profile({
  searchParams,
}: {
  searchParams: Promise<{ error?: string }>;
}) {
  const user = await requireUser();
  const { error } = await searchParams;
  return (
    <>
      {error && <p className="error">{error}</p>}
      <h2>{user.name}</h2>
      <h3>Change e-mail</h3>
      <form action={email} className="row">
        <input name="email" type="email" placeholder="New e-mail" required />
        <button>Save</button>
      </form>
      <h3>Change password</h3>
      <form action={password} className="row">
        <input name="old" type="password" placeholder="Current password" required />
        <input
          name="new"
          type="password"
          placeholder="New password (min. 8)"
          minLength={8}
          required
        />
        <button>Save (logs you out)</button>
      </form>
      <h3>Delete account</h3>
      <p>
        {user.role === 'admin'
          ? 'This deletes the whole club with all teams, people and appointments.'
          : 'This deletes your account.'}
      </p>
      <form action={deleteAccount} className="row">
        <input name="password" type="password" placeholder="Password" required />
        <button>Delete</button>
      </form>
    </>
  );
}
