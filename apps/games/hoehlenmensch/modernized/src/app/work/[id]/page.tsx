import { notFound } from 'next/navigation';
import { requireUser } from '../../../lib/auth';
import { cost, CRAFT_DURATION, DURATIONS, activity } from '../../../lib/game';
import { work } from '../../actions';

export default async function Work({
  params,
  searchParams,
}: {
  params: Promise<{ id: string }>;
  searchParams: Promise<{ error?: string }>;
}) {
  await requireUser();
  const id = Number((await params).id);
  const a = activity(id);
  if (!a) notFound();
  const { error } = await searchParams;
  const durations = a.item ? [CRAFT_DURATION] : DURATIONS;
  return (
    <>
      <h2>{a.name}</h2>
      {error && <p className="error">{error}</p>}
      <form action={work}>
        <input type="hidden" name="id" value={id} />
        {durations.map((d) => (
          <button key={d} name="duration" value={d}>
            {d} min (food {cost(d).food}, water {cost(d).water})
          </button>
        ))}
      </form>
    </>
  );
}
