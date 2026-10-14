import { io } from 'socket.io-client';
import './style.css';

interface Measure {
  levelCm: number;
  liters: number;
  percent: number;
}

const $ = <T extends HTMLElement>(id: string) => document.getElementById(id) as T;
const token = new URLSearchParams(location.hash.slice(1)).get('token') ?? undefined;
const socket = io({ auth: { token } });

const status = $('status');
const start = $<HTMLButtonElement>('start');
const stop = $<HTMLButtonElement>('stop');

start.onclick = () => socket.emit('start');
stop.onclick = () => socket.emit('stop');

let mayControl = false;
socket.on('state', (s: { running: boolean; mayControl?: boolean; reason?: string }) => {
  mayControl = s.mayControl ?? mayControl;
  start.disabled = !mayControl || s.running;
  stop.disabled = !mayControl || !s.running;
  if (s.reason === 'timeout') status.textContent = 'Messung nach Zeitlimit beendet.';
  else if (!s.running)
    status.textContent = mayControl ? 'Messung gestoppt.' : 'Nur Ansicht (kein Token).';
  else status.textContent = 'Messe …';
});

socket.on('measure', (m: Measure) => {
  const percent = Math.min(m.percent, 100);
  $('liquid').style.height = `${percent}%`;
  $('percent').textContent = `${Math.round(percent)} %`;
  status.textContent = `Aktueller Füllstand: ${Math.round(m.liters)} Liter (${m.levelCm.toFixed(1)} cm)`;
});

socket.on('disconnect', () => (status.textContent = 'Verbindung getrennt.'));
