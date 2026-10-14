import fastifyStatic from '@fastify/static';
import Fastify from 'fastify';
import { existsSync } from 'node:fs';
import { timingSafeEqual } from 'node:crypto';
import { resolve } from 'node:path';
import { Server } from 'socket.io';
import type { Config } from './config';
import type { Db, Reading } from './db';
import type { Sensor } from './sensor';
import { createStation } from './station';

const clientDir = resolve(import.meta.dirname, '../../dist/client');

export async function buildServer(cfg: Config, sensor: Sensor, db: Db) {
  const app = Fastify({ logger: cfg.production });
  const io = new Server(app.server);

  /** Without a token everything is open in development and closed in production. */
  const authorized = (given: unknown) => {
    if (!cfg.apiToken) return !cfg.production;
    if (typeof given !== 'string') return false;
    const a = Buffer.from(given);
    const b = Buffer.from(cfg.apiToken);
    return a.length === b.length && timingSafeEqual(a, b);
  };

  const station = createStation(sensor, cfg, {
    onMeasure: (m) => io.emit('measure', m),
    onStop: (reason) => io.emit('state', { running: false, reason }),
  });

  io.on('connection', (socket) => {
    const mayControl = authorized(socket.handshake.auth.token);
    socket.emit('state', { running: station.running, mayControl });
    socket.on('start', () => {
      if (!mayControl) return;
      if (station.start()) io.emit('state', { running: true });
    });
    socket.on('stop', () => {
      if (mayControl) station.stop();
    });
    socket.on('disconnect', () => {
      if (io.engine.clientsCount === 0) station.stop();
    });
  });

  app.get<{ Querystring: { from?: string; to?: string } }>('/api/readings', (req) =>
    db.list(req.query.from, req.query.to),
  );

  app.get<{ Querystring: { from?: string; to?: string } }>('/api/readings.csv', (req, reply) => {
    const rows = db.list(req.query.from, req.query.to);
    const csv = ['ts,distance_cm,level_cm,liters,percent']
      .concat(rows.map((r) => [r.ts, r.distanceCm, r.levelCm, r.liters, r.percent].join(',')))
      .join('\n');
    return reply.type('text/csv').send(csv + '\n');
  });

  /** Called by cron: takes one series measurement and stores it. */
  app.post('/api/readings', async (req, reply) => {
    const bearer = req.headers.authorization?.replace(/^Bearer /, '');
    if (!authorized(bearer)) return reply.code(401).send({ error: 'unauthorized' });
    let m;
    try {
      m = await station.series();
    } catch {
      return reply.code(409).send({ error: 'sensor busy' });
    }
    if (!m) return reply.code(503).send({ error: 'no valid echo' });
    const reading: Reading = { ts: new Date().toISOString(), ...m };
    db.insert(reading);
    return reply.code(201).send(reading);
  });

  if (existsSync(clientDir)) await app.register(fastifyStatic, { root: clientDir });

  app.addHook('onClose', (_app, done) => {
    station.stop();
    void io.close().then(() => done());
  });
  return { app, io, station };
}
