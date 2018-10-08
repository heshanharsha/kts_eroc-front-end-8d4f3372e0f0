import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from '../../../http/services/authentication.service';
import { IUser } from '../../../http/models/user.model';
import { AuthService } from '../../../http/shared/auth.service';
import { UserService } from '../../../http/services/user.service';
import { DataService } from '../../../storage/data.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {



  constructor(
    public Authentication: AuthenticationService,
    public Auth: AuthService,
    public dataService: DataService) { }

  ngOnInit() {
  }


  cklogOut(): void {
    this.Authentication.aulogout();
  }

}
