export type Role = 'admin' | 'coach' | 'player';
export const EVENT_TYPES = ['training', 'match', 'tournament', 'other'] as const;
export type EventType = (typeof EVENT_TYPES)[number];

// Legacy convertTeamTypeID (1-9), German age groups with English labels.
export const TEAM_TYPES = [
  '1st team',
  '2nd team',
  'A juniors (U19)',
  'B juniors (U17)',
  'C juniors (U15)',
  'D juniors (U13)',
  'E juniors (U11)',
  'F juniors (U9)',
  'Hobby',
];

// Legacy offered 9 layouts; 8 and 9 repeated 5-3-2 and 5-4-1 with other pitch positions.
export const FORMATIONS = ['4-3-3', '4-4-2', '3-4-3', '4-5-1', '5-3-2', '5-4-1', '3-5-2'];
export const FIELD_SLOTS = 11;
export const SLOTS = 16; // 11 on the pitch + 5 reserves

export const ATTENDANCE = ['absent', 'present', 'excused'] as const;

export const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
export const DATE_TIME = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;

export interface Actor {
  id: number;
  role: Role;
  clubId: number;
  teamIds: number[]; // teams a coach is assigned to, or the player's team
}

/** Admins manage every team of their club, coaches only their assigned teams. */
export function canManageTeam(a: Actor, teamId: number): boolean {
  return a.role === 'admin' || (a.role === 'coach' && a.teamIds.includes(teamId));
}

/** Label of a slot (1-based) in a formation, e.g. 4-3-3: GK, D1-D4, M1-M3, F1-F3, then reserves. */
export function slotLabel(formation: string, slot: number): string {
  if (slot > FIELD_SLOTS) return `Sub ${slot - FIELD_SLOTS}`;
  if (slot === 1) return 'GK';
  const [d, m] = formation.split('-').map(Number);
  if (slot <= 1 + d) return `D${slot - 1}`;
  if (slot <= 1 + d + m) return `M${slot - 1 - d}`;
  return `F${slot - 1 - d - m}`;
}

/** Returns an error message or undefined. Players and slots must be unique. */
export function validateLineup(formation: string, picks: { playerId: number; slot: number }[]) {
  if (!FORMATIONS.includes(formation)) return 'Unknown formation';
  const slots = new Set<number>();
  const players = new Set<number>();
  for (const { playerId, slot } of picks) {
    if (!Number.isInteger(slot) || slot < 1 || slot > SLOTS) return 'Invalid position';
    if (slots.has(slot)) return 'Position used twice';
    if (players.has(playerId)) return 'Player used twice';
    slots.add(slot);
    players.add(playerId);
  }
}

export function parseGoals(v: string): number | null | undefined {
  if (v.trim() === '') return null;
  const n = Number(v);
  return Number.isInteger(n) && n >= 0 && n <= 99 ? n : undefined;
}

export function parseAttendance(v: string): number | undefined {
  const n = Number(v);
  return Number.isInteger(n) && n >= 0 && n < ATTENDANCE.length ? n : undefined;
}

export function validateEvent(e: {
  type: string;
  title: string;
  start: string;
  end: string;
}): string | undefined {
  if (!EVENT_TYPES.includes(e.type as EventType)) return 'Invalid type';
  if (!e.title.trim()) return 'Title is required';
  if (!DATE_TIME.test(e.start) || !DATE_TIME.test(e.end)) return 'Invalid date';
  if (e.end < e.start) return 'End is before start'; // ISO strings compare chronologically
}
