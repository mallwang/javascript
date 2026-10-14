import Link from 'next/link';
import { listBooks } from '@/lib/books';
import { getDb } from '@/lib/db';
import { requireUser } from '@/lib/session';
import { createBookAction } from '../actions';
import { BookFields } from '../book-fields';
import { Notice, type Notices } from '../notice';

export default async function Books({ searchParams }: { searchParams: Notices }) {
  await requireUser();
  const books = listBooks(getDb());
  return (
    <>
      <h1>Bücherregal</h1>
      <Notice searchParams={searchParams} />
      <table>
        <thead>
          <tr>
            <th>Titel</th>
            <th>Autor</th>
            <th>Jahr</th>
            <th>Besitzer</th>
          </tr>
        </thead>
        <tbody>
          {books.map((b) => (
            <tr key={b.id}>
              <td>
                <Link href={`/books/${b.id}`}>{b.title}</Link>
              </td>
              <td>{b.author}</td>
              <td>{b.year}</td>
              <td>{b.ownerName}</td>
            </tr>
          ))}
        </tbody>
      </table>
      <h2>Buch hinzufügen</h2>
      <form action={createBookAction}>
        <BookFields />
        <button>Hinzufügen</button>
      </form>
    </>
  );
}
