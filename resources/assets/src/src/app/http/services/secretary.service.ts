import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { APIConnection } from './connections/APIConnection';
import { Observable } from 'rxjs';
import { ISecretaryData, ISecretaryLoad, ISecretaryDataFirm, IDeletePdfIndividual } from '../models/secretary.model';

@Injectable({
  providedIn: 'root'
})
export class SecretaryService {

  url: APIConnection = new APIConnection();

  constructor(private router: Router, private http: HttpClient) { }



  // to submit secretary data...
  secretaryDataSubmit(data: ISecretaryData): Observable<ISecretaryData> {
    return this.http.post<ISecretaryData>(this.url.getSecretaryDataSubmit(), data);
  }
  // to laod secretary data using nic...
  secretaryData(data: ISecretaryLoad): Observable<ISecretaryLoad> {
    return this.http.post<ISecretaryLoad>(this.url.getSecretaryData(), data);
  }

  // to submit secretary firm data...
  secretaryFirmDataSubmit(data: ISecretaryDataFirm): Observable<ISecretaryDataFirm> {
    return this.http.post<ISecretaryDataFirm>(this.url.getSecretaryFirmDataSubmit(), data);
  }

  // to laod secretary firm and pvt limited data using nic...
  secretaryFirmPartnerData(data: ISecretaryLoad): Observable<ISecretaryLoad> {
    return this.http.post<ISecretaryLoad>(this.url.getSecretaryFirmPartnerData(), data);
  }

  // to delete uploaded secretary individual pdf...
  secretaryDeleteUploadedPdf(data: IDeletePdfIndividual): Observable<IDeletePdfIndividual> {
    return this.http.post<IDeletePdfIndividual>(this.url.getSecretaryNaturalFileUploadedDelete(), data);
  }

  // to view uploaded secretary pdf...
  getDocumenttoServer(token: string) {
    return this.http.post(this.url.getSecretaryDocumentDownloadAPI(), { token: token }, { responseType: 'arraybuffer' });
  }





}
