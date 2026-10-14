# zisterne

Source: `gitlab-export/zisterne-master/` (81 files without `node_modules`). Private project by Markus Allwang (MIT, 2018): live fill-level display for a rainwater cistern. A Raspberry Pi measures the water level with an HC-SR04 ultrasonic sensor; a Node.js server pushes the values to the browser. Will live in `apps/misc/zisterne/`.

## Inspect

| Area         | Files                                                                                                                                                                               |
| ------------ | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Server       | `app.js` (236 lines, entry point), `config.json`, `pigpio-mock.js` (GPIO mock for development), `package.json`, `.npmrc`                                                            |
| Frontend     | `public/index.html` (inline jQuery script), `css/style.css`, Bootstrap 3 (`bootstrap.min.*`, glyphicons fonts), `jquery-3.2.1.slim.min.js`                                          |
| Python       | `zisterne.py` (Python 2 CGI, single reading), `cronjob.py` (Python 2, cron logger writing `logs/<year>.log`)                                                                        |
| `PIGPIO/`    | Vendored pigpio C library v64 (sources, man pages, `UNLICENCE`) plus **compiled ARM binaries** (`*.o`, `*.so`, `pigpiod`, `pigs`, `x_*`) and `build/lib.linux-armv6l-2.7/pigpio.py` |
| `Sonstiges/` | Assets: HC-SR04 datasheets (3 PDFs, 1 DOCX), `RaspberryPiPinbelegung.jpg` (pinout), `Zeichnung.png` (drawing)                                                                       |
| Other        | `logs/.gitkeep`, `.gitattributes` (generic web template), `.gitignore`, `README.md`                                                                                                 |

Images: `public/img/quelle_farbig.png` (favicon), `quelle_sw.png` and `gitlab.png` are not referenced anywhere.

Dependencies (all from 2018): `express` 4.16.4, `socket.io` 2.1.1, `pigpio` 0.6.4 (native addon, needs the pigpio C library and a Raspberry Pi), `detect-port` 1.2.3, `socket-io` 1.0.0 (unrelated package, probably a typo; unused), `nodemon` 1.18.5 (dev).

## Purpose and features

- Sensor (trigger pin 17, echo pin 27) is mounted above the water. Distance to the surface is converted to fill height, litres and percent of capacity.
- The browser shows a stylized tank whose water level moves, plus the text "Aktueller Füllstand: X Liter (Y cm)". UI language is German.
- **Start / Stop buttons:** every client can start or stop the measurement for everybody. Start sends a 10 µs trigger pulse every `triggerInterval` (200 ms). An automatic stop follows after `measurementTimeoutSeconds` (30 s). The measurement also stops when the last client disconnects.
- Every `averageMeasurements` (10) echoes are averaged into one `measure` event.
- Activity log (client connected/disconnected/started/stopped) exists in `index.html`, but see "Issues": it is probably hidden.
- The Python scripts are older/alternative paths: `zisterne.py` returns one averaged value (50 readings) as an HTML page via CGI; `cronjob.py` (BURST 20, 6 rounds, first round discarded) appends the average net fill level in cm to `logs/<year>.log`. Meant for cron; the log is not read by anything.

## Request and event flow

```
GET /*  -> express.static(public) -> otherwise public/index.html
Browser <-> socket.io
  client -> server: start | stop
  server -> all:    client_connected, client_disconnected, client_started,
                    client_stopped, client_timeout_reached (payload: client count),
                    measure {distanceInCm, netFillLevelInCm, fillLevelInLtr, fillLevelInPercentage}
Sensor: trigger.trigger(10, 1) every 200 ms
        echo 'alert' rising edge -> startTick, falling edge -> diff -> distance
        distance = diff / 2 / microsecondsPerCm   (speed of sound = 331.6 + 0.6 * 15 °C)
        10 readings -> mean -> net distance = mean - 27 cm -> fill = 184.5 - net distance
```

The server listens on `defaultPort` 80 (or the next free port, via `detect-port`). `NODE_ENV=development` loads `pigpio-mock.js` instead of the real driver.

