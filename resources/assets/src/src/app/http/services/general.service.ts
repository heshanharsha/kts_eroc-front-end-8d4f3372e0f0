import { ITitle } from '../models/title.model';
import { ICompanyType } from '../models/search.model';
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { APIConnection } from './connections/APIConnection';
import { Observable } from 'rxjs';
import { IDocGroup } from '../models/doc.model';
import { IStatusCount } from '../models/recervationdata.model';
import { AuthService } from '../shared/auth.service';
import { tap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class GeneralService {

  url: APIConnection = new APIConnection();

  constructor(
    private router: Router,
    private http: HttpClient,
    private Auth: AuthService) { }

  getDocFeild(type: number, reqType: string = 'NAME_REG'): Observable<IDocGroup> {
    return this.http.post<IDocGroup>(this.url.getDocFeildAPI(), { type: type, req: reqType });
  }

  getComSubData(id: number): Observable<any> {
    return this.http.get<any>(this.url.getSubdataAPI() + id);
  }

  getStatusCount(): Observable<IStatusCount> {
    return this.http.post<IStatusCount>(this.url.getStatusCountAPI(), { email: this.Auth.getEmail() });
  }

  getCompanyType(): Observable<ICompanyType> {
    return this.http.get<ICompanyType>(this.url.getCompanyTypeAPI());
  }

  getDocumenttoServer(token: string) {
    return this.http.post(this.url.getDocumentDownloadAPI(), { token : token }, { responseType: 'arraybuffer'});
  }

  onDestroytoServer(token: string): Observable<any> {
    return this.http.delete<any>(this.url.getFileDestroyAPI() + '?token=' + token );
  }

  getSocietyDocumenttoServer(token: string, type: string = 'CAT_SOCIETY_DOCUMENT') {
    return this.http.post(this.url.getDocumentDownloadAPI(), { token : token , type: type }, { responseType: 'arraybuffer'});
  }

}
