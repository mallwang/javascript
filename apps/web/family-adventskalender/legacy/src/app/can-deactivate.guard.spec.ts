import {inject, TestBed} from '@angular/core/testing';
import {CanDeactivateGuard} from './can-deactivate.guard';
import {Location} from '@angular/common';
import {RouterTestingModule} from '@angular/router/testing';

const locationStub = {
  back: jasmine.createSpy('back')
};

describe('CanDeactivateGuard', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [RouterTestingModule],
      providers: [CanDeactivateGuard, {provide: Location, useValue: locationStub}]
    });
  });

  it('should ...', inject([CanDeactivateGuard], (guard: CanDeactivateGuard) => {
    expect(guard).toBeTruthy();
  }));
});
