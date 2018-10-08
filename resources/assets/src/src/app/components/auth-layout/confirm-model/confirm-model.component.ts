import { ModalDirective } from 'angular-bootstrap-md';
import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-confirm-model',
  templateUrl: './confirm-model.component.html',
  styleUrls: ['./confirm-model.component.scss']
})
export class ConfirmModelComponent implements OnInit {
  @ViewChild('frame') modal: ModalDirective;

  private routerSLParam: string;
  private routerNSLParam: string;
  private paramiter: Array<any>;
  constructor(
    private router: Router
  ) { }

  ngOnInit() {
  }

  onShow(routerSlParam: string, routerNSLParam: string, paramiter: Array<any>): void {
    this.routerSLParam = routerSlParam;
    this.routerNSLParam = routerNSLParam;
    this.paramiter = paramiter;
    this.modal.show();
  }

  sriLankan(): void {
    console.log(this.routerSLParam);
    console.log(this.paramiter);
    this.router.navigate([this.routerSLParam, { request: this.paramiter['sparam'] }]);
    this.modal.hide();
  }

  nonSriLankan() {
    this.router.navigate([this.routerNSLParam, { request: this.paramiter['nsparam'] }]);
    this.modal.hide();
  }

}
