import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RegisterSecretaryCardComponent } from './register-secretary-card.component';

describe('RegisterSecretaryCardComponent', () => {
  let component: RegisterSecretaryCardComponent;
  let fixture: ComponentFixture<RegisterSecretaryCardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RegisterSecretaryCardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RegisterSecretaryCardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
