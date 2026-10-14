'use client';

import { useState } from 'react';

const POINTS = 5;

export default function MemoryGame({ deck }: { deck: number[] }) {
  const [open, setOpen] = useState<number[]>([]);
  const [done, setDone] = useState<number[]>([]);
  const [moves, setMoves] = useState(0);
  const [wrong, setWrong] = useState(0);

  function flip(i: number) {
    if (done.includes(i) || open.includes(i)) return;
    // a mismatched pair stays visible until the next click, as in the legacy game
    const cur = open.length === 2 ? [] : open;
    const next = [...cur, i];
    setOpen(next);
    if (next.length < 2) return;
    setMoves(moves + 1);
    if (deck[next[0]!] === deck[i]) {
      setDone([...done, ...next]);
      setOpen([]);
    } else setWrong(wrong + 1);
  }

  return (
    <>
      <p>
        Points: {(done.length / 2) * POINTS} · Moves: {moves} · Mistakes: {wrong}
      </p>
      <div className="board">
        {deck.map((v, i) => (
          <button key={i} onClick={() => flip(i)} aria-label="card">
            <img
              src={`/img/memory/${done.includes(i) ? 'aufgedeckt.jpg' : open.includes(i) ? `${v}.jpg` : 'deck.jpg'}`}
              alt=""
            />
          </button>
        ))}
      </div>
    </>
  );
}
