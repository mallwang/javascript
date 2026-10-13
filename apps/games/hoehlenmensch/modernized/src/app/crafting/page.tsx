import Link from 'next/link';
import { requireUser } from '../../lib/auth';
import { craftable } from '../../lib/player';

export default async function Crafting() {
  await requireUser();
  return (
    <>
      <h2>Crafting</h2>
      <ul>
        {craftable.map((a) => (
          <li key={a.id}>
            <Link href={`/work/${a.id}`}>{a.name}</Link>
          </li>
        ))}
      </ul>
    </>
  );
}
