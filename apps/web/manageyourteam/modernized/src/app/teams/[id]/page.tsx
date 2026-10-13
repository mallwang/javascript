import { notFound } from 'next/navigation';
import { requireUser } from '../../../lib/auth';
import { getTeam, listCoaches, listPlayers } from '../../../lib/club';
import { getDb } from '../../../lib/db';
import { FORMATIONS, SLOTS, TEAM_TYPES, canManageTeam, slotLabel } from '../../../lib/rules';
import { coachTeam, lineup } from '../../actions';

export default async function Team({
  params,
  searchParams,
}: {
  params: Promise<{ id: string }>;
  searchParams: Promise<{ error?: string }>;
}) {
  const user = await requireUser();
  const id = Number((await params).id);
  const db = getDb();
  const team = getTeam(db, user.clubId, id);
  if (!team) notFound();
  const { error } = await searchParams;
  const players = listPlayers(db, user.clubId, id);
  const coaches = listCoaches(db, user.clubId);
  const manage = canManageTeam(user, id);
  const slots = Array.from({ length: SLOTS }, (_, i) => i + 1);
  return (
    <>
      {error && <p className="error">{error}</p>}
      <h2>
        {TEAM_TYPES[team.type_id]} {team.name}
      </h2>
      <h3>Coaches</h3>
      <ul>
        {coaches.map((c) => {
          const on = (c.teams?.split(',') ?? []).includes(String(id));
          return (
            (on || user.role === 'admin') && (
              <li key={c.id}>
                {c.first_name} {c.last_name}
                {user.role === 'admin' && (
                  <form action={coachTeam} className="inline">
                    <input type="hidden" name="id" value={c.id} />
                    <input type="hidden" name="team" value={id} />
                    <input type="hidden" name="on" value={on ? 0 : 1} />{' '}
                    <button>{on ? 'Remove' : 'Assign'}</button>
                  </form>
                )}
              </li>
            )
          );
        })}
      </ul>
      <h3>Line-up</h3>
      <form action={lineup}>
        <input type="hidden" name="team" value={id} />
        <p>
          Formation{' '}
          <select name="formation" defaultValue={team.formation} disabled={!manage}>
            {FORMATIONS.map((f) => (
              <option key={f}>{f}</option>
            ))}
          </select>
        </p>
        <table>
          <tbody>
            {players.map((p) => (
              <tr key={p.id}>
                <td>
                  {p.first_name} {p.last_name}
                </td>
                <td>
                  <select name={`slot-${p.id}`} defaultValue={p.slot ?? ''} disabled={!manage}>
                    <option value="">Not selected</option>
                    {slots.map((s) => (
                      <option key={s} value={s}>
                        {slotLabel(team.formation, s)}
                      </option>
                    ))}
                  </select>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
        {manage && <button>Save line-up</button>}
      </form>
    </>
  );
}
