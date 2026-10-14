import { makeDeck } from '../../lib/site';
import MemoryGame from './MemoryGame';

export default function Memory() {
  return (
    <>
      <h2>A memory game to warm up...</h2>
      <MemoryGame deck={makeDeck(10)} />
      <a href="/memory">New game</a>
    </>
  );
}
