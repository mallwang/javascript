import { createHash, randomBytes } from 'node:crypto';
import type { Database } from 'better-sqlite3';
import { hashPassword, verifyPassword } from './password';
import {
  type Actor,
  type Role,
  EMAIL,
  FORMATIONS,
  canManageTeam,
  parseAttendance,
  parseGoals,
  validateEvent,
  validateLineup,
} from './rules';

const SESSION_MS = 7 * 24 * 60 * 60 * 1000;
const sha256 = (s: string) => createHash('sha256').update(s).digest('hex');
type Err = string | undefined;
type Row = Record<string, unknown>;

function checkAccount(email: string, password: string, first: string, last: string): Err {
  if (!first.trim() || !last.trim()) return 'First and last name are required';
  if (!EMAIL.test(email)) return 'Invalid e-mail address';
  if (password.length < 8) return 'Password must be at least 8 characters';
}

function insertUser(
  db: Database,
  role: Role,
  clubId: number,
  email: string,
  password: string,
  first: string,
  last: string,
) {
  return Number(
    db
      .prepare(
        'INSERT INTO users (email, password, role, club_id, first_name, last_name) VALUES (?,?,?,?,?,?)',
      )
      .run(
        email.trim().toLowerCase(),
        hashPassword(password),
        role,
        clubId,
        first.trim(),
        last.trim(),
      ).lastInsertRowid,
  );
}

const emailTaken = (db: Database, email: string) =>
  !!db.prepare('SELECT 1 FROM users WHERE email = ?').get(email.trim().toLowerCase());

// --- accounts and sessions ---

export function registerClub(
  db: Database,
  i: { club: string; first: string; last: string; email: string; password: string },
): { error: string } | { id: number } {
  const error = checkAccount(i.email, i.password, i.first, i.last);
  if (error) return { error };
  if (!i.club.trim()) return { error: 'Club name is required' };
  if (emailTaken(db, i.email)) return { error: 'E-mail address already registered' };
  return db.transaction(() => {
    const clubId = Number(
      db.prepare('INSERT INTO clubs (name) VALUES (?)').run(i.club.trim()).lastInsertRowid,
    );
    return { id: insertUser(db, 'admin', clubId, i.email, i.password, i.first, i.last) };
  })();
}

export function authenticate(
  db: Database,
  email: string,
  password: string,
): { error: string } | { id: number } {
  const u = db
    .prepare('SELECT id, password FROM users WHERE email = ?')
    .get(email.trim().toLowerCase()) as { id: number; password: string } | undefined;
  // same message for unknown user and wrong password
  return u && verifyPassword(password, u.password)
    ? { id: u.id }
    : { error: 'Invalid e-mail or password' };
}

export function createSession(db: Database, userId: number, now = Date.now()) {
  const token = randomBytes(32).toString('hex');
  db.prepare('DELETE FROM sessions WHERE expires_at < ?').run(now);
  db.prepare('INSERT INTO sessions VALUES (?, ?, ?)').run(sha256(token), userId, now + SESSION_MS);
  return token;
}

export function deleteSession(db: Database, token: string) {
  db.prepare('DELETE FROM sessions WHERE token_hash = ?').run(sha256(token));
}

export type User = Actor & { name: string; clubName: string };

export function getSession(db: Database, token: string, now = Date.now()): User | undefined {
  const u = db
    .prepare(
      `SELECT u.id, u.role, u.club_id, u.first_name || ' ' || u.last_name AS name, c.name AS club
       FROM sessions s JOIN users u ON u.id = s.user_id JOIN clubs c ON c.id = u.club_id
       WHERE s.token_hash = ? AND s.expires_at > ?`,
    )
    .get(sha256(token), now) as Row | undefined;
  if (!u) return undefined;
  const id = u.id as number;
  const teamIds = (
    db
      .prepare(
        `SELECT team_id FROM team_coaches WHERE user_id = ?
         UNION SELECT team_id FROM players WHERE user_id = ? AND team_id IS NOT NULL`,
      )
      .all(id, id) as { team_id: number }[]
  ).map((r) => r.team_id);
  return {
    id,
    role: u.role as Role,
    clubId: u.club_id as number,
    teamIds,
    name: u.name as string,
    clubName: u.club as string,
  };
}

