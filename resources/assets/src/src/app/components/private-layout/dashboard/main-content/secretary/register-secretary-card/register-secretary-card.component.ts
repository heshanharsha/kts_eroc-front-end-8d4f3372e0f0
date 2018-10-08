import { Component, Input, Output, OnInit, ViewChild } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { ModalDirective } from 'angular-bootstrap-md';
import { NgxSpinnerService } from 'ngx-spinner';
import * as $ from 'jquery';
import { HttpHeaders } from '@angular/common/http';
import { HttpClient } from '@angular/common/http';
import { DomSanitizer } from '@angular/platform-browser';
import { RegisterSecretaryNaturalpComponent } from '../register-secretary-naturalp/register-secretary-naturalp.component';
import { FormControl, Validators, FormGroup } from '@angular/forms';




@Component({
  selector: 'app-register-secretary-card',
  providers: [RegisterSecretaryNaturalpComponent],
  templateUrl: './register-secretary-card.component.html',
  styleUrls: ['./register-secretary-card.component.scss']
})
export class RegisterSecretaryCardComponent implements OnInit {


  enableNic = false;

  nic: string;


  constructor(private comp: RegisterSecretaryNaturalpComponent, private route: ActivatedRoute, private router: Router, ) { }

  ngOnInit() {

    document.getElementById('div1').style.display = 'none';
    document.getElementById('div2').style.display = 'none';
    document.getElementById('div3').style.display = 'block';
    document.getElementById('div4').style.display = 'none';



  }


  sriLankan() {
    document.getElementById('div1').style.display = 'block';
    document.getElementById('div2').style.display = 'none';
    document.getElementById('div3').style.display = 'block';
    document.getElementById('div4').style.display = 'none';
  }
  nonSriLankan() {
    document.getElementById('div1').style.display = 'none';
    document.getElementById('div2').style.display = 'block';
    document.getElementById('div3').style.display = 'none';
    document.getElementById('div4').style.display = 'block';
  }
  navigateRegNaturalSec() {
    this.router.navigate(['dashboard/registersecretarynatural']);
  }
  navigateRegFermSec() {
    this.router.navigate(['dashboard/registersecretaryfirm']);
  }
  navigateRegPvtSec() {
    this.router.navigate(['dashboard/registersecretarypvt']);
  }
  navigateCheckNationality() {
    this.router.navigate(['dashboard/checknationality']);
  }

/*-------------NIC Validation function----------------*/
  nicValidate() {
    if (
      this.nic
    ) {
      this.enableNic = true;
    } else {
      this.enableNic = false;
    }
  }



}
