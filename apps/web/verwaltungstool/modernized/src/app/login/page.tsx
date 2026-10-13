import { login } from '../actions';
import { Msg, type Search } from '../Msg';

export default function Login({ searchParams }: { searchParams: Search }) {
  return (
    <>
      <h1>Log in</h1>
      <Msg search={searchParams} />
      <form action={login}>
        <label>
          Nickname or e-mail
          <input name="login" required />
        </label>
        <label>
          Password
          <input name="password" type="password" required />
        </label>
        <button>Log in</button>
      </form>
    </>
  );
}
