import { GeneralService } from '../../../../http/services/general.service';
import { AuthenticationService } from '../../../../http/services/authentication.service';
import { IStatusCount } from '../../../../http/models/recervationdata.model';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-horizontal-status-bar',
  templateUrl: './horizontal-status-bar.component.html',
  styleUrls: ['./horizontal-status-bar.component.scss']
})
export class HorizontalStatusBarComponent implements OnInit {
  public status: IStatusCount;
  constructor(
    public AuthService: AuthenticationService,
    public generalService: GeneralService
  ) { }

  ngOnInit() {
    this.getStatus();
  }

  getStatus(): void {
    this.generalService.getStatusCount().subscribe(
      req => {
        this.status = req;
      }
    );
  }

}
