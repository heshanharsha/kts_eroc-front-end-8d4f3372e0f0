import { onIRegWithCred } from '../models/register.model';
import { Injectable } from '@angular/core';
import { APIConnection } from './connections/APIConnection';
import { Router } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { IAuth } from '../models/auth.model';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';
import { AuthService } from '../shared/auth.service';
import { DataService } from '../../storage/data.service';

@Injectable({
  providedIn: 'root'
})
export class AuthenticationService {

  url: APIConnection = new APIConnection();
  reqHeader = new HttpHeaders({ 'Content-Type': 'application/x-www-urlencoded', 'No-Auth': 'True' });

  constructor(private router: Router, private http: HttpClient, private Auth: AuthService) { }

  aulogin(data: IAuth): Observable<IAuth> {
    return this.http.post<IAuth>(this.url.getLoginAPI(), data)
      .pipe(
        tap(req => {
          return this.Auth.setToken(req);
        })
      );
  }

  auActivation(email: string, token: string) {
    return this.http.put<any>(this.url.getActivationAPI(), { email: email, token: token })
      .pipe(tap(req => {
            return this.Auth.setToken(req);
    }));
  }

  auRegister(data: onIRegWithCred) {
    return this.http.post<onIRegWithCred>(this.url.getRegisterAPI(), data).pipe(
      tap(req => {
        return this.Auth.setToken(req);
      })
    );
  }

  aulogout() {
    localStorage.removeItem('AccessToken');
    localStorage.removeItem('currentUser');
    this.router.navigate(['/home']);
    return this.http.get(this.url.getLogoutAPI());
  }

  auIsChackSamePassword(c: string, email: string = this.Auth.getEmail()): Observable<any> {
    return this.http.get<any>(this.url.getCheckSamePasswordAPI() + '?password=' + c + '&email=' + email);
  }

  auPasswordChange(data: any): Observable<any> {
    return this.http.put<any>(this.url.getCheckSamePasswordAPI(), { data });
  }

}
