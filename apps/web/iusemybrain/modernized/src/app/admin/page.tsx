import { requireAdmin } from '../../lib/auth';
import { getDb } from '../../lib/db';
import { CATEGORIES, listPending } from '../../lib/site';
import { moderate } from '../actions';

export default async function Admin({
  searchParams,
}: {
  searchParams: Promise<{ error?: string }>;
}) {
  await requireAdmin();
  const { error } = await searchParams;
  const pending = listPending(getDb());
  return (
    <>
      <h2>Pending places</h2>
      {error && <p className="error">{error}</p>}
      {pending.length === 0 && <p>Nothing to approve.</p>}
      {pending.map((p) => (
        <form key={p.id} action={moderate} className="row">
          <input type="hidden" name="id" value={p.id} />
          <span>
            <b>{p.name}</b> ({CATEGORIES[p.category]}, {p.city}, {p.state}; {p.lat}, {p.lng}):{' '}
            {p.description}
          </span>
          <button name="approve" value="1">
            Approve
          </button>
          <button name="approve" value="0">
            Reject
          </button>
        </form>
      ))}
    </>
  );
}
