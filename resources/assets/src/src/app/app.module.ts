import { SearchBarComponent } from './components/public-layout/search-bar/search-bar.component';
// Common Module
import { BrowserModule } from '@angular/platform-browser';
import { NgModule, NO_ERRORS_SCHEMA, APP_INITIALIZER } from '@angular/core';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MDBBootstrapModule } from 'angular-bootstrap-md';
import { MaterialModule } from './material-module/material.module';

// Install Library module
import { NgxSpinnerModule } from 'ngx-spinner';
import { SnotifyModule, SnotifyService, ToastDefaults } from 'ng-snotify';
import { AppRoutingModule } from './app-routing.module';

// App Component
import { AppComponent } from './app.component';
import { HeaderComponent } from './components/main-layout/header/header.component';
import { FooterComponent } from './components/main-layout/footer/footer.component';
import { SignUpComponent } from './components/auth-layout/sign-up/sign-up.component';
import { SignInComponent } from './components/auth-layout/sign-in/sign-in.component';
import { CredentialComponent } from './components/auth-layout/credential/credential.component';
import { ActivationComponent } from './components/public-layout/activation/activation.component';
import { BarComponent } from './components/public-layout/search-bar/bar/bar.component';
import { NavMenuComponent } from './components/public-layout/search-bar/nav-menu/nav-menu.component';
import { HorizontalStatusBarComponent } from './components/private-layout/dashboard/horizontal-status-bar/horizontal-status-bar.component';
import { ConfirmModelComponent } from './components/auth-layout/confirm-model/confirm-model.component';
import { NonSriLankanComponent } from './components/auth-layout/sign-up/non-sri-lankan/non-sri-lankan.component';
import { SriLankanComponent } from './components/auth-layout/sign-up/sri-lankan/sri-lankan.component';


// App Directive
import { CompareDirective } from './directive/validator/compare.directive';
import { UniqueEmailDirective } from './directive/validator/unique-email.directive';

// App Guard
import { AuthGuard } from './http/guards/auth.guard';
import { NonauthGuard } from './http/guards/nonauth.guard';

// App Interceptor
import { ErrorInterceptor } from './http/helpers/error.interceptor';
import { HorizontalMenuComponent } from './components/private-layout/dashboard/horizontal-menu/horizontal-menu.component';
import { VerticalMenuComponent } from './components/private-layout/dashboard/vertical-menu/vertical-menu.component';
import { DashboardComponent } from './components/private-layout/dashboard/dashboard.component';
import { ReservationComponent } from './components/private-layout/dashboard/main-content/reservation/reservation.component';
import { CompanyListComponent } from './components/private-layout/dashboard/main-content/company-list/company-list.component';
// tslint:disable-next-line:max-line-length
import { NameWithAgreeComponent } from './components/private-layout/dashboard/main-content/reservation/name-with-agree/name-with-agree.component';
// tslint:disable-next-line:max-line-length
import { NameWithDocumentsComponent } from './components/private-layout/dashboard/main-content/reservation/name-with-documents/name-with-documents.component';
// tslint:disable-next-line:max-line-length
import { NameWithPaymentComponent } from './components/private-layout/dashboard/main-content/reservation/name-with-payment/name-with-payment.component';

import { AppLoadService } from './http/shared/app-load.service';
import { SecretaryService } from './http/services/secretary.service';  //add by ravihansa...

