import { redirect } from 'next/navigation';
import { currentUser } from '../lib/auth';
import { LOCATIONS } from '../lib/goods';
import { login, register } from './actions';
import { Flash, type SP } from './flash';

export default async function Home({ searchParams }: { searchParams: SP }) {
  if (await currentUser()) redirect('/company');
  return (
    <>
      <p>Build your brewery pub empire.</p>
      <Flash sp={searchParams} />
      <h2>Log in</h2>
      <form action={login} className="row">
        <input name="login" placeholder="Nickname or e-mail" required />
        <input name="password" type="password" placeholder="Password" required />
        <button>Log in</button>
      </form>
      <h2>Register</h2>
      <form action={register} className="row">
        <input name="nickname" placeholder="Nickname" required />
        <input name="email" type="email" placeholder="E-mail" required />
        <input
          name="password"
          type="password"
          placeholder="Password (min. 8)"
          minLength={8}
          required
        />
        <input name="password2" type="password" placeholder="Repeat password" required />
        <input name="company" placeholder="Company name" required />
        <select name="hq">
          {LOCATIONS.map((l, i) => (
            <option key={l} value={i + 1}>
              {l}
            </option>
          ))}
        </select>
        <button>Register</button>
      </form>
    </>
  );
}
