import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { APIConnection } from './connections/APIConnection';
import { Injectable } from '@angular/core';
import { AuthService } from '../shared/auth.service';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  url: APIConnection = new APIConnection();

  constructor(
    private http: HttpClient,
    private Auth: AuthService
  ) { }

  getUser(): Observable<any> {
    return this.http.post<any>(this.url.getUserAPI(), { email: this.Auth.getEmail() });
  }

  getUserByEmail(c: string): Observable<any> {
    return this.http.get<any>(this.url.checkEmailAPI() + '?email=' + c);
  }

}
