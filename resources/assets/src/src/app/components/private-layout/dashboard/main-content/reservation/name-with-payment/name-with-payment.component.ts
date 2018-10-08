import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { SnotifyService } from 'ng-snotify';
import { NameResarvationService } from '../../../../../../http/services/name-resarvation.service';

@Component({
  selector: 'app-name-with-payment',
  templateUrl: './name-with-payment.component.html',
  styleUrls: ['./name-with-payment.component.scss']
})
export class NameWithPaymentComponent implements OnInit {

  constructor(
    private router: Router,
    private snotifyService: SnotifyService,
    private resar: NameResarvationService
  ) { }

  ngOnInit() {
  }

  onPay(): void {
    this.resar.setPayment().subscribe(
      req => {
        this.snotifyService.success('Congratulation You have received Company Name.', 'Success');
        this.router.navigate(['dashboard/home']);
      }, error => {
        this.snotifyService.error('Payment unsuccessful.', 'error');
      }
    );
  }
}
