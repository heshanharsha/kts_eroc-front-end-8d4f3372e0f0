import { NameWithReSubmitedComponent } from './../name-with-re-submited/name-with-re-submited.component';
import { GeneralService } from './../../../../../../http/services/general.service';
import { DataService } from './../../../../../../storage/data.service';
import { NameResarvationService } from './../../../../../../http/services/name-resarvation.service';
import { AuthService } from './../../../../../../http/shared/auth.service';
import { AuthenticationService } from './../../../../../../http/services/authentication.service';
import { ICompanyType, ISearch, IHasMeny } from './../../../../../../http/models/search.model';
import { ModalDirective } from 'angular-bootstrap-md';
import { Component, OnInit, ViewChild, Output, EventEmitter } from '@angular/core';
import { Router } from '@angular/router';
import { SnotifyService } from 'ng-snotify';
import { NgxSpinnerService } from 'ngx-spinner';
import { FormControl, Validators, FormGroup, FormBuilder } from '@angular/forms';

@Component({
  selector: 'app-name-check-name-with-re-submite',
  templateUrl: './name-check-name-with-re-submite.component.html',
  styleUrls: ['./name-check-name-with-re-submite.component.scss']
})
export class NameCheckNameWithReSubmiteComponent implements OnInit {
  @ViewChild('frame') modal: ModalDirective;
  public formGroup: FormGroup;
  public nameWithreSubmit: NameWithReSubmitedComponent;
  private search: ISearch = { criteria: 1, searchtext: '', companyType: 0, token: '' };
  public subCatogorys: Array<any> = [];

  companyTypes: ICompanyType;

  public current_page: number;

  public availableData: IHasMeny;
  public notHasData: IHasMeny;
  public seResults: Array<any>;
  public dataLink: Array<any>;


  public pages: Array<number>;

  public endPage: number;
  public startPage: number;
  public loopNumber: Array<number>;

  public meta: Array<any>;
  public available: boolean;
  public searchName: string;
  public postfixName: string;
  public comType: number;
  public cRriteria = 1;
  startFrom: any;

  @Output() getName = new EventEmitter();

  constructor(
    private router: Router,
    private formBuilder: FormBuilder,
    public Authentication: AuthenticationService,
    public Auth: AuthService,
    private Resarvation: NameResarvationService,
    private data: DataService,
    private general: GeneralService,
    private snotifyService: SnotifyService,
    private spinner: NgxSpinnerService) {

  }

  ngOnInit() {
    this.setPage(10);
    this.current_page = 0;
    // this.__getType();
    this.postfixName = '';
    this.formGroup = this.formBuilder.group({
      companyType: new FormControl(''),
      postfix: new FormControl(''),
      criteria: new FormControl(this.cRriteria, [
        Validators.required
      ]),
      search: new FormControl(this.search.searchtext, Validators.required)
    });
    this.getCompanyType();
  }


  show(): void {
    this.modal.show();
  }


  get companyType() { return this.formGroup.get('companyType'); }

  get postfix() { return this.formGroup.get('postfix'); }

  get criteria() { return this.formGroup.get('criteria'); }

  get searchtext() { return this.formGroup.get('search'); }

  changeCriteria(event: number) {
    this.cRriteria = event;
  }

  ckSearch(): void {
    this.spinner.show();
    const newstr = this.searchtext.value.replace(this.postfix.value, '');

    if (!this.searchtext.value) { this.seResults = undefined; this.meta = undefined; this.pages = undefined; return; }
    this.startFrom = new Date().getTime();

    const searchData: ISearch = {
      criteria: this.cRriteria,
      searchtext: newstr,
      companyType: this.companyType.value,
      // tslint:disable-next-line:max-line-length
      token: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjBiZjNjZWNjNjQ4MWY3ZWYwZWFlNGZmYzJhMjZjMDMwMWFhYTJjY2U2NWVlMmRiZjdkMjg1NjBjYjZlMTM1ODIyYTQ5MGZiMTdjNDhkYmZiIn0'
    };

    this.Resarvation.getSearchResult(searchData, this.current_page)
      .subscribe(
        req => {
          this.availableData = req['availableData'];
          this.notHasData = req['notHasData'];
          this.pages = new Array(req['availableData']['mata']['last_page']);
          this.meta = req['availableData']['mata']['total'];
          this.searchName = newstr;
          this.postfixName = this.postfix.value;
          this.comType = this.companyType.value;
        },
        error => {
          this.snotifyService.error('oops something went wrong', 'error');
        }
      );
    this.spinner.hide();
    this.startFrom = (new Date().getTime() - this.startFrom) / 1000.0;
  }

  setPages(i, event: any): void {
    this.current_page = i;
    this.startPage = this.startPage - 5;
    this.endPage = this.startPage + 10;
    console.log(this.startPage);

    this.setPage(this.endPage, this.startPage);
    // this.ckSearch();
  }

  setPage(i: number, start: number = 0): void {
    this.loopNumber = [];
    for (let index = start; index <= i; index++) {
      this.loopNumber.push(index);
    }
  }

  ckPageZero(): void {
    this.current_page = 0;
  }

  getCompanyType() {
    this.general.getCompanyType().subscribe(
      req => {
        this.companyTypes = req;
        this.getSubCatogory(req[0]['id']);
        this.formGroup.controls['companyType'].setValue(req[0]['id'], { onlySelf: true });
      }
    );
  }

  getSubCatogory(id: number) {
    this.general.getComSubData(id).subscribe(
      req => {
        if (req === undefined || req.length === 0) {
          this.postfix.reset();
          this.subCatogorys = undefined;
        } else {
          this.subCatogorys = req;
          this.formGroup.controls['postfix'].setValue(req[0]['postfix'], { onlySelf: true });
        }
      }
    );
  }

  Clean() {
    this.availableData = undefined;
    this.notHasData = undefined;
    this.pages = undefined;
    this.ngOnInit();
  }

  onResavation() {

    this.Resarvation.isCheckPostfix(this.companyType.value)
      .subscribe(
        req => {
          if (req === true) {
            if (!this.postfixName) {
              this.ckSearch();
              this.snotifyService.warning('Please select the type of the company', 'warning');
              return;
            }
          }
        }
      );

    this.getName.emit({ name: this.searchName, postFix: this.postfixName, type: this.comType });
    this.Clean();
  }

}


