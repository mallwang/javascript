import { beforeEach, describe, expect, it } from 'vitest';
import * as c from '../src/lib/club';
import { openDb } from '../src/lib/db';
import { canManageTeam, slotLabel, validateEvent, validateLineup } from '../src/lib/rules';

let db: ReturnType<typeof openDb>;
let admin: c.User;
const actor = (id: number) => c.getSession(db, c.createSession(db, id))!;

function setup() {
  db = openDb(':memory:');
  const r = c.registerClub(db, {
    club: 'FC Test',
    first: 'A',
    last: 'Dmin',
    email: 'a@x.de',
    password: 'password1',
  });
  admin = actor((r as { id: number }).id);
  c.createTeam(db, admin, 2, 'a');
  c.createTeam(db, admin, 3, 'b');
}
const person = (role: string, email: string, teamId: number) =>
  c.createPerson(db, admin, {
    role,
    email,
    password: 'password1',
    first: 'F',
    last: 'L',
    teamId,
    birthdate: '',
  });

beforeEach(setup);

describe('rules', () => {
  it('labels slots per formation', () => {
    expect(slotLabel('4-3-3', 1)).toBe('GK');
    expect(slotLabel('4-3-3', 5)).toBe('D4');
    expect(slotLabel('4-3-3', 6)).toBe('M1');
    expect(slotLabel('4-3-3', 11)).toBe('F3');
    expect(slotLabel('4-3-3', 12)).toBe('Sub 1');
  });
  it('rejects duplicate slots, duplicate players and unknown formations', () => {
    expect(
      validateLineup('4-4-2', [
        { playerId: 1, slot: 1 },
        { playerId: 2, slot: 1 },
      ]),
    ).toBe('Position used twice');
    expect(
      validateLineup('4-4-2', [
        { playerId: 1, slot: 1 },
        { playerId: 1, slot: 2 },
      ]),
    ).toBe('Player used twice');
    expect(validateLineup('9-9-9', [])).toBe('Unknown formation');
    expect(validateLineup('4-4-2', [{ playerId: 1, slot: 17 }])).toBe('Invalid position');
  });
  it('validates events', () => {
    const e = { type: 'match', title: 'x', start: '2026-01-01T10:00', end: '2026-01-01T09:00' };
    expect(validateEvent(e)).toBe('End is before start');
    expect(validateEvent({ ...e, end: '2026-01-01T12:00' })).toBeUndefined();
  });
  it('lets coaches manage only their teams', () => {
    const coach = { id: 2, role: 'coach' as const, clubId: 1, teamIds: [5] };
    expect(canManageTeam(coach, 5)).toBe(true);
    expect(canManageTeam(coach, 6)).toBe(false);
  });
});

