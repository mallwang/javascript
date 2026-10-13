import { requireUser } from '../../lib/auth';
import { getDb } from '../../lib/db';
import { getPlayer } from '../../lib/player';

export default async function Status() {
  const p = getPlayer(getDb(), (await requireUser()).id);
  return (
    <>
      <h2>Status</h2>
      {p.current ? (
        <p>
          {p.current.name} until {new Date(p.ends_at).toLocaleString('en-GB')}. Reload the page when
          it is over to collect the result.
        </p>
      ) : (
        <p>You are idle.</p>
      )}
      <h3>Skills</h3>
      <ul>
        {Object.entries(p.skills).map(([k, v]) => (
          <li key={k}>
            {k}: {v}
          </li>
        ))}
      </ul>
    </>
  );
}