export function changePassword(db: Database, userId: number, oldPw: string, newPw: string): Err {
  if (newPw.length < 8) return 'Password must be at least 8 characters';
  const u = db.prepare('SELECT password FROM users WHERE id = ?').get(userId) as {
    password: string;
  };
  if (!verifyPassword(oldPw, u.password)) return 'Current password is wrong';
  db.prepare('UPDATE users SET password = ? WHERE id = ?').run(hashPassword(newPw), userId);
  db.prepare('DELETE FROM sessions WHERE user_id = ?').run(userId); // log out everywhere
}

export function changeEmail(db: Database, userId: number, email: string): Err {
  if (!EMAIL.test(email)) return 'Invalid e-mail address';
  if (emailTaken(db, email)) return 'E-mail address already registered';
  db.prepare('UPDATE users SET email = ? WHERE id = ?').run(email.trim().toLowerCase(), userId);
}

/** Deleting an admin deletes the whole club (cascade); the legacy 3-day grace period is dropped. */
export function deleteAccount(db: Database, a: Actor, password: string): Err {
  const u = db.prepare('SELECT password FROM users WHERE id = ?').get(a.id) as { password: string };
  if (!verifyPassword(password, u.password)) return 'Password is wrong';
  if (a.role === 'admin') db.prepare('DELETE FROM clubs WHERE id = ?').run(a.clubId);
  else db.prepare('DELETE FROM users WHERE id = ?').run(a.id);
}

// --- club, teams, people (admin only) ---

const deny: Err = 'Not allowed';
const isAdmin = (a: Actor) => a.role === 'admin';

export function updateClub(
  db: Database,
  a: Actor,
  c: { name: string; short: string; long: string; history: string },
): Err {
  if (!isAdmin(a)) return deny;
  if (!c.name.trim()) return 'Club name is required';
  db.prepare(
    'UPDATE clubs SET name = ?, short_text = ?, long_text = ?, history = ? WHERE id = ?',
  ).run(c.name.trim(), c.short, c.long, c.history, a.clubId);
}

export const getClub = (db: Database, clubId: number) =>
  db
    .prepare('SELECT name, short_text, long_text, history FROM clubs WHERE id = ?')
    .get(clubId) as Row;

export const listTeams = (db: Database, clubId: number) =>
  db
    .prepare(
      'SELECT id, name, type_id, formation FROM teams WHERE club_id = ? ORDER BY type_id, name',
    )
    .all(clubId) as {
    id: number;
    name: string;
    type_id: number;
    formation: string;
  }[];

export const getTeam = (db: Database, clubId: number, id: number) =>
  listTeams(db, clubId).find((t) => t.id === id);

export function createTeam(db: Database, a: Actor, typeId: number, name: string): Err {
  if (!isAdmin(a)) return deny;
  if (!Number.isInteger(typeId) || typeId < 0 || typeId > 8) return 'Invalid team type';
  db.prepare('INSERT INTO teams (club_id, name, type_id) VALUES (?,?,?)').run(
    a.clubId,
    name.trim(),
    typeId,
  );
}

export function deleteTeam(db: Database, a: Actor, teamId: number): Err {
  if (!isAdmin(a)) return deny;
  db.prepare('DELETE FROM teams WHERE id = ? AND club_id = ?').run(teamId, a.clubId);
}

export function createPerson(
  db: Database,
  a: Actor,
  p: {
    role: string;
    email: string;
    password: string;
    first: string;
    last: string;
    teamId: number;
    birthdate: string;
  },
): Err {
  if (!isAdmin(a)) return deny;
  if (p.role !== 'coach' && p.role !== 'player') return 'Invalid role';
  const error = checkAccount(p.email, p.password, p.first, p.last);
  if (error) return error;
  if (emailTaken(db, p.email)) return 'E-mail address already registered';
  if (p.teamId && !getTeam(db, a.clubId, p.teamId)) return 'Unknown team';
  db.transaction(() => {
    const id = insertUser(db, p.role as Role, a.clubId, p.email, p.password, p.first, p.last);
    if (p.role === 'player')
      db.prepare('INSERT INTO players (user_id, team_id, birthdate) VALUES (?,?,?)').run(
        id,
        p.teamId || null,
        p.birthdate,
      );
    else if (p.teamId) db.prepare('INSERT INTO team_coaches VALUES (?,?)').run(id, p.teamId);
  })();
}

export function deletePerson(db: Database, a: Actor, userId: number): Err {
  if (!isAdmin(a)) return deny;
  if (userId === a.id) return 'Use the profile page to delete your own account';
  db.prepare('DELETE FROM users WHERE id = ? AND club_id = ?').run(userId, a.clubId);
}

