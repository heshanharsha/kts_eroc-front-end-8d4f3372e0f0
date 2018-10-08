import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RegisterSecretaryPvtComponent } from './register-secretary-pvt.component';

describe('RegisterSecretaryPvtComponent', () => {
  let component: RegisterSecretaryPvtComponent;
  let fixture: ComponentFixture<RegisterSecretaryPvtComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RegisterSecretaryPvtComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RegisterSecretaryPvtComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
