import { SnotifyService } from 'ng-snotify';
import { HelperService } from './../../../../../../http/shared/helper.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { GeneralService } from '../../../../../../http/services/general.service';
import { ReservationComponent } from '../reservation.component';
import { DocumentsService } from '../../../../../../http/services/documents.service';
import { AuthService } from '../../../../../../http/shared/auth.service';
import { IDocGroup } from '../../../../../../http/models/doc.model';
import { IFile } from '../../../../../../http/models/file.model';
import { HttpEventType, HttpClient, HttpResponse } from '@angular/common/http';
import swal from 'sweetalert2';

@Component({
  selector: 'app-name-with-documents',
  templateUrl: './name-with-documents.component.html',
  styleUrls: ['./name-with-documents.component.scss']
})
export class NameWithDocumentsComponent implements OnInit {

  public docs: IDocGroup;
  private files: IFile;
  percentDone: Array<number> = [];
  uploadSuccess: boolean;
  public id: number;
  public i = 1;
  public fileToken: Array<number> = [];

  constructor(
    private helper: HelperService,
    private route: Router,
    private http: HttpClient,
    private general: GeneralService,
    public res: ReservationComponent,
    private spinner: NgxSpinnerService,
    private imageService: DocumentsService,
    private snotifyService: SnotifyService,
    private auth: AuthService) {}

  ngOnInit() {
    this.getDocumentfield();
  }

  getDocumentfield(): void {
    // this.res.companyType
    this.general.getDocFeild(3)
      .subscribe(
        req => {
          this.docs = req;
        }
      );
  }

  upload(files: File[], id: string) {
    this.uploadAndProgress(files, id);
    this.id += 1;
  }

  uploadAndProgress(files: File[], id: string) {
    const formData = new FormData();
    Array.from(files).forEach(f => {
      formData.append('file', f);
      formData.append('id', this.auth.getCompanyId());
      formData.append('docId', id);
    });
    this.http.post(this.imageService.url.setfileUploadAPI(), formData, { reportProgress: true, observe: 'events' }).subscribe(
      event => {
        if (event.type === HttpEventType.UploadProgress) {
          this.percentDone[id] = 50;  //   Math.round(100 * event.loaded / event.total);
        } else if (event instanceof HttpResponse) {
          this.uploadSuccess = true;
          this.percentDone[id] = 100;
          this.fileToken[id] = event['body']['key'];
        }
      }
    );
  }

  onSubmit() {
    swal({
      title: 'Are you sure?',
      text: 'You won\'t be able to revert this!',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Continue'
    }).then((result) => {
      if (result.value) {
        this.route.navigate(['reservation/payment']);
      }
    });
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

  // tslint:disable-next-line:use-life-cycle-interface
  ngOnDestroy(token: string, id: string): void {
    this.percentDone[id] = 0;
    this.general.onDestroytoServer(token)
      .subscribe(
        response => {
          if (response === true) {
            this.percentDone[id] = 0;
            // this.snotifyService.success('File Deleted Successfully.', 'success');
          } else {
            this.percentDone[id] = 100;
            // this.snotifyService.error('File uploded Destroy. Please Contact eRoc Administrator', 'error');
          }
        },
        error => {
          this.percentDone[id] = 100;
          // this.snotifyService.error('File uploded Destroy. Please Contact eRoc Administrator', 'error');
        }
      );
  }
}
