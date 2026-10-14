// Notices are passed between pages as codes (?e=... / ?ok=...), never as free text.
export const ERRORS: Record<string, string> = {
  invalid_name: 'Bitte Vor- und Nachname angeben (max. 100 Zeichen).',
  invalid_email: 'Ungültige E-Mail-Adresse.',
  invalid_password: 'Das Passwort muss 8 bis 128 Zeichen lang sein.',
  credentials: 'E-Mail oder Passwort falsch, oder die E-Mail-Adresse ist noch nicht bestätigt.',
  too_many: 'Zu viele Versuche. Bitte später erneut versuchen.',
  invalid_token: 'Der Link ist ungültig oder abgelaufen.',
  forbidden: 'Dafür fehlt die Berechtigung.',
  notfound: 'Nicht gefunden.',
  duplicate_isbn: 'Ein Buch mit dieser ISBN existiert bereits.',
  invalid_title: 'Bitte einen Titel angeben (max. 200 Zeichen).',
  invalid_author: 'Bitte einen Autor angeben (max. 200 Zeichen).',
  invalid_isbn: 'Ungültige ISBN (10 oder 13 Stellen).',
  invalid_year: 'Das Jahr muss zwischen 1900 und dem aktuellen Jahr liegen.',
  invalid_language: 'Bitte eine Sprache angeben (max. 50 Zeichen).',
};

export const NOTICES: Record<string, string> = {
  registered:
    'Falls die Registrierung möglich war, wurde eine E-Mail zur Bestätigung der Adresse gesendet.',
  verified: 'Die E-Mail-Adresse ist bestätigt. Du kannst dich jetzt anmelden.',
  resent: 'Falls ein unbestätigtes Konto existiert, wurde die E-Mail erneut gesendet.',
  forgot: 'Falls ein Konto existiert, wurde eine E-Mail zum Zurücksetzen gesendet.',
  reset: 'Das Passwort wurde geändert. Du kannst dich jetzt anmelden.',
  saved: 'Gespeichert.',
  deleted: 'Gelöscht.',
};