export function assignCoach(
  db: Database,
  a: Actor,
  userId: number,
  teamId: number,
  on: boolean,
): Err {
  if (!isAdmin(a)) return deny;
  const ok = db
    .prepare(
      `SELECT 1 FROM users u, teams t WHERE u.id = ? AND u.role = 'coach' AND u.club_id = ?
       AND t.id = ? AND t.club_id = ?`,
    )
    .get(userId, a.clubId, teamId, a.clubId);
  if (!ok) return 'Unknown coach or team';
  if (on) db.prepare('INSERT OR IGNORE INTO team_coaches VALUES (?,?)').run(userId, teamId);
  else db.prepare('DELETE FROM team_coaches WHERE user_id = ? AND team_id = ?').run(userId, teamId);
}

export function movePlayer(db: Database, a: Actor, userId: number, teamId: number): Err {
  if (!isAdmin(a)) return deny;
  if (teamId && !getTeam(db, a.clubId, teamId)) return 'Unknown team';
  db.prepare(
    `UPDATE players SET team_id = ?, slot = NULL
     WHERE user_id = ? AND user_id IN (SELECT id FROM users WHERE club_id = ?)`,
  ).run(teamId || null, userId, a.clubId);
}

export const listCoaches = (db: Database, clubId: number) =>
  db
    .prepare(
      `SELECT u.id, u.first_name, u.last_name, u.email,
        (SELECT group_concat(team_id) FROM team_coaches WHERE user_id = u.id) AS teams
       FROM users u WHERE u.club_id = ? AND u.role = 'coach' ORDER BY u.last_name, u.first_name`,
    )
    .all(clubId) as {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    teams: string | null;
  }[];

export const listPlayers = (db: Database, clubId: number, teamId?: number) =>
  db
    .prepare(
      `SELECT u.id, u.first_name, u.last_name, u.email, p.team_id, p.birthdate, p.slot
       FROM players p JOIN users u ON u.id = p.user_id
       WHERE u.club_id = ? AND (? IS NULL OR p.team_id = ?) ORDER BY u.last_name, u.first_name`,
    )
    .all(clubId, teamId ?? null, teamId ?? null) as {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    team_id: number | null;
    birthdate: string;
    slot: number | null;
  }[];

// --- line-up ---

export function saveLineup(
  db: Database,
  a: Actor,
  teamId: number,
  formation: string,
  picks: { playerId: number; slot: number }[],
): Err {
  if (!canManageTeam(a, teamId) || !getTeam(db, a.clubId, teamId)) return deny;
  if (!FORMATIONS.includes(formation)) return 'Unknown formation';
  const error = validateLineup(formation, picks);
  if (error) return error;
  const members = new Set(listPlayers(db, a.clubId, teamId).map((p) => p.id));
  if (picks.some((p) => !members.has(p.playerId))) return 'Player is not in this team';
  db.transaction(() => {
    db.prepare('UPDATE players SET slot = NULL WHERE team_id = ?').run(teamId);
    const set = db.prepare('UPDATE players SET slot = ? WHERE user_id = ?');
    for (const p of picks) set.run(p.slot, p.playerId);
    db.prepare('UPDATE teams SET formation = ? WHERE id = ?').run(formation, teamId);
  })();
}

// --- events, results, attendance ---

export interface EventRow {
  id: number;
  team_id: number;
  type: string;
  title: string;
  location: string;
  opponent: string;
  start_at: string;
  end_at: string;
  team_goals: number | null;
  opponent_goals: number | null;
}

/** Players only see their own team's events; admins and coaches see the whole club. */
export function listEvents(db: Database, a: Actor, type?: string) {
  const scope = a.role === 'player' ? `AND team_id IN (${a.teamIds.join(',') || 'NULL'})` : '';
  return db
    .prepare(
      `SELECT * FROM events WHERE club_id = ? ${scope} AND (? IS NULL OR type = ?) ORDER BY start_at`,
    )
    .all(a.clubId, type ?? null, type ?? null) as EventRow[];
}

export function getEvent(db: Database, a: Actor, id: number) {
  const e = db.prepare('SELECT * FROM events WHERE id = ? AND club_id = ?').get(id, a.clubId) as
    EventRow | undefined;
  return e && (a.role !== 'player' || a.teamIds.includes(e.team_id)) ? e : undefined;
}

