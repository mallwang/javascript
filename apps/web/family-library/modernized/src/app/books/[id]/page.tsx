import { notFound } from 'next/navigation';
import { getBook } from '@/lib/books';
import { getDb } from '@/lib/db';
import { requireUser } from '@/lib/session';
import { deleteBookAction, updateBookAction } from '../../actions';
import { BookFields } from '../../book-fields';
import { Notice, type Notices } from '../../notice';

export default async function BookPage({
  params,
  searchParams,
}: {
  params: Promise<{ id: string }>;
  searchParams: Notices;
}) {
  const user = await requireUser();
  const book = getBook(getDb(), Number((await params).id));
  if (!book) notFound();
  const canEdit = book.ownerId === user.id || user.role === 'admin';
  return (
    <>
      <h1>{book.title}</h1>
      <Notice searchParams={searchParams} />
      <p>
        {book.author}, {book.year}, {book.language} · ISBN {book.isbn} · Besitzer: {book.ownerName}
      </p>
      {canEdit && (
        <>
          <h2>Bearbeiten</h2>
          <form action={updateBookAction}>
            <input type="hidden" name="id" value={book.id} />
            <BookFields book={book} />
            <button>Speichern</button>
          </form>
          <form action={deleteBookAction}>
            <input type="hidden" name="id" value={book.id} />
            <button>Löschen</button>
          </form>
        </>
      )}
    </>
  );
}
