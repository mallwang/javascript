export type Search = Promise<{ error?: string; ok?: string }>;

export async function Msg({ search }: { search: Search }) {
  const { error, ok } = await search;
  return (
    <>
      {error && <p className="error">{error}</p>}
      {ok && <p className="ok">{ok}</p>}
    </>
  );
}
