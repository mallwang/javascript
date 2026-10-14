/** Expected, user-facing failure (shown as a flash message, not a crash). */
export class AppError extends Error {}

export function assert(cond: unknown, message: string): asserts cond {
  if (!cond) throw new AppError(message);
}
