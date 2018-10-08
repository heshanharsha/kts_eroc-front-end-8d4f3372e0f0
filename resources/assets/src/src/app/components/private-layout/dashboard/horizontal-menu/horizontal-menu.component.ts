import { Component, OnInit } from '@angular/core';
import { CompanyListComponent } from '../main-content/company-list/company-list.component';
import { NameResarvationService } from '../../../../http/services/name-resarvation.service';
import { first } from 'rxjs/operators';

@Component({
  selector: 'app-horizontal-menu',
  templateUrl: './horizontal-menu.component.html',
  styleUrls: ['./horizontal-menu.component.scss']
})
export class HorizontalMenuComponent implements OnInit {
  public CompanyList: CompanyListComponent;
  constructor(public rec: NameResarvationService) { }

  ngOnInit() {
  }

  onSearch(ever: any): void {

    this.rec.getReceivedData(1, ever.target.value).pipe().subscribe(
      req => {
        if (req !== undefined || req.length !== 0) {
          this.CompanyList.names = req['data'];
          this.CompanyList.pages = new Array(req['meta']['last_page']);
        }
      }
    );
  }

}
