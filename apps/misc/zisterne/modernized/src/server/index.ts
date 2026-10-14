import { loadConfig } from './config';
import { openDb } from './db';
import { createSensor } from './sensor';
import { buildServer } from './server';

const cfg = loadConfig();
if (cfg.production && !cfg.apiToken) {
  console.warn('API_TOKEN is not set: start/stop and POST /api/readings are disabled.');
}
const sensor = await createSensor(cfg);
const db = openDb(cfg.dbPath);
const { app } = await buildServer(cfg, sensor, db);

for (const signal of ['SIGINT', 'SIGTERM']) {
  process.once(signal, () => {
    void app.close().then(() => {
      sensor.close();
      db.close();
      process.exit(0);
    });
  });
}

await app.listen({ port: cfg.port, host: cfg.host });
console.log(`zisterne listening on ${cfg.host}:${cfg.port} (sensor: ${cfg.sensor})`);
