// @vitest-environment jsdom
import { expect, test } from 'vitest';
import { pageTitle, renderHost } from '../src/host';

test('pageTitle includes the host', () => {
  expect(pageTitle('example.com')).toBe('Internet Seite: - example.com -');
});

test('renderHost escapes markup (legacy XSS via HTTP_HOST)', () => {
  document.body.innerHTML = '<span id="host"></span>';
  renderHost(document, '<img src=x onerror=alert(1)>');
  expect(document.querySelector('#host img')).toBeNull();
  expect(document.getElementById('host')?.textContent).toBe('<img src=x onerror=alert(1)>');
});
