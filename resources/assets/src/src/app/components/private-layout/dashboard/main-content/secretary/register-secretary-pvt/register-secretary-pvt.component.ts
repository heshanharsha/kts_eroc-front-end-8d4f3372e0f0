import { Component, OnInit } from '@angular/core';
// import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { FormControl, Validators, FormGroup } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { NgxSpinnerService } from 'ngx-spinner';
import { SecretaryService } from '../../../../../../http/services/secretary.service';
import { DataService } from '../../../../../../storage/data.service';
import { ISecretaryDataFirm, ISecretaryFirmPartnerData } from '../../../../../../http/models/secretary.model';
import * as $ from 'jquery';
import { HttpHeaders } from '@angular/common/http';
import { HttpClient } from '@angular/common/http';
import { DomSanitizer } from '@angular/platform-browser';
import { IncorporationService } from '../../../../../../http/services/incorporation.service';
import { APIConnection } from '../../../../../../http/services/connections/APIConnection';

@Component({
  selector: 'app-register-secretary-pvt',
  templateUrl: './register-secretary-pvt.component.html',
  styleUrls: ['./register-secretary-pvt.component.scss']
})
export class RegisterSecretaryPvtComponent implements OnInit {


  nic: string;
  nicStatus: string;
  visiblePartnerDetailBlock = false;

  url: APIConnection = new APIConnection();

  //secretary details object to register as a natural person...  
  secretaryFirmDetails: ISecretaryDataFirm = { id: 0, name: '', registrationNumber: '', businessLocalAddress1: '', businessLocalAddress2: '', businessProvince: '', isUndertakeSecWork: '', businessDistrict: '', businessCity: '', isUnsoundMind: '', isInsolventOrBankrupt: '', reason1: '', isCompetentCourt: '', reason2: '', firmPartners: '', };
  secretaryFirmPartners: ISecretaryFirmPartnerData = { id: 0, name: '', residentialAddress: '', citizenship: '', whichQualified: '', pQualification: '', };

  secretaryFirmPartnerDetails = [];
  enableStep1Submission = false;
  enableStep2Submission = false;


  processStatus: string;

  docList = [];
  uploadList = [];
  uploadedList = [];

  stepOn = 0;

  totalPayment = 0;

  progress = {

    stepArr: [
      { label: 'Firm Details', icon: 'fa fa-list-ol', status: 'active' },
      { label: 'Add Partners', icon: 'fa fa-users', status: '' },
      { label: 'Download Documents', icon: 'fa fa-download', status: '' },
      { label: 'Upload Documents', icon: 'fa fa-upload', status: '' },
      { label: 'Payments', icon: 'fa fa-money-bill-alt', status: '' },

    ],

    progressPercentage: '10%'

  };


  provinces: Array<{ id: number; name: string; }> = [

    { id: 1, name: 'Western' },
    { id: 2, name: 'Central' },
    { id: 3, name: 'Northern' },
    { id: 4, name: 'North Western' },


  ];
  districts: Array<{ id: number; name: string; }> = [

    { id: 1, name: 'Colombo' },
    { id: 2, name: 'Gampaha' },
    { id: 3, name: 'Kaluthara' },
    { id: 4, name: 'Ampara' },
    { id: 5, name: 'Anuradhapura' },

  ];
  cities: Array<{ id: number; name: string; }> = [

    { id: 1, name: 'Colomb 01' },
    { id: 2, name: 'Colombo 02' },
    { id: 3, name: 'Mt. Lavinia' },
    { id: 4, name: 'Maharagama' },
    { id: 5, name: 'Moratuwa' },
  ];











  constructor(public data: DataService, private secretaryService: SecretaryService, private sanitizer: DomSanitizer, private route: ActivatedRoute, private router: Router, private iNcoreService: IncorporationService, private spinner: NgxSpinnerService, private httpClient: HttpClient) {




  }

