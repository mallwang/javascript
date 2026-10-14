import {AppPage} from './app.po';

describe('new App', () => {
  let page: AppPage;

  beforeEach(() => {
    page = new AppPage();
  });

  it('Should redirect to Home Page', async () => {
    const url = await page.navigateTo('/');
    expect(url).toBe('http://localhost:4200/home');
  });

  it('Should have the title Adventskalender', async () => {
    await page.navigateTo('/');
    expect(page.getTitle()).toContain('Adventskalender');
  });
});
