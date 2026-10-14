import { verifyAction } from '../actions';

export default async function Verify({
  searchParams,
}: {
  searchParams: Promise<{ token?: string }>;
}) {
  const { token = '' } = await searchParams;
  return (
    <>
      <h1>E-Mail-Adresse bestätigen</h1>
      <form action={verifyAction}>
        <input type="hidden" name="token" value={token} />
        <button>Adresse bestätigen</button>
      </form>
    </>
  );
}
