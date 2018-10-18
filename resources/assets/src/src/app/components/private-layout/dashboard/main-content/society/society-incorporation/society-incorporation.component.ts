import { Component, OnInit,ViewChild, ElementRef } from '@angular/core';
import { FormControl, Validators, FormGroup } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { NgxSpinnerService } from 'ngx-spinner';
import * as $ from 'jquery';
import { SecretaryService } from '../../../../../../http/services/secretary.service';
import { HelperService } from '../../../../../../http/shared/helper.service';
import { DataService } from '../../../../../../storage/data.service';
import {  ISecretaryWorkHistoryData } from '../../../../../../http/models/secretary.model';
import { ISocietyData,IPresident,ISecretary,ITreasurer,IAddit,IMemb } from '../../../../../../http/models/society.model';
import { HttpHeaders } from '@angular/common/http';
import { HttpClient } from '@angular/common/http';
import { DomSanitizer } from '@angular/platform-browser';
import { IncorporationService } from '../../../../../../http/services/incorporation.service';
import { APIConnection } from '../../../../../../http/services/connections/APIConnection';
import { SocietyService } from '../../../../../../http/services/society.service';
import { IDirectors, IDirector, ISecretories, ISecretory, IShareHolders, IShareHolder } from '../../../../../../http/models/stakeholder.model';


@Component({
  selector: 'app-society-incorporation',
  templateUrl: './society-incorporation.component.html',
  styleUrls: ['./society-incorporation.component.scss']
})
export class SocietyIncorporationComponent implements OnInit {
  name: string;
  sinhalaName: string;
  tamilname: string;
  abreviations: string;
  

  @ViewChild('content') content: ElementRef;


  myForm: FormGroup;


  loggedinUserEmail: string;
  nic: string;
  secTitleId: string;
  nicStatus: string;
  
  


  url: APIConnection = new APIConnection();

  //secretary details object to register as a natural person...
  societyDetails: ISocietyData = { name_of_society: null,id: null,place_of_office: null,whole_of_the_objects: null,funds: null,condition_under_which_any: null,
    terms_of_admission: null,fines_and_foreitures: null,mode_of_holding_meetings: null,manner_of_rules: null,investment_of_funds: null,
    keeping_accounts: null,audit_of_the_accounts: null,annual_returns: null,number_of_members: null,inspection_of_the_books: null,disputes_manner: null,case_of_society: null,email: null,appointment_and_removal_committee: null,applicability: ''};
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

  directorList: IDirectors = { directors: [] };
  
