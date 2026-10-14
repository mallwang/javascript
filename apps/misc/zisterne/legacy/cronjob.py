#!/usr/bin/python

import RPi.GPIO as GPIO
import time
from datetime import datetime
import sys
import os

# GPIO Pins
TRIG = 17
ECHO = 27

# Dauer Trigger-Impuls
PULSE = 0.00001

# Anzahl Messwerte fuer Mittelwertbildung
BURST = 20

# Schallgeschwindigkeit/2
SPEED_2 = 17015

# BCM GPIO-Referenen verwenden (anstelle der Pin-Nummern)
# und GPIO-Eingang definieren
GPIO.setmode(GPIO.BCM)
GPIO.setup(TRIG,GPIO.OUT)
GPIO.setup(ECHO,GPIO.IN)
GPIO.remove_event_detect(ECHO)
GPIO.output(TRIG, False)
time.sleep(1)                   # Setup-Zeit fuer Sensor

stopp = 0                       # Variableninit
start = 0
distance = 0

# Konfiguration
distance_between_us_and_max_height_in_cm = 27
max_fill_level_in_cm = 184.5


def pulse():                    # Funktion zum Starten der Messung
  global start
  global stopp
  global distance

  GPIO.output(TRIG, True)       # Triggerimpuls  erzeugen
  time.sleep(PULSE)
  GPIO.output(TRIG, False)
  stopp = 0                     # Werte auf 0 setzen
  start = 0
  distance = 0                  # und Event starten


def measure(x):                 # Callback-Funktion fuer ECHO
  global start
  global stopp
  global distance
  if GPIO.input(ECHO) == 1:     # steigende Flanke, Startzeit speichern
    start = time.time()
  else:                         # fallende Flanke, Endezeit speichern
    stopp = time.time()
    delta = stopp - start       # Zeitdifferenz und Entfernung berechnen
    distance = delta * SPEED_2


def write_log(text):
  today = datetime.now().strftime('%Y') + ".log" # "2018.log"
  current_dir = os.path.abspath(os.path.dirname(sys.argv[0]))
  log_dir = os.path.join(*[current_dir, "logs", today])
  print("create log entry in directory " + log_dir)
  logfile = open(log_dir, "a")
  sttime = datetime.now().strftime('%d.%m.%Y %H:%M:%S - ')
  logfile.write(sttime + text + "\n")
  return


def measure_range():            # Bildet Mittelwert von BURST Messungen
  values = []
  sum = 0
  for i in range(0, BURST):
    pulse()                     # Messung starten
    time.sleep(0.040)           # Warten, bis Messung zuende
    values.append(distance)     # Wert im Array speichern und aufsummieren
    sum = sum + values[i]
    # print("Einzelmesswert: %1.1f" % distance) # Kontrollausgabe
    sys.stdout.write(".")
    sys.stdout.flush()
    time.sleep(0.05)
  return sum/BURST;             # Mittelwert zurueckgeben


# do it
try:
  amount = 0
  total = 0
  summed_measurements = 0
  print("Start Gesamtmessung")
  GPIO.add_event_detect(ECHO, GPIO.BOTH, callback=measure)
  while amount < 6:
    amount += 1
    print("Start Messung " + str(amount))
    distance_in_cm = measure_range()
    net_distance_in_cm = distance_in_cm - distance_between_us_and_max_height_in_cm
    net_fill_level_in_cm = max_fill_level_in_cm - net_distance_in_cm
    formatted_net_fill_level_in_cm = str(round(net_fill_level_in_cm, 2))
    last_measurement = distance_in_cm
    formatted_distance_in_cm = str(round(distance_in_cm, 2))
    print("\nSumme Messung " + str(amount) + ": " + formatted_distance_in_cm + " cm / " + formatted_net_fill_level_in_cm + " cm")
    if amount > 1:
      summed_measurements += 1
      total += net_fill_level_in_cm
    time.sleep(1)
  print(str(summed_measurements) + " Messungen durchgefuehrt")
  logmsg = str(round(total/summed_measurements, 2))
  print("Durchschnitt: " + logmsg + " cm")
  write_log(logmsg)


# Messung stoppen und GPIO aufraeumen
except KeyboardInterrupt:
  print("Messung vom User gestoppt")
  GPIO.cleanup()
