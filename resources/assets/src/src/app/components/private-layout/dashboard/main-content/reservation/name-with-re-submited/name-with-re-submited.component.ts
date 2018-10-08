import { HttpEventType, HttpResponse } from '@angular/common/http';
import { DocumentsService } from './../../../../../../http/services/documents.service';
import { IReqDocument } from './../../../../../../http/models/file.model';
import { DataService } from './../../../../../../storage/data.service';
import { SnotifyService } from 'ng-snotify';
import { INames, IReSubmit } from '../../../../../../http/models/recervationdata.model';
import { NameResarvationService } from '../../../../../../http/services/name-resarvation.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Component, OnInit, OnDestroy, Output, EventEmitter } from '@angular/core';
import { FormBuilder, FormGroup, FormControl } from '@angular/forms';
import { GeneralService } from '../../../../../../http/services/general.service';
import { NgxSpinnerService } from '../../../../../../../../node_modules/ngx-spinner';
import { HelperService } from '../../../../../../http/shared/helper.service';

@Component({
  selector: 'app-name-with-re-submited',
  templateUrl: './name-with-re-submited.component.html',
  styleUrls: ['./name-with-re-submited.component.scss']
})
export class NameWithReSubmitedComponent implements OnInit, OnDestroy {
  id: number;
  private sub: any;
  public name: INames;
  public formGroup: FormGroup;
  public reqDocument: Array<IReqDocument>;
  constructor(
    private formBuilder: FormBuilder,
    public data: DataService,
    private router: Router,
    private docService: DocumentsService,
    private helper: HelperService,
    private route: ActivatedRoute,
    private general: GeneralService,
    private spinner: NgxSpinnerService,
    private reservationService: NameResarvationService,
    private snotifyService: SnotifyService) { }

  ngOnInit() {
    this.sub = this.route.params.subscribe(params => {
      this.reservationService.getNameReservationData(params['id'])
        .subscribe(
          req => {
            this.name = req['companyInfor'];
            this.reqDocument = req['companyResubmitedDoc'];
            this.id = params['id'];
          }
        );
    });

    this.formGroup = this.formBuilder.group({
      companyName: new FormControl(null),
      sinhalaName: new FormControl(null),
      tamileName: new FormControl(null),
      abbreviations: new FormControl(null)
    });
  }
  get getcompanyName() { return this.formGroup.get('companyName'); }

  get getSinhalaName() { return this.formGroup.get('sinhalaName'); }

  get getTamileName() { return this.formGroup.get('tamileName'); }

  get getAbbreviationName() { return this.formGroup.get('abbreviations'); }

  ngOnDestroy() {
    this.sub.unsubscribe();
  }

  onSubmit(): void {
    const reSubmit: IReSubmit = {
      refId: this.id,
      companyName: this.getcompanyName.value,
      sinhalaName: this.getSinhalaName.value,
      tamileName: this.getTamileName.value,
      abbreviation_desc: this.getAbbreviationName.value
    };

    this.reservationService.setReSubmitedData(reSubmit)
      .subscribe(
        req => {
          this.router.navigate(['/dashboard/home']);
          this.snotifyService.success('Re-Submit update Successful', 'Success');
        },
        error => {
          this.snotifyService.error('Re-Submit update un-successful!', 'error');
        }
      );
  }

  ngOnDownload(token: string): void {
    this.spinner.show();
    this.general.getDocumenttoServer(token)
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

  ngBind(event): void {
    this.getcompanyName.setValue(event.name + ' ' + event.postFix);
  }

  upload(files: File[], id: string) {
    this.uploadAndProgress(files, id);
  }

  uploadAndProgress(files: File[], id: string) {
    const formData = new FormData();
    Array.from(files).forEach(f => {
      formData.append('file', f);
      formData.append('id', id);
    });
    this.docService.uploadFile(formData).subscribe(
      event => {
        if (event.type === HttpEventType.UploadProgress) {
          // this.percentDone = 50;  //   Math.round(100 * event.loaded / event.total);
        } else if (event instanceof HttpResponse) {
        }
      }
    );
  }
}
