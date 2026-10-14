import { forgotAction } from '../actions';
import { Notice, type Notices } from '../notice';

export default function Forgot({ searchParams }: { searchParams: Notices }) {
  return (
    <>
      <h1>Passwort vergessen</h1>
      <Notice searchParams={searchParams} />
      <form action={forgotAction}>
        <label>
          E-Mail <input name="email" type="email" required />
        </label>
        <button>Link senden</button>
      </form>
    </>
  );
}
