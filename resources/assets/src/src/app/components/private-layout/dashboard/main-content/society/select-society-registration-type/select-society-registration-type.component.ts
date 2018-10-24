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
  mainMembers = [];

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
      this.memberload(socId);
      this.SocData.setMembArray(this.mainMembers);
      console.log(this.mainMembers)
      this.router.navigate(['/dashboard/societyincorporation']);
    }
  }

  designation_type: any;
  x: any;

  // main 8 members load function setMembArray
memberload(societyid) {
  const data = {
    societyid: societyid,
  };
  
  this.societyService.memberload(data)
    .subscribe(
      req => {
        //console.log(response['data']);
        if (req['data']) {
          if (req['data']['member']) {
            let x = 1;
            for (let i in req['data']['member']) {
              if(req['data']['member'][i]['designation_type']==1){
                this.designation_type = 'President';
              }
              else if(req['data']['member'][i]['designation_type']==2){
                this.designation_type = 'Secretary';
              }
              else if(req['data']['member'][i]['designation_type']==3){
                this.designation_type = 'Treasurer';
              }
              else if(req['data']['member'][i]['designation_type']==4){
                
                this.designation_type = 'Member'+x;
                x = x +1;

              }
              const data1 = {
                id: req['data']['member'][i]['id'],
                first_name: req['data']['member'][i]['first_name'],
                last_name: req['data']['member'][i]['last_name'],
                designation_type: this.designation_type,
                nic: req['data']['member'][i]['nic']
                  
              };
              this.mainMembers.push(data1);
              this.designation_type='';
            }
          }
          
        }
      },
      error => {
        console.log(error);
        
      }
    );
}



}
