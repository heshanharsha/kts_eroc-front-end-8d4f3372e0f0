import { SnotifyService } from 'ng-snotify';
import { Component, OnInit, Input, ViewChild, AfterViewInit } from '@angular/core';
import { FormGroup, Validators, FormControl } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { trigger, state, style, animate, transition } from '@angular/animations';
import { SignUpComponent } from '../sign-up/sign-up.component';
import { DataService } from '../../../storage/data.service';
import { AuthenticationService } from '../../../http/services/authentication.service';
import { compareValidator } from '../../../directive/validator/compare.directive';
import { onIRegWithCred, ICredential } from '../../../http/models/register.model';
import { NgxSpinnerService } from 'ngx-spinner';

@Component({
  selector: 'app-credential',
  templateUrl: './credential.component.html',
  styleUrls: ['./credential.component.scss']
})
export class CredentialComponent implements OnInit {

  @ViewChild(SignUpComponent) SignUpchild;
  public formGroup: FormGroup;
  showSpinner = false;

  constructor(
    private router: Router,
    private AuthService: AuthenticationService,
    private Data: DataService,
    private snotifyService: SnotifyService,
    private spinner: NgxSpinnerService
  ) {

   }

  ngOnInit() {
    this.formGroup = new FormGroup({
      'email': new FormControl('', [
        Validators.email,
        Validators.required
      ]),
      'password': new FormControl('', Validators.required),
      'confirmpassword': new FormControl('', [
        Validators.required,
        compareValidator('password')
      ])
    });
  }

  get email() { return this.formGroup.get('email'); }

  get password() { return this.formGroup.get('password'); }

  get confirmpassword() { return this.formGroup.get('confirmpassword'); }

  onSubmit(): void {

    this.spinner.show();

    const user: ICredential = {
      email: this.email.value,
      password: this.password.value,
      password_confirmation: this.confirmpassword.value
    };

    const onRegWithCred: onIRegWithCred = {
      registerData: this.Data.regData,
      credential: user
    };
    // const form: FormData = new FormData();
    // form.append('name', 'Name');
    // form.append('file',  this.Data.file, this.Data.file.name);
    // console.log(this.Data.file);
    // console.log(form);
    this.AuthService.auRegister(onRegWithCred)
      .subscribe(
        req => {
          this.router.navigate(['activation']);
          this.snotifyService.success('Registration Successful!', 'Success');
          this.spinner.hide();
        },
        error => {
          this.spinner.hide();
          this.snotifyService.error('Registration Unsuccessful!', 'Error');
        }
      );
  }
}
