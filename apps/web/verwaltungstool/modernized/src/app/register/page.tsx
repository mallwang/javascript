import { register } from '../actions';
import { Msg, type Search } from '../Msg';

export default function Register({ searchParams }: { searchParams: Search }) {
  return (
    <>
      <h1>Register</h1>
      <Msg search={searchParams} />
      <form action={register}>
        <label>
          Nickname
          <input name="nickname" required minLength={4} maxLength={30} />
        </label>
        <label>
          E-mail
          <input name="email" type="email" required />
        </label>
        <label>
          Password
          <input name="password" type="password" required minLength={6} />
        </label>
        <label>
          Repeat password
          <input name="password2" type="password" required minLength={6} />
        </label>
        <button>Register</button>
      </form>
    </>
  );
}
