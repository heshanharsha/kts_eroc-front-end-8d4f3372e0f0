import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RegisterSecretaryFirmComponent } from './register-secretary-firm.component';

describe('RegisterSecretaryFirmComponent', () => {
  let component: RegisterSecretaryFirmComponent;
  let fixture: ComponentFixture<RegisterSecretaryFirmComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RegisterSecretaryFirmComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RegisterSecretaryFirmComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
