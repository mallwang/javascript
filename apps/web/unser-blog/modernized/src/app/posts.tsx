import Link from 'next/link';
import { PAGE_SIZE, type Post } from '../lib/blog';
import { deleteComment, deletePost, newComment } from './actions';

const when = (ms: number) => new Date(ms).toLocaleString('en-GB');

export function Pager({ base, page, pages }: { base: string; page: number; pages: number }) {
  return (
    <p>
      {page > 1 && <Link href={`${base}?page=${page - 1}`}>« Newer</Link>} Page {page} of {pages}{' '}
      {page < pages && <Link href={`${base}?page=${page + 1}`}>Older »</Link>}
    </p>
  );
}

export const pageOf = (raw?: string) => Math.max(1, Math.floor(Number(raw)) || 1);

export function Posts({ posts, userId, back }: { posts: Post[]; userId: number; back: string }) {
  if (!posts.length) return <p>No posts yet (showing {PAGE_SIZE} per page).</p>;
  return posts.map((p) => (
    <article key={p.id}>
      <h3>{p.subject}</h3>
      <p className="meta">
        <Link href={`/users/${p.userId}`}>{p.author}</Link>, {when(p.createdAt)}
      </p>
      <p className="text">{p.content}</p>
      {p.userId === userId && (
        <form action={deletePost} className="inline">
          <input type="hidden" name="id" value={p.id} />
          <input type="hidden" name="back" value={back} />
          <button>Delete post</button>
        </form>
      )}
      {p.comments.map((c) => (
        <div key={c.id} className="comment">
          <strong>{c.subject}</strong>
          <span className="meta">
            {' '}
            <Link href={`/users/${c.userId}`}>{c.author}</Link>, {when(c.createdAt)}
          </span>
          <p className="text">{c.content}</p>
          {(c.userId === userId || p.userId === userId) && (
            <form action={deleteComment} className="inline">
              <input type="hidden" name="id" value={c.id} />
              <input type="hidden" name="back" value={back} />
              <button>Delete comment</button>
            </form>
          )}
        </div>
      ))}
      <form action={newComment} className="col">
        <input type="hidden" name="blog" value={p.id} />
        <input type="hidden" name="back" value={back} />
        <input
          name="subject"
          defaultValue={`Re: ${p.subject}`.slice(0, 50)}
          maxLength={50}
          required
        />
        <textarea name="content" placeholder="Comment" required />
        <button>Comment</button>
      </form>
    </article>
  ));
}
