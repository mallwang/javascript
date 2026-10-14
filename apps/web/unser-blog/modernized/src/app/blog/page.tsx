import { requireUser } from '../../lib/auth';
import { ownPosts } from '../../lib/blog';
import { getDb } from '../../lib/db';
import { newPost } from '../actions';
import { Flash, type SP } from '../flash';
import { Pager, pageOf, Posts } from '../posts';

export default async function MyBlog({
  searchParams,
}: {
  searchParams: SP & Promise<{ page?: string }>;
}) {
  const user = await requireUser();
  const page = pageOf((await searchParams).page);
  const { posts, pages } = ownPosts(getDb(), user.id, page);
  return (
    <>
      <h2>My blog</h2>
      <Flash sp={searchParams} />
      <form action={newPost} className="col">
        <input name="subject" placeholder="Subject" maxLength={50} required />
        <textarea name="content" placeholder="Text" rows={5} required />
        <button>Publish</button>
      </form>
      <Posts posts={posts} userId={user.id} back={`/blog?page=${page}`} />
      <Pager base="/blog" page={page} pages={pages} />
    </>
  );
}
