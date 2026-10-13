import { currentUser } from '../lib/auth';
import { redirect } from 'next/navigation';
import { login, register } from './actions';

export default async function Landing({
  searchParams,
}: {
  searchParams: Promise<{ error?: string }>;
}) {
  if (await currentUser()) redirect('/scene/home');
  const { error } = await searchParams;
  return (
    <>
      <p>Survive in the Stone Age: gather, hunt, fish and craft. Every job takes real time.</p>
      {error && <p className="error">{error}</p>}
      <h2>Log in</h2>
      <form action={login}>
        <label>
          Nickname
          <input name="nickname" required />
        </label>
        <label>
          Password
          <input name="password" type="password" required />
        </label>
        <button>Log in</button>
      </form>
      <h2>Register</h2>
      <form action={register}>
        <label>
          Nickname
          <input name="nickname" required pattern="[A-Za-z0-9_\-]{3,20}" />
        </label>
        <label>
          Password (min. 8 characters)
          <input name="password" type="password" required minLength={8} />
        </label>
        <label>
          Repeat password
          <input name="password2" type="password" required minLength={8} />
        </label>
        <button>Register</button>
      </form>
    </>
  );
}
