import { redirect } from 'next/navigation';
import { currentUser } from '../lib/auth';
import { login, register } from './actions';

export default async function Home({
  searchParams,
}: {
  searchParams: Promise<{ error?: string }>;
}) {
  if (await currentUser()) redirect('/club');
  const { error } = await searchParams;
  return (
    <>
      <p>Manage your football club: teams, players, coaches, appointments, matches, attendance.</p>
      {error && <p className="error">{error}</p>}
      <h2>Log in</h2>
      <form action={login} className="row">
        <input name="email" type="email" placeholder="E-mail" required />
        <input name="password" type="password" placeholder="Password" required />
        <button>Log in</button>
      </form>
      <h2>Register a club</h2>
      <form action={register} className="row">
        <input name="club" placeholder="Club name" required />
        <input name="first" placeholder="First name" required />
        <input name="last" placeholder="Last name" required />
        <input name="email" type="email" placeholder="E-mail" required />
        <input
          name="password"
          type="password"
          placeholder="Password (min. 8)"
          minLength={8}
          required
        />
        <input name="password2" type="password" placeholder="Repeat password" required />
        <button>Register</button>
      </form>
    </>
  );
}
