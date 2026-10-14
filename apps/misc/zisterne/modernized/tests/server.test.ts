import { io as connect } from 'socket.io-client';
import { afterEach, describe, expect, it } from 'vitest';
import { loadConfig } from '../src/server/config';
import { openDb } from '../src/server/db';
import { createSimulator } from '../src/server/sensor';
import { buildServer } from '../src/server/server';

let close: () => Promise<void>;
afterEach(() => close());

async function setup(env: NodeJS.ProcessEnv = {}) {
  const cfg = loadConfig({
    SENSOR: 'simulator',
    TRIGGER_INTERVAL_MS: '60',
    AVERAGE_MEASUREMENTS: '2',
    ...env,
  });
  const { app } = await buildServer(cfg, createSimulator(cfg), openDb(':memory:'));
  close = () => app.close();
  await app.listen({ port: 0, host: '127.0.0.1' });
  return { app, url: `http://127.0.0.1:${(app.server.address() as { port: number }).port}` };
}

describe('server', () => {
  it('rejects the cron endpoint without the token', async () => {
    const { app } = await setup({ API_TOKEN: 'secret' });
    const res = await app.inject({ method: 'POST', url: '/api/readings' });
    expect(res.statusCode).toBe(401);
  });

  it('is closed in production without a token', async () => {
    const { app } = await setup({ NODE_ENV: 'production' });
    const res = await app.inject({ method: 'POST', url: '/api/readings' });
    expect(res.statusCode).toBe(401);
  });

  it('stores a series reading and exports it as JSON and CSV', async () => {
    const { app } = await setup({ API_TOKEN: 'secret' });
    const post = await app.inject({
      method: 'POST',
      url: '/api/readings',
      headers: { authorization: 'Bearer secret' },
    });
    expect(post.statusCode).toBe(201);
    const list = (await app.inject('/api/readings')).json();
    expect(list).toHaveLength(1);
    expect(list[0].percent).toBeGreaterThan(0);
    const csv = (await app.inject('/api/readings.csv')).body.split('\n');
    expect(csv[0]).toBe('ts,distance_cm,level_cm,liters,percent');
    expect(csv).toHaveLength(3);
  }, 15000);

  it('streams measurements to a client that may start them, but not to one without token', async () => {
    const { url } = await setup({ API_TOKEN: 'secret' });
    const owner = connect(url, { auth: { token: 'secret' } });
    const guest = connect(url);
    const state = (s: ReturnType<typeof connect>) =>
      new Promise<{ mayControl: boolean }>((r) => s.once('state', r));
    expect((await state(owner)).mayControl).toBe(true);
    expect((await state(guest)).mayControl).toBe(false);

    guest.emit('start'); // ignored
    const measure = new Promise<{ percent: number }>((r) => owner.once('measure', r));
    owner.emit('start');
    expect((await measure).percent).toBeGreaterThan(0);
    owner.close();
    guest.close();
  }, 10000);
});
