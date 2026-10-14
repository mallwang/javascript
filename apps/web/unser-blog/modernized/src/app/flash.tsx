export type SP = Promise<{ error?: string; ok?: string }>;

export async function Flash({ sp }: { sp: SP }) {
  const { error, ok } = await sp;
  return (
    <>
      {error && <p className="error">{error}</p>}
      {ok && <p className="ok">{ok}</p>}
    </>
  );
}
