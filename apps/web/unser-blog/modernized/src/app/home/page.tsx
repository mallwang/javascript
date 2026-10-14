import Link from 'next/link';
import { requireUser } from '../../lib/auth';
import { feed } from '../../lib/blog';
import { getDb } from '../../lib/db';
import { Flash, type SP } from '../flash';
import { Pager, pageOf, Posts } from '../posts';

export default async function Home({
  searchParams,
}: {
  searchParams: SP & Promise<{ page?: string }>;
}) {
  const user = await requireUser();
  const page = pageOf((await searchParams).page);
  const { posts, pages } = feed(getDb(), user.id, page);
  return (
    <>
      <h2>Posts of your subscriptions</h2>
      <Flash sp={searchParams} />
      {!posts.length && page === 1 && (
        <p>
          Nothing here yet. <Link href="/users">Find users</Link> to subscribe to.
        </p>
      )}
      <Posts posts={posts} userId={user.id} back={`/home?page=${page}`} />
      <Pager base="/home" page={page} pages={pages} />
    </>
  );
}
