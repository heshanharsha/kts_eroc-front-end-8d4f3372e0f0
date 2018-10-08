import { SettingComponent } from './components/private-layout/dashboard/main-content/setting/setting.component';
import { CompanyCardComponent } from './components/private-layout/dashboard/main-content/company-card/company-card.component';
// tslint:disable-next-line:max-line-length
import { NameWithReSubmitedComponent } from './components/private-layout/dashboard/main-content/reservation/name-with-re-submited/name-with-re-submited.component';
// tslint:disable-next-line:max-line-length
import { AdvanceSearchBarComponent } from './components/private-layout/dashboard/main-content/advance-search-bar/advance-search-bar.component';
import { NonSriLankanComponent } from './components/auth-layout/sign-up/non-sri-lankan/non-sri-lankan.component';
import { SriLankanComponent } from './components/auth-layout/sign-up/sri-lankan/sri-lankan.component';
import { ActivationComponent } from './components/public-layout/activation/activation.component';
import { AuthActivationGuard } from './http/guards/auth-activation.guard';
import { NonauthGuard } from './http/guards/nonauth.guard';
import { CredentialComponent } from './components/auth-layout/credential/credential.component';
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SearchBarComponent } from './components/public-layout/search-bar/search-bar.component';
import { ReservationComponent } from './components/private-layout/dashboard/main-content/reservation/reservation.component';
import { AuthGuard } from './http/guards/auth.guard';
import { DashboardComponent } from './components/private-layout/dashboard/dashboard.component';
import { CompanyListComponent } from './components/private-layout/dashboard/main-content/company-list/company-list.component';
// tslint:disable-next-line:max-line-length
import { NameWithAgreeComponent } from './components/private-layout/dashboard/main-content/reservation/name-with-agree/name-with-agree.component';
// tslint:disable-next-line:max-line-length
import { NameWithDocumentsComponent } from './components/private-layout/dashboard/main-content/reservation/name-with-documents/name-with-documents.component';
// tslint:disable-next-line:max-line-length
import { NameWithPaymentComponent } from './components/private-layout/dashboard/main-content/reservation/name-with-payment/name-with-payment.component';
// tslint:disable-next-line:max-line-length
import { ChangePasswordComponent } from './components/private-layout/dashboard/main-content/setting/change-password/change-password.component';
import { IncomparationComponent } from './components/private-layout/dashboard/main-content/incomparation/incomparation.component';
import { RegisterSecretaryCardComponent } from './components/private-layout/dashboard/main-content/secretary/register-secretary-card/register-secretary-card.component';
import { RegisterSecretaryNaturalpComponent } from './components/private-layout/dashboard/main-content/secretary/register-secretary-naturalp/register-secretary-naturalp.component';
import { RegisterSecretaryFirmComponent } from './components/private-layout/dashboard/main-content/secretary/register-secretary-firm/register-secretary-firm.component';
import { RegisterSecretaryPvtComponent } from './components/private-layout/dashboard/main-content/secretary/register-secretary-pvt/register-secretary-pvt.component';

// society components
import { SelectSocietyRegistrationTypeComponent } from './components/private-layout/dashboard/main-content/society/select-society-registration-type/select-society-registration-type.component';
import { SocietyNameReservationComponent } from './components/private-layout/dashboard/main-content/society/society-name-reservation/society-name-reservation.component';




const routes: Routes = [
  { path: 'user/activation', component: ActivationComponent },
  { path: 'home', component: SearchBarComponent },
  {
    path: 'activation',
    component: ActivationComponent,
    canActivate: [AuthActivationGuard],
    data: {
      breadcrumb: 'Active Varification'
    }
  },
  {
    path: 'srilankan/register',
    component: SriLankanComponent,
    canActivate: [NonauthGuard],
    data: {
      breadcrumb: 'Registration'
    }
  },
  {
    path: 'nonesrilankan/register',
    component: NonSriLankanComponent,
    canActivate: [NonauthGuard],
    data: {
      breadcrumb: 'Registration'
    }
  },
  {
    path: 'credential',
    component: CredentialComponent,
    canActivate: [NonauthGuard],
    data: {
      breadcrumb: 'Account Credential'
    }
  },
  {
    path: 'reservation',
    component: ReservationComponent,
    canActivate: [AuthGuard],
    data: {
      breadcrumb: 'Name Reservation'
    },
    children: [
      { path: '', component: NameWithAgreeComponent, canActivate: [AuthGuard] },
      {
        path: 'documents',
        component: NameWithDocumentsComponent,
        canActivate: [AuthGuard],
        data: {
          breadcrumb: 'Name Reservation Documents'
        },

      },
      {
        path: 'payment',
        component: NameWithPaymentComponent,
        canActivate: [AuthGuard],
        data: {
          breadcrumb: 'Payment'
        }
      }
    ]
  },
  {
    path: 'dashboard',
    component: DashboardComponent,
    data: {
      breadcrumb: 'Dashboard'
    },
    children: [
      {
        path: 'home',
        component: CompanyListComponent,
        canActivate: [AuthGuard],
        data: {
          breadcrumb: 'My Company'
        }
      },

      // society dashboard routes
      {
        path: 'selectregistersociety',
        component: SelectSocietyRegistrationTypeComponent,
        canActivate: [AuthGuard],
      },

      {
        path: 'selectregistersociety/namereservation',
        component: SocietyNameReservationComponent,
        canActivate: [AuthGuard],
      },


      
      /*-------------------reavihansa------------------*/
      {
        path: 'selectregistersecretary',
        component: RegisterSecretaryCardComponent,
        canActivate: [AuthGuard],
      },
      /*-------------------reavihansa------------------*/



      {
        path: 'advance/name/reservation',
        component: AdvanceSearchBarComponent,
        canActivate: [AuthGuard],
        data: {
          breadcrumb: 'My Company'
        }
      },
      {
        path: 'name/re-submition/:id',
        component: NameWithReSubmitedComponent,
        canActivate: [AuthGuard],
        data: {
          breadcrumb: 'My Company'
        }
      },
      {
        path: 'home/company/card/:id',
        component: CompanyCardComponent,
        canActivate: [AuthGuard],
      },
      {
        path: 'settings',
        component: SettingComponent,
        canActivate: [AuthGuard],
      },
      {
        path: 'settings/change/my/password',
        component: ChangePasswordComponent,
        canActivate: [AuthGuard],
      }

    ]

  },

  /*-------------------reavihansa------------------*/
  {
    path: 'dashboard/selectregistersecretary/registersecretarynatural/:nic',
    canActivate: [AuthGuard],
    component: RegisterSecretaryNaturalpComponent

  },
  {
    path: 'dashboard/selectregistersecretary/registersecretaryfirm',
    canActivate: [AuthGuard],
    component: RegisterSecretaryFirmComponent

  },
  {
    path: 'dashboard/selectregistersecretary/registersecretarypvt',
    canActivate: [AuthGuard],
    component: RegisterSecretaryPvtComponent

  },

  /*-------------------reavihansa------------------*/




  {
    path: 'dashboard/incorporation/:companyId',
    canActivate: [AuthGuard],
    component: IncomparationComponent
  },



  { path: '**', redirectTo: 'home', pathMatch: 'full' },


];

@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: true })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
