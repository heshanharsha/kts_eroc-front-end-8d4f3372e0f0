import { NgxSpinnerService } from 'ngx-spinner';
import { HelperService } from '../../../../../http/shared/helper.service';
import { GeneralService } from '../../../../../http/services/general.service';
import { NameResarvationService } from '../../../../../http/services/name-resarvation.service';
import { INames } from '../../../../../http/models/recervationdata.model';
import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { DomSanitizer } from '@angular/platform-browser';

@Component({
  selector: 'app-company-card',
  templateUrl: './company-card.component.html',
  styleUrls: ['./company-card.component.scss']
})
export class CompanyCardComponent implements OnInit, OnDestroy {

  id: number;
  private sub: any;
  public name: INames;
  fileUrl;

  constructor(
    private helper: HelperService,
    private route: ActivatedRoute,
    private general: GeneralService,
    private spinner: NgxSpinnerService,
    private reservationService: NameResarvationService) { }

  ngOnInit() {
    this.sub = this.route.params.subscribe(params => {
      this.reservationService.getNameReservationData(params['id'])
        .subscribe(
          req => {
            this.name = req['companyInfor'];
            this.name.documents = req['companyDocument'];
            this.id = params['id'];
          }
        );
    });
  }

  ngOnDestroy() {
    this.sub.unsubscribe();
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
}
