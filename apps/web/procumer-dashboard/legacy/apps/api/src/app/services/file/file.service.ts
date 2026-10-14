import {Injectable, Logger} from '@nestjs/common';
import * as XLSX from 'xlsx';
import {ConfigService} from '../config/config.service';
import {Cache} from '../../decorators/cache.decorator';

@Injectable()
export class FileService {
  public constructor(private readonly configService: ConfigService) {}

  /**
   * Read from the given sheet name and return the results as JSON data.
   * @param {string} sheetName: The name of the sheet to read from.
   * @return {Promise<any>} The content of the sheet as JSON data.
   */
  // @Cache({key: 'file', ttl: 60})
  public async read(sheetName: string): Promise<any> {
    const filePath = this.configService.get('FILE_PATH');
    Logger.debug(`Read file '${filePath}'`, FileService.name);
    const workbook = XLSX.readFile(`${__dirname}/${filePath}`, {cellDates: true});
    const data = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], {header: 1});
    return data;
  }
}
