import { login, register } from '../actions';

export default async function Login({
  searchParams,
}: {
  searchParams: Promise<{ error?: string }>;
}) {
  const { error } = await searchParams;
  return (
    <>
      {error && <p className="error">{error}</p>}
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
        <button>Register</button>
      </form>
    </>
  );
}
