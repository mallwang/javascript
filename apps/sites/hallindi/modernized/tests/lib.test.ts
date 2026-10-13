import { expect, test } from 'vitest';
import { counterValue, filterProjects, projects } from '../src/lib';

test('filterProjects keeps all projects for "all"', () => {
  expect(filterProjects(projects, 'all')).toHaveLength(5);
});

test('filterProjects splits websites and apps', () => {
  expect(filterProjects(projects, 'websites').map((p) => p.kind)).toEqual(['websites', 'websites']);
  expect(filterProjects(projects, 'apps')).toHaveLength(3);
});

test('counterValue counts up and is clamped', () => {
  expect(counterValue(20, 0, 2000)).toBe(0);
  expect(counterValue(20, 1000, 2000)).toBe(10);
  expect(counterValue(20, 5000, 2000)).toBe(20);
  expect(counterValue(20, -5, 2000)).toBe(0);
});
