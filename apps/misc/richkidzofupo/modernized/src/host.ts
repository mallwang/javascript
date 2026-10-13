/** Title text for the placeholder page, e.g. "Internet Seite: - example.com -". */
export const pageTitle = (host: string): string => `Internet Seite: - ${host} -`;

/** Writes the host name into the page. Uses textContent, so the host is never parsed as HTML. */
export function renderHost(doc: Document, host: string): void {
  doc.title = pageTitle(host);
  const el = doc.getElementById('host');
  if (el) el.textContent = host;
}
