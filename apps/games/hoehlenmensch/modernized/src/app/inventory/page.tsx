import { requireUser } from '../../lib/auth';
import { getDb } from '../../lib/db';
import { craftable, getPlayer } from '../../lib/player';

export default async function Inventory() {
  const p = getPlayer(getDb(), (await requireUser()).id);
  return (
    <>
      <h2>Inventory</h2>
      <ul>
        {craftable.map((a) => (
          <li key={a.id}>
            {a.name}: {p.items[a.item!] ?? 0}
          </li>
        ))}
      </ul>
    </>
  );
}
