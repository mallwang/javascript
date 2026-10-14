import { resetPassword } from '../actions';
import { Flash, type SP } from '../flash';

export default async function Reset({
  searchParams,
}: {
  searchParams: SP & Promise<{ token?: string }>;
}) {
  const { token = '' } = await searchParams;
  return (
    <>
      <h2>Set a new password</h2>
      <Flash sp={searchParams} />
      <form action={resetPassword} className="col">
        <input type="hidden" name="token" value={token} />
        <input name="password" type="password" placeholder="New password" required />
        <input name="password2" type="password" placeholder="Repeat password" required />
        <button>Change password</button>
      </form>
    </>
  );
}