describe('club', () => {
  it('registers, authenticates and expires sessions', () => {
    expect(
      c.registerClub(db, {
        club: 'X',
        first: 'a',
        last: 'b',
        email: 'A@x.de',
        password: 'password1',
      }),
    ).toHaveProperty('error');
    expect(c.authenticate(db, 'A@X.de', 'password1')).toHaveProperty('id');
    expect(c.authenticate(db, 'a@x.de', 'wrong')).toHaveProperty('error');
    const token = c.createSession(db, admin.id, 0);
    expect(c.getSession(db, token, 1)).toBeDefined();
    expect(c.getSession(db, token, 8 * 24 * 3600 * 1000)).toBeUndefined();
  });

  it('only admins manage people and teams', () => {
    const [t1] = c.listTeams(db, admin.clubId);
    expect(person('coach', 'c@x.de', t1.id)).toBeUndefined();
    const coach = actor(c.listCoaches(db, admin.clubId)[0].id);
    expect(c.createTeam(db, coach, 1, '')).toBe('Not allowed');
    expect(c.deletePerson(db, coach, admin.id)).toBe('Not allowed');
  });

  it('keeps clubs isolated', () => {
    const other = actor(
      (
        c.registerClub(db, {
          club: 'Other',
          first: 'O',
          last: 'O',
          email: 'o@x.de',
          password: 'password1',
        }) as { id: number }
      ).id,
    );
    const [t1] = c.listTeams(db, admin.clubId);
    expect(c.deleteTeam(db, other, t1.id)).toBeUndefined(); // no-op, scoped to own club
    expect(c.listTeams(db, admin.clubId)).toHaveLength(2);
    expect(
      c.createEvent(db, other, {
        teamId: t1.id,
        type: 'training',
        title: 't',
        location: '',
        opponent: '',
        start: '2026-01-01T10:00',
        end: '2026-01-01T11:00',
      }),
    ).toBe('Not allowed');
  });

  it('scopes coaches to assigned teams and players to their own entries', () => {
    const [t1, t2] = c.listTeams(db, admin.clubId);
    person('coach', 'c@x.de', t1.id);
    person('player', 'p@x.de', t1.id);
    person('player', 'q@x.de', t1.id);
    const coach = actor(c.listCoaches(db, admin.clubId)[0].id);
    const [p1, p2] = c.listPlayers(db, admin.clubId, t1.id);
    const ev = (a: c.User, team: number) =>
      c.createEvent(db, a, {
        teamId: team,
        type: 'training',
        title: 't',
        location: '',
        opponent: '',
        start: '2026-01-01T10:00',
        end: '2026-01-01T11:00',
      });
    expect(ev(coach, t1.id)).toBeUndefined();
    expect(ev(coach, t2.id)).toBe('Not allowed');
    const event = c.listEvents(db, admin)[0];
    expect(c.saveAttendance(db, coach, event.id, { [p1.id]: '1', [p2.id]: '2' })).toBeUndefined();
    expect(c.saveAttendance(db, coach, event.id, { [p1.id]: '7' })).toBe(
      'Invalid attendance entry',
    );
    const player = actor(p1.id);
    expect(c.listAttendance(db, player, event.id)).toEqual([{ user_id: p1.id, value: 1 }]);
    expect(c.saveAttendance(db, player, event.id, { [p1.id]: '1' })).toBe('Not allowed');
    expect(c.deleteEvent(db, player, event.id)).toBe('Not allowed');
  });

  it('saves a line-up only for players of the team', () => {
    const [t1, t2] = c.listTeams(db, admin.clubId);
    person('player', 'p@x.de', t1.id);
    person('player', 'q@x.de', t2.id);
    const [p1] = c.listPlayers(db, admin.clubId, t1.id);
    const [p2] = c.listPlayers(db, admin.clubId, t2.id);
    expect(c.saveLineup(db, admin, t1.id, '4-4-2', [{ playerId: p2.id, slot: 1 }])).toBe(
      'Player is not in this team',
    );
    expect(c.saveLineup(db, admin, t1.id, '4-4-2', [{ playerId: p1.id, slot: 1 }])).toBeUndefined();
    expect(c.listPlayers(db, admin.clubId, t1.id)[0].slot).toBe(1);
  });

  it('records results and tournament matches', () => {
    const [t1] = c.listTeams(db, admin.clubId);
    const mk = (type: string) =>
      c.createEvent(db, admin, {
        teamId: t1.id,
        type,
        title: type,
        location: '',
        opponent: 'Foes',
        start: '2026-01-01T10:00',
        end: '2026-01-01T18:00',
      });
    mk('match');
    mk('tournament');
    const [m, t] = c.listEvents(db, admin);
    expect(c.setResult(db, admin, m.id, '3', '1')).toBeUndefined();
    expect(c.setResult(db, admin, m.id, 'x', '1')).toContain('whole numbers');
    expect(c.setResult(db, admin, t.id, '1', '1')).toBe('Not allowed'); // not a match
    expect(c.addTournamentMatch(db, admin, t.id, 'Bears', '2026-02-01T10:00')).toContain('within');
    expect(c.addTournamentMatch(db, admin, t.id, 'Bears', '2026-01-01T12:00')).toBeUndefined();
    const [tm] = c.listTournamentMatches(db, t.id);
    expect(c.setTournamentResult(db, admin, tm.id, '2', '2')).toBeUndefined();
  });

  it('deleting the admin account deletes the club', () => {
    expect(c.deleteAccount(db, admin, 'nope')).toBe('Password is wrong');
    expect(c.deleteAccount(db, admin, 'password1')).toBeUndefined();
    expect(c.listTeams(db, admin.clubId)).toHaveLength(0);
  });
});
