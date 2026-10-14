"use strict";

// configuration file
const CONFIG = require('./config');

// websocket and webserver
const express = require('express');
const app = express();
const http = require('http').Server(app);
const io = require('socket.io')(http);
const detect = require('detect-port');

let Gpio;
if (process.env.NODE_ENV && process.env.NODE_ENV.trim() === 'development') {
  Gpio = require('./pigpio-mock');
} else {
  Gpio = require('pigpio').Gpio;
}

// get dependency and create trigger and echo
const trigger = new Gpio(CONFIG.triggerPin, {mode: Gpio.OUTPUT});
const echo = new Gpio(CONFIG.echoPin, {mode: Gpio.INPUT, alert: true});

// serve static files
app.use(express.static('public'));

// if no file found -> route to index.html
app.use((req, res, next) => {
  res.sendFile(__dirname + '/public/index.html');
});

console.log("webserver gets started...");

// detect free port
detect(CONFIG.defaultPort, (err, _port) => {
  if (err) {
    console.error(err);
    return;
  }

  const freePort = CONFIG.defaultPort === _port ? CONFIG.defaultPort : _port;

  // listen on free port
  http.listen(freePort, () => {
    console.log(`webserver successfully started on port ${freePort}`);
    //startInterval(CONFIG.triggerInterval);
  });
});

// The number of microseconds it takes sound to travel 1cm at 20 degrees celcius
const MICROSECONDS_PER_CM = 1e6 / (calculateMsPerCm(CONFIG.averageTemperatureCelsius) * 100);
const AMOUNT_OF_LTR_PER_CM = Math.sqrt((CONFIG.diameterInCm / 2)) * Math.PI;
const MAXIMUM_CONTENT_IN_LTR = AMOUNT_OF_LTR_PER_CM * CONFIG.maxFillLevelInCm;
let intervalId = null;
let clients = 0;
let isMeasurementActive = false;

// make sure trigger is low
trigger.digitalWrite(0);

/**
 * Watches the ultrasonic sensor for echo alerts and send it to the connected user via the websocket
 */
function watchHCSR04() {
  let startTick;
  let countMeasurements = 0;
  let sumMeasurements = 0;

  // wait for echo signal and calculate the distance
  echo.on('alert', (level, tick) => {
    //console.log('level, tick:', level, tick);

    let endTick;
    let diff;

    if (level === 1) {
      startTick = tick;
    } else {
      endTick = tick;
      diff = (endTick >> 0) - (startTick >> 0); // Unsigned 32 bit arithmetic
      const distanceInCm = calculateDistance(diff, MICROSECONDS_PER_CM);
      countMeasurements++;

      // sum up
      sumMeasurements = parseFloat((sumMeasurements + distanceInCm).toFixed(2));
      //console.log("sumMeasurements: " + sumMeasurements);
      if (countMeasurements % CONFIG.averageMeasurements === 0 && countMeasurements > 0) {
        let fillLevelInLtr = 0;
        let fillLevelInPercentage = 0;
        const distanceInCm = sumMeasurements / CONFIG.averageMeasurements;
        const netDistanceInCm = (distanceInCm - CONFIG.distanceBetweenUltrasonicAndMaxHeightInCm);
        const netFillLevelInCm = CONFIG.maxFillLevelInCm - netDistanceInCm;
        if (netDistanceInCm <= CONFIG.maxFillLevelInCm) {
          fillLevelInLtr = (CONFIG.maxFillLevelInCm - netDistanceInCm) * AMOUNT_OF_LTR_PER_CM;
          fillLevelInPercentage = (fillLevelInLtr / MAXIMUM_CONTENT_IN_LTR) * 100;
        }

        process.stdout.write('\n');
        console.log(`Distance: ${parseFloat(distanceInCm.toFixed(2))} cm`);
        io.emit('measure', createMessage(distanceInCm, netFillLevelInCm, fillLevelInLtr, fillLevelInPercentage));
        sumMeasurements = 0;
        countMeasurements = 0;
      }
      process.stdout.write('.');
      //console.log(`[${countMeasurements}] Distance: ${distanceInCm} cm`);
    }
  });
}

