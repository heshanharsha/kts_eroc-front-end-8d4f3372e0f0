import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { DataService } from '../../storage/data.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(private router: Router, private dataService: DataService) { }

  auCheckActivation() {
    return this.router.navigate(['/activation']);
  }

  AuthGuard(): boolean {
    return !!localStorage.getItem('AccessToken');
  }

  accountVerify(): boolean {
    return (localStorage.getItem('AcountStatus') === '1');
  }

  getEmail() {
    return localStorage.getItem('currentUser');
  }

  getCompanyId() {
    return localStorage.getItem('ID');
  }

  setToken(req: any): void {
    if (req) {
      this.dataService.user = req['data'].user;
      localStorage.setItem('currentUser', JSON.stringify(req['data'].user.email));
      localStorage.setItem('AccessToken', JSON.stringify(req['data'].accessToken));
      localStorage.setItem('AcountStatus', JSON.stringify(req['data'].user.is_activation));
    }
  }
}