  // tslint:disable-next-line:max-line-length
  director: IDirector = { id: 0, showEditPaneForDirector: 0, type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', phone: '', mobile: '', email: '' };
  president: IPresident = { id: 0,showEditPaneForPresident: 0, type: null,firstname: null, lastname: null,designation_soc: null, province: null, district: null, city: null, localAddress1: null, localAddress2: null, postcode: null, nic: null, designation_type: null, contact_number: null };
  presidents = [];
  presidentValidationMessage = '';
  validPresident = false;
  hideAndshowP = false;

  secretary: ISecretary = { id: 0,showEditPaneForSecretary: 0, type: null,firstname: null, lastname: null,designation_soc: null, province: null, district: null, city: null, localAddress1: null, localAddress2: null, postcode: null, nic: null, designation_type: null, contact_number: null };    
  secretaries = [];
  secretaryValidationMessage = '';
  validSecretary = false;
  hideAndshowS = false;


  treasurer: ITreasurer = { id: 0,showEditPaneForTreasurer: 0, type: null,firstname: null, lastname: null,designation_soc: null, province: null, district: null, city: null, localAddress1: null, localAddress2: null, postcode: null, nic: null, designation_type: null, contact_number: null }; 
  treasurers = [];
  treasurerValidationMessage = '';
  validTreasurer = false;
  hideAndshowT = false;


  addit: IAddit = { id: 0,showEditPaneForAddit: false, type: null,firstname: null, lastname: null,designation_soc: null, province: null, district: null, city: null, localAddress1: null, localAddress2: null, postcode: null, nic: null, designation_type: null, contact_number: null }; 
  addits = [];
  additValidationMessage = '';
  validAddit = false;
  hideAndshowA = false;

  memb: IMemb = { id: 0,showEditPaneForMemb: false, type: null,firstname: null, lastname: null,designation_soc: null, province: null, district: null, city: null, localAddress1: null, localAddress2: null, postcode: null, nic: null, designation_type: null, contact_number: null }; 
  membs = [];
  membValidationMessage = '';
  validMemb = false;
  hideAndshowM = false;
  
  stepOn = 0;

  totalPayment = 0;

  





  progress = {

    stepArr: [
      { label: 'Society Details', icon: 'fa fa-list-ol', status: 'active' },
      { label: 'Members', icon: 'fa fa-users', status: '' },
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


  hideAndShow = false;
  

  constructor(public data: DataService, private helper: HelperService, private secretaryService: SecretaryService,private societyService: SocietyService, private sanitizer: DomSanitizer, private route: ActivatedRoute, private router: Router, private iNcoreService: IncorporationService, private spinner: NgxSpinnerService, private httpClient: HttpClient) {


    this.nic = route.snapshot.paramMap.get('nic');
    //this.loadSecretaryData(this.nic);

    this.loggedinUserEmail = localStorage.getItem('currentUser');
    this.loggedinUserEmail = this.loggedinUserEmail.replace(/^"(.*)"$/, '$1');



    // if (this.secretaryDetails.isCompetentCourt === 'no') {
    //   this.secretaryDetails.reason2 = undefined
    // }

  }

  ngOnInit() {
    // document.getElementById('div1').style.display = 'none';
    // document.getElementById('div2').style.display = 'none';
    // document.getElementById('div3').style.display = 'none';
    document.getElementById('div3').style.display = 'none';
    this.name = this.data.storage1['name'];
    this.sinhalaName = this.data.storage1['sinhalaName'];
    this.tamilname = this.data.storage1['tamilname'];
    this.abreviations = this.data.storage1['abreviations'];

  }

  ngAfterViewInit() {

    $(document).on('click', '.record-handler-remove', function () {
      // tslint:disable-next-line:prefer-const
      let self = $(this);
      self.parent().parent().remove();
    });

    $('button.add-director').on('click', function () {
      $('#director-modal .close-modal-item').trigger('click');
    });

    $('button.add-sec').on('click', function () {
      $('#sec-modal .close-modal-item').trigger('click');
    });

    $('button.add-tre').on('click', function () {
      $('#tre-modal .close-modal-item').trigger('click');
    });

    $('button.add-addit').on('click', function () {
      $('#addit-modal .close-modal-item').trigger('click');
    });

    $('button.add-memb').on('click', function () {
      $('#memb-modal .close-modal-item').trigger('click');
    });

    $('.stakeholder-type-tab-wrapper .tab').on('click', function () {
      // tslint:disable-next-line:prefer-const
      let self = $(this);
      $('.stakeholder-type-tab-wrapper .tab').removeClass('active');
      $(this).addClass('active');

    });


  }
  
  ShowAndHide(){
    this.hideAndShow = !this.hideAndShow;
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
  show5() {
    document.getElementById('div3').style.display = 'block';
  }
  show6() {
    document.getElementById('div3').style.display = 'none';
    this.societyDetails['case_of_society'] =null;
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


  // loadSecretaryData(nic) {
  //   const data = {
  //     nic: nic,
  //   };
    
  //   // load secretary data from the server
  //   this.secretaryService.secretaryData(data)
  //     .subscribe(
  //       req => {
  //         this.societyDetails.name_of_society = req['data']['secretaryTitle'];
  //         this.societyDetails.place_of_office = req['data']['secretary']['first_name'];
  //         this.societyDetails.whole_of_the_objects = req['data']['secretary']['last_name'];
  //         this.societyDetails.funds = req['data']['secretary']['other_name'];
  //         this.societyDetails.terms_of_admission = req['data']['secretaryAddress']['address1'];
  //         this.societyDetails.condition_under_which_any = req['data']['secretaryAddress']['address2'];
  //         this.societyDetails.fines_and_foreitures = req['data']['secretaryAddress']['city'];
  //         this.societyDetails.mode_of_holding_meetings = req['data']['secretaryAddress']['district'];
  //         this.societyDetails.manner_of_rules = req['data']['secretaryAddress']['province'];
  //         this.societyDetails.investment_of_funds = req['user'];
  //         this.societyDetails.audit_of_the_accounts = req['data']['secretaryAddress']['district'];
  //         this.societyDetails.annual_returns = req['data']['secretaryAddress']['province'];
  //         this.societyDetails.number_of_members = req['user'];
  //         this.societyDetails.inspection_of_the_books = req['data']['secretaryAddress']['district'];
  //         this.societyDetails.disputes_manner = req['data']['secretaryAddress']['province'];
  //         this.societyDetails.case_of_society = req['user'];
          


  //         //console.log(this.nicStatus);
  //         console.log(req['data']['secretaryTitle']);

  //         this.societyValidationStep1();
  //       }
  //     );

  // }
  email ='';
  
  getEmail(){
    
    this.email = localStorage.getItem('currentUser');
    this.email = this.email.replace(/^"(.*)"$/, '$1');
    return this.email;
  }
  
  societyDataSubmit() {

  
    const data = {
      
      
      id: this.societyDetails['id'],
      name_of_society: this.societyDetails['name_of_society'],
      place_of_office: this.societyDetails['place_of_office'],
      whole_of_the_objects: this.societyDetails['whole_of_the_objects'],
      funds: this.societyDetails['funds'],
      terms_of_admission: this.societyDetails['terms_of_admission'],
      condition_under_which_any: this.societyDetails['condition_under_which_any'],
      fines_and_foreitures: this.societyDetails['fines_and_foreitures'],
      mode_of_holding_meetings: this.societyDetails['mode_of_holding_meetings'],
      manner_of_rules: this.societyDetails['manner_of_rules'],
      investment_of_funds: this.societyDetails['investment_of_funds'],
      keeping_accounts: this.societyDetails['keeping_accounts'],
      audit_of_the_accounts: this.societyDetails['audit_of_the_accounts'],
      annual_returns: this.societyDetails['annual_returns'],
      number_of_members: this.societyDetails['number_of_members'],
      inspection_of_the_books: this.societyDetails['inspection_of_the_books'],
      appointment_and_removal_committee: this.societyDetails['appointment_and_removal_committee'],
      disputes_manner: this.societyDetails['disputes_manner'],
      case_of_society: this.societyDetails['case_of_society'],
      applicability: this.societyDetails['applicability'],
      email: this.getEmail(),
      name: this.data.storage1['name'],
      sinhalaName: this.data.storage1['sinhalaName'],
      tamilname: this.data.storage1['tamilname'],
      abreviations: this.data.storage1['abreviations']
      
    };

    console.log(data);
    this.societyService.societyDataSubmit(data)
      .subscribe(
        req => {
          console.log(req['message']);
          console.log("society added sucessfuly!!!");
          this.changeProgressStatuses(1);
          this.data.storage2 = {
            societyid: req['socID'] 
          };
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

  societyValidationStep1() {
    if (
      this.societyDetails.name_of_society &&
      this.societyDetails.place_of_office &&
      this.societyDetails.whole_of_the_objects &&
      this.societyDetails.funds &&
      this.societyDetails.terms_of_admission &&
      this.societyDetails.condition_under_which_any &&
      this.societyDetails.fines_and_foreitures &&
      this.societyDetails.mode_of_holding_meetings &&
      this.societyDetails.investment_of_funds &&
      this.societyDetails.keeping_accounts &&
      this.societyDetails.audit_of_the_accounts &&
      this.societyDetails.annual_returns &&
      this.societyDetails.number_of_members &&
      this.societyDetails.inspection_of_the_books &&
      this.societyDetails.disputes_manner 
      //this.societyDetails.case_of_society 
    ) {
      if(this.societyDetails.applicability === 'true' && this.societyDetails.case_of_society){
      this.enableStep1Submission = true;
    }
    else if(this.societyDetails.applicability === 'false'){
      this.enableStep1Submission = true;
    }
    else{
      this.enableStep1Submission = false;
    }
      
      
    }
     else {
      this.enableStep1Submission = false;
    }
  }






  // secretaryValidationStep2() {
  //   if (
  //     this.secretaryDetails.pQualification &&
  //     this.secretaryDetails.eQualification &&
  //     this.secretaryDetails.wExperience &&
  //     // (this.workHistory != null) &&
  //     (typeof this.workHistory != "undefined" && this.workHistory != null && this.workHistory.length != null && this.workHistory.length > 0) &&//for check aray is not null
  //     (this.secretaryDetails.isUnsoundMind === 'yes' || this.secretaryDetails.isUnsoundMind === 'no') &&
  //     (this.secretaryDetails.isInsolventOrBankrupt === 'yes' || this.secretaryDetails.isInsolventOrBankrupt === 'no') &&
  //     (this.secretaryDetails.isCompetentCourt === 'yes' || this.secretaryDetails.isCompetentCourt === 'no')
  //   ) {
  //     this.enableStep2Submission = true;

  //     if (this.secretaryDetails.isInsolventOrBankrupt === 'yes') {
  //       if (this.secretaryDetails.reason1) {
  //         this.enableStep2Submission = true;
  //         if (this.secretaryDetails.isCompetentCourt === 'yes') {
  //           if (this.secretaryDetails.reason2 === 'pardoned' || this.secretaryDetails.reason2 === 'appeal') {
  //             this.enableStep2Submission = true;
  //           } else {
  //             this.enableStep2Submission = false;
  //           }
  //         } else if (this.secretaryDetails.isCompetentCourt === 'no') {
  //           if (this.secretaryDetails.reason2 === 'pardoned' || this.secretaryDetails.reason2 === 'appeal') {
  //             this.enableStep2Submission = false;
  //           } else {
  //             this.enableStep2Submission = true;
  //           }
  //         }
  //       } else {
  //         this.enableStep2Submission = false;
  //       }

  //     } else if (this.secretaryDetails.isInsolventOrBankrupt === 'no') {
  //       if (this.secretaryDetails.reason1) {
  //         this.enableStep2Submission = false;
  //       } else {
  //         this.enableStep2Submission = true;

  //         if (this.secretaryDetails.isCompetentCourt === 'yes') {
  //           if (this.secretaryDetails.reason2 === 'pardoned' || this.secretaryDetails.reason2 === 'appeal') {
  //             this.enableStep2Submission = true;
  //           } else {
  //             this.enableStep2Submission = false;
  //           }
  //         } else if (this.secretaryDetails.isCompetentCourt === 'no') {
  //           if (this.secretaryDetails.reason2 === 'pardoned' || this.secretaryDetails.reason2 === 'appeal') {
  //             this.enableStep2Submission = false;
  //           } else {
  //             this.enableStep2Submission = true;
  //           }
  //         }
  //       }
  //     }
  //   } else {
  //     this.enableStep2Submission = false;
  //   }
  // }

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

  validatePresident() {

      if (!
        (
        this.president.nic && this.validateNIC(this.president.nic) &&
        this.president.firstname &&
        this.president.lastname &&
        this.president.designation_soc &&
        this.president.province &&
        this.president.district &&
        this.president.city &&
        this.president.contact_number && this.phonenumber(this.president.contact_number) &&
        this.president.localAddress1 &&
        this.president.localAddress2 &&
        this.president.postcode

        

      )


      ) {


        this.presidentValidationMessage = 'Please fill all  required fields denoted by asterik(*)';
        this.validPresident = false;

        return false;
      } else {

        this.presidentValidationMessage = '';
        this.validPresident = true;
        return true;

      }


  }
  addPresidentDataToArray() {
    const data = {
      id: 0,
      showEditPaneForPresident: 0,
      firstname: this.president['firstname'],
      lastname: this.president['lastname'],
      designation_soc: this.president['designation_soc'],
      province: this.president['province'],
      district: this.president['district'],
      city: this.president['city'],
      localAddress1: this.president['localAddress1'],
      localAddress2: this.president['localAddress2'],
      postcode: this.president['postcode'],
      nic: this.president['nic'],
      contact_number: this.president['contact_number'],
      designation_type: 1
    };
    this.presidents.push(data);
    console.log(this.presidents);
  }
  
  editPresidentDataArray() {
    const data = {
      id: 0,
      showEditPaneForPresident: 0,
      firstname: this.president['firstname'],
      lastname: this.president['lastname'],
      designation_soc: this.president['designation_soc'],
      province: this.president['province'],
      district: this.president['district'],
      city: this.president['city'],
      localAddress1: this.president['localAddress1'],
      localAddress2: this.president['localAddress2'],
      postcode: this.president['postcode'],
      nic: this.president['nic'],
      contact_number: this.president['contact_number'],
      designation_type: 1
    };
    this.presidents.push(data);
    console.log(this.presidents);
  }

  validateSecretary() {

    if (!
      (
      this.secretary.nic && this.validateNIC(this.secretary.nic) &&
      this.secretary.firstname &&
      this.secretary.lastname &&
      this.secretary.designation_soc &&
      this.secretary.province &&
      this.secretary.district &&
      this.secretary.city &&
      this.secretary.contact_number && this.phonenumber(this.secretary.contact_number) &&
      this.secretary.localAddress1 &&
      this.secretary.localAddress2 &&
      this.secretary.postcode

      

    )


    ) {


      this.secretaryValidationMessage = 'Please fill all  required fields denoted by asterik(*)';
      this.validSecretary = false;

      return false;
    } else {

      this.secretaryValidationMessage = '';
      this.validSecretary = true;
      return true;

    }


}

addSecretaryDataToArray() {
  const data = {
    id: 0,
    showEditPaneForSecretary: 0,
    firstname: this.secretary['firstname'],
    lastname: this.secretary['lastname'],
    designation_soc: this.secretary['designation_soc'],
    province: this.secretary['province'],
    district: this.secretary['district'],
    city: this.secretary['city'],
    localAddress1: this.secretary['localAddress1'],
    localAddress2: this.secretary['localAddress2'],
    postcode: this.secretary['postcode'],
    nic: this.secretary['nic'],
    contact_number: this.secretary['contact_number'],
    designation_type: 2
  };
  this.secretaries.push(data);
  console.log(this.secretaries);
}



validateTreasurer() {

  if (!
    (
    this.treasurer.nic && this.validateNIC(this.treasurer.nic) &&
    this.treasurer.firstname &&
    this.treasurer.lastname &&
    this.treasurer.designation_soc &&
    this.treasurer.province &&
    this.treasurer.district &&
    this.treasurer.city &&
    this.treasurer.contact_number && this.phonenumber(this.treasurer.contact_number) &&
    this.treasurer.localAddress1 &&
    this.treasurer.localAddress2 &&
    this.treasurer.postcode

    

  )


  ) {


    this.treasurerValidationMessage = 'Please fill all  required fields denoted by asterik(*)';
    this.validTreasurer = false;

    return false;
  } else {

    this.treasurerValidationMessage = '';
    this.validTreasurer = true;
    return true;

  }


}
addTreasurerDataToArray() {
const data = {
  id: 0,
  showEditPaneForTreasurer: 0,
  firstname: this.treasurer['firstname'],
  lastname: this.treasurer['lastname'],
  designation_soc: this.treasurer['designation_soc'],
  province: this.treasurer['province'],
  district: this.treasurer['district'],
  city: this.treasurer['city'],
  localAddress1: this.treasurer['localAddress1'],
  localAddress2: this.treasurer['localAddress2'],
  postcode: this.treasurer['postcode'],
  nic: this.treasurer['nic'],
  contact_number: this.treasurer['contact_number'],
  designation_type: 3
};
this.treasurers.push(data);
console.log(this.treasurers);
}



validateAddit() {

  if (!
    (
    this.addit.nic && this.validateNIC(this.addit.nic) &&
    this.addit.firstname &&
    this.addit.lastname &&
    this.addit.designation_soc &&
    this.addit.province &&
    this.addit.district &&
    this.addit.city &&
    this.addit.contact_number && this.phonenumber(this.addit.contact_number) &&
    this.addit.localAddress1 &&
    this.addit.localAddress2 &&
    this.addit.postcode

    

  )


  ) {


    this.additValidationMessage = 'Please fill all  required fields denoted by asterik(*)';
    this.validAddit = false;

    return false;
  } else {

    this.additValidationMessage = '';
    this.validAddit = true;
    return true;

  }


}
addAdditDataToArray() {
const data = {
  id: 0,
  showEditPaneForAddit: false,
  firstname: this.addit['firstname'],
  lastname: this.addit['lastname'],
  designation_soc: this.addit['designation_soc'],
  province: this.addit['province'],
  district: this.addit['district'],
  city: this.addit['city'],
  localAddress1: this.addit['localAddress1'],
  localAddress2: this.addit['localAddress2'],
  postcode: this.addit['postcode'],
  nic: this.addit['nic'],
  contact_number: this.addit['contact_number'],
  designation_type: 4
};
this.addits.push(data);
console.log(this.addits);
console.log(this.addits.length);
this.addit = { id: 0,showEditPaneForAddit: false, type: null,firstname: null, lastname: null,designation_soc: null, province: null, district: null, city: null, localAddress1: null, localAddress2: null, postcode: null, nic: null, designation_type: null, contact_number: null };
}




validateMemb() {

  if (!
    (
    this.memb.nic && this.validateNIC(this.memb.nic) &&
    this.memb.firstname &&
    this.memb.lastname &&
    this.memb.designation_soc &&
    this.memb.province &&
    this.memb.district &&
    this.memb.city &&
    this.memb.contact_number && this.phonenumber(this.memb.contact_number) &&
    this.memb.localAddress1 &&
    this.memb.localAddress2 &&
    this.memb.postcode

    

  )


  ) {


    this.membValidationMessage = 'Please fill all  required fields denoted by asterik(*)';
    this.validMemb = false;

    return false;
  } else {

    this.membValidationMessage = '';
    this.validMemb = true;
    return true;

  }


}
addMembDataToArray() {
const data = {
  id: 0,
  showEditPaneForMemb: false,
  firstname: this.memb['firstname'],
  lastname: this.memb['lastname'],
  designation_soc: this.memb['designation_soc'],
  province: this.memb['province'],
  district: this.memb['district'],
  city: this.memb['city'],
  localAddress1: this.memb['localAddress1'],
  localAddress2: this.memb['localAddress2'],
  postcode: this.memb['postcode'],
  nic: this.memb['nic'],
  contact_number: this.memb['contact_number'],
  designation_type: 5
};
this.membs.push(data);
console.log(this.membs);
console.log(this.membs.length);
this.memb = { id: 0,showEditPaneForMemb: false, type: null,firstname: null, lastname: null,designation_soc: null, province: null, district: null, city: null, localAddress1: null, localAddress2: null, postcode: null, nic: null, designation_type: null, contact_number: null };
}




  private validateNIC(nic) {
    if (!nic) {
      return true;
    }
    // tslint:disable-next-line:prefer-const
    let regx = /^[0-9]{9}[x|X|v|V]$/;
    return nic.match(regx);
  }

  private phonenumber(inputtxt) {
    if (!inputtxt) { return true; }
    // tslint:disable-next-line:prefer-const
    let phoneno = /^\d{10}$/;
    return inputtxt.match(phoneno);
  }
  

  

  
  showToggle(userType, index= 0 ) {

    if (userType === 'president') {

      // tslint:disable-next-line:prefer-const
      this.presidents[index]['showEditPaneForPresident'] = index;
      this.hideAndshowP = !this.hideAndshowP;
      return true;
        
      
    }

    if (userType === 'sec') {

      // tslint:disable-next-line:prefer-const
      this.secretaries[index]['showEditPaneForSecretary'] = index;
      this.hideAndshowS = !this.hideAndshowS;
      return true;
    }

    if (userType === 'tre') {

      // tslint:disable-next-line:prefer-const
      this.treasurers[index]['showEditPaneForTreasurer'] = index;
      this.hideAndshowT = !this.hideAndshowT;
      return true;
    }

    if (userType === 'addit') {

      // tslint:disable-next-line:prefer-const
      this.addits[index]['showEditPaneForAddit'] = !this.addits[index]['showEditPaneForAddit'];
      this.hideAndshowA = !this.hideAndshowA;
      return true;
    }

    if (userType === 'memb') {

      // tslint:disable-next-line:prefer-const
      this.membs[index]['showEditPaneForMemb'] = !this.membs[index]['showEditPaneForMemb'];
      this.hideAndshowM = !this.hideAndshowM;
      return true;
    }

  }
  

}
