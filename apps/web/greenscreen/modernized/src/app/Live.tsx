'use client';
import { useEffect, useState, Children, type ReactNode } from 'react';
import { useRouter } from 'next/navigation';
import { formatClock } from '../lib/parse';

// Re-runs the server component (and its cached fetches) every minute.
export function Refresh() {
  const router = useRouter();
  useEffect(() => {
    const id = setInterval(() => router.refresh(), 60_000);
    return () => clearInterval(id);
  }, [router]);
  return null;
}

export function Clock() {
  const [now, setNow] = useState<Date | null>(null);
  useEffect(() => {
    setNow(new Date());
    const id = setInterval(() => setNow(new Date()), 10_000);
    return () => clearInterval(id);
  }, []);
  return <h2 className="clock">{now ? formatClock(now) : ' '}</h2>;
}

// Shows one child at a time, replacing slick.
export function Rotator({ children, seconds }: { children: ReactNode; seconds: number }) {
  const items = Children.toArray(children);
  const [i, setI] = useState(0);
  useEffect(() => {
    const id = setInterval(() => setI((n) => n + 1), seconds * 1000);
    return () => clearInterval(id);
  }, [seconds]);
  return <div>{items[i % Math.max(items.length, 1)]}</div>;
}
