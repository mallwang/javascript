# zisterne (modernized)

Fill-level meter for a rainwater cistern: a Raspberry Pi measures the distance to the water surface with an HC-SR04 ultrasonic sensor. A Fastify + socket.io server shows the level live in the browser (Vite + TypeScript, no jQuery) and stores periodic readings in SQLite.

## Prerequisites

Node.js >= 22 (see `.nvmrc` at the repo root). On the Raspberry Pi additionally `sudo apt install pigpio` (the optional `pigpio` npm package builds against it).

## Run

```sh
npm install          # run once at the repo root
cp .env.example .env
npm run dev      # builds the client, starts the server with the simulator on :3000
npm run build    # type-check and build the client to dist/client
npm start        # server only (serves dist/client)
npm test         # Vitest
npm run lint     # ESLint + Prettier check
```

## Configuration

All settings are environment variables, see `.env.example` (wiring, tank dimensions, temperature, averaging, timeout). `SENSOR=simulator` (default) runs on any machine; `SENSOR=pigpio` uses the real sensor.

Access control: set `API_TOKEN`. The UI offers Start/Stop only when opened as `http://host:3000/#token=<API_TOKEN>`; everybody else sees live values read-only. Without a token, control is open in development and disabled when `NODE_ENV=production`.

## HTTP API

| Route                   | Description                                                            |
| ----------------------- | ---------------------------------------------------------------------- |
| `POST /api/readings`    | Takes one series measurement and stores it (token required, see below) |
| `GET /api/readings`     | JSON time series, optional `?from=<ISO>&to=<ISO>`                      |
| `GET /api/readings.csv` | Same as CSV (`ts,distance_cm,level_cm,liters,percent`)                 |

The series (replacement for `cronjob.py`) is 6 rounds of 20 pulses; the first round is discarded and invalid echoes are ignored. It takes about 8 seconds and answers `409` while the live measurement runs, `503` if no valid echo came back. Cron example (every 15 minutes):

```cron
*/15 * * * * curl -fsS -X POST -H "Authorization: Bearer $API_TOKEN" http://localhost:3000/api/readings
```

## Raspberry Pi

`pigpio` needs root for GPIO access. Prefer a systemd unit with `AmbientCapabilities` or run it as root on an internal network only; the server itself listens on an unprivileged port (3000), put a reverse proxy in front for port 80. The HC-SR04 echo pin outputs 5 V, use a voltage divider for the 3.3 V GPIO (see `legacy/Sonstiges/`).
