export const DAYS = 24;

/** A door opens only in the required month (0 = January) and from its day number on. */
export function isLocked(day: number, now: Date, requiredMonth: number): boolean {
  return !(now.getMonth() === requiredMonth && now.getDate() >= day);
}

export function lockedMessage(now: Date, requiredMonth: number): string {
  if (now.getMonth() !== requiredMonth) {
    const month = new Intl.DateTimeFormat('de', { month: 'long' }).format(
      new Date(2000, requiredMonth, 1),
    );
    return `Du musst bis ${month} warten, ehe du das Päckchen öffnen darfst...`;
  }
  return 'Nur noch ein paar Tage schlafen...';
}

/** Fisher-Yates; the legacy `sort(() => Math.random() - 0.5)` is biased. */
export function shuffledDays(random: () => number = Math.random): number[] {
  const days = Array.from({ length: DAYS }, (_, i) => i + 1);
  for (let i = days.length - 1; i > 0; i--) {
    const j = Math.floor(random() * (i + 1));
    [days[i], days[j]] = [days[j], days[i]];
  }
  return days;
}
