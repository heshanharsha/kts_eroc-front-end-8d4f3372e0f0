import { Component, OnInit } from '@angular/core';
import { Validators, FormBuilder, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../../../../../http/shared/auth.service';
import { ReservationComponent } from '../reservation.component';
import { NameResarvationService } from '../../../../../../http/services/name-resarvation.service';
import { SnotifyService } from 'ng-snotify';

@Component({
  selector: 'app-name-with-agree',
  templateUrl: './name-with-agree.component.html',
  styleUrls: ['./name-with-agree.component.scss']
})
export class NameWithAgreeComponent implements OnInit {

  nameResForm: FormGroup;

  constructor(
    private formBuilder: FormBuilder,
    public route: Router,
    private service: AuthService,
    public res: ReservationComponent,
    public resvation: NameResarvationService,
    private snotifyService: SnotifyService
  ) { }

  ngOnInit() {
    this.nameResForm = this.formBuilder.group({
      sinhalaName: [null],
      tamilname: [null],
      abreviations: [null],
      agreeCheck: [null, Validators.required]
    });
  }

  get getControler() { return this.nameResForm.controls; }

  onSubmit() {

    if (this.getControler.invalid) {
      return;
    }

    const nameReceive: any = {
      email: this.service.getEmail(),
      typeId: this.res.companyType,
      englishName: this.res.name,
      sinhalaName: this.getControler.sinhalaName.value,
      tamilname: this.getControler.tamilname.value,
      postfix: this.res.postfixname,
      abreviations: this.getControler.abreviations.value
    };

    this.resvation.setNameReceive(nameReceive)
      .subscribe(
        req => {
          localStorage.setItem('ID', req['company']);
          this.route.navigate(['reservation/documents']);
        },
        error => {
          this.route.navigate(['/']);
          this.snotifyService.error(this.res.name + ' ' + this.res.postfixname + ' name reserved Unsuccessfully!', 'Error');
        }
      );
  }


}
