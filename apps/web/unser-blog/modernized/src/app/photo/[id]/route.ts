import { readPhoto } from '../../../lib/photo';

export async function GET(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const data = readPhoto(Number((await params).id));
  if (!data) return Response.redirect(new URL('/images/Userbilder/Dummyuser.jpg', _.url), 302);
  return new Response(new Uint8Array(data), { headers: { 'Content-Type': 'image/jpeg' } });
}
