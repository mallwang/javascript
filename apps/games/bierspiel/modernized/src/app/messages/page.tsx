import { requireUser } from '../../lib/auth';
import { getDb } from '../../lib/db';
import { inbox, outbox, type Message } from '../../lib/messages';
import { deleteMessage, sendMessage } from '../actions';
import { Flash, type SP } from '../flash';

function List({
  title,
  rows,
  peer,
}: {
  title: string;
  rows: Message[];
  peer: 'from_name' | 'to_name';
}) {
  return (
    <>
      <h2>{title}</h2>
      {!rows.length && <p>Empty.</p>}
      {rows.map((m) => (
        <article key={m.id}>
          <h4>
            {m.subject} — {m[peer]}, {new Date(m.sent_at).toLocaleString('en-GB')}
          </h4>
          <p style={{ whiteSpace: 'pre-wrap' }}>{m.text}</p>
          <form action={deleteMessage}>
            <input type="hidden" name="id" value={m.id} />
            <button>Delete</button>
          </form>
        </article>
      ))}
    </>
  );
}

export default async function Messages({ searchParams }: { searchParams: SP }) {
  const u = await requireUser();
  const db = getDb();
  return (
    <>
      <Flash sp={searchParams} />
      <h2>New message</h2>
      <form action={sendMessage} className="row">
        <input name="to" placeholder="Recipient nickname" required />
        <input name="subject" placeholder="Subject" maxLength={100} required />
        <textarea name="text" maxLength={2000} required />
        <button>Send</button>
      </form>
      <List title="Inbox" rows={inbox(db, u.id)} peer="from_name" />
      <List title="Outbox" rows={outbox(db, u.id)} peer="to_name" />
    </>
  );
}
