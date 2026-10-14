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

export const money = (n: number) =>
  n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
