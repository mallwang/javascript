import {async, ComponentFixture, TestBed} from '@angular/core/testing';
import {IonicModule, NavParams} from '@ionic/angular';

import {ImageModalComponent} from './image-modal.component';

class MockNavParams {
  public day: 1;
  public get() {
    return this.day;
  }
}

describe('ImageModalComponent', () => {
  let component: ImageModalComponent;
  let fixture: ComponentFixture<ImageModalComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ImageModalComponent],
      imports: [IonicModule.forRoot()],
      providers: [{provide: NavParams, useClass: MockNavParams}]
    }).compileComponents();

    fixture = TestBed.createComponent(ImageModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
