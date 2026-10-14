import { sendSupport } from '../actions';

export default async function Support({
  searchParams,
}: {
  searchParams: Promise<{ error?: string; ok?: string }>;
}) {
  const { error, ok } = await searchParams;
  return (
    <>
      <h2>Support</h2>
      {error && <p className="error">{error}</p>}
      {ok && <p className="ok">{ok}</p>}
      <form action={sendSupport} className="row">
        <input name="subject" placeholder="Subject" required />
        <input name="email" type="email" placeholder="Your e-mail" required />
        <textarea name="text" placeholder="Message" rows={5} cols={50} required />
        <button>Send</button>
      </form>
    </>
  );
}
