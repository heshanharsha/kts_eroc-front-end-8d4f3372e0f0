import { Component, OnInit } from '@angular/core';
import { Validators, FormBuilder, FormGroup } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { UserService } from '../../../../../../http/services/user.service';
import { DataService } from '../../../../../../storage/data.service';
import { SnotifyService } from 'ng-snotify';

@Component({
  selector: 'app-name-with-agree-reservation',
  templateUrl: './name-with-agree-reservation.component.html',
  styleUrls: ['./name-with-agree-reservation.component.scss']
})
export class NameWithAgreeReservationComponent implements OnInit {
  name: string;
  postfixname: string;
  companyType: number;
  applicantName: string;
  nameResForm: FormGroup;

  constructor(private router: ActivatedRoute,
    private formBuilder: FormBuilder,
    private route: Router,
    private user: UserService,
    private data: DataService,
    private snotifyService: SnotifyService) { }

  ngOnInit() {
    
    this.getUserInfor();
    if (typeof this.data.storage === 'undefined') {
      this.route.navigate(['/']);
      this.snotifyService.error('Your request has failed. Please try again', 'Error');
    }
    this.name = this.data.storage['name'];
    this.postfixname = this.data.storage['postfix'];
    this.companyType = this.data.storage['comType'];
    
    this.nameResForm = this.formBuilder.group({
      sinhalaName: [null],
      tamilname: [null],
      abreviations: [null],
      agreeCheck: [null, Validators.required]
    });
  }

  getUserInfor(): void {
        this.applicantName = this.data.user.first_name + ' ' +  this.data.user.last_name;
  }

  get getControler() { return this.nameResForm.controls; }
  // onSubmit() {

  //   if (this.getControler.invalid) {
  //     return;
  //   }

  //   const nameReceive: any = {
  //     email: this.service.getEmail(),
  //     typeId: this.res.companyType,
  //     englishName: this.res.name,
  //     sinhalaName: this.getControler.sinhalaName.value,
  //     tamilname: this.getControler.tamilname.value,
  //     postfix: this.res.postfixname,
  //     abreviations: this.getControler.abreviations.value
  //   };

  //   this.resvation.setNameReceive(nameReceive)
  //     .subscribe(
  //       req => {
  //         localStorage.setItem('ID', req['company']);
  //         this.route.navigate(['reservation/documents']);
  //       },
  //       error => {
  //         this.route.navigate(['/']);
  //         this.snotifyService.error(this.res.name + ' ' + this.res.postfixname + ' name reserved Unsuccessfully!', 'Error');
  //       }
  //     );
  // }

  onSubmit() {

    this.data.storage1 = {
      name: this.name.toUpperCase(),
      sinhalaName: this.getControler.sinhalaName.value,
      tamilname: this.getControler.tamilname.value,
      abreviations: this.getControler.abreviations.value
      
    };
    console.log(this.data.storage1);
    this.route.navigate(['/dashboard/societyincorporation']);
  }

}


