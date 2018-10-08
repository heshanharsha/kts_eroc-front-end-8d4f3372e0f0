import { ICompanyType, ISearch } from '../models/search.model';
import { Injectable } from '@angular/core';
import { INamereceive } from '../models/search.model';
import { Observable } from 'rxjs';
import { APIConnection } from './connections/APIConnection';
import { HttpClient } from '@angular/common/http';
import { INames, IReSubmit } from '../models/recervationdata.model';
import { AuthService } from '../shared/auth.service';

@Injectable({
  providedIn: 'root'
})
export class NameResarvationService {

  url: APIConnection = new APIConnection();

  constructor(private http: HttpClient, private Auth: AuthService) { }

  getSearchResult(data: ISearch, i: number): Observable<ISearch> {
    return this.http.post<ISearch>(this.url.getResultAPI() + (++i), data);
  }

  getNameReceive(data: INamereceive): Observable<INamereceive> {
    return this.http.post<INamereceive>(this.url.getNameReceiveAPI(), data);
  }

  getReceivedData(i: number, key: string = null): Observable<any> {
    return this.http.post<any>(this.url.getNameReceived() + (++i), { email: this.Auth.getEmail(), key: key });
  }

  setNameReceive(data: INamereceive): Observable<INamereceive> {
    return this.http.post<INamereceive>(this.url.getNameReceiveAPI(), data);
  }

  searchData(key: string): Observable<INames> {
    return this.http.get<INames>(this.url.getSearchData() + '?key=' + key + '&email=' + this.Auth.getEmail());
  }

  setPayment(): Observable<string> {
    return this.http.put<string>(this.url.getPaymentAPI(), { id: this.Auth.getCompanyId() });
  }

  getNameReservationData(id: number): Observable<INames> {
    return this.http.get<INames>(this.url.getNameReservationDataAPI() + id);
  }

  setReSubmitedData(data: IReSubmit): Observable<IReSubmit> {
    return this.http.put<IReSubmit>(this.url.getResubmitDataAPI(), { data });
  }

  isCheckPostfix(data: string): Observable<any> {
    return this.http.get<any>(this.url.getCheckFixDataAPI() + '?hasfix=' + data);
  }
}
