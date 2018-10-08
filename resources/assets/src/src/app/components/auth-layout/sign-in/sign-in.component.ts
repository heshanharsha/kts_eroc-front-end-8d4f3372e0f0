import { DataService } from './../../../storage/data.service';
import { GeneralService } from './../../../http/services/general.service';
import { Component, OnInit, ViewChild } from '@angular/core';
import { AbstractControl, FormGroup, FormControl, Validators, FormBuilder } from '@angular/forms';
import { AuthenticationService } from '../../../http/services/authentication.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { Router } from '@angular/router';
import { IAuth } from '../../../http/models/auth.model';
import { ModalDirective } from 'angular-bootstrap-md';

@Component({
  selector: 'app-sign-in',
  templateUrl: './sign-in.component.html',
  styleUrls: ['./sign-in.component.scss']
})
export class SignInComponent implements OnInit {
  @ViewChild('frame') modal: ModalDirective;

  showSpinner: boolean;
  public formGroup: FormGroup;
  isLoginError: boolean;

  private auth: IAuth ;

  constructor(
    private formBuilder: FormBuilder,
    private router: Router,
    private dataService: DataService,
    private AuthService: AuthenticationService,
    private spinner: NgxSpinnerService
  ) { }

  public ngOnInit() {
    this.isLoginError = false;
    this.formGroup = this.formBuilder.group({
      email: new FormControl(null, [
        Validators.email,
        Validators.required
      ]),
      password: new FormControl(null, [Validators.required])
    });
  }

  get email() { return this.formGroup.get('email'); }

  get password() { return this.formGroup.get('password'); }

  showModal() {
    this.modal.show();
  }

  onSubmit() {
    this.spinner.show();
    this.isLoginError = false;
    const authData: IAuth = {
      email: this.email.value.trim(),
      password: this.password.value
    };

    this.AuthService.aulogin(authData)
      .subscribe(
        req => {
          this.isLoginError = false;
          this.router.navigate(['/dashboard/home']);
          this.resetForm(this.formGroup);
          this.spinner.hide();
          this.modal.hide();
        },
        error => {
          this.spinner.hide();
          this.isLoginError = true;
        }
      );
  }

  resetForm(formGroup: FormGroup) {
    let control: AbstractControl = null;
    formGroup.reset();
    formGroup.markAsUntouched();
    Object.keys(formGroup.controls).forEach((name) => {
      control = formGroup.controls[name];
      control.setErrors(null);
    });
  }
}
