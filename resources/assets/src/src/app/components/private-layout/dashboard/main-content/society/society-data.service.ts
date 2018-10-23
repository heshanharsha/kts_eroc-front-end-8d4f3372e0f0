import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class SocietyDataService {

  socId: string;
  
  downloadlink: string;
  constructor() { }

  // for continue upload process after some reasons...
  setSocId(socId: string) {
    this.socId = socId;
  }

  get getSocId() {
    return this.socId;
  }

  

  setDownloadlink(downloadlink: string) {
    this.downloadlink = downloadlink;
  }

  get getDownloadlink() {
    return this.downloadlink;
  }
}
