import Link from 'next/link';
import { requireUser } from '../../lib/auth';
import { listEvents, listTeams } from '../../lib/club';
import { getDb } from '../../lib/db';
import { EVENT_TYPES, TEAM_TYPES, canManageTeam } from '../../lib/rules';
import { addEvent, removeEvent } from '../actions';

export default async function Calendar({
  searchParams,
}: {
  searchParams: Promise<{ error?: string; type?: string }>;
}) {
  const user = await requireUser();
  const { error, type } = await searchParams;
  const db = getDb();
  const teams = listTeams(db, user.clubId);
  const teamName = (id: number) => {
    const t = teams.find((x) => x.id === id);
    return t ? `${TEAM_TYPES[t.type_id]} ${t.name}` : '-';
  };
  const manageable = teams.filter((t) => canManageTeam(user, t.id));
  return (
    <>
      {error && <p className="error">{error}</p>}
      <h2>Calendar</h2>
      <p>
        <Link href="/calendar">All</Link>
        {EVENT_TYPES.map((t) => (
          <span key={t}>
            {' | '}
            <Link href={`/calendar?type=${t}`}>{t}</Link>
          </span>
        ))}
      </p>
      <table>
        <tbody>
          {listEvents(
            db,
            user,
            EVENT_TYPES.find((t) => t === type),
          ).map((e) => (
            <tr key={e.id}>
              <td>{e.start_at.replace('T', ' ')}</td>
              <td>{e.type}</td>
              <td>
                <Link href={`/events/${e.id}`}>{e.title}</Link>
              </td>
              <td>{teamName(e.team_id)}</td>
              <td>{e.location}</td>
              <td>
                {canManageTeam(user, e.team_id) && (
                  <form action={removeEvent} className="inline">
                    <input type="hidden" name="id" value={e.id} /> <button>Delete</button>
                  </form>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {manageable.length > 0 && (
        <>
          <h3>New appointment</h3>
          <form action={addEvent} className="row">
            <select name="team">
              {manageable.map((t) => (
                <option key={t.id} value={t.id}>
                  {teamName(t.id)}
                </option>
              ))}
            </select>
            <select name="type">
              {EVENT_TYPES.map((t) => (
                <option key={t}>{t}</option>
              ))}
            </select>
            <input name="title" placeholder="Title" required />
            <input name="location" placeholder="Location" />
            <input name="opponent" placeholder="Opponent (matches)" />
            <label>
              Start <input name="start" type="datetime-local" required />
            </label>
            <label>
              End <input name="end" type="datetime-local" required />
            </label>
            <button>Add</button>
          </form>
        </>
      )}
    </>
  );
}
