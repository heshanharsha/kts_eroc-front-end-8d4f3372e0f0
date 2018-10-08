import swal from 'sweetalert2';

import { AuthService } from '../../../../http/shared/auth.service';
import { NameResarvationService } from '../../../../http/services/name-resarvation.service';
import { IHasMeny, ISearch, ICompanyType } from '../../../../http/models/search.model';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthenticationService } from '../../../../http/services/authentication.service';
import { DataService } from '../../../../storage/data.service';
import { GeneralService } from '../../../../http/services/general.service';
import { SnotifyService } from 'ng-snotify';
import { NgxSpinnerService } from 'ngx-spinner';
import { FormControl, Validators, FormGroup, FormBuilder } from '@angular/forms';

@Component({
  selector: 'app-bar',
  templateUrl: './bar.component.html',
  styleUrls: ['./bar.component.scss']
})
export class BarComponent implements OnInit {
  public formGroup: FormGroup;
  private search: ISearch = { criteria: 1, searchtext: '', companyType: 0, token: '' };
  public subCatogorys: Array<any> = [];

  companyTypes: ICompanyType;

  public current_page: number;

  public availableData: IHasMeny;
  public notHasData: IHasMeny;
  public seResults: Array<any>;
  public dataLink: Array<any>;
  public pages: Array<number>;
  public loopNumber: Array<number> = new Array(10);
  public meta: Array<any>;
  public available: boolean;
  public searchName: string;
  public postfixName: string;
  public comType: number;
  public cRriteria = 1;
  startFrom: any;

  // criterias
  criterias: Array<any> = [
    { label: 'Contains', value: '1' },
    { label: 'Begins with', value: '2' }
  ];

  public selected = this.criterias[0].value; // default selected 1 element for a criteria

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

    this.formGroup.controls['criteria'].setValue(this.selected, { onlySelf: true });
  }

  get companyType() { return this.formGroup.get('companyType'); }

  get postfix() { return this.formGroup.get('postfix'); }

  get criteria() { return this.formGroup.get('criteria'); }

  get searchtext() { return this.formGroup.get('search'); }

  changeCriteria(event: number) {
    this.cRriteria = event;
  }

  ckSearch(): void {

    const newstr = this.searchtext.value.replace(this.postfix.value, '');

    if (!this.searchtext.value) { this.seResults = undefined; this.meta = undefined; this.pages = undefined; return; }
    this.spinner.show();
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
          this.pages = req['availableData']['mata'];
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
    this.ckSearch();
  }

  ckPageZero(): void {
    this.current_page = 0;
  }


  onKeydown($ev) {
    console.log($ev);
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
    // this.availableData = undefined;
    // this.notHasData = undefined;
    // this.pages = undefined;
    // this.ngOnInit();
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

    this.data.storage = {
      name: this.searchName,
      postfix: this.postfixName,
      comType: this.comType
    };

    this.router.navigate(['/reservation']);

  }
}
