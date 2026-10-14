import { registerAction } from '../actions';
import { Notice, type Notices } from '../notice';

export default function Register({ searchParams }: { searchParams: Notices }) {
  return (
    <>
      <h1>Registrieren</h1>
      <Notice searchParams={searchParams} />
      <form action={registerAction}>
        <label>
          Vorname <input name="firstName" required maxLength={100} />
        </label>
        <label>
          Nachname <input name="lastName" required maxLength={100} />
        </label>
        <label>
          E-Mail <input name="email" type="email" required autoComplete="username" />
        </label>
        <label>
          Passwort (mind. 8 Zeichen){' '}
          <input
            name="password"
            type="password"
            required
            minLength={8}
            maxLength={128}
            autoComplete="new-password"
          />
        </label>
        <button>Registrieren</button>
      </form>
    </>
  );
}
