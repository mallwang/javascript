import Link from 'next/link';
import { getDb } from '../../lib/db';
import { CATEGORIES, cities, listPlaces } from '../../lib/site';

export default async function Places({
  searchParams,
}: {
  searchParams: Promise<{ city?: string; category?: string }>;
}) {
  const { city = '', category = '' } = await searchParams;
  const db = getDb();
  const places = listPlaces(db, city, category);
  return (
    <>
      <h2>Places</h2>
      <form className="row">
        <select name="city" defaultValue={city}>
          <option value="">All cities</option>
          {cities(db).map((c) => (
            <option key={c}>{c}</option>
          ))}
        </select>
        <select name="category" defaultValue={category}>
          <option value="">All categories</option>
          {Object.entries(CATEGORIES).map(([k, v]) => (
            <option key={k} value={k}>
              {v}
            </option>
          ))}
        </select>
        <button>Show</button>
        <Link href="/places/new">Submit a place</Link>
      </form>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Category</th>
            <th>City</th>
            <th>Description</th>
            <th>Map</th>
          </tr>
        </thead>
        <tbody>
          {places.map((p) => (
            <tr key={p.id}>
              <td>{p.name}</td>
              <td>{CATEGORIES[p.category]}</td>
              <td>
                {p.city}, {p.state}
              </td>
              <td>{p.description}</td>
              <td>
                <a
                  href={`https://www.openstreetmap.org/?mlat=${p.lat}&mlon=${p.lng}#map=15/${p.lat}/${p.lng}`}
                >
                  {p.lat}, {p.lng}
                </a>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {places.length === 0 && <p>No places found.</p>}
    </>
  );
}
