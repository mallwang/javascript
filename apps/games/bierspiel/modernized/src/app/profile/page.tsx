import { requireUser } from '../../lib/auth';
import { changeEmail, changePassword, renameCompany, requestDeletion } from '../actions';
import { Flash, type SP } from '../flash';

export default async function Profile({ searchParams }: { searchParams: SP }) {
  const u = await requireUser();
  return (
    <>
      <Flash sp={searchParams} />
      <h2>{u.nickname}</h2>
      <h3>Company name</h3>
      <form action={renameCompany} className="row">
        <input name="name" defaultValue={u.companyName} required />
        <button>Rename</button>
      </form>
      <h3>E-mail</h3>
      <form action={changeEmail} className="row">
        <input name="email" type="email" placeholder="New e-mail" required />
        <input name="password" type="password" placeholder="Password" required />
        <button>Change</button>
      </form>
      <h3>Password</h3>
      <form action={changePassword} className="row">
        <input name="current" type="password" placeholder="Current password" required />
        <input
          name="password"
          type="password"
          placeholder="New password (min. 8)"
          minLength={8}
          required
        />
        <button>Change</button>
      </form>
      <h3>Delete account</h3>
      <form action={requestDeletion} className="row">
        <input name="password" type="password" placeholder="Password" required />
        <button>Request deletion</button>
      </form>
    </>
  );
}
