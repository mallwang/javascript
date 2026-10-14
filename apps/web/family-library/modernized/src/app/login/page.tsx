import Link from 'next/link';
import { loginAction, resendAction } from '../actions';
import { Notice, type Notices } from '../notice';

export default function Login({ searchParams }: { searchParams: Notices }) {
  return (
    <>
      <h1>Anmelden</h1>
      <Notice searchParams={searchParams} />
      <form action={loginAction}>
        <label>
          E-Mail <input name="email" type="email" required autoComplete="username" />
        </label>
        <label>
          Passwort{' '}
          <input name="password" type="password" required autoComplete="current-password" />
        </label>
        <button>Anmelden</button>
      </form>
      <p>
        <Link href="/register">Registrieren</Link> ·{' '}
        <Link href="/forgot-password">Passwort vergessen?</Link>
      </p>
      <h2>Bestätigungs-E-Mail erneut senden</h2>
      <form action={resendAction}>
        <label>
          E-Mail <input name="email" type="email" required />
        </label>
        <button>Senden</button>
      </form>
    </>
  );
}
