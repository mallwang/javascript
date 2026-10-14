import {browser, by, element} from 'protractor';

export class AppPage {
  public async navigateTo(route: string): Promise<string> {
    await browser.get(route);
    const url = await browser.driver.getCurrentUrl();
    return url;
  }

  public getTitle(): any {
    return element(by.deepCss('app-root ion-title')).getText();
  }

  public getParagraphText(): any {
    return element(by.deepCss('app-root ion-content')).getText();
  }
}
