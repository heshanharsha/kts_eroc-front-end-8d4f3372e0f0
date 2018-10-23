import { Injectable } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';

@Injectable({
  providedIn: 'root'
})
export class HelperService {

  constructor(private sanitizer: DomSanitizer) { }

  download(response: ArrayBuffer): void {
    const blob = new Blob([new Uint8Array(response)], { type: 'application/pdf' });
    const objectUrl = URL.createObjectURL(blob);
    window.open(objectUrl);
  }

  view(response: ArrayBuffer) {
    const blob = new Blob([new Uint8Array(response)], { type: 'image/jpeg' });
    const objectUrl =  window.URL.createObjectURL(blob);
    return objectUrl;
  }
}