## Configuration (`config.json`)

Pins `17`/`27`, `diameterInCm` 199.7, `maxFillLevelInCm` 184.5, `distanceBetweenUltrasonicAndMaxHeightInCm` 27, `emptyFillLevelInCm` 16.5 (unused), `defaultPort` 80, `triggerInterval` 200, `averageMeasurements` 10, `averageTemperatureCelsius` 15 (fixed, no temperature sensor), `measurementTimeoutSeconds` 30. No secrets; these are tank dimensions and wiring. Python scripts hard-code the same numbers separately.

## Data model

None. State is in memory only (client counter, running flag). The only persistence is the Python cron log (`DD.MM.YYYY HH:MM:SS - <cm>` lines).

## External services

None (no database, no network calls, no CDN). The `socket.io` client is served by the server.

## Issues

Correctness:

- **Wrong volume formula:** `AMOUNT_OF_LTR_PER_CM = sqrt(d/2) * PI`. The correct value is `PI * (d/2)^2 / 1000` litres per cm (31.32 L/cm for d = 199.7 cm; capacity about 5780 L). The legacy value is 31.39 L/cm, only about 0.2 % off by coincidence for a radius near 100 cm. A tank of other size would be badly wrong. The modernized version must use the correct formula.
- **Signed tick arithmetic:** `(end >> 0) - (start >> 0)` should use `>>> 0` (unsigned 32-bit) to survive the pigpio tick wrap-around (about every 72 minutes), otherwise one reading is wildly wrong.
- **Out-of-range echoes** (very short/long pulses, no echo) enter the average unfiltered. A negative or too large reading skews the 10-sample mean. `emptyFillLevelInCm` suggests a plausibility check was planned.
- **Timeout is never cancelled:** `startInterval` creates a `setTimeout` that is never cleared. Stop and quick restart within 30 s are cut short by the old timer.
- `isMeasurementActive` is set after `startInterval`; `stopInterval` logs the private `_idleStart` of the timer.
- The client count is broadcast to everyone, but any client can stop everybody's measurement. No authentication.
- Fill percentage is 0 when the net distance exceeds the max height (empty tank) but can exceed 100 when the water rises above max height (the client clips to 100).
- `MICROSECONDS_PER_CM` is computed correctly (about 29.4 µs/cm) but is misnamed; `calculateDistance`'s parameter `msPerSecond` as well.
- Python: `cronjob.py` never closes the log file, no `GPIO.cleanup()` on normal exit, race on the global `distance` between callback and loop; Python 2 `print` statements (`zisterne.py` does not run on Python 3); `cgi`/`cgitb` removed in Python 3.13.

Security:

- No authentication or authorization on the socket (start/stop for anybody in the network); no rate limiting; no origin check.
- The server must run as root (`sudo node app.js`) because it binds port 80 and uses pigpio. The README installs with `sudo npm install --unsafe-perm`.
- Catch-all `res.sendFile(index.html)` serves HTML for every unknown URL, including missing assets (hides 404s).
- Very old dependencies with known vulnerabilities (socket.io 2.1.1, express 4.16.4, nodemon 1.18.5, jQuery 3.2.1).
- Status text is injected with `.html()`, but only from numbers the server calculates (no user input), so no real XSS today.

Quality:

- Bootstrap 3 (glyphicons) mixed with Bootstrap 4 class names (`float-right`, `justify-content-md-center`). The activity log wrapper has class `hidden`, so the log is probably never visible.
- Whole frontend logic is an inline script in `index.html`; uses jQuery only for DOM access.
- `README.md` TODOs: how to set config variables, how to open the app in the local network.
- Windows-only script `start:dev:windows`.

## Dead code and junk

