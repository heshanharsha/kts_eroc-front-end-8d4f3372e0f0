import { Component, OnInit } from '@angular/core';
import { SocietyService } from '../../../../../../http/services/society.service';
import { SocietyDataService } from '../society-data.service';
import { Router, ActivatedRoute } from '@angular/router';
import { DataService } from '../../../../../../storage/data.service';

@Component({
  selector: 'app-select-society-registration-type',
  templateUrl: './select-society-registration-type.component.html',
  styleUrls: ['./select-society-registration-type.component.scss']
})
export class SelectSocietyRegistrationTypeComponent implements OnInit {

  constructor(
    private societyService: SocietyService,
    private SocData: SocietyDataService,
    private router: Router,
    private route: ActivatedRoute,
    public data: DataService
  ) {
    this.loggedinUserEmail = localStorage.getItem('currentUser');
      this.loggedinUserEmail = this.loggedinUserEmail.replace(/^"(.*)"$/, '$1');
      this.loadSecretaryProfileCard(this.loggedinUserEmail);
   }

  ngOnInit() {
  }

  loggedinUserEmail: any;
  registeredSocieties = [];
  needApproval1: any;

  loadSecretaryProfileCard(loggedUsr) {
    const data = {
      loggedInUser: loggedUsr,
    };
    this.societyService.societyProfile(data)
      .subscribe(
        req => {
          if (req['data']) {
            if (req['data']['society']) {
              for (let i in req['data']['society']) {
                const data1 = {
                  id: req['data']['society'][i]['id'],
                  name: req['data']['society'][i]['name'],
                  type_id: req['data']['society'][i]['type_id'],
                  created_at: req['data']['society'][i]['created_at'],
                  status: req['data']['society'][i]['value'],
                  statuskey: req['data']['society'][i]['status'],
                  name_si: req['data']['society'][i]['name_si'],
                  name_ta: req['data']['society'][i]['name_ta'],
                  abbreviation_desc: req['data']['society'][i]['abbreviation_desc']
                  
                };
                this.registeredSocieties.push(data1);
              }
            }
            
          }
        },
        error => {
          console.log(error);
        }
      );
  }

  continueRegistration(key, socId,name,name_si,name_ta,abbreviation_desc,type_id) {
    if (key === 'SOCIETY_PROCESSING') {
      if(type_id==1){
        this.needApproval1 = true;
      }
      else if(type_id==0){
        this.needApproval1 = false;
      }
      this.data.storage1 = {
        name: name,
        sinhalaName: name_si,
        tamilname: name_ta,
        abreviations: abbreviation_desc,
        needApproval: this.needApproval1
      };
       this.SocData.setSocId(socId);
      this.SocData.setDownloadlink(localStorage.getItem(socId));
      this.router.navigate(['/dashboard/societyincorporation']);
    }
  }



}