/**
 * Calculate the sound velocity based on the current temperature
 *
 * @param degreesCelcius
 * @returns {number}
 */
function calculateMsPerCm(degreesCelcius) {
  return 331.6 + 0.6 * degreesCelcius;
}

/**
 * Creates the object passed to the connected clients via the socket
 *
 * @param distanceInCm
 * @param netFillLevelInCm
 * @param fillLevelInLtr
 * @param fillLevelInPercentage
 * @returns {{distanceInCm: number, netFillLevelInCm: number, fillLevelInLtr: number, fillLevelInPercentage: number}}
 */
function createMessage(distanceInCm, netFillLevelInCm, fillLevelInLtr, fillLevelInPercentage) {
  return {
    distanceInCm: parseFloat(distanceInCm.toFixed(2)),
    netFillLevelInCm: Math.round(netFillLevelInCm),
    fillLevelInLtr: Math.round(fillLevelInLtr),
    fillLevelInPercentage: parseFloat(fillLevelInPercentage.toFixed(2))
  }
}

/**
 * Calculates the distance based on the time difference and the sound velocity
 *
 * @param diff
 * @param msPerSecond
 * @returns {number}
 */
function calculateDistance(diff, msPerSecond) {
  //console.log(`diff: ${diff}, msPerSecond: ${msPerSecond}`);
  return parseFloat((diff / 2 / msPerSecond).toFixed(2));
}

/**
 * Starts an interval with given number of milliseconds
 *
 * @param ms
 */
function startInterval(ms) {
  intervalId = setInterval(() => {
    trigger.trigger(10, 1); // Set trigger high for 10 microseconds
    // echo.emitMessage();
  }, ms);

  // set timeout of sensor
  setTimeout(() => {
    // only stop interval if a measurement is currently active
    if (isMeasurementActive) {
      io.emit("client_timeout_reached", clients);
      stopInterval(intervalId);
    }
  }, CONFIG.measurementTimeoutSeconds * 1000);
}

/**
 * Stops an interval object based on its id
 *
 * @param intervalId
 */
function stopInterval(intervalId) {
  clearInterval(intervalId);
  console.log(`stopped intervalId ${intervalId._idleStart}`);
  isMeasurementActive = false;
}

/**
 * Websocket events
 */
io.on('connection', client => {
  // increment clients counter
  clients++;
  console.log(`new client connected, now ${clients} clients are connected in total.`);
  io.emit("client_connected", clients);

  // client is disconnected
  client.on('disconnect', () => {
    // decrement clients counter
    clients--;
    console.log(`client is disconnected, now ${clients} clients are connected in total.`);
    io.emit("client_disconnected", clients);
    if (clients === 0) {
      console.log("all clints are disconnected -> close measuring");
      if (isMeasurementActive) {
        stopInterval(intervalId);
      }
    }
  });

  // client want to stop measurement
  client.on('stop', () => {
    console.log("client stopped measuring");
    //console.log(`isMeasurementActive: ${isMeasurementActive}`);
    if (isMeasurementActive) {
      io.emit("client_stopped");
      stopInterval(intervalId);
    }
  });

  // client want to start measurement
  client.on('start', () => {
    console.log("client started measuring");
    //console.log(`isMeasurementActive: ${isMeasurementActive}`);
    if (!isMeasurementActive) {
      startInterval(CONFIG.triggerInterval);
      isMeasurementActive = true;
      io.emit("client_started");
    }
  });
});

/**
 * Catches the process termination by the user
 */
process.on('SIGINT', () => {
  console.log("Node process exited by user (Ctrl+C)");
  process.exit();
});

// start watching the ultrasonic sensor
watchHCSR04();
