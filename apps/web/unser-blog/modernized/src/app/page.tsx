import { redirect } from 'next/navigation';
import { currentUser } from '../lib/auth';
import { forgotPassword, login, register } from './actions';
import { Flash, type SP } from './flash';

export default async function Index({ searchParams }: { searchParams: SP }) {
  if (await currentUser()) redirect('/home');
  return (
    <>
      <p>Write a blog, follow other users and comment on their posts.</p>
      <Flash sp={searchParams} />
      <h2>Log in</h2>
      <form action={login} className="col">
        <input name="login" placeholder="Nickname or e-mail" required />
        <input name="password" type="password" placeholder="Password" required />
        <button>Log in</button>
      </form>
      <h2>Register</h2>
      <form action={register} className="col">
        <input name="name" placeholder="Nickname" maxLength={30} required />
        <input name="email" type="email" placeholder="E-mail" maxLength={50} required />
        <input
          name="password"
          type="password"
          placeholder="Password (min. 8 characters)"
          required
        />
        <input name="password2" type="password" placeholder="Repeat password" required />
        <button>Register</button>
      </form>
      <h2>Forgot password</h2>
      <form action={forgotPassword} className="col">
        <input name="email" type="email" placeholder="E-mail" required />
        <button>Send reset link</button>
      </form>
    </>
  );
}
