import { getDb } from '../../lib/db';
import { distinct, searchBooks } from '../../lib/site';

export default async function Library({
  searchParams,
}: {
  searchParams: Promise<{ category?: string; format?: string; q?: string }>;
}) {
  const { category = '', format = '', q = '' } = await searchParams;
  const db = getDb();
  const books = searchBooks(db, category, format, q);
  return (
    <>
      <h2>Library</h2>
      <form className="row">
        <select name="category" defaultValue={category}>
          <option value="">All categories</option>
          {distinct(db, 'category').map((c) => (
            <option key={c}>{c}</option>
          ))}
        </select>
        <select name="format" defaultValue={format}>
          <option value="">All formats</option>
          {distinct(db, 'format').map((c) => (
            <option key={c}>{c}</option>
          ))}
        </select>
        <input name="q" defaultValue={q} placeholder="Title or author" />
        <button>Search</button>
      </form>
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
          </tr>
        </thead>
        <tbody>
          {books.map((b) => (
            <tr key={b.id}>
              <td>{b.title}</td>
              <td>{b.author}</td>
              <td>{b.year}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
}
