import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class HelperService {

  constructor() { }

  download(response: ArrayBuffer): void {
    const blob = new Blob([new Uint8Array(response)], { type: 'application/pdf' });
    const objectUrl = URL.createObjectURL(blob);
    window.open(objectUrl);
  }
}
