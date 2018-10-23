import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { APIConnection } from './connections/APIConnection';
import { Observable } from 'rxjs';
import { ISocietyData } from '../models/society.model';

@Injectable({
  providedIn: 'root'
})
export class SocietyService {

  url: APIConnection = new APIConnection();

  constructor(private router: Router, private http: HttpClient) { }


  // to submit society data...
  societyDataSubmit(data: any): Observable<any> {
    return this.http.post(this.url.getSocietyDataSubmit(), data);
  }

  // to load registered secretary profile card...
  societyProfile(data: any): Observable<any> {
    return this.http.post(this.url.getSocietyProfileData(), data);
  }

  // to society payments...
  societyPay(data: any): Observable<any> {
    return this.http.post(this.url.getSocietyPay(), data);
  }

  // to download affidavit pdf...
  getDocumenttoServer(token: string) {
    return this.http.post(this.url.getSocietyDocumentDownloadAPI(), { token: token }, { responseType: 'arraybuffer' });
  }

  getPDFService() {
    return this.http.post(this.url.getSocietyDocumentDownloadAPI(),{},{ responseType: 'arraybuffer' });
  }

  getApplicationPDFService(societyid): Observable<any> {
    return this.http.post(this.url.getSocietyApplicationDownloadAPI(), { societyid: societyid }, { responseType: 'arraybuffer' });
  }






}
