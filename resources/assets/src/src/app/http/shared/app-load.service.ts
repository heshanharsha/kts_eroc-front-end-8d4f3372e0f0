import { APIConnection } from '../services/connections/APIConnection';
import { ITitle } from '../models/title.model';
import { DataService } from '../../storage/data.service';
import { GeneralService } from '../services/general.service';
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class AppLoadService {
  url: APIConnection = new APIConnection();
  constructor(
    private generalService: GeneralService,
    private http: HttpClient,
    private dataService: DataService) { }

  initializeApp(): Promise<ITitle> {

    return this.http.get<ITitle>(this.url.getMemberTitleAPI())
      .toPromise()
      .then(req => {
        this.dataService.ititles = req;
        return req;
      });
  }


}
