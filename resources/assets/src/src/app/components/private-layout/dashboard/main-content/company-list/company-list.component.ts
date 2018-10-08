import { Component, OnInit } from '@angular/core';
import { NameResarvationService } from '../../../../../http/services/name-resarvation.service';
import { INames } from '../../../../../http/models/recervationdata.model';

@Component({
  selector: 'app-company-list',
  templateUrl: './company-list.component.html',
  styleUrls: ['./company-list.component.scss']
})
export class CompanyListComponent implements OnInit {

  public names: INames[] = [];
  public pages: Array<any> = [];
  public current_page = 0;

  constructor(
    public rec: NameResarvationService
  ) { }

  ngOnInit() {
    this.getReceivedName();
  }

  getReceivedName() {
    this.rec.getReceivedData(this.current_page).pipe().subscribe(
      req => {
        if (req !== undefined || req.length !== 0) {
          this.names = req['data'];
          this.pages = new Array(req['meta']['last_page']);
        }
      }
    );
  }

  setPages(i, event: any): void {
    this.current_page = i;
    this.getReceivedName();
  }

}
