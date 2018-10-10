import { Component, OnInit,ViewChild, ElementRef } from '@angular/core';
import { FormControl, Validators, FormGroup } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { NgxSpinnerService } from 'ngx-spinner';
import { SecretaryService } from '../../../../../../http/services/secretary.service';
import { HelperService } from '../../../../../../http/shared/helper.service';
import { DataService } from '../../../../../../storage/data.service';
import { ISecretaryData, ISecretaryWorkHistoryData } from '../../../../../../http/models/secretary.model';
import { HttpHeaders } from '@angular/common/http';
import { HttpClient } from '@angular/common/http';
import { DomSanitizer } from '@angular/platform-browser';
import { IncorporationService } from '../../../../../../http/services/incorporation.service';
import { APIConnection } from '../../../../../../http/services/connections/APIConnection';


@Component({
  selector: 'app-society-incorporation',
  templateUrl: './society-incorporation.component.html',
  styleUrls: ['./society-incorporation.component.scss']
})
export class SocietyIncorporationComponent implements OnInit {

  @ViewChild('content') content: ElementRef;


  myForm: FormGroup;


  loggedinUserEmail: string;
  nic: string;
  secTitleId: string;
  nicStatus: string;


  url: APIConnection = new APIConnection();

  //secretary details object to register as a natural person...
  secretaryDetails: ISecretaryData = { registeredUser: false, nic: '', loggedInUser: '', id: 0, title: '', firstname: '', lastname: '', othername: '', residentialLocalAddress1: '', residentialLocalAddress2: '', residentialProvince: '', residentialDistrict: '', residentialCity: '', businessName: '', businessLocalAddress1: '', businessLocalAddress2: '', businessProvince: '', businessDistrict: '', businessCity: '', subClauseQualified: '', pQualification: '', eQualification: '', wExperience: '', isUnsoundMind: '', isInsolventOrBankrupt: '', reason1: '', isCompetentCourt: '', reason2: '', workHis: '', };
  secretaryWorkHistory: ISecretaryWorkHistoryData = { id: 0, companyName: '', position: '', from: '', to: '', };

  enableStep1Submission = false;
  enableStep2Submission = false;
  enableWorkHistorySubmission = false;


  workHistory = [];
  index: string;



  processStatus: string;

  //variables for pdf upload...
  downloadLink: string;
  secId: string;
  application = [];
  eCertificateUploadList = [];
  pCertificateUploadList = [];
  experienceUploadList = [];
  description1: string;
  description2: string;
  description3: string;

  //application: object[] = new Array(1);
  //application = new Array(1); 



  stepOn = 0;

  totalPayment = 0;




  progress = {

    stepArr: [
      { label: 'Personal Details', icon: 'fa fa-list-ol', status: 'active' },
      { label: 'Qualifications', icon: 'fa fa-users', status: '' },
      { label: 'Download Documents', icon: 'fa fa-download', status: '' },
      { label: 'Upload Documents', icon: 'fa fa-upload', status: '' },
      { label: 'Payments', icon: 'fa fa-money-bill-alt', status: '' },

    ],

    progressPercentage: '10%'

  };

  title: Array<{ id: number; name: string; }> = [
    { id: 1, name: 'Mr' },
    { id: 2, name: 'Mrs' },
    { id: 3, name: 'Miss' },
    { id: 4, name: 'Dr' },
    { id: 5, name: 'Proff' },
    { id: 6, name: 'Rev' },
  ];

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




