import { resetAction } from '../actions';

export default async function Reset({
  searchParams,
}: {
  searchParams: Promise<{ token?: string }>;
}) {
  const { token = '' } = await searchParams;
  return (
    <>
      <h1>Neues Passwort</h1>
      <form action={resetAction}>
        <input type="hidden" name="token" value={token} />
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
        <button>Passwort ändern</button>
      </form>
    </>
  );
}
