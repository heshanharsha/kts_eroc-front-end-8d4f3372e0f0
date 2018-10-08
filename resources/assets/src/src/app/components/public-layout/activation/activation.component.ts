import { SnotifyService } from 'ng-snotify';
import { AuthenticationService } from './../../../http/services/authentication.service';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router, Params } from '../../../../../node_modules/@angular/router';

@Component({
  selector: 'app-activation',
  templateUrl: './activation.component.html',
  styleUrls: ['./activation.component.scss']
})
export class ActivationComponent implements OnInit {

  private email: string;
  private token: string;

  constructor(
    private router: ActivatedRoute,
    private route: Router,
    public auth: AuthenticationService,
    private snotifyService: SnotifyService) {}

  ngOnInit() {
    this.router.queryParams
    .subscribe(params => {
      this.email = params.email;
      this.token = params.token;
    });

    if (this.email && this.token) {
      this.auth.auActivation(this.email, this.token).subscribe(
        req => {
         this.route.navigate(['dashboard']);
          this.snotifyService.success('Grate User Varification Successfull!', 'Success');
        }
      );
    }
  }

}
