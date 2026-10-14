import {TestBed} from '@angular/core/testing';
import {GlobalAuthenticationInterceptorService} from './global-authentication-interceptor.service';

describe('GlobalAuthenticationInterceptorService', () => {
  let service: GlobalAuthenticationInterceptorService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(GlobalAuthenticationInterceptorService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
