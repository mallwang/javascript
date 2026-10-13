import Link from 'next/link';
import { notFound } from 'next/navigation';
import { requireUser } from '../../../lib/auth';
import { SCENES } from '../../../lib/scenes';

export default async function Scene({ params }: { params: Promise<{ name: string }> }) {
  await requireUser();
  const scene = SCENES[(await params).name];
  if (!scene) notFound();
  return (
    <>
      <h2>{scene.title}</h2>
      <img src={`/images/${scene.image}`} alt={scene.title} useMap="#scene" />
      <map name="scene">
        {scene.areas.map((a, i) => (
          <area
            key={i}
            shape="rect"
            coords={a.coords}
            alt={a.title}
            title={a.title}
            href={a.work ? `/work/${a.work}` : `/scene/${a.scene}`}
          />
        ))}
      </map>
      <p>
        <Link href="/scene/home">Cave</Link>
      </p>
    </>
  );
}