  ngOnInit() {

    document.getElementById('div1').style.display = 'none';
    document.getElementById('div2').style.display = 'none';
  }


  /*.....below show () functions for the radio buttons....*/
  show1() {
    document.getElementById('div1').style.display = 'none';
  }
  show2() {
    document.getElementById('div1').style.display = 'block';
  }
  show3() {
    document.getElementById('div2').style.display = 'none';
  }
  show4() {
    document.getElementById('div2').style.display = 'block';
  }
  /*.....above show () functions for the radio buttons....*/


  changeProgressStatuses(newStatus = 1) {

    this.stepOn = newStatus;
    this.progress.progressPercentage = (this.stepOn >= 4) ? (10 * 2 + this.stepOn * 20) + '%' : (10 + this.stepOn * 20) + '%';

    for (let i = 0; i < this.progress['stepArr'].length; i++) {


      if (this.stepOn > i) {

        this.progress['stepArr'][i]['status'] = 'activated';
      } else if (this.stepOn === i) {



        this.progress['stepArr'][i]['status'] = 'active';
      } else {
        this.progress['stepArr'][i]['status'] = '';
      }


    }

    return this.progress;
  }





  secretaryFirmDataSubmit() {

    if (this.secretaryFirmDetails['isCompetentCourt'] = 'no') {

      this.secretaryFirmDetails['reason2'] = 'no'

    }

    if (this.secretaryFirmDetails['isInsolventOrBankrupt'] = 'no') {

      this.secretaryFirmDetails['reason1'] = 'no'

    }


    const data = {
      id: this.secretaryFirmDetails['id'],
      name: this.secretaryFirmDetails['name'],
      registrationNumber: this.secretaryFirmDetails['registrationNumber'],
      businessLocalAddress1: this.secretaryFirmDetails['businessLocalAddress1'],
      businessLocalAddress2: this.secretaryFirmDetails['businessLocalAddress2'],
      businessProvince: this.secretaryFirmDetails['businessProvince'],
      businessDistrict: this.secretaryFirmDetails['businessDistrict'],
      businessCity: this.secretaryFirmDetails['businessCity'],
      isUndertakeSecWork: this.secretaryFirmDetails['isUndertakeSecWork'],
      isUnsoundMind: this.secretaryFirmDetails['isUnsoundMind'],
      isInsolventOrBankrupt: this.secretaryFirmDetails['isInsolventOrBankrupt'],
      reason1: this.secretaryFirmDetails['reason1'],
      isCompetentCourt: this.secretaryFirmDetails['isCompetentCourt'],
      reason2: this.secretaryFirmDetails['reason2'],
      firmPartners: this.secretaryFirmPartnerDetails,


    };
    console.log(data);
    this.secretaryService.secretaryFirmDataSubmit(data)
      .subscribe(
        req => {
          console.log(req['data']);
          console.log("sectretary firm details added sucessfuly!!!");
          this.changeProgressStatuses(2);
        },
        error => {
          console.log(error);
        }

      );


  }


  addSecretaryFirmPartnerDetailsToArray() {

    const data = {

      id: 0,
      name: this.secretaryFirmPartners['name'],
      residentialAddress: this.secretaryFirmPartners['residentialAddress'],
      citizenship: this.secretaryFirmPartners['citizenship'],
      whichQualified: this.secretaryFirmPartners['whichQualified'],
      pQualification: this.secretaryFirmPartners['pQualification']

    };

    this.secretaryFirmPartnerDetails.push(data);

    console.log(this.secretaryFirmPartnerDetails);


  }


  clickNic() {
    this.loadSecretaryFirmPartnerData(this.nic);
    this.visiblePartnerDetailBlock = true;
  }

