import { beforeEach, describe, expect, it } from 'vitest';
import { openDb } from '../src/lib/db';
import * as s from '../src/lib/site';

let db: ReturnType<typeof openDb>;
const place = (over: Partial<s.PlaceInput> = {}): s.PlaceInput => ({
  category: 'sight',
  state: 'Bayern',
  city: 'München',
  name: 'Frauenkirche <b>',
  description: 'A & B',
  lat: 48.138,
  lng: 11.573,
  ...over,
});
const admin = () => s.getSession(db, s.createSession(db, 1))!;

beforeEach(() => {
  db = openDb(':memory:', { email: 'admin@x.de', password: 'adminpass1' });
});

describe('accounts', () => {
  it('registers, rejects duplicates and weak input, logs in by nickname or e-mail', () => {
    const r = s.register(db, { nickname: 'brain', email: 'b@x.de', password: 'password1' });
    expect(r).toHaveProperty('id');
    expect(s.register(db, { nickname: 'BRAIN', email: 'c@x.de', password: 'password1' })).toEqual({
      error: 'Nickname or e-mail already in use',
    });
    expect(s.register(db, { nickname: 'x', email: 'bad', password: 'password1' })).toHaveProperty(
      'error',
    );
    expect(s.authenticate(db, 'brain', 'password1')).toEqual(r);
    expect(s.authenticate(db, 'b@x.de', 'password1')).toEqual(r);
    expect(s.authenticate(db, 'brain', 'nope')).toHaveProperty('error');
  });

  it('seeds the admin from env and sessions expire', () => {
    const token = s.createSession(db, 1, 1000);
    expect(s.getSession(db, token, 2000)).toMatchObject({ nickname: 'admin', isAdmin: true });
    expect(s.getSession(db, token, 1000 + 8 * 24 * 3600 * 1000)).toBeUndefined();
    expect(
      s.register(db, { nickname: 'evil', email: 'ADMIN@x.de', password: 'password1' }),
    ).toHaveProperty('error');
  });
});

describe('places', () => {
  it('stay hidden until an admin approves them', () => {
    expect(s.submitPlace(db, place())).toBeUndefined();
    expect(s.listPlaces(db)).toHaveLength(0);
    const [p] = s.listPending(db);
    expect(s.moderate(db, undefined, p!.id, true)).toBe('Not allowed');
    s.moderate(db, admin(), p!.id, true);
    expect(s.listPlaces(db, 'münchen', 'sight')).toHaveLength(1);
    expect(s.listPlaces(db, 'Berlin')).toHaveLength(0);
    expect(s.cities(db)).toEqual(['München']);
  });

  it('rejecting deletes only pending places', () => {
    s.submitPlace(db, place());
    s.submitPlace(db, place({ name: 'Other' }));
    const [a, b] = s.listPending(db);
    s.moderate(db, admin(), a!.id, true);
    s.moderate(db, admin(), a!.id, false);
    s.moderate(db, admin(), b!.id, false);
    expect(s.listPlaces(db)).toHaveLength(1);
    expect(s.listPending(db)).toHaveLength(0);
  });

  it('validates input', () => {
    expect(s.submitPlace(db, place({ category: 'x' }))).toBe('Unknown category');
    expect(s.submitPlace(db, place({ state: 'Atlantis' }))).toBe('Unknown state');
    expect(s.submitPlace(db, place({ lat: 91 }))).toBe('Invalid coordinates');
    expect(s.submitPlace(db, place({ lat: NaN }))).toBe('Invalid coordinates');
    expect(s.submitPlace(db, place({ name: ' ' }))).toBe('City and name are required');
  });

  it('XML escapes user content', () => {
    s.submitPlace(db, place());
    s.moderate(db, admin(), s.listPending(db)[0]!.id, true);
    const xml = s.placesXml(s.listPlaces(db));
    expect(xml).toContain('<name>Frauenkirche &lt;b&gt;</name>');
    expect(xml).toContain('<beschreibung>A &amp; B</beschreibung>');
    expect(xml).toContain('<long>11.573</long>');
  });
});

describe('library, support and memory', () => {
  it('filters books', () => {
    const ins = db.prepare(
      'INSERT INTO books (title, author, year, category, format) VALUES (?,?,?,?,?)',
    );
    ins.run('PHP Handbook', 'Meier', 2012, 'IT', 'PDF');
    ins.run('Gardening', 'Schulz', 2001, 'Hobby', 'Book');
    expect(s.searchBooks(db)).toHaveLength(2);
    expect(s.searchBooks(db, 'IT')).toHaveLength(1);
    expect(s.searchBooks(db, '', 'Book', 'schu')).toHaveLength(1);
    expect(s.searchBooks(db, 'IT', 'Book')).toHaveLength(0);
    expect(s.searchBooks(db, '', '', "'; DROP TABLE books;--")).toHaveLength(0);
    expect(s.distinct(db, 'category')).toEqual(['Hobby', 'IT']);
  });

  it('stores support tickets', () => {
    expect(s.addSupport(db, { subject: 'Hi', email: 'a@x.de', text: 'Help' })).toBeUndefined();
    expect(s.addSupport(db, { subject: 'Hi', email: 'nope', text: 'Help' })).toBeDefined();
  });

  it('deals every card exactly twice', () => {
    const deck = s.makeDeck(10);
    expect(deck).toHaveLength(20);
    for (let v = 1; v <= 10; v++) expect(deck.filter((c) => c === v)).toHaveLength(2);
  });
});