export function createEvent(
  db: Database,
  a: Actor,
  e: {
    teamId: number;
    type: string;
    title: string;
    location: string;
    opponent: string;
    start: string;
    end: string;
  },
): Err {
  if (!canManageTeam(a, e.teamId) || !getTeam(db, a.clubId, e.teamId)) return deny;
  const error = validateEvent(e);
  if (error) return error;
  db.prepare(
    'INSERT INTO events (club_id, team_id, type, title, location, opponent, start_at, end_at) VALUES (?,?,?,?,?,?,?,?)',
  ).run(a.clubId, e.teamId, e.type, e.title.trim(), e.location, e.opponent, e.start, e.end);
}

/** Loads an event the actor may manage. */
function managedEvent(db: Database, a: Actor, id: number) {
  const e = getEvent(db, a, id);
  return e && canManageTeam(a, e.team_id) ? e : undefined;
}

export function deleteEvent(db: Database, a: Actor, id: number): Err {
  if (!managedEvent(db, a, id)) return deny;
  db.prepare('DELETE FROM events WHERE id = ?').run(id);
}

export function setResult(db: Database, a: Actor, id: number, goals: string, opp: string): Err {
  const e = managedEvent(db, a, id);
  if (!e || e.type !== 'match') return deny;
  const [g, o] = [parseGoals(goals), parseGoals(opp)];
  if (g === undefined || o === undefined) return 'Goals must be whole numbers from 0 to 99';
  db.prepare('UPDATE events SET team_goals = ?, opponent_goals = ? WHERE id = ?').run(g, o, id);
}

export const listTournamentMatches = (db: Database, eventId: number) =>
  db
    .prepare('SELECT * FROM tournament_matches WHERE event_id = ? ORDER BY start_at')
    .all(eventId) as {
    id: number;
    opponent: string;
    start_at: string;
    team_goals: number | null;
    opponent_goals: number | null;
  }[];

export function addTournamentMatch(
  db: Database,
  a: Actor,
  eventId: number,
  opponent: string,
  start: string,
): Err {
  const e = managedEvent(db, a, eventId);
  if (!e || e.type !== 'tournament') return deny;
  if (!opponent.trim()) return 'Opponent is required';
  if (start < e.start_at || start > e.end_at) return 'Match start must be within the tournament';
  db.prepare('INSERT INTO tournament_matches (event_id, opponent, start_at) VALUES (?,?,?)').run(
    eventId,
    opponent.trim(),
    start,
  );
}

export function setTournamentResult(
  db: Database,
  a: Actor,
  matchId: number,
  goals: string,
  opp: string,
): Err {
  const m = db.prepare('SELECT event_id FROM tournament_matches WHERE id = ?').get(matchId) as
    { event_id: number } | undefined;
  if (!m || !managedEvent(db, a, m.event_id)) return deny;
  const [g, o] = [parseGoals(goals), parseGoals(opp)];
  if (g === undefined || o === undefined) return 'Goals must be whole numbers from 0 to 99';
  db.prepare('UPDATE tournament_matches SET team_goals = ?, opponent_goals = ? WHERE id = ?').run(
    g,
    o,
    matchId,
  );
}

export function deleteTournamentMatch(db: Database, a: Actor, matchId: number): Err {
  const m = db.prepare('SELECT event_id FROM tournament_matches WHERE id = ?').get(matchId) as
    { event_id: number } | undefined;
  if (!m || !managedEvent(db, a, m.event_id)) return deny;
  db.prepare('DELETE FROM tournament_matches WHERE id = ?').run(matchId);
}

export function saveAttendance(
  db: Database,
  a: Actor,
  eventId: number,
  values: Record<number, string>,
): Err {
  const e = managedEvent(db, a, eventId);
  if (!e) return deny;
  const members = new Set(listPlayers(db, a.clubId, e.team_id).map((p) => p.id));
  const rows: [number, number][] = [];
  for (const [id, v] of Object.entries(values)) {
    const n = parseAttendance(v);
    if (n === undefined || !members.has(Number(id))) return 'Invalid attendance entry';
    rows.push([Number(id), n]);
  }
  const up = db.prepare(
    'INSERT INTO attendance VALUES (?,?,?) ON CONFLICT (event_id, user_id) DO UPDATE SET value = excluded.value',
  );
  db.transaction(() => rows.forEach(([id, n]) => up.run(eventId, id, n)))();
}

/** Players only see their own entry. */
export function listAttendance(db: Database, a: Actor, eventId: number) {
  return db
    .prepare(
      "SELECT user_id, value FROM attendance WHERE event_id = ? AND (? <> 'player' OR user_id = ?)",
    )
    .all(eventId, a.role, a.id) as { user_id: number; value: number }[];
}
