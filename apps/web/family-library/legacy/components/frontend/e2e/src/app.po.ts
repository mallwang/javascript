import {browser, by, element} from 'protractor';

export class AppPage {
  public navigateTo(): Promise<unknown> {
    return <Promise<unknown>>browser.get(browser.baseUrl);
  }

  public getTitleText(): Promise<string> {
    return <Promise<string>>element(by.css('app-root .content span')).getText();
  }
}
