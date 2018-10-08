import { ITitle } from './../http/models/title.model';
import { Injectable } from '@angular/core';
import { onIReg } from '../http/models/register.model';
import { IUser } from '../http/models/user.model';

@Injectable({
  providedIn: 'root'
})
export class DataService {

  public regData: onIReg;
  public storage: any;
  public file: File;

  // tslint:disable-next-line:member-ordering
  public user: IUser;
  public ititles: ITitle;
}
