import {TestBed} from '@angular/core/testing';

import {PelletService} from './pellet.service';

describe('PelletService', () => {
  let service: PelletService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PelletService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
