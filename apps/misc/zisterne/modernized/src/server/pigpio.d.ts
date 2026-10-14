// Minimal typing for the optional native `pigpio` package (no bundled types).
declare module 'pigpio' {
  export class Gpio {
    static INPUT: number;
    static OUTPUT: number;
    constructor(pin: number, options: { mode: number; alert?: boolean });
    digitalWrite(level: number): this;
    trigger(pulseLen: number, level: number): this;
    disableAlert(): this;
    on(event: 'alert', listener: (level: number, tick: number) => void): this;
    removeListener(event: 'alert', listener: (level: number, tick: number) => void): this;
  }
}
