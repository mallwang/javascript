import Link from 'next/link';
import { requireUser } from '../../lib/auth';
import { getClub, listCoaches, listPlayers, listTeams } from '../../lib/club';
import { getDb } from '../../lib/db';
import { TEAM_TYPES } from '../../lib/rules';
import { addPerson, addTeam, playerTeam, removePerson, removeTeam, saveClub } from '../actions';

export default async function Club({
  searchParams,
}: {
  searchParams: Promise<{ error?: string }>;
}) {
  const user = await requireUser();
  const { error } = await searchParams;
  const db = getDb();
  const c = getClub(db, user.clubId) as Record<string, string>;
  const teams = listTeams(db, user.clubId);
  const admin = user.role === 'admin';
  const teamName = (id: number | null) => {
    const t = teams.find((x) => x.id === id);
    return t ? `${TEAM_TYPES[t.type_id]} ${t.name}` : '-';
  };
  return (
    <>
      {error && <p className="error">{error}</p>}
      <h2>{c.name}</h2>
      {admin ? (
        <form action={saveClub}>
          <p>
            <input name="name" defaultValue={c.name} required />
          </p>
          <p>
            <input
              name="short"
              defaultValue={c.short_text}
              placeholder="Short description"
              size={60}
            />
          </p>
          <p>
            <textarea
              name="long"
              defaultValue={c.long_text}
              placeholder="Description"
              cols={60}
              rows={3}
            />
          </p>
          <p>
            <textarea
              name="history"
              defaultValue={c.history}
              placeholder="History"
              cols={60}
              rows={3}
            />
          </p>
          <button>Save club</button>
        </form>
      ) : (
        <>
          <p>{c.short_text}</p>
          <p>{c.long_text}</p>
          <p>{c.history}</p>
        </>
      )}

      <h2>Teams</h2>
      <ul>
        {teams.map((t) => (
          <li key={t.id}>
            <Link href={`/teams/${t.id}`}>{teamName(t.id)}</Link>
            {admin && (
              <form action={removeTeam} className="inline">
                <input type="hidden" name="id" value={t.id} /> <button>Delete</button>
              </form>
            )}
          </li>
        ))}
      </ul>
      {admin && (
        <form action={addTeam} className="row">
          <select name="type">
            {TEAM_TYPES.map((n, i) => (
              <option key={n} value={i}>
                {n}
              </option>
            ))}
          </select>
          <input name="name" placeholder="Name (optional)" />
          <button>Add team</button>
        </form>
      )}

      <h2>Coaches</h2>
      <table>
        <tbody>
          {listCoaches(db, user.clubId).map((p) => (
            <tr key={p.id}>
              <td>
                {p.first_name} {p.last_name}
              </td>
              <td>{p.email}</td>
              <td>
                {admin && (
                  <form action={removePerson} className="inline">
                    <input type="hidden" name="id" value={p.id} /> <button>Delete</button>
                  </form>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      <h2>Players</h2>
      <table>
        <tbody>
          {listPlayers(db, user.clubId).map((p) => (
            <tr key={p.id}>
              <td>
                {p.first_name} {p.last_name}
              </td>
              <td>{p.birthdate}</td>
              <td>
                {admin ? (
                  <form action={playerTeam} className="inline">
                    <input type="hidden" name="id" value={p.id} />
                    <select name="team" defaultValue={p.team_id ?? 0}>
                      <option value={0}>-</option>
                      {teams.map((t) => (
                        <option key={t.id} value={t.id}>
                          {teamName(t.id)}
                        </option>
                      ))}
                    </select>{' '}
                    <button>Move</button>
                  </form>
                ) : (
                  teamName(p.team_id)
                )}
              </td>
              <td>
                {admin && (
                  <form action={removePerson} className="inline">
                    <input type="hidden" name="id" value={p.id} /> <button>Delete</button>
                  </form>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {admin && (
        <>
          <h2>Add coach or player</h2>
          <form action={addPerson} className="row">
            <select name="role">
              <option value="coach">Coach</option>
              <option value="player">Player</option>
            </select>
            <input name="first" placeholder="First name" required />
            <input name="last" placeholder="Last name" required />
            <input name="email" type="email" placeholder="E-mail" required />
            <input
              name="password"
              type="password"
              placeholder="Initial password (min. 8)"
              minLength={8}
              required
            />
            <input name="birthdate" type="date" title="Birth date (players)" />
            <select name="team">
              <option value={0}>No team</option>
              {teams.map((t) => (
                <option key={t.id} value={t.id}>
                  {teamName(t.id)}
                </option>
              ))}
            </select>
            <button>Add</button>
          </form>
        </>
      )}
    </>
  );
}
