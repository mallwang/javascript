import { message } from './email';

const input = document.querySelector<HTMLInputElement>('#test')!;
const result = document.querySelector<HTMLElement>('#result')!;

document.querySelector('#form')!.addEventListener('submit', (e) => {
  e.preventDefault();
  result.textContent = message(input.value);
});
