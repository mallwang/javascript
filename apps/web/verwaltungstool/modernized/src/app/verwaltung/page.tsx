import { requireUser } from '../../lib/auth';

export default async function Verwaltung() {
  const user = await requireUser();
  return (
    <>
      <h1>Welcome, {user.nickname}</h1>
      <p>Here you will manage your study materials (not implemented in the legacy app either).</p>
    </>
  );
}