- Unused: `socket-io` dependency, `emptyFillLevelInCm`, `quelle_sw.png`, `gitlab.png`, the commented-out `startInterval` in the `listen` callback, many commented-out `console.log` calls.
- `pigpio-mock.js`: its `Gpio` class assigns `this.mode = ...` and `this.pullUpDown = ...`, which shadow the methods of the same name; `emitMessage()` is the only way to simulate an echo, and the real code never calls it (commented out), so the dev mode produces no measurements.
- Redundant vendored `PIGPIO/`: library sources and build artefacts (`*.o`, `*.so`, ARM executables, `build/lib.linux-armv6l-2.7/pigpio.py`, a copy of `pigpio.py`). The npm package `pigpio` already requires the system library installed (`apt install pigpio`), so the `make`/`make install` steps in the README would normally not be needed. Deleting any of it needs approval (cleanup step); `Sonstiges/` assets are kept in any case.

## Credentials

None found: no `.env`, no passwords or tokens in `app.js`, `config.json`, Python scripts or `public/`. (Authors name and e-mail appear in `package.json`.) The compiled binaries in `PIGPIO/` were not inspected for embedded data.

## Decisions

- **Cleanup (approved):** vendored `PIGPIO/` (sources, compiled ARM binaries, build output) and `package-lock.json` were removed before the legacy commit. `pigpio` is installed from the system package (`apt install pigpio`). `Sonstiges/` and all images are kept.
- **Stack (approved):** Node.js 22 + TypeScript, Fastify + socket.io for server and WebSocket, Vite + TypeScript frontend without jQuery/Bootstrap. `pigpio` is an optional dependency behind a small `SensorDriver` interface; a simulating driver is used for development and tests.
- **Logger (approved):** `cronjob.py` is needed. It is called periodically from outside (cron) to log fill levels and to feed later evaluation (time series). It is ported as a Node CLI/HTTP endpoint that takes a measurement series, drops the first round, and appends to a log and SQLite time series. Details are settled in the modernized README.
- **Core logic:** distance, volume (correct formula), averaging and outlier filtering are pure functions with unit tests.

## Modernization result

`apps/misc/zisterne/modernized/`: Fastify 5 + socket.io server, Vite client, SQLite (`better-sqlite3`), config via environment variables (`.env.example`). Sensor access sits behind a `Sensor` interface with a simulator (default) and a `pigpio` driver loaded on demand.

Fixed legacy bugs:

- Volume uses `PI * (d/2)^2 / 1000` litres per cm instead of `sqrt(d/2) * PI`.
- Echo width uses unsigned 32-bit tick arithmetic (`>>> 0`), so the pigpio tick wrap-around no longer produces one wild reading.
- Distances outside the HC-SR04 range (2-400 cm) and lost echoes are dropped before averaging; if fewer than half of a batch is valid, no value is emitted.
- The measurement timeout is cleared on stop and renewed on restart. The loop also stops when the last client leaves.
- Fill level is clamped to 0..maximum, so the percentage never exceeds 100.
- No catch-all route: unknown URLs return 404.
- Start/stop requires `API_TOKEN` (socket handshake token, constant-time comparison); other clients are read-only.
- The development mode now produces measurements (simulator with noise, lost and garbage echoes).

Deviations from the legacy app, with reasons:

- Port 3000 instead of 80: no root needed for the port. Use a reverse proxy for 80.
- Python scripts are not ported one to one. `cronjob.py` becomes `POST /api/readings` (same 6x20 series, first round dropped) with a SQLite time series and CSV export instead of `logs/<year>.log`; cron calls it with `curl`. `zisterne.py` (CGI single reading) is dropped, the live UI covers it.
- The old events `client_connected`, `client_disconnected`, `client_started`, `client_stopped`, `client_timeout_reached` and the hidden activity log are replaced by one `state` event (`running`, optional `reason`). The legacy log was never visible.
- Bootstrap 3 and jQuery are removed; the tank graphic is plain CSS. All images are kept in `public/img/` (only the favicon is used); `emptyFillLevelInCm` and the `socket-io` dependency are dropped as unused.
- A started measurement is shared by all clients; a series reading is refused while it runs (and vice versa), because there is only one sensor.

Open points: the hardware path (`SENSOR=pigpio`) is untested on a real Pi; the Pi needs the 5 V to 3.3 V divider on the echo pin as before.
