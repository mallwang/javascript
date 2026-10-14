import nodemailer from 'nodemailer';

export const baseUrl = () => process.env.BASE_URL ?? 'http://localhost:3000';

/**
 * Plain-text mail via SMTP; without SMTP_HOST the message is printed to the console (development).
 * Failures are only logged, so the caller's response does not reveal whether an address exists.
 */
export async function sendMail(to: string, subject: string, text: string): Promise<void> {
  const host = process.env.SMTP_HOST;
  if (!host) {
    console.log(`[mail] to=${to} subject=${subject}\n${text}`);
    return;
  }
  try {
    const user = process.env.SMTP_USER;
    await nodemailer
      .createTransport({
        host,
        port: Number(process.env.SMTP_PORT ?? 587),
        secure: process.env.SMTP_SECURE === 'true',
        auth: user ? { user, pass: process.env.SMTP_PASSWORD } : undefined,
      })
      .sendMail({ from: process.env.MAIL_FROM ?? user, to, subject, text });
  } catch (e) {
    console.error('[mail] sending failed:', (e as Error).message);
  }
}
