import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SriLankanComponent } from './sri-lankan.component';

describe('SriLankanComponent', () => {
  let component: SriLankanComponent;
  let fixture: ComponentFixture<SriLankanComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SriLankanComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SriLankanComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
