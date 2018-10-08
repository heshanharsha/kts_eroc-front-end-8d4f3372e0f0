import { Observable } from 'rxjs';
import { APIConnection } from './connections/APIConnection';
import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class DocumentsService {
  url: APIConnection = new APIConnection();

  constructor(
    private http: HttpClient
  ) { }

  uploadFile(formData: FormData): Observable<any> {
    return this.http.post<any>(this.url.setfileUploadAPI(), formData, { reportProgress: true, observe: 'events' });
  }
}
