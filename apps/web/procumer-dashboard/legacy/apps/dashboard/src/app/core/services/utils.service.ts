import {Inject, Injectable, LOCALE_ID} from '@angular/core';
import {Title} from '@angular/platform-browser';

@Injectable({
  providedIn: 'root'
})
export class UtilsService {
  public static urlRegex = '^(https?:\\/)?\\/?([^:\\/\\s]+)((\\/\\w+)*\\/)([\\w\\-\\.]+[^#?\\s]+)(.*)?(#[\\w\\-]+)?$';
  public static uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}$/i;
  private localeId: string;

  public constructor(private readonly titleService: Title, @Inject(LOCALE_ID) private readonly locale: string) {
    this.localeId = locale;
  }

  /**
   * Returns the current users locale id (e.g. 'de', 'en' or 'hu').
   * @return {string} The locale id.
   */
  public getLocaleId(): string {
    return this.localeId;
  }

  /**
   * Sets the title of the current HTML document using the Angular title service.
   * @param {string} title: The prefix to be shown before the common application name.
   */
  public setTitle(title: string): void {
    this.titleService.setTitle(`${title} - Procumer Dashboard`);
  }
}
