import {TestBed} from '@angular/core/testing';

import {StromService} from './strom.service';

describe('StromService', () => {
  let service: StromService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(StromService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