  constructor(public data: DataService, private helper: HelperService, private secretaryService: SecretaryService, private sanitizer: DomSanitizer, private route: ActivatedRoute, private router: Router, private iNcoreService: IncorporationService, private spinner: NgxSpinnerService, private httpClient: HttpClient) {


    this.nic = route.snapshot.paramMap.get('nic');
    this.loadSecretaryData(this.nic);

    this.loggedinUserEmail = localStorage.getItem('currentUser');
    this.loggedinUserEmail = this.loggedinUserEmail.replace(/^"(.*)"$/, '$1');



    // if (this.secretaryDetails.isCompetentCourt === 'no') {
    //   this.secretaryDetails.reason2 = undefined
    // }

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



  sanitize(url: string) {
    return this.sanitizer.bypassSecurityTrustUrl(url);
  }




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


  loadSecretaryData(nic) {
    const data = {
      nic: nic,
    };
    // load secretary data from the server
    this.secretaryService.secretaryData(data)
      .subscribe(
        req => {
          this.secretaryDetails.title = req['data']['secretaryTitle'];
          this.secretaryDetails.firstname = req['data']['secretary']['first_name'];
          this.secretaryDetails.lastname = req['data']['secretary']['last_name'];
          this.secretaryDetails.othername = req['data']['secretary']['other_name'];
          this.secretaryDetails.residentialLocalAddress1 = req['data']['secretaryAddress']['address1'];
          this.secretaryDetails.residentialLocalAddress2 = req['data']['secretaryAddress']['address2'];
          this.secretaryDetails.residentialCity = req['data']['secretaryAddress']['city'];
          this.secretaryDetails.residentialDistrict = req['data']['secretaryAddress']['district'];
          this.secretaryDetails.residentialProvince = req['data']['secretaryAddress']['province'];
          this.secretaryDetails.registeredUser = req['user'];
          this.nicStatus = req['message'];


          //console.log(this.nicStatus);
          console.log(req['data']['secretaryTitle']);

          this.secretaryValidationStep1();
        }
      );

  }

  secretaryDataSubmit() {

    if (this.secretaryDetails['isInsolventOrBankrupt'] === 'no') {
      this.secretaryDetails['reason1'] = 'no'
    }

    if (this.secretaryDetails['isCompetentCourt'] === 'no') {
      this.secretaryDetails['reason2'] = 'no'
    }

    if (this.secretaryDetails['title'] === 'Rev') {
      this.secTitleId = '87'
    } else if (this.secretaryDetails['title'] === 'Mr') {
      this.secTitleId = '88'
    } else if (this.secretaryDetails['title'] === 'Mrs') {
      this.secTitleId = '89'
    } else if (this.secretaryDetails['title'] === 'Miss') {
      this.secTitleId = '90'
    } else if (this.secretaryDetails['title'] === 'Dr') {
      this.secTitleId = '91'
    }


    const data = {
      nic: this.nic,
      loggedInUser: this.loggedinUserEmail,
      registeredUser: this.secretaryDetails['registeredUser'],
      id: this.secretaryDetails['id'],
      title: this.secTitleId,
      firstname: this.secretaryDetails['firstname'],
      lastname: this.secretaryDetails['lastname'],
      othername: this.secretaryDetails['othername'],
      residentialLocalAddress1: this.secretaryDetails['residentialLocalAddress1'],
      residentialLocalAddress2: this.secretaryDetails['residentialLocalAddress2'],
      residentialProvince: this.secretaryDetails['residentialProvince'],
      residentialDistrict: this.secretaryDetails['residentialDistrict'],
      residentialCity: this.secretaryDetails['residentialCity'],
      businessName: this.secretaryDetails['businessName'],
      businessLocalAddress1: this.secretaryDetails['businessLocalAddress1'],
      businessLocalAddress2: this.secretaryDetails['businessLocalAddress2'],
      businessProvince: this.secretaryDetails['businessProvince'],
      businessDistrict: this.secretaryDetails['businessDistrict'],
      businessCity: this.secretaryDetails['businessCity'],
      subClauseQualified: this.secretaryDetails['subClauseQualified'],
      pQualification: this.secretaryDetails['pQualification'],
      eQualification: this.secretaryDetails['eQualification'],
      wExperience: this.secretaryDetails['wExperience'],
      isUnsoundMind: this.secretaryDetails['isUnsoundMind'],
      isInsolventOrBankrupt: this.secretaryDetails['isInsolventOrBankrupt'],
      reason1: this.secretaryDetails['reason1'],
      isCompetentCourt: this.secretaryDetails['isCompetentCourt'],
      reason2: this.secretaryDetails['reason2'],
      workHis: this.workHistory,
    };
    console.log(data);
    this.secretaryService.secretaryDataSubmit(data)
      .subscribe(
        req => {
          console.log(req['data']);
          console.log("sectretary added sucessfuly!!!");
          this.downloadLink = req['data'];
          this.secId = req['secId'];
          this.changeProgressStatuses(2);
        },
        error => {
          console.log(error);
        }
      );
  }



  addWorkHistoryToArray() {
    const data = {
      id: 0,
      companyName: this.secretaryWorkHistory['companyName'],
      position: this.secretaryWorkHistory['position'],
      from: this.secretaryWorkHistory['from'],
      to: this.secretaryWorkHistory['to'],
    };
    this.workHistory.push(data);
    console.log(this.workHistory);
    this.resetAddWorkHistory();
  }

  removeWorkHistoryFromArray(index) {
    if (index > -1) {
      this.workHistory.splice(index, 1);
    }
  }

  resetAddWorkHistory() {
    this.secretaryWorkHistory['companyName'] = '';
    this.secretaryWorkHistory['position'] = '';
    this.secretaryWorkHistory['from'] = '';
    this.secretaryWorkHistory['to'] = '';
  }


  // for uplaod secretary pdf files...
  fileUpload(event, description, docType) {

    let fileList: FileList = event.target.files;

    if (fileList.length > 0) {

      let file: File = fileList[0];

      let fileSize = fileList[0].size;

      if (fileSize > 1024 * 1024 * 4) { // 4mb restriction
        alert('File size should be less than 4 MB');
        return false;
      }


      let formData: FormData = new FormData();

      formData.append('uploadFile', file, file.name);
      formData.append('docType', docType);
      formData.append('secId', this.secId);
      formData.append('description', description);

      let headers = new HttpHeaders();
      headers.append('Content-Type', 'multipart/form-data');
      headers.append('Accept', 'application/json');

      let uploadurl = this.url.getSecretaryNaturalFileUploadUrl();
      //console.log(uploadurl);

      this.spinner.show();

      this.httpClient.post(uploadurl, formData, { headers: headers })
        .subscribe(
          (data: any) => {

            const datas = {
              id: data['docid'],
              name: data['name'],
              token: data['token'],

            };


            //console.log(data);
            if (docType === 'applicationUpload') {
              this.application.push(datas);
            } else if (docType === 'eCertificateUpload') {
              this.eCertificateUploadList.push(datas);
            } else if (docType === 'pCertificateUpload') {
              this.pCertificateUploadList.push(datas);
            } else if (docType === 'experienceUpload') {
              this.experienceUploadList.push(datas);
            }
            this.spinner.hide();
            this.description1 = '';
            this.description2 = '';
            this.description3 = '';
          },
          error => {
            console.log(error);
            this.spinner.hide();
          }
        );
    }



  }

  // for delete the uploaded pdf from the database...
  fileDelete(docId, docType, index) {

    const data = {
      documentId: docId,
    };
    this.spinner.show();
    this.secretaryService.secretaryDeleteUploadedPdf(data)
      .subscribe(
        rq => {
          this.spinner.hide();

          if (index > -1) {
            if (docType === 'applicationUpload') {
              this.application.splice(index, 1);
            } else if (docType === 'eCertificateUpload') {
              this.eCertificateUploadList.splice(index, 1);
            } else if (docType === 'pCertificateUpload') {
              this.pCertificateUploadList.splice(index, 1);
            } else if (docType === 'experienceUpload') {
              this.experienceUploadList.splice(index, 1);
            }
          }
        },
        error => {
          this.spinner.hide();
          console.log(error);
        }

      );

  }


  // for view the uploaded pdf...
  ngOnDownload(token: string): void {
    alert(token);
    this.spinner.show();
    this.secretaryService.getDocumenttoServer(token)
      .subscribe(
        response => {
          this.helper.download(response);
          this.spinner.hide();
        },
        error => {
          this.spinner.hide();
        }
      );
  }











  /*-------------Validation functions----------------*/

  secretaryValidationStep1() {
    if (
      this.secretaryDetails.title &&
      this.secretaryDetails.firstname &&
      this.secretaryDetails.lastname &&
      //this.secretaryDetails.othername &&
      this.secretaryDetails.residentialLocalAddress1 &&
      this.secretaryDetails.residentialLocalAddress2 &&
      this.secretaryDetails.residentialProvince &&
      this.secretaryDetails.residentialCity &&
      this.secretaryDetails.residentialDistrict &&
      this.secretaryDetails.subClauseQualified
    ) {
      this.enableStep1Submission = true;
      if (this.secretaryDetails.businessName) {
        if (this.secretaryDetails.businessLocalAddress1 &&
          this.secretaryDetails.businessLocalAddress2 &&
          this.secretaryDetails.businessProvince &&
          this.secretaryDetails.businessDistrict &&
          this.secretaryDetails.businessCity
        ) {
          this.enableStep1Submission = true;
        } else {
          this.enableStep1Submission = false;
        }
      } else {
        if (this.secretaryDetails.businessLocalAddress1 &&
          this.secretaryDetails.businessLocalAddress2 &&
          this.secretaryDetails.businessProvince &&
          this.secretaryDetails.businessDistrict &&
          this.secretaryDetails.businessCity
        ) {
          this.enableStep1Submission = false;
        } else {
          this.enableStep1Submission = true;
        }
      }
    } else {
      this.enableStep1Submission = false;
    }
  }





  secretaryValidationStep2() {
    if (
      this.secretaryDetails.pQualification &&
      this.secretaryDetails.eQualification &&
      this.secretaryDetails.wExperience &&
      // (this.workHistory != null) &&
      (typeof this.workHistory != "undefined" && this.workHistory != null && this.workHistory.length != null && this.workHistory.length > 0) &&//for check aray is not null
      (this.secretaryDetails.isUnsoundMind === 'yes' || this.secretaryDetails.isUnsoundMind === 'no') &&
      (this.secretaryDetails.isInsolventOrBankrupt === 'yes' || this.secretaryDetails.isInsolventOrBankrupt === 'no') &&
      (this.secretaryDetails.isCompetentCourt === 'yes' || this.secretaryDetails.isCompetentCourt === 'no')
    ) {
      this.enableStep2Submission = true;

      if (this.secretaryDetails.isInsolventOrBankrupt === 'yes') {
        if (this.secretaryDetails.reason1) {
          this.enableStep2Submission = true;
          if (this.secretaryDetails.isCompetentCourt === 'yes') {
            if (this.secretaryDetails.reason2 === 'pardoned' || this.secretaryDetails.reason2 === 'appeal') {
              this.enableStep2Submission = true;
            } else {
              this.enableStep2Submission = false;
            }
          } else if (this.secretaryDetails.isCompetentCourt === 'no') {
            if (this.secretaryDetails.reason2 === 'pardoned' || this.secretaryDetails.reason2 === 'appeal') {
              this.enableStep2Submission = false;
            } else {
              this.enableStep2Submission = true;
            }
          }
        } else {
          this.enableStep2Submission = false;
        }

      } else if (this.secretaryDetails.isInsolventOrBankrupt === 'no') {
        if (this.secretaryDetails.reason1) {
          this.enableStep2Submission = false;
        } else {
          this.enableStep2Submission = true;

          if (this.secretaryDetails.isCompetentCourt === 'yes') {
            if (this.secretaryDetails.reason2 === 'pardoned' || this.secretaryDetails.reason2 === 'appeal') {
              this.enableStep2Submission = true;
            } else {
              this.enableStep2Submission = false;
            }
          } else if (this.secretaryDetails.isCompetentCourt === 'no') {
            if (this.secretaryDetails.reason2 === 'pardoned' || this.secretaryDetails.reason2 === 'appeal') {
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

  //validate add work history modal...  
  secretaryValidationStep3() {
    if (this.secretaryWorkHistory.companyName &&
      this.secretaryWorkHistory.position &&
      this.secretaryWorkHistory.from &&
      this.secretaryWorkHistory.to
    ) {
      this.enableWorkHistorySubmission = true;
    } else {
      this.enableWorkHistorySubmission = false;
    }
  }

}
