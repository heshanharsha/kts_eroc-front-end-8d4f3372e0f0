import { Component, OnInit, AfterViewInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { NgxSpinnerService } from 'ngx-spinner';
import * as $ from 'jquery';
import { HttpHeaders } from '@angular/common/http';
import { HttpClient } from '@angular/common/http';
import {DomSanitizer} from '@angular/platform-browser';
import { IncorporationService } from '../../../../../http/services/incorporation.service';
import { IDirectors, IDirector, ISecretories, ISecretory, IShareHolders, IShareHolder } from '../../../../../http/models/stakeholder.model';
import { APIConnection } from '../../../../../http/services/connections/APIConnection';
import { IcompanyInfo, IcompanyAddress, IcompanyType, IcompnayTypesItem, IcompanyObjective, IloginUserAddress, IloginUser, IcoreShareGroup, Icountry } from '../../../../../http/models/incorporation.model';
@Component({
  selector: 'app-incomparation',
  templateUrl: './incomparation.component.html',
  styleUrls: ['./incomparation.component.scss'],
})
export class IncomparationComponent implements OnInit, AfterViewInit {

  url: APIConnection = new APIConnection();
  // company id
  companyId: string;
  loginUserEmail: string;
  // process status
  processStatus: string;
  loginUserInfo: IloginUser;
  loginUserAddress: IloginUserAddress;
  // company_types
  companyTypes: Array<IcompnayTypesItem> = [];
  companyObjectives: Array<IcompanyObjective> = [];

  companyInfo: IcompanyInfo = {

    abbreviation_desc: '', address_id: null, created_at: null, created_by: null, email: '', id: null, name: '', name_si: '', name_ta: '', objective: '', postfix: '', status: null, type_id: null,  updated_at: null
  };

  companyAddress: IcompanyAddress = { address1: '', address2: '', city: '', country: '', created_at: '', district: '', id: 0, postcode: '', province: '', updated_at: ''
  };

  compayType: IcompanyType = { key: '', value: '', id: null, value_si: '', value_ta: '' };


  coreShareGroups: Array<IcoreShareGroup> = [];
  paymentSuccess = false;
  resubmitSuccess = false;
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
  cityRecs = [

    {
      id: 1,
      name: 'Western',
      districts: [
        {
          id: 1,
          name: 'Colombo',
          cities: [

            { id: 1, name: 'Colomb 01' },
            { id: 2, name: 'Colombo 02' },
            { id: 3, name: 'Mt. Lavinia' },
            { id: 4, name: 'Maharagama' },
            { id: 5, name: 'Moratuwa' },
          ]
        },
        {
          id: 2,
          name: 'Gampaha',
          cities: [

            { id: 6, name: 'Attanagalla' },
            { id: 7, name: 'Biyagama' },
            { id: 8, name: 'Divulapitiya' },
          ]
        }

      ]
    },

    {
      id: 2,
      name: 'Central',
      districts: [
        {
          id: 3,
          name: 'Kandy',
          cities: [

            { id: 9, name: 'Kandy' },
            { id: 10, name: 'Gampola' },
            { id: 11, name: 'Hatton' },

          ]
        },
        {
          id: 4,
          name: 'Nuwara Eliya',
          cities: [

            { id: 12, name: 'Ambewela' },
            { id: 13, name: 'Bogawantalawa' },
            { id: 14, name: 'Haggala' },
          ]
        }

      ]
    },

  ];
  countries: Array<Icountry> = [];
  docList = [];
  uploadList = [];
  uploadedList = [];

  stepOn = 0;

  payment = {
    'FORM01': { val: 4000.00, copies: 1 },
    'FORM05': { val: 4000.00, copies: 1 },
    'FORM18': { val: 2000.00, copies: 1 },
    'FORM19': { val: 2000.00, copies: 1 },
    'FORM44': { val: 2000.00, copies: 1 },
    'FORM45': { val: 2000.00, copies: 1 },
    'FORM46': { val: 2000.00, copies: 1 },
    'FORMAASSOC': { val: 2000.00, copies: 1 },
    'COMP_REG': { val: 1500.00, copies: 1 },
    'RCCCIC': { val: 1500.00, copies: 1 },
    'RCMAC': { val: 1500.00, copies: 1 },
    'RPACL': { val: 1500.00, copies: 1 },

  };

  totalPayment = 0;

  progress = {

    stepArr: [
      { label: 'Company Details', icon: 'fa fa-list-ol', status: 'active' },
      { label: 'Stakeholders', icon: 'fa fa-users', status: '' },
      { label: 'Download Documents', icon: 'fa fa-download', status: '' },
      { label: 'Upload Documents', icon: 'fa fa-upload', status: '' },
      // { label: 'Payments', icon: 'fa fa-money-bill-alt', status: '' },

    ],

    progressPercentage: '10%'

  };


  coreShareHolderGroups = [
    { id: 1, name: 'Group1' }, { id: 2, name: 'Group2' }, { id: 3, name: 'Group3' }
  ];


  companyDocuments = [];

  // director interfaces
  directorList: IDirectors = { directors: [] };
  // tslint:disable-next-line:max-line-length
  director: IDirector = { id: 0, showEditPaneForDirector: 0, type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', phone: '', mobile: '', email: '' };
  // sercretory interfaces
  secList: ISecretories = { secs: [] };
  // tslint:disable-next-line:max-line-length
  sec: ISecretory = { id: 0, showEditPaneForSec: 0,  type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', isReg: false, regDate: '', phone: '', mobile: '', email: '' };
  // share holder interfaces
  shList: IShareHolders = { shs: [] };
  // tslint:disable-next-line:max-line-length
  public sh: IShareHolder = { id: 0, showEditPaneForSh: 0,  type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', phone: '', mobile: '', email: '', shareType: 'single', noOfShares: 0 };

  directorToShareHolder = false;
  shareHolderToDirector = false;

  /**validation vars */

  step1ValidationStatus = false;
  enableStep1Submission = false;

  enableStep2Submission = false;
  enableStep2SubmissionEdit = true;
  step2SubmitMessage = '';
  step2SubmitStatus = false;
  enableStep2Next = false;

  directorValitionMessage = '';
  directorAlreadyExistMessage = '';
  secAlreadyExistMessage = '';
  shAlreadyExistMessage = '';

  designationValidationRule = '';

  validDirector = false;
  shValitionMessage = '';
  validSh = false;
  secValitionMessage = '';
  validSec = false;


  directorNicLoaded = false;
  directorPassportLoaded = false;

  secNicLoaded = false;
  shNicLoaded = false;

  validateUploadeStatusFlag = false;


  getProvinces() {
    // tslint:disable-next-line:prefer-const
    for (let i in this.cityRecs) {
      // tslint:disable-next-line:prefer-const
      let rec = {
        id: this.cityRecs[i].id,
        name: this.cityRecs[i].name
      };
      this.provinces.push(rec);
    }

  }

  getDistcits(provinceName) {

    this.districts = [];
   // tslint:disable-next-line:prefer-const
    for (let i in this.cityRecs) {

      console.log('name::' + this.cityRecs[i].name);
      console.log('sup province::' + provinceName);

      if (this.cityRecs[i].name === provinceName) {
        // tslint:disable-next-line:prefer-const
        let distrcits = this.cityRecs[i].districts;

        console.log('province name is::' + provinceName );

        // tslint:disable-next-line:prefer-const
        for (let j in distrcits) {
          // tslint:disable-next-line:prefer-const
          let rec = {
            id: distrcits[j].id,
            name: distrcits[j].name
          };
          this.districts.push(rec);
        }
        console.log('districts list');
        console.log(this.districts);

        return true;


      } else {
        continue;
      }

    }

  }

  getCities(provinceName, districtName) {
    this.cities = [];
    // tslint:disable-next-line:prefer-const
    for (let i in this.cityRecs) {

      if (this.cityRecs[i].name === provinceName) {

        // tslint:disable-next-line:prefer-const
        let distrcits = this.cityRecs[i].districts;

        // tslint:disable-next-line:prefer-const
        for (let j in distrcits) {

          if (distrcits[j].name === districtName) {
            // tslint:disable-next-line:prefer-const
            let cities = distrcits[j].cities;

            // tslint:disable-next-line:prefer-const
            for (let k in cities) {

              // tslint:disable-next-line:prefer-const
              let rec = {
                id: cities[k].id,
                name: cities[k].name
              };
              this.cities.push(rec);

            }

          } else {
            continue;
          }

        }

      } else {
        continue;
      }

    }

  }


  constructor(private sanitizer: DomSanitizer , private route: ActivatedRoute, private router: Router, private iNcoreService: IncorporationService, private spinner: NgxSpinnerService, private httpClient: HttpClient) {

    this.companyInfo.email = '';
    this.companyInfo.objective = '';

    this.companyId = route.snapshot.paramMap.get('companyId');
    this.loginUserEmail = localStorage.getItem('currentUser');

    this.loadData();

    // tslint:disable-next-line:prefer-const
    for (let i in this.payment) {
      console.log(this.payment[i]);
      this.totalPayment = this.totalPayment + parseFloat(this.payment[i]['val']) * parseFloat(this.payment[i]['copies']);
      console.log(this.totalPayment);
    }
  }

  sanitize( url: string ) {
    return this.sanitizer.bypassSecurityTrustUrl(url);
}

  calculatePayment() {
    this.totalPayment = 0;
    // tslint:disable-next-line:prefer-const
    for (let i in this.payment) {

      this.totalPayment = this.totalPayment + this.payment[i]['val'] * this.payment[i]['copies'];

    }

  }

  showToggle(userType, userId= 0 ) {

    if (userType === 'director') {

      // tslint:disable-next-line:prefer-const
      for (let i in this.directorList.directors) {
        if (this.directorList.directors[i]['id'] === userId) {
          this.directorList.directors[i]['showEditPaneForDirector'] = this.directorList.directors[i]['showEditPaneForDirector'] === userId ? null : userId;
          return true;
        }
      }
    }

    if (userType === 'sec') {

      // tslint:disable-next-line:prefer-const
      for (let i in this.secList.secs) {

        if (this.secList.secs[i]['id'] === userId) {

          this.secList.secs[i]['showEditPaneForSec'] = this.secList.secs[i]['showEditPaneForSec'] === userId ? null : userId;
          return true;
        }
      }
    }

    if (userType === 'sh') {

      // tslint:disable-next-line:prefer-const
      for (let i in this.shList.shs) {

        if (this.shList.shs[i]['id'] === userId) {
          this.shList.shs[i]['showEditPaneForSh'] = this.shList.shs[i]['showEditPaneForSh'] === userId ? null : userId;
          return true;
        }
      }
    }

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

    $('button.add-share').on('click', function () {
      $('#share-modal .close-modal-item').trigger('click');
    });

    $('.stakeholder-type-tab-wrapper .tab').on('click', function () {
      // tslint:disable-next-line:prefer-const
      let self = $(this);
      $('.stakeholder-type-tab-wrapper .tab').removeClass('active');
      $(this).addClass('active');

    });


  }

  ngOnInit() {

    this.spinner.show();

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


  private loadData() {

    const data = {
      companyId: this.companyId,
      loginUser: this.loginUserEmail
    };
    this.spinner.show();

    // load Company data from the server
    this.iNcoreService.incorporationData(data)
      .subscribe(
        req => {
          this.companyTypes = Array.of(req['data']['compnayTypes']),
          this.companyObjectives = Array.of(req['data']['companyObjectives']),
          this.companyAddress = req['data']['companyAddress'];
          this.companyInfo = req['data']['companyInfo'];
          this.processStatus = req['data']['processStatus'];
          this.loginUserInfo = req['data']['loginUser'];
          this.loginUserAddress = req['data']['loginUserAddress'];

          // change the process steps
          this.progress.stepArr = [
            { label: '', icon: 'fa fa-list-ol', status: 'active' },
            { label: 'Stakeholders', icon: 'fa fa-users', status: '' },
            { label: 'Download Documents', icon: 'fa fa-download', status: '' },
            { label: 'Upload Documents', icon: 'fa fa-upload', status: '' },
          ];

          if (this.processStatus !== 'COMPANY_STATUS_REQUEST_TO_RESUBMIT') {
            this.progress.stepArr.push({ label: 'Payments', icon: 'fa fa-money-bill-alt', status: '' });
          } else {
            this.progress.stepArr.push({ label: 'Complete', icon: 'fas fa-check', status: '' });
          }


          this.compayType = req['data']['companyType'];
          this.countries = req['data']['countries'];

          this.coreShareGroups = req['data']['coreShareGroups'];


          this.secList.secs = req['data']['secs'];
          // tslint:disable-next-line:prefer-const
          for (let i in this.secList.secs) {
            this.validateSecEdit(i);
          }


          this.shList.shs = req['data']['shareholders'];
          // tslint:disable-next-line:prefer-const
          for (let i in this.shList.shs) {
            this.validateShareHolderEdit(i);
          }

          this.directorList.directors = req['data']['directors'];
          // tslint:disable-next-line:prefer-const
          for (let i in this.directorList.directors) {
            this.validateDirectorEdit(i);
          }

          this.enableStep2Next = req['data']['enableStep2Next'];
          this.companyDocuments = req['data']['documents'];
          this.step1Validation();
          this.step2Validation();

          this.changeProgressStatuses(this.stepOn);

          this.spinner.hide();
        }
      );



  }

  submitStep1() {

    if (this.processStatus === 'COMPANY_STATUS_REQUEST_TO_RESUBMIT') {
      this.changeProgressStatuses(1);
      this.stepOn = 1;
      return true;
    }

    const data = {
      companyId: this.companyId,
      companyType: this.companyInfo['type_id'],
      address1: this.companyAddress['address1'],
      address2: this.companyAddress['address2'],
      city: this.companyAddress['city'],
      district: this.companyAddress['district'],
      province: this.companyAddress['province'],
      email: this.companyInfo['email'],
      objective: this.companyInfo['objective'],

    };
    this.iNcoreService.incorporationDataStep1Submit(data)
      .subscribe(
        req => {
          this.loadData();
         this.changeProgressStatuses(1);
        },
        error => {
          console.log(error);
        }

      );


  }




  saveDirectorRecord() {

    if (this.director.type === 'local') {
      this.director.country = 'Sri Lanka';
    }
    // tslint:disable-next-line:prefer-const
    let copy = Object.assign({}, this.director);

    this.removeDuplicatesByNIC(1);

    this.directorList.directors.push(copy);

    this.directorNicLoaded = false;
    this.secNicLoaded = false;
    this.shNicLoaded = false;

    this.director = { id: 0, showEditPaneForDirector: 0, type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', phone: '', mobile: '', email: '' };
    this.step2Validation();
    this.validDirector = false;
    this.submitStakeholers('remove');

  }
  resetDirRecord() {
    // tslint:disable-next-line:prefer-const
    let conf = confirm('Are you sure you want to reset ?');

    if (!conf) {
      return true;
    }
    this.director = { id: 0, showEditPaneForDirector: 0, type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', phone: '', mobile: '', email: '', shareType: null, noOfSingleShares: null, coreGroupSelected: null, coreShareGroupName: '', coreShareValue: null };
  }


  removeDirectorRecord(i: number, userId: number = 0) {
    this.directorList.directors.splice(i, 1);
    if (!userId) {

      return true;
    }
    const data = {
      userId: userId
    };
    this.iNcoreService.incorporationDeleteStakeholder(data)
      .subscribe(
        req => {
          this.spinner.hide();
          this.step2Validation();
          this.loadData();

        },
        error => {
          this.spinner.hide();
          console.log(error);
          alert(error);

        }

      );


  }



  saveSecRecord() {

    if (this.sec.type === 'local') {
      this.sec.country = 'Sri Lanka';
    }

    // tslint:disable-next-line:prefer-const
    let copy1 = Object.assign({}, this.sec);

    this.removeDuplicatesByNIC(2); // remove nic duplicates
    this.secList.secs.push(copy1);

    this.directorNicLoaded = false;
    this.secNicLoaded = false;
    this.shNicLoaded = false;

    this.sec = { id: 0, showEditPaneForSec: 0, type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', isReg: false, regDate: '', phone: '', mobile: '', email: '' };
    this.step2Validation();
    this.validSec = false;
    this.submitStakeholers('remove');

  }

  resetSecRecord() {
    // tslint:disable-next-line:prefer-const
    let conf = confirm('Are you sure you want to reset ?');

    if (!conf) {
      return true;
    }
    // tslint:disable-next-line:max-line-length
    this.sec = { id: 0, showEditPaneForSec: 0, type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', isReg: false, regDate: '', phone: '', mobile: '', email: '', isShareholder: false, shareType: null, noOfSingleShares: null, coreGroupSelected: null, coreShareGroupName: '', coreShareValue: null, secType: null, secCompanyFirmId: '', pvNumber: '', firm_name: '', firm_province: null, firm_district: null, firm_city: null, firm_localAddress1: null, firm_localAddress2: null, firm_postcode: null, };
  }

  removeSecRecord(i: number, userId: number = 0) {

    this.secList.secs.splice(i, 1);
    if (!userId) {
      return true;
    }
    const data = {
      userId: userId
    };

    this.iNcoreService.incorporationDeleteStakeholder(data)
      .subscribe(
        req => {
          this.spinner.hide();
          this.step2Validation();
          this.loadData();
        },
        error => {
          this.spinner.hide();
          console.log(error);
          alert(error);
        }

      );

  }

  saveShareHolderRecord() {

    if (this.sh['type'] === 'local') {
      this.sh.country = 'Sri Lanka';
    }

    // tslint:disable-next-line:prefer-const
    let copy3 = Object.assign({}, this.sh);

    this.removeDuplicatesByNIC(3);
    this.shList.shs.push(copy3);

    this.sh = { id: 0, showEditPaneForSh: 0 ,  type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', phone: '', mobile: '', email: '', shareType: 'single', noOfShares: 0 };
    this.step2Validation();
    this.validSh = false;
    this.submitStakeholers('remove');
  }
  resetShRecord() {
    // tslint:disable-next-line:prefer-const
    let conf = confirm('Are you sure you want to reset ?');

    if (!conf) {
      return true;
    }
    this.sh = { id: 0, showEditPaneForSh: 0 , type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', phone: '', mobile: '', email: '', shareType: 'single', shareholderType: null, shareholderFirmCompanyisForiegn: null, noOfShares: null, coreGroupSelected: null, coreShareGroupName: null };
  }



  removeShRecord(i: number, userId: number = 0) {
    this.shList.shs.splice(i, 1);
    if (!userId) {

      return true;
    }
    const data = {
      userId: userId
    };
    this.iNcoreService.incorporationDeleteStakeholder(data)
      .subscribe(
        req => {
          this.spinner.hide();
          this.step2Validation();
          this.loadData();
        },
        error => {
          this.spinner.hide();
          console.log(error);
          alert(error);

        }

      );

  }

  private slugify(text) {
    return text.toString().toLowerCase()
      .replace(/\s+/g, '-')           // Replace spaces with -
      .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
      .replace(/\-\-+/g, '-')         // Replace multiple - with single -
      .replace(/^-+/, '')             // Trim - from start of text
      .replace(/-+$/, '');            // Trim - from end of text
  }

  fileChange(event, fileNane, fileUser = null) {

    console.log(event);
    console.log(fileNane);
    console.log(fileUser);

    // tslint:disable-next-line:radix
    if ( fileUser && parseInt( fileUser ) ) {
      // tslint:disable-next-line:radix
      fileUser = parseInt( fileUser );
    } else {
      fileUser = '';
    }

    // tslint:disable-next-line:prefer-const
    let fileList: FileList = event.target.files;
    if (fileList.length > 0) {
      // tslint:disable-next-line:prefer-const
      let file: File = fileList[0];

      // tslint:disable-next-line:prefer-const
      let fileSize =  fileList[0].size;

      if ( fileSize > 1024 * 1024 * 4 ) { // 4mb restriction
          alert( 'File size should be less than 4 MB' );
          return false;
      }

      // tslint:disable-next-line:prefer-const
      let formData: FormData = new FormData();
      formData.append('uploadFile', file, file.name);
      formData.append('fileName', this.slugify(fileNane) + '-' + this.slugify(fileUser));
      formData.append('fileType', fileNane);
      formData.append('companyId', this.companyId);
      formData.append('userId', fileUser);
      // let headers = new Headers();
      /** In Angular 5, including the header Content-Type can invalidate your request */

      // tslint:disable-next-line:prefer-const
      let headers = new HttpHeaders();
      headers.append('Content-Type', 'multipart/form-data');
      headers.append('Accept', 'application/json');


      // tslint:disable-next-line:prefer-const
      let uploadurl = this.url.getFileUploadURL();

      this.spinner.show();

      this.httpClient.post(uploadurl, formData, { headers: headers })
        .subscribe(
          (data: any) => {
            console.log(data);

            const datas = {
              companyId: this.companyId,
              directors: this.directorList,
              secretories: this.secList,
              shareholders: this.shList

            };

            this.iNcoreService.incorporationDataStep2Submit(datas)
              .subscribe(
                req => {
                  this.spinner.hide();
                  console.log(req);

                  this.docList = req['data']['docList'];
                  this.uploadList = req['data']['uploadList'];
                  this.uploadedList = req['data']['uploadedList'];

                  if (this.validateUploadeStatus( req['data']['uploadList'], req['data']['uploadedList'] )) {
                    this.validateUploadeStatusFlag = true;
                  } else {
                    this.validateUploadeStatusFlag = false;
                  }
                  this.loadData();
                  this.changeProgressStatuses(3);

                },
                error => {
                  this.spinner.hide();
                  this.changeProgressStatuses(3);
                }

              );



          },
          error => {
            console.log(error);
            this.spinner.hide();
          }
        );
    }

  }

  payAction() {
    this.loadData();
    this.router.navigate(['/dashboard']);
  }

  pay() {

    this.spinner.show();

    const data = {
      company_id: this.companyId
    };
    this.iNcoreService.incorpPay(data)
      .subscribe(
        req => {
          console.log(req);
          this.spinner.hide();
          this.paymentSuccess = true;
          this.loadData();

        },
        error => {
          console.log(error);
          this.spinner.hide();
          this.paymentSuccess = false;
        }

      );
  }

  resubmit() {

    this.spinner.show();

    const data = {
      company_id: this.companyId
    };

    this.iNcoreService.incorpResubmit(data)
      .subscribe(
        req => {
          this.spinner.hide();
          this.resubmitSuccess = true;

        },
        error => {
          this.spinner.hide();
          this.resubmitSuccess = false;
        }

      );
  }

  removeDirSec(userId) {

    const data = {
      companyId: this.companyId,
      userId: userId,

    };
    this.spinner.show();
    this.iNcoreService.incorpSecForDirRemove(data)
      .subscribe(
        req => {
          this.spinner.hide();
          this.loadData();
          this.changeProgressStatuses(1);

        },
        error => {
          this.spinner.hide();
          console.log(error);
        }

      );

  }

  removeDirSh(userId) {
    const data = {
      companyId: this.companyId,
      userId: userId,

    };
    this.spinner.show();
    this.iNcoreService.incorpShForDirRemove(data)
      .subscribe(
        req => {
          this.spinner.hide();
          this.loadData();
          this.changeProgressStatuses(1);

        },
        error => {
          this.spinner.hide();
          console.log(error);
        }

      );
  }

  removeSecSh(userId) {
    const data = {
      companyId: this.companyId,
      userId: userId,

    };
    this.spinner.show();
    this.iNcoreService.incorpShForSecRemove(data)
      .subscribe(
        req => {
          this.spinner.hide();
          this.loadData();
          this.changeProgressStatuses(1);

        },
        error => {
          this.spinner.hide();
          console.log(error);
        }

      );
  }
  validateSecEdit(rowId) {
    // tslint:disable-next-line:prefer-const
    let secRow = this.secList.secs[rowId];
    if (!(

      ((secRow.secType === 'firm' && this.compayType.value === 'Public') ? secRow.pvNumber : true) &&
      ((secRow.secType === 'firm') ? secRow.firm_name : true) &&
      ((secRow.secType === 'firm') ? secRow.firm_province : true) &&
      ((secRow.secType === 'firm') ? secRow.firm_district : true) &&
      ((secRow.secType === 'firm') ? secRow.firm_city : true) &&
      ((secRow.secType === 'firm') ? secRow.firm_localAddress1 : true) &&
      ((secRow.secType === 'firm') ? secRow.firm_postcode : true) &&


      secRow.nic && this.validateNIC(secRow.nic) &&
      // this.sec.title &&
      secRow.firstname &&
      secRow.lastname &&
      secRow.province &&
      secRow.district &&
      secRow.city &&
      secRow.postcode &&
      secRow.mobile && this.phonenumber(secRow.mobile) &&
      secRow.email && this.validateEmail(secRow.email) &&
      secRow.localAddress1 &&

      ((secRow.isShareholderEdit === undefined || secRow.isShareholderEdit === false) || secRow.shareTypeEdit === 'single' && secRow.noOfSingleSharesEdit ||
        secRow.shareTypeEdit === 'core' && secRow.coreGroupSelectedEdit ||
        secRow.shareTypeEdit === 'core' && (secRow.coreShareGroupNameEdit && secRow.coreShareValueEdit)

      )


    )) {

      this.enableStep2Submission = false;
      this.enableStep2SubmissionEdit = false;
      return false;
    } else {

      if (secRow.isReg) {

        if (!secRow.regDate) {

          this.enableStep2Submission = false;
          this.enableStep2SubmissionEdit = false;
          return false;

        } else {

          this.enableStep2Submission = true;
          this.enableStep2SubmissionEdit = true;
          return true;

        }
      } else {
        this.enableStep2Submission = true;
        this.enableStep2SubmissionEdit = true;
        return true;
      }


    }
  }

  validateRegCheckEdit($e, rowId) {
    // tslint:disable-next-line:prefer-const
    let secRow = this.secList.secs[rowId];

    secRow.isReg = $e ? true : false;
    this.validateSecEdit(rowId);

  }
  validateDirectorEdit(rowId) {

    // tslint:disable-next-line:prefer-const
    let directorRow = this.directorList.directors[rowId];
    if (directorRow.type === 'local') {

      if (!(directorRow.nic && this.validateNIC(directorRow.nic) &&
        // this.director.title &&
        directorRow.email && this.validateEmail(directorRow.email) &&
        directorRow.firstname &&
        directorRow.lastname &&
        directorRow.province &&
        directorRow.district &&
        directorRow.city &&
        directorRow.mobile && this.phonenumber(directorRow.mobile) &&
        directorRow.localAddress1 &&
        directorRow.postcode &&
        ((directorRow.isShareholderEdit === undefined || directorRow.isShareholderEdit === false) || directorRow.shareTypeEdit === 'single' && directorRow.noOfSingleSharesEdit ||
          directorRow.shareTypeEdit === 'core' && directorRow.coreGroupSelectedEdit ||
          directorRow.shareTypeEdit === 'core' && (directorRow.coreShareGroupNameEdit && directorRow.coreShareValueEdit)

        )
      )
      ) {
        //  this.directorValitionMessage = 'Please fill all  required fields denoted by asterik(*)';
        this.enableStep2Submission = false;
        this.enableStep2SubmissionEdit = false;
        return false;
      } else {

        // this.directorValitionMessage = '';
        this.enableStep2Submission = true;
        this.enableStep2SubmissionEdit = true;
        return true;

      }

    }

    if (directorRow.type === 'foreign') {
      if (!(directorRow.passport &&
        // this.director.title &&
        directorRow.email && this.validateEmail(directorRow.email) &&
        directorRow.firstname &&
        directorRow.lastname &&
        directorRow.province &&

        directorRow.city &&
        directorRow.country &&
        directorRow.mobile && this.phonenumber(directorRow.mobile) &&
        directorRow.localAddress1 &&
        directorRow.postcode &&
        ((directorRow.isShareholderEdit === undefined || directorRow.isShareholderEdit === false) || directorRow.shareTypeEdit === 'single' && directorRow.noOfSingleSharesEdit ||
          directorRow.shareTypeEdit === 'core' && directorRow.coreGroupSelectedEdit ||
          directorRow.shareTypeEdit === 'core' && (directorRow.coreShareGroupNameEdit && directorRow.coreShareValueEdit)

        )
      )) {

        // this.directorValitionMessage = 'Please fill all  required fields denoted by asterik(*)';
        this.enableStep2Submission = false;
        this.enableStep2SubmissionEdit = false;
        return false;

      } else {
        // this.directorValitionMessage = '';
        this.enableStep2Submission = true;
        this.enableStep2SubmissionEdit = true;
        return true;
      }
    }

  }

  validateShareHolderEdit(rowId) {

    // tslint:disable-next-line:prefer-const
    let shRow = this.shList.shs[rowId];

    if (shRow.type === 'local') {

      if (!(shRow.nic && this.validateNIC(shRow.nic) &&
        //  this.sh.title &&
        shRow.email && this.validateEmail(shRow.email) &&
        shRow.firstname &&
        shRow.lastname &&
        shRow.province &&
        shRow.district &&
        shRow.city &&
        shRow.localAddress1 &&
        shRow.postcode &&
        shRow.mobile && this.phonenumber(shRow.mobile) &&
        (shRow.shareType === 'single' && shRow.noOfShares ||
          shRow.shareType === 'core' && shRow.coreGroupSelected ||
          shRow.shareType === 'core' && (shRow.coreShareGroupName && shRow.noOfShares)

        )


      )) {

        this.enableStep2Submission = false;
        this.enableStep2SubmissionEdit = false;
        return false;

      } else {
        this.enableStep2Submission = true;
        this.enableStep2SubmissionEdit = true;
        return true;

      }

    }

    if (shRow.type === 'foreign') {

      if (!(shRow.passport &&
        // this.sh.title &&
        shRow.email && this.validateEmail(shRow.email) &&
        shRow.firstname &&
        shRow.lastname &&
        shRow.country &&
        shRow.city &&
        shRow.localAddress1 &&
        shRow.postcode &&
        shRow.mobile && this.phonenumber(shRow.mobile) &&


        (shRow.shareType === 'single' && shRow.noOfShares ||
          shRow.shareType === 'core' && shRow.coreGroupSelected ||
          shRow.shareType === 'core' && (shRow.coreShareGroupName && shRow.noOfShares)

        )
      )) {

        this.enableStep2Submission = false;
        this.enableStep2SubmissionEdit = false;
        return false;

      } else {
        this.enableStep2Submission = true;
        this.enableStep2SubmissionEdit = true;
        return true;

      }

    }

  }

  validateOppDate( type = 'add', stakeholder = 'director', rowId = 0) {

    let date;
    if (type === 'add') {

      date = (stakeholder === 'sh') ? this.sh.date : (stakeholder === 'sec') ? this.sec.date : this.director.date;

    } else if (type === 'edit' && rowId >= 0) {

      date = (stakeholder === 'sh') ? this.shList.shs[rowId].date : (stakeholder === 'sec') ? this.secList.secs[rowId].date : this.directorList.directors[rowId].date;

    } else {
      alert('Something went wrong.');
      return false;
    }

    if (!date) {
      return true;
    }

    // tslint:disable-next-line:prefer-const
    let sendDate: Date = new Date(Date.parse(date.replace(/-/g, ' ')));
    // tslint:disable-next-line:prefer-const
    let today = new Date();
    today.setHours(0, 0, 0, 0);
    if (sendDate > today) {
      alert('The  appointment can\'t be in the future. Please pick another date.');

      if (type === 'add') {

        if (stakeholder === 'sh') {this.sh.date = null; }
        if (stakeholder === 'sec') {this.sec.date = null; }
        if (stakeholder === 'director') {this.director.date = null; }

      }
      if (type === 'edit') {
        if (stakeholder === 'sh') { this.shList.shs[rowId].date = null; }
        if (stakeholder === 'sec') {this.secList.secs[rowId].date = null; }
        if (stakeholder === 'director') {this.directorList.directors[rowId].date = null; }

      }

      return false;
    }


  }


  submitStakeholers(action = '') {
    const data = {
      companyId: this.companyId,
      loginUser: this.loginUserEmail,
      directors: this.directorList,
      secretories: this.secList,
      shareholders: this.shList,
      action: action
    };
    this.spinner.show();

    this.iNcoreService.incorporationDataStep2Submit(data)
      .subscribe(
        req => {

          this.spinner.hide();

          if (req['status'] === false) {

            this.changeProgressStatuses(1);
            this.step2SubmitMessage = req['message'];
            this.step2SubmitStatus = false;

            return false;

          }



          this.docList = req['data']['docList'];
          this.uploadList = req['data']['uploadList'];
          this.uploadedList = req['data']['uploadedList'];

          if (this.validateUploadeStatus( req['data']['uploadList'], req['data']['uploadedList'] )) {
            this.validateUploadeStatusFlag = true;
          } else {
            this.validateUploadeStatusFlag = false;
          }

          if (action !== 'remove') { // in case of removing stakeholder, keep the position on same page.
            this.loadData();
            this.changeProgressStatuses(2);
            return false;
          }



          this.directorNicLoaded = false;
          this.secNicLoaded = false;
          this.shNicLoaded = false;

          this.loadData();
          this.changeProgressStatuses(1);
          this.step2SubmitMessage = req['message'];
          this.step2SubmitStatus = true;

        },
        error => {
          this.spinner.hide();
          console.log(error);

          this.directorNicLoaded = false;
          this.secNicLoaded = false;
          this.shNicLoaded = false;

        }

      );


  }


  checkNIC(memberType: number = 1) {


    this.directorNicLoaded = false;
     this.secNicLoaded = false;
     this.shNicLoaded = false;

     // tslint:disable-next-line:prefer-const
     let checker = (memberType === 1) ? this.director.nic : (memberType === 2) ? this.sec.nic : this.sh.nic;
     // tslint:disable-next-line:prefer-const
     let type = (memberType === 1) ? this.director.type : (memberType === 2) ? this.sec.type : this.sh.type;

     if ( !checker ) {
       this.directorNicLoaded = false;
       this.secNicLoaded = false;
       this.shNicLoaded = false;
       return false;
     }

     if ( type  !== 'local' ) {
      this.directorNicLoaded = true;
      this.secNicLoaded = true;
      this.shNicLoaded = true;

      return true;
     }

     const data = {
       companyId: this.companyId,
       nic: checker,
       memberType: memberType

     };


     this.iNcoreService.incorporationNICcheck(data)
       .subscribe(
         req => {

           if (memberType === 1) {

             if (req['status'] && req['data']['member_count'] === 1) {

               this.director.firstname = req['data']['member_record'][0]['first_name'];
               this.director.title = req['data']['member_record'][0]['title'];
               this.director.lastname = req['data']['member_record'][0]['last_name'];
               this.director.email = req['data']['member_record'][0]['email'];
               this.director.country = req['data']['member_record'][0]['passport_issued_country'];
               this.director.nic = req['data']['member_record'][0]['nic'];


               this.director.province = req['data']['address_record']['province'];
               this.director.district = req['data']['address_record']['district'];
               this.director.city = req['data']['address_record']['city'];
               this.director.localAddress1 = req['data']['address_record']['address1'];
               this.director.localAddress2 = req['data']['address_record']['address1'];
               this.director.postcode = req['data']['address_record']['postcode'];

               this.director.passport = req['data']['member_record'][0]['passport_no'];
               this.director.phone = req['data']['member_record'][0]['telephone'];
               this.director.mobile = req['data']['member_record'][0]['mobile'];
               this.director.share = req['data']['member_record'][0]['no_of_shares'];
               this.director.date = req['data']['member_record'][0]['date_of_appointment'];
               this.director.date = (this.director.date === '1970-01-01') ? '' : this.director.date;
               this.director.occupation = req['data']['member_record'][0]['occupation'];
               this.director.id  = 0;
               this.director.showEditPaneForDirector = 0;

               console.log(this.directorList.directors);
               this.directorNicLoaded = true;
               this.validateDirector();

             } else { // reset
              this.director = { id: 0, showEditPaneForDirector: 0, type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', phone: '', mobile: '', email: '', shareType: null, noOfSingleShares: null, coreGroupSelected: null, coreShareGroupName: '', coreShareValue: null };
              this.director.nic = checker;
              this.directorNicLoaded = true;
             }

             return true;

           }

         if (memberType === 2) {

             if (req['status'] && req['data']['member_count'] === 1) {
               this.sec.title = req['data']['member_record'][0]['title'];
               this.sec.firstname = req['data']['member_record'][0]['first_name'];
               this.sec.lastname = req['data']['member_record'][0]['last_name'];
               this.sec.email = req['data']['member_record'][0]['email'];
               this.sec.country = req['data']['member_record'][0]['passport_issued_country'];
               this.sec.nic = req['data']['member_record'][0]['nic'];


               this.sec.province = req['data']['address_record']['province'];
               this.sec.district = req['data']['address_record']['district'];
               this.sec.city = req['data']['address_record']['city'];
               this.sec.localAddress1 = req['data']['address_record']['address1'];
               this.sec.localAddress2 = req['data']['address_record']['address2'];
               this.sec.postcode = req['data']['address_record']['postcode'];

               this.sec.passport = req['data']['member_record'][0]['passport_no'];
               this.sec.phone = req['data']['member_record'][0]['telephone'];
               this.sec.mobile = req['data']['member_record'][0]['mobile'];
               this.sec.share = req['data']['member_record'][0]['no_of_shares'];
               this.sec.date = req['data']['member_record'][0]['date_of_appointment'];
               this.sec.date = (this.sec.date === '1970-01-01') ? '' : this.sec.date;
               this.sec.occupation = req['data']['member_record'][0]['occupation'];
               this.sec.isReg = ( req['data']['member_record'][0]['is_registered_secretary'] === 'yes' ) ? true : false;
               this.sec.regDate = req['data']['member_record'][0]['secretary_registration_no'];
               this.sec.secType =  ( req['data']['member_record'][0]['company_member_firm_id'] ) ? 'firm' : 'natural';

               this.validateSec();
               this.secNicLoaded = true;
             } else { // reset
              // tslint:disable-next-line:max-line-length
              this.sec = { secType : 'natural', id: 0, showEditPaneForSec: 0, type: 'local', title: '', firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: '', passport: '', country: '', share: 0, date: '', occupation: '', isReg: false, regDate: '', phone: '', mobile: '', email: '', isShareholder: false, shareType: null, noOfSingleShares: null, coreGroupSelected: null, coreShareGroupName: '', coreShareValue: null, secCompanyFirmId: '', pvNumber: '', firm_name: '', firm_province: null, firm_district: null, firm_city: null, firm_localAddress1: null, firm_localAddress2: null, firm_postcode: null };
              this.sec.nic = checker;
              this.secNicLoaded = true;

             }
             return true;

           }

          if (memberType === 3) {

             if (req['status'] && req['data']['member_count'] === 1) {

               this.sh.title = req['data']['member_record'][0]['title'];

               this.sh.firstname = req['data']['member_record'][0]['first_name'];
               this.sh.lastname = req['data']['member_record'][0]['last_name'];
               this.sh.email = req['data']['member_record'][0]['email'];
               this.sh.country = req['data']['member_record'][0]['passport_issued_country'];
               this.sh.nic = req['data']['member_record'][0]['nic'];


               this.sh.province = req['data']['address_record']['province'];
               this.sh.district = req['data']['address_record']['district'];
               this.sh.city = req['data']['address_record']['city'];
               this.sh.localAddress1 = req['data']['address_record']['address1'];
               this.sh.localAddress2 = req['data']['address_record']['address2'];
               this.sh.postcode = req['data']['address_record']['postcode'];

               this.sh.passport = req['data']['member_record'][0]['passport_no'];
               this.sh.phone = req['data']['member_record'][0]['telephone'];
               this.sh.mobile = req['data']['member_record'][0]['mobile'];
              // this.sh.share = req['data']['member_record'][0]['no_of_shares'];
               this.sh.date = req['data']['member_record'][0]['date_of_appointment'];
               this.sh.date = (this.sh.date === '1970-01-01') ? '' : this.sh.date;
               this.sh.occupation = req['data']['member_record'][0]['occupation'];

               this.validateShareHolder();
               this.shNicLoaded = true;


             } else { // reset
               this.sh = { id: 0 , showEditPaneForSh: 0,  type: 'local' , title: '' , firstname: '', lastname: '', province: '', district: '', city: '', localAddress1: '', localAddress2: '', postcode: '', nic: this.sh.nic, passport: '', country: '', share: 0, date: '', occupation: '', phone: '', mobile: '', email: '',  shareType: 'single', noOfShares: 0 };
               this.shNicLoaded = true;
               this.sh.nic = checker;
             }

             return true;

           }


         },
         error => {
           console.log(error);
         }

       );
  }

  private removeDuplicatesByNIC(memberType = 1) {

    if (memberType === 1) {

      // tslint:disable-next-line:prefer-const
      let copy = Object.assign({}, this.director);

      // tslint:disable-next-line:prefer-const
      for (let i in this.directorList.directors) {

        if (this.directorList.directors[i]['nic'] === copy['nic']) {
          let index;
          // tslint:disable-next-line:radix
          index = parseInt(i);
          this.directorList.directors.splice(index, 1);

        }
      }

      return true;

    }

    if (memberType === 2) {

      // tslint:disable-next-line:prefer-const
      let copy = Object.assign({}, this.sec);

      // tslint:disable-next-line:prefer-const
      for (let i in this.secList.secs) {

        if (this.secList.secs[i]['nic'] === copy['nic']) {
          let index;
          // tslint:disable-next-line:radix
          index = parseInt(i);
          this.secList.secs.splice(index, 1);

        }
      }

      return true;

    }

    if (memberType === 3) {

      // tslint:disable-next-line:prefer-const
      let copy = Object.assign({}, this.sh);

      // tslint:disable-next-line:prefer-const
      for (let i in this.shList.shs) {

        if (this.shList.shs[i]['nic'] === copy['nic']) {
          let index;
          // tslint:disable-next-line:radix
          index = parseInt(i);
          this.shList.shs.splice(index, 1);

        }
      }

      return true;

    }



  }


  /*************validation functions*****/

  step1Validation() {
    if (
      this.companyInfo.type_id &&
      this.companyAddress.address1 &&
      this.companyAddress.city &&
      this.companyAddress.district &&
      this.companyAddress.province &&
      this.companyInfo.objective &&
      this.companyInfo.email && this.validateEmail(this.companyInfo.email )

    ) {
      this.enableStep1Submission = true;
    } else {
      this.enableStep1Submission = false;
    }
  }

  directorsNicList() {

    // tslint:disable-next-line:prefer-const
    let directors = this.directorList.directors;
    // tslint:disable-next-line:prefer-const
    let directorNICList = {
        'local': [],
        'foreign': []
    };

    if ( !directors.length ) {
        return directorNICList;
    }

    // tslint:disable-next-line:prefer-const
    for (let i in directors ) {

      if ( directors[i].type === 'local' ) {
        directorNICList.local.push( directors[i].nic.toLowerCase() );
      }

      if ( directors[i].type === 'foreign' ) {
        directorNICList.foreign.push( directors[i].passport.toLowerCase() );
      }

    }

    return directorNICList;

  }

  isDirectorAlreadyExist( directorType= 'local' ) {

    const directorList = this.directorsNicList();

    const directorLocalList = directorList.local;
    const directorForeignList = directorList.foreign;

    if ( directorType === 'foreign' ) {

      return (  directorForeignList.indexOf(this.director.passport.toLowerCase()) >= 0  );
    } else if (  directorType === 'local' ) {

      return ( directorLocalList.indexOf(this.director.nic.toLowerCase()) >= 0  );
    } else {
      return false;
    }

  }

  isDirectorAlreadyExistAction(directorType= 'local') {

    // tslint:disable-next-line:prefer-const
    let message = (directorType === 'foreign') ?
    'This director Already Exist. Please try different passport number' :
    'This director Already Exist. Please try different NIC';

    console.log( this.isDirectorAlreadyExist( directorType ) );
    if ( this.isDirectorAlreadyExist( directorType ) ) {
      // this.checkNIC(1);
      this.directorNicLoaded = false;
      this.directorAlreadyExistMessage = message;
     } else {
      this.directorAlreadyExistMessage = '';
      this.checkNIC(1);
    }

  }


  secNicList() {

    // tslint:disable-next-line:prefer-const
    let secs = this.secList.secs;
    // tslint:disable-next-line:prefer-const
    let secNICList = {
        'local': [],
    };

    if ( !secs.length ) {
        return secNICList;
    }
    // tslint:disable-next-line:prefer-const
    for (let i in secs ) {
        secNICList.local.push( secs[i].nic.toLowerCase() );
    }
    return secNICList;
  }

  isSecAlreadyExist() {

    const secList = this.secNicList();
    const secLocalList = secList.local;
    return ( secLocalList.indexOf(this.sec.nic.toLowerCase() ) >= 0  );
  }

  isSecAlreadyExistAction() {

    // tslint:disable-next-line:prefer-const
    let message =  'This secretory Already Exist. Please try different NIC';

    if ( this.isSecAlreadyExist() ) {
      this.secNicLoaded = false;
      this.secAlreadyExistMessage = message;
     } else {
      this.secAlreadyExistMessage = '';
      this.checkNIC(2);
    }

  }

  shareholderNicList() {

    // tslint:disable-next-line:prefer-const
    let shs = this.shList.shs;
    // tslint:disable-next-line:prefer-const
    let shNICList = {
        'local': [],
        'foreign': []
    };

    if ( !shs.length ) {
        return shNICList;
    }

    // tslint:disable-next-line: prefer-const
    for (let i in shs ) {

      if ( shs[i].type === 'local' ) {
        shNICList.local.push( shs[i].nic.toLowerCase() );
      }

      if ( shs[i].type === 'foreign' ) {
        shNICList.foreign.push( shs[i].passport.toLowerCase() );
      }

    }

    return shNICList;

  }

  isShAlreadyExist( shType= 'local' ) {

    const shList = this.shareholderNicList();

    const shLocalList = shList.local;
    const shForeignList = shList.foreign;

    if ( shType === 'foreign' ) {

      return (  shForeignList.indexOf(this.sh.passport.toLowerCase() ) >= 0  );

    } else if (  shType === 'local' ) {

      return ( shForeignList.indexOf(this.sh.nic.toLowerCase() ) >= 0  );
    } else {
      return false;
    }

  }

  isShAlreadyExistAction(shType= 'local') {

    // tslint:disable-next-line:prefer-const
    let message = (shType === 'foreign') ?
    'This shareholder Already Exist. Please try different passport number' :
    'This shareholder Already Exist. Please try different NIC';

    if ( this.isShAlreadyExist( shType ) ) {
      this.shAlreadyExistMessage = message;
      this.shNicLoaded = false;
     } else {
      this.shAlreadyExistMessage = '';
      this.checkNIC(3);
    }

  }

private arraysEqual(_arr1, _arr2) {

    if (!Array.isArray(_arr1) || ! Array.isArray(_arr2) || _arr1.length !== _arr2.length) {
      return false;
    }
    // tslint:disable-next-line:prefer-const
    let arr1 = _arr1.concat().sort();
    // tslint:disable-next-line:prefer-const
    let arr2 = _arr2.concat().sort();

    for (let i = 0; i < arr1.length; i++) {

        if (arr1[i] !== arr2[i]) {
          return false;
        }
    }

    return true;

}




  step2Validation() {
    if (!(this.directorList.directors.length && this.secList.secs.length && this.shList.shs.length)) {
      this.enableStep2Submission = false;
    } else {
     // tslint:disable-next-line:prefer-const
     let directors = this.directorsNicList();
     // tslint:disable-next-line:prefer-const
     let secs = this.secNicList();
     // tslint:disable-next-line:prefer-const
     let shs = this.shareholderNicList();
     // check nic vice
     if (
        this.arraysEqual(directors.local, secs.local) &&  this.arraysEqual(directors.local, shs.local)
      ) {
        this.designationValidationRule = 'There has to be a minimum 2 natural persons to incorporate';
        this.enableStep2Submission = false;
     } else {
      this.designationValidationRule = '';
      this.enableStep2Submission = true;
     }

    }

  }

  checkdir() {
    console.log(this.director);
    console.log(this.directorList.directors);
  }
  checkSh() {
    console.log(this.sh);
  }


  validateDirector() {

    if (this.director.type === 'local') {

      if (!
        (
        this.director.nic && this.validateNIC(this.director.nic) &&
        !this.isDirectorAlreadyExist( 'local') &&
        this.director.title &&
        this.director.email && this.validateEmail(this.director.email) &&
        this.director.firstname &&
        this.director.lastname &&
        this.director.province &&
        this.director.district &&
        this.director.city &&
        this.director.mobile && this.phonenumber(this.director.mobile) &&
        this.director.localAddress1 &&
        this.director.postcode &&

        ((this.director.isShareholder === undefined || this.director.isShareholder === false) || this.director.shareType === 'single' && this.director.noOfSingleShares ||
          this.director.shareType === 'core' && this.director.coreGroupSelected ||
          this.director.shareType === 'core' && (this.director.coreShareGroupName && this.director.coreShareValue)

        )

      )


      ) {


        this.directorValitionMessage = 'Please fill all  required fields denoted by asterik(*)';
        this.validDirector = false;

        return false;
      } else {

        this.directorValitionMessage = '';
        this.validDirector = true;
        return true;

      }

    }

    if (this.director.type === 'foreign') {

      if (!(this.director.passport &&
        !this.isDirectorAlreadyExist( 'foreign') &&
        this.director.title &&
        this.director.email && this.validateEmail(this.director.email) &&
        this.director.firstname &&
        this.director.lastname &&
        this.director.province &&

        this.director.city &&
        this.director.country &&
        this.director.mobile && this.phonenumber(this.director.mobile) &&
        this.director.localAddress1 &&
        this.director.postcode &&
        ((this.director.isShareholder === undefined || this.director.isShareholder === false) || this.director.shareType === 'single' && this.director.noOfSingleShares ||
          this.director.shareType === 'core' && this.director.coreGroupSelected ||
          this.director.shareType === 'core' && (this.director.coreShareGroupName && this.director.coreShareValue)

        )
      )) {

        this.directorValitionMessage = 'Please fill all required fields denoted by asterik(*)';
        this.validDirector = false;
        return false;

      } else {
        this.directorValitionMessage = '';
        this.validDirector = true;
        return true;

      }

    }

  }

  validateShareHolder() {

    if (this.sh.type === 'local') {

      if (!(this.sh.nic && this.validateNIC(this.sh.nic) &&
        !this.isShAlreadyExist('local') &&
        this.sh.title &&
        this.sh.email && this.validateEmail(this.sh.email) &&
        this.sh.firstname &&
        this.sh.lastname &&
        this.sh.province &&
        this.sh.district &&
        this.sh.city &&
        this.sh.localAddress1 &&
        this.sh.postcode &&
        this.sh.mobile && this.phonenumber(this.sh.mobile) &&
        this.sh.shareType && (this.sh.coreGroupSelected || this.sh.noOfShares)
      )) {

        this.shValitionMessage = 'Please fill all  required fields denoted by asterik(*)';
        this.validSh = false;
        return false;
      } else {
        this.shValitionMessage = '';
        this.validSh = true;
        return true;

      }

    }

    if (this.sh.type === 'foreign') {
      if (!(this.sh.passport &&
        !this.isShAlreadyExist('foreign') &&
        this.sh.title &&
        this.sh.email && this.validateEmail(this.sh.email) &&
        this.sh.firstname &&
        this.sh.lastname &&
        this.sh.country &&
        this.sh.city &&
        this.sh.localAddress1 &&
        this.sh.postcode &&
        this.sh.mobile && this.phonenumber(this.sh.mobile) &&
        this.sh.shareType && (this.sh.coreGroupSelected || this.sh.noOfShares)
      )) {

        this.shValitionMessage = 'Please fill all  required fields denoted by asterik(*)';
        this.validSh = false;
        return false;

      } else {
        this.shValitionMessage = '';
        this.validSh = true;
        return true;

      }

    }

  }

  validateSec() {
    if (!(

      ((this.sec.secType === 'firm' && this.compayType.value === 'Public') ? this.sec.pvNumber : true) &&
      ((this.sec.secType === 'firm') ? this.sec.firm_name : true) &&
      ((this.sec.secType === 'firm') ? this.sec.firm_province : true) &&
      ((this.sec.secType === 'firm') ? this.sec.firm_district : true) &&
      ((this.sec.secType === 'firm') ? this.sec.firm_city : true) &&
      ((this.sec.secType === 'firm') ? this.sec.firm_localAddress1 : true) &&
      ((this.sec.secType === 'firm') ? this.sec.firm_postcode : true) &&


      this.sec.nic && this.validateNIC(this.sec.nic) &&
      !this.isSecAlreadyExist() &&
      this.sec.title &&
      this.sec.firstname &&
      this.sec.lastname &&
      this.sec.province &&
      this.sec.district &&
      this.sec.city &&
      this.sec.postcode &&
      this.sec.mobile && this.phonenumber(this.sec.mobile) &&
      this.sec.email && this.validateEmail(this.sec.email) &&
      this.sec.localAddress1 &&

      ((this.sec.isShareholder === undefined || this.sec.isShareholder === false) || this.sec.shareType === 'single' && this.sec.noOfSingleShares ||
        this.sec.shareType === 'core' && this.sec.coreGroupSelected ||
        this.sec.shareType === 'core' && (this.sec.coreShareGroupName && this.sec.coreShareValue)

      )

    )


    ) {

      this.secValitionMessage = 'Please fill all  required fields denoted by asterik(*)';
      this.validSec = false;

      return false;
    } else {

      if (this.sec.isReg) {

        if (!this.sec.regDate) {

          this.secValitionMessage = 'Please add the registration Number';
          this.validSec = false;
          return false;

        } else {

          this.secValitionMessage = '';
          this.validSec = true;
          return true;

        }
      } else {
        this.secValitionMessage = '';
        this.validSec = true;
        return true;
      }


    }




  }


  validateRegCheck($e) {

    this.validateSec();
    this.sec.isReg = $e ? true : false;
    this.validateSec();

  }


  selectStakeHolderType(stakeholder, type) {

    if (stakeholder === 'director') {
      this.director.type = type;

      if (  this.director.type  !== 'local' ) {
        this.directorNicLoaded = true;
      } else {
        this.directorNicLoaded = false;
      }

      this.validateDirector();
    } else if (stakeholder === 'sec') {
      this.sec.type = type;

      if (  this.sec.type  !== 'local' ) {
        this.secNicLoaded = true;
      } else {
        this.secNicLoaded = false;
      }

      this.validateSec();

    } else if (stakeholder === 'sh') {
      this.sh.type = type;
      if (  this.sh.type  !== 'local' ) {
        this.shNicLoaded = true;
      } else {
        this.shNicLoaded = false;
      }
      this.validateShareHolder();
    }
  }

   validateUploadeStatus(uploadeList, uploadedList) {

    if ( this.processStatus === 'COMPANY_STATUS_REQUEST_TO_RESUBMIT') {

      return true;
    }

      // tslint:disable-next-line:prefer-const
      let uploadCount =  Object.keys(uploadeList['director']).length +
                          Object.keys(uploadeList['sec']).length +
                          Object.keys(uploadeList['other']).length;


      let uploadedCount;
      uploadedCount = 0;

      // tslint:disable-next-line:prefer-const
      for (let i in uploadedList ) {
        if ( typeof uploadedList[i] === 'string') {
          uploadedCount =  uploadedCount + 1;
        } else {
          uploadedCount =  uploadedCount + Object.keys(uploadedList[i]).length;
        }
      }

      if (uploadCount === uploadedCount ) {
        return true;
      } else {
        return false;
      }


  }

  removeDoc(companyId, docTypeId, userId = null ) {

    const data = {
      companyId: companyId,
      docTypeId: docTypeId,
      userId : userId

    };
    this.spinner.show();
    this.iNcoreService.incorpFileRemove(data)
      .subscribe(
        rq => {

          const datas = {
            companyId: this.companyId,
            directors: this.directorList,
            secretories: this.secList,
            shareholders: this.shList

          };

          this.iNcoreService.incorporationDataStep2Submit(datas)
            .subscribe(
              req => {
                this.spinner.hide();

                this.docList = req['data']['docList'];
                this.uploadList = req['data']['uploadList'];
                this.uploadedList = req['data']['uploadedList'];

                if (this.validateUploadeStatus( req['data']['uploadList'], req['data']['uploadedList'] )) {
                  this.validateUploadeStatusFlag = true;
                } else {
                  this.validateUploadeStatusFlag = false;
                }
                this.loadData();
                this.changeProgressStatuses(3);

              },
              error => {
                this.spinner.hide();
                this.changeProgressStatuses(3);
              }

            );

        },
        error => {
          this.spinner.hide();
          console.log(error);
        }

      );


  }

  /*********util functions  */
  private validateEmail(email) {
    if (!email) { return true; }
    // tslint:disable-next-line:prefer-const
    let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }
  private phonenumber(inputtxt) {
    if (!inputtxt) { return true; }
    // tslint:disable-next-line:prefer-const
    let phoneno = /^\d{10}$/;
    return inputtxt.match(phoneno);
  }
  private validateNIC(nic) {
    if (!nic) {
      return true;
    }
    // tslint:disable-next-line:prefer-const
    let regx = /^[0-9]{9}[x|X|v|V]$/;
    return nic.match(regx);
  }


}
