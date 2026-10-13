import { notFound } from 'next/navigation';
import { requireUser } from '../../../lib/auth';
import { getEvent, listAttendance, listPlayers, listTournamentMatches } from '../../../lib/club';
import { getDb } from '../../../lib/db';
import { ATTENDANCE, canManageTeam } from '../../../lib/rules';
import {
  addTournamentMatch,
  attendance,
  removeTournamentMatch,
  result,
  tournamentResult,
} from '../../actions';

const score = (g: number | null, o: number | null) =>
  g === null || o === null ? '-' : `${g}:${o}`;

export default async function EventPage({
  params,
  searchParams,
}: {
  params: Promise<{ id: string }>;
  searchParams: Promise<{ error?: string }>;
}) {
  const user = await requireUser();
  const db = getDb();
  const e = getEvent(db, user, Number((await params).id));
  if (!e) notFound();
  const { error } = await searchParams;
  const manage = canManageTeam(user, e.team_id);
  const players = listPlayers(db, user.clubId, e.team_id);
  const att = new Map(listAttendance(db, user, e.id).map((a) => [a.user_id, a.value]));
  return (
    <>
      {error && <p className="error">{error}</p>}
      <h2>{e.title}</h2>
      <p>
        {e.type}, {e.start_at.replace('T', ' ')} - {e.end_at.replace('T', ' ')}
        {e.location && `, ${e.location}`}
      </p>

      {e.type === 'match' && (
        <>
          <h3>Result against {e.opponent || 'unknown opponent'}</h3>
          {manage ? (
            <form action={result} className="row">
              <input type="hidden" name="id" value={e.id} />
              <input name="goals" size={3} defaultValue={e.team_goals ?? ''} aria-label="Goals" />:
              <input
                name="opponent"
                size={3}
                defaultValue={e.opponent_goals ?? ''}
                aria-label="Opponent goals"
              />
              <button>Save</button>
            </form>
          ) : (
            <p>{score(e.team_goals, e.opponent_goals)}</p>
          )}
        </>
      )}

      {e.type === 'tournament' && (
        <>
          <h3>Matches</h3>
          <table>
            <tbody>
              {listTournamentMatches(db, e.id).map((m) => (
                <tr key={m.id}>
                  <td>{m.start_at.replace('T', ' ')}</td>
                  <td>{m.opponent}</td>
                  <td>
                    {manage ? (
                      <form action={tournamentResult} className="inline">
                        <input type="hidden" name="id" value={m.id} />
                        <input type="hidden" name="event" value={e.id} />
                        <input
                          name="goals"
                          size={3}
                          defaultValue={m.team_goals ?? ''}
                          aria-label="Goals"
                        />
                        :
                        <input
                          name="opponent"
                          size={3}
                          defaultValue={m.opponent_goals ?? ''}
                          aria-label="Opponent goals"
                        />{' '}
                        <button>Save</button>
                      </form>
                    ) : (
                      score(m.team_goals, m.opponent_goals)
                    )}
                  </td>
                  <td>
                    {manage && (
                      <form action={removeTournamentMatch} className="inline">
                        <input type="hidden" name="id" value={m.id} />
                        <input type="hidden" name="event" value={e.id} /> <button>Delete</button>
                      </form>
                    )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          {manage && (
            <form action={addTournamentMatch} className="row">
              <input type="hidden" name="id" value={e.id} />
              <input name="opponent" placeholder="Opponent" required />
              <input name="start" type="datetime-local" required />
              <button>Add match</button>
            </form>
          )}
        </>
      )}

      {e.type === 'training' && (
        <>
          <h3>Attendance</h3>
          <form action={attendance}>
            <input type="hidden" name="id" value={e.id} />
            <table>
              <tbody>
                {players
                  .filter((p) => user.role !== 'player' || p.id === user.id)
                  .map((p) => (
                    <tr key={p.id}>
                      <td>
                        {p.first_name} {p.last_name}
                      </td>
                      <td>
                        <select
                          name={`att-${p.id}`}
                          defaultValue={att.get(p.id) ?? 0}
                          disabled={!manage}
                        >
                          {ATTENDANCE.map((v, i) => (
                            <option key={v} value={i}>
                              {v}
                            </option>
                          ))}
                        </select>
                      </td>
                    </tr>
                  ))}
              </tbody>
            </table>
            {manage && <button>Save attendance</button>}
          </form>
        </>
      )}
    </>
  );
}
