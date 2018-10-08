import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RegisterSecretaryNaturalpComponent } from './register-secretary-naturalp.component';

describe('RegisterSecretaryNaturalpComponent', () => {
  let component: RegisterSecretaryNaturalpComponent;
  let fixture: ComponentFixture<RegisterSecretaryNaturalpComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RegisterSecretaryNaturalpComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RegisterSecretaryNaturalpComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