  loadSecretaryFirmPartnerData(nic) {

    const data = {
      nic: nic,

    };

    // load data from the server
    this.secretaryService.secretaryFirmPartnerData(data)
      .subscribe(
        req => {


          var fname = req['data']['partner']['first_name'];
          var lname = req['data']['partner']['last_name'];
          var oname = req['data']['partner']['other_name'];

          var address1 = req['data']['partnerAddress']['address1'];
          var address2 = req['data']['partnerAddress']['address2'];
          var district = req['data']['partnerAddress']['district'];
          var province = req['data']['partnerAddress']['province'];
          var city = req['data']['partnerAddress']['city'];

          this.secretaryFirmPartners.name = (fname + ' ' + lname + ' ' + oname);
          this.secretaryFirmPartners.residentialAddress = (address1 + ', ' + address2 + ', ' + province + ', ' + district + ', ' + city);
          this.secretaryFirmPartners.citizenship = req['data']['partner']['is_srilankan'];
          // this.secretaryFirmPartners.pQualification = req['data']['partner']['is_srilankan'];

        }
      );





  }




  secretaryPvtValidationStep1() {

    if (
      this.secretaryFirmDetails.name &&
      this.secretaryFirmDetails.registrationNumber &&
      this.secretaryFirmDetails.businessLocalAddress1 &&
      this.secretaryFirmDetails.businessLocalAddress2 &&
      this.secretaryFirmDetails.businessProvince &&
      this.secretaryFirmDetails.businessCity &&
      this.secretaryFirmDetails.businessDistrict &&
      this.secretaryFirmDetails.isUndertakeSecWork
    ) {
      this.enableStep1Submission = true;
    } else {
      this.enableStep1Submission = false;
    }
  }

  secretaryPvtValidationStep2() {
    if (
      (this.secretaryFirmDetails.isUnsoundMind === 'yes' || this.secretaryFirmDetails.isUnsoundMind === 'no') &&
      (this.secretaryFirmDetails.isInsolventOrBankrupt === 'yes' || this.secretaryFirmDetails.isInsolventOrBankrupt === 'no') &&
      (this.secretaryFirmDetails.isCompetentCourt === 'yes' || this.secretaryFirmDetails.isCompetentCourt === 'no')
    ) {
      this.enableStep2Submission = true;

      if (this.secretaryFirmDetails.isInsolventOrBankrupt === 'yes') {
        if (this.secretaryFirmDetails.reason1) {
          this.enableStep2Submission = true;
          if (this.secretaryFirmDetails.isCompetentCourt === 'yes') {
            if (this.secretaryFirmDetails.reason2 === 'pardoned' || this.secretaryFirmDetails.reason2 === 'appeal') {
              this.enableStep2Submission = true;
            } else {
              this.enableStep2Submission = false;
            }
          } else if (this.secretaryFirmDetails.isCompetentCourt === 'no') {
            if (this.secretaryFirmDetails.reason2 === 'pardoned' || this.secretaryFirmDetails.reason2 === 'appeal') {
              this.enableStep2Submission = false;
            } else {
              this.enableStep2Submission = true;
            }
          }
        } else {
          this.enableStep2Submission = false;
        }

      } else if (this.secretaryFirmDetails.isInsolventOrBankrupt === 'no') {
        if (this.secretaryFirmDetails.reason1) {
          this.enableStep2Submission = false;
        } else {
          this.enableStep2Submission = true;

          if (this.secretaryFirmDetails.isCompetentCourt === 'yes') {
            if (this.secretaryFirmDetails.reason2 === 'pardoned' || this.secretaryFirmDetails.reason2 === 'appeal') {
              this.enableStep2Submission = true;
            } else {
              this.enableStep2Submission = false;
            }
          } else if (this.secretaryFirmDetails.isCompetentCourt === 'no') {
            if (this.secretaryFirmDetails.reason2 === 'pardoned' || this.secretaryFirmDetails.reason2 === 'appeal') {
              this.enableStep2Submission = false;
            } else {
              this.enableStep2Submission = true;
            }
          }
        }
      }
    } else {
      this.enableStep2Submission = false;
    }
  }








}
