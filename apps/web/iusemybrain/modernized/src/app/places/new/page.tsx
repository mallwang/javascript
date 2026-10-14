import { CATEGORIES, STATES } from '../../../lib/site';
import { submitPlace } from '../../actions';

export default async function NewPlace({
  searchParams,
}: {
  searchParams: Promise<{ error?: string; ok?: string }>;
}) {
  const { error, ok } = await searchParams;
  return (
    <>
      <h2>Submit a place</h2>
      {error && <p className="error">{error}</p>}
      {ok && <p className="ok">{ok}</p>}
      <form action={submitPlace} className="row">
        <select name="category" required>
          {Object.entries(CATEGORIES).map(([k, v]) => (
            <option key={k} value={k}>
              {v}
            </option>
          ))}
        </select>
        <select name="state" required>
          {STATES.map((s) => (
            <option key={s}>{s}</option>
          ))}
        </select>
        <input name="city" placeholder="City" required />
        <input name="name" placeholder="Name" required />
        <input name="lat" type="number" step="any" placeholder="Latitude" required />
        <input name="lng" type="number" step="any" placeholder="Longitude" required />
        <textarea name="description" placeholder="Description" rows={3} cols={50} />
        <button>Submit</button>
      </form>
    </>
  );
}
