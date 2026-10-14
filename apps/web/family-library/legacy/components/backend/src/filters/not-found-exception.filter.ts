import {ArgumentsHost, Catch, ExceptionFilter, HttpException, NotFoundException} from '@nestjs/common';
import * as path from 'path';
import * as fs from 'fs';
import {ConfigService} from '../config/config.service';

@Catch(NotFoundException)
export class NotFoundExceptionFilter implements ExceptionFilter {
  private readonly filePath: string;
  private readonly errorFilePath = '../404.html';

  public constructor(private readonly configService: ConfigService) {
    this.filePath = `${this.configService.get('FRONTEND_STATIC_DIR')}/index.html`;
  }

  /**
   * Catch all NotFoundExceptions and return the frontend index.html so the Angular app will do the routing.
   * The backend api is still reachable via '/v1'.
   * The OpenAPI documentation is still available via '/api'.
   * @param {HttpException} exception: The Nest HTTP Exception.
   * @param {ArgumentsHost} host: The handler for requesting the arguments from.
   */
  public catch(exception: HttpException, host: ArgumentsHost): void {
    // Get the http response object
    const ctx = host.switchToHttp();
    const response = ctx.getResponse();

    // Check if the file exists before sending (required for local development)
    if (fs.existsSync(path.join(__dirname, '..', this.filePath))) {
      // Send the Angular index.html file
      const indexHtml = fs.readFileSync(path.join(__dirname, '..', this.filePath));
      response.type('text/html').send(indexHtml);
    } else {
      // Display a common error page
      const errorHtml = fs.readFileSync(path.join(__dirname, this.errorFilePath));
      response.type('text/html').send(errorHtml);
    }
  }
}
