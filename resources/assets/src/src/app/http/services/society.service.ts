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
  societyDataSubmit(data: ISocietyData): Observable<ISocietyData> {
    return this.http.post<ISocietyData>(this.url.getSocietyDataSubmit(), data);
  }






}
