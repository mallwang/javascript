import type { BookInput } from '@/lib/validate';

export function BookFields({ book }: { book?: BookInput }) {
  return (
    <>
      <label>
        Titel <input name="title" defaultValue={book?.title} required maxLength={200} />
      </label>
      <label>
        Autor <input name="author" defaultValue={book?.author} required maxLength={200} />
      </label>
      <label>
        ISBN <input name="isbn" defaultValue={book?.isbn} required />
      </label>
      <label>
        Jahr{' '}
        <input
          name="year"
          type="number"
          min={1900}
          max={new Date().getFullYear()}
          defaultValue={book?.year}
          required
        />
      </label>
      <label>
        Sprache <input name="language" defaultValue={book?.language} required maxLength={50} />
      </label>
    </>
  );
}
