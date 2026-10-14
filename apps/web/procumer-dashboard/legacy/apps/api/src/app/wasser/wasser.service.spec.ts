import {TestBed} from '@angular/core/testing';

import {WasserService} from './wasser.service';

describe('WasserService', () => {
  let service: WasserService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(WasserService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
