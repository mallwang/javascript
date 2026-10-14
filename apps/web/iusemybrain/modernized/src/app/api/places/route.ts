import { getDb } from '../../../lib/db';
import { listPlaces, placesXml } from '../../../lib/site';

/** GET /api/places?city=&category=sight|park|museum - XML for the Android app. */
export function GET(req: Request) {
  const q = new URL(req.url).searchParams;
  const xml = placesXml(listPlaces(getDb(), q.get('city') ?? '', q.get('category') ?? ''));
  return new Response(xml, { headers: { 'content-type': 'application/xml; charset=utf-8' } });
}
