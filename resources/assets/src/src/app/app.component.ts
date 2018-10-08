import { AuthenticationService } from './http/services/authentication.service';
import { GeneralService } from './http/services/general.service';
import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'app';

  constructor(private Auth: AuthenticationService) {
     this.Auth.aulogout();
  }
}