// tslint:disable-next-line:max-line-length
import { AdvanceSearchBarComponent } from './components/private-layout/dashboard/main-content/advance-search-bar/advance-search-bar.component';
// tslint:disable-next-line:max-line-length
import { NameWithReSubmitedComponent } from './components/private-layout/dashboard/main-content/reservation/name-with-re-submited/name-with-re-submited.component';
import { OtherServiceComponent } from './components/private-layout/dashboard/main-content/other-service/other-service.component';
import { IncomparationComponent } from './components/private-layout/dashboard/main-content/incomparation/incomparation.component';
import { CompanyCardComponent } from './components/private-layout/dashboard/main-content/company-card/company-card.component';
import { SettingComponent } from './components/private-layout/dashboard/main-content/setting/setting.component';
// tslint:disable-next-line:max-line-length
import { ChangePasswordComponent } from './components/private-layout/dashboard/main-content/setting/change-password/change-password.component';
import { UniquePasswordDirective } from './directive/validator/unique-password.directive';
import { NameCheckNameWithReSubmiteComponent } from './components/private-layout/dashboard/main-content/reservation/name-check-name-with-re-submite/name-check-name-with-re-submite.component';
import { LocationStrategy, HashLocationStrategy } from '@angular/common';
import { SweetAlert2Module } from '@toverux/ngx-sweetalert2';
import { RegisterSecretaryCardComponent } from './components/private-layout/dashboard/main-content/secretary/register-secretary-card/register-secretary-card.component';
import { RegisterSecretaryNaturalpComponent } from './components/private-layout/dashboard/main-content/secretary/register-secretary-naturalp/register-secretary-naturalp.component';
import { RegisterSecretaryFirmComponent } from './components/private-layout/dashboard/main-content/secretary/register-secretary-firm/register-secretary-firm.component';
import { RegisterSecretaryPvtComponent } from './components/private-layout/dashboard/main-content/secretary/register-secretary-pvt/register-secretary-pvt.component';
import { SelectSocietyRegistrationTypeComponent } from './components/private-layout/dashboard/main-content/society/select-society-registration-type/select-society-registration-type.component';
import { SocietyNameReservationComponent } from './components/private-layout/dashboard/main-content/society/society-name-reservation/society-name-reservation.component';
import { NameWithAgreeReservationComponent } from './components/private-layout/dashboard/main-content/society/name-with-agree-reservation/name-with-agree-reservation.component';
import { SocietyIncorporationComponent } from './components/private-layout/dashboard/main-content/society/society-incorporation/society-incorporation.component';




export function init_app(appLoadService: AppLoadService) {
  return () => appLoadService.initializeApp();
}

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    FooterComponent,
    SignUpComponent,
    SignInComponent,
    CompareDirective,
    UniqueEmailDirective,
    CredentialComponent,
    ActivationComponent,
    SearchBarComponent,
    NavMenuComponent,
    BarComponent,
    HorizontalMenuComponent,
    VerticalMenuComponent,
    DashboardComponent,
    ReservationComponent,
    CompanyListComponent,
    NameWithAgreeComponent,
    NameWithDocumentsComponent,
    NameWithPaymentComponent,
    HorizontalStatusBarComponent,
    ConfirmModelComponent,
    NonSriLankanComponent,
    SriLankanComponent,
    AdvanceSearchBarComponent,
    NameWithReSubmitedComponent,
    OtherServiceComponent,
    IncomparationComponent,
    SettingComponent,
    CompanyCardComponent,
    ChangePasswordComponent,
    UniquePasswordDirective,
    NameCheckNameWithReSubmiteComponent,
    RegisterSecretaryCardComponent,
    RegisterSecretaryNaturalpComponent,
    RegisterSecretaryFirmComponent,
    RegisterSecretaryPvtComponent,
    SelectSocietyRegistrationTypeComponent,
    SocietyNameReservationComponent,
    NameWithAgreeReservationComponent,
    SocietyIncorporationComponent,


  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    MDBBootstrapModule.forRoot(),
    MaterialModule,
    AppRoutingModule,
    NgxSpinnerModule,
    SnotifyModule,
    SweetAlert2Module.forRoot({
      buttonsStyling: false,
      customClass: 'modal-content',
      confirmButtonClass: 'btn btn-primary',
      cancelButtonClass: 'btn'
    })
  ],
  schemas: [NO_ERRORS_SCHEMA],
  providers: [
    AppLoadService,
    { provide: APP_INITIALIZER, useFactory: init_app, deps: [AppLoadService], multi: true },
    AuthGuard,
    NonauthGuard,
    { provide: HTTP_INTERCEPTORS, useClass: ErrorInterceptor, multi: true },
    { provide: 'SnotifyToastConfig', useValue: ToastDefaults },
    { provide: LocationStrategy, useClass: HashLocationStrategy },
    SnotifyService,
    SecretaryService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
