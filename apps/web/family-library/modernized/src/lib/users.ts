import type { Role, SessionUser } from './auth';
import type { Db } from './db';

export type UserRow = {
  id: number;
  firstName: string;
  lastName: string;
  email: string;
  role: Role;
  verified: boolean;
};

export const listUsers = (db: Db) =>
  (
    db
      .prepare(
        `SELECT id, first_name AS firstName, last_name AS lastName, email, role, verified
         FROM user ORDER BY last_name, first_name`,
      )
      .all() as (Omit<UserRow, 'verified'> & { verified: number })[]
  ).map((u) => ({ ...u, verified: u.verified === 1 }));

// Admins cannot change or delete themselves, so the last admin can never be removed.
export function setRole(db: Db, actor: SessionUser, id: number, role: Role): boolean {
  if (actor.role !== 'admin' || actor.id === id) return false;
  return db.prepare('UPDATE user SET role = ? WHERE id = ?').run(role, id).changes > 0;
}

export function deleteUser(db: Db, actor: SessionUser, id: number): boolean {
  if (actor.role !== 'admin' || actor.id === id) return false;
  return db.prepare('DELETE FROM user WHERE id = ?').run(id).changes > 0;
}
