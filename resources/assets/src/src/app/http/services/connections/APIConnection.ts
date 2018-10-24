export class APIConnection {
  _getStatusCountUrl: string;
  _getgetPaymentURL: string;
  _getComsubData: string;
  _getSearchDataUrl: string;
  _getReceivedUrl: string;
  _getuploadURL: string;
  _getcheckEmail: string;
  _getdocAPI: string;
  _getauthUser: string;
  _apiUrl: string;
  _getauthLogin: string;
  _getauthRegister: string;
  _getauthlogout: string;
  _getSEUrlPages: string;
  _getcTypeUrl: string;
  _getcReceUrl: string;
  _getauActivationUrl: string;
  _getMemberTitleUrl: string;
  _getNameReservationDataUrl: string;
  _getResubmitDataUrl: string;
  _getCheckFoxDataUrl: string;
  _getDownloadUrl: string;
  _getCheckSamePasswordUrl: string;

  /* ---------- Udara Madushan -----------*/
  _incorporationData: string;
  _incorporationDataStep1Submit: string;
  _incorporationDataStep2Submit: string;
  _incorporationCheckNic: string;
  _incorporationDeleteStakeholder: string;
  _incorporationFileUpload: string;
  _incorporationPay: string;
  _getFileDestroyUrl: string;

  // 2018-09-03 Updated
  _incorporationSecForDirDelete: string;
  _incorporationShForDirDelete: string;
  _incorporationShForSecDelete: string;

  // 2018-09-06 Updated
  _getResubmitURL: string;

  // 2018-09-11 Updated
  _incorporationFileRemove: string;
  /* ---------- Udara Madushan -----------*/

  /* ---------- ravihansa 20180919-----------*/
  _secretaryDataSubmit: string;
  _secretaryData: string;
  _secretaryFirmDataSubmit: string;
  _secretaryPvtDataSubmit: string;
  _secretaryFirmPartnerData: string;
  _secretaryNaturalUpload: string;
  _secretaryNaturalUploadedDelete: string;
  _secretaryGetDownloadUrl: string;

/* ---------- thilan 20181010-----------*/
_societyDataSubmit: string;
_societyProfileData: string;
_societyPay: string;
_societyUpload: string;
_societyMemberData: string;

//----------------heshan------------------//
_societyGetDownloadUrl: string;
_societyApplicantGetDownloadUrl: string;

//------------sahani------------
_getSocietySEUrlPages: string;



  constructor() {
    //this._apiUrl = `http://220.247.219.173/frontend/API/eRoc/public/`;
    //this._apiUrl = `http://localhost:8000`;
    this._apiUrl = `http://localhost/kts_eroc-front-end-8d4f3372e0f0/public/`;
    this._getauthLogin = `${this._apiUrl}/api/v1/eroc/login`;
    this._getauthRegister = `${this._apiUrl}/api/v1/eroc/register`;
    this._getauthlogout = `${this._apiUrl}/api/v1/eroc/logout`;
    this._getauthUser = `${this._apiUrl}/api/v1/eroc/users`;
    this._getSEUrlPages = `${this._apiUrl}/api/v1/eroc/name/search?page=`;
    this._getcTypeUrl = `${this._apiUrl}/api/v1/eroc/company/type`;
    this._getcReceUrl = `${this._apiUrl}/api/v1/eroc/name/receive`;
    this._getauActivationUrl = `${this._apiUrl}/api/v1/eroc/user/verification`;
    this._getdocAPI = `${this._apiUrl}/api/v1/eroc/document/feild`;
    this._getcheckEmail = `${this._apiUrl}/api/v1/eroc/user/exists`;
    this._getuploadURL = `${this._apiUrl}/api/v1/eroc/name/receive/files/upload`;
    this._getReceivedUrl = `${this._apiUrl}/api/v1/eroc/name/received?page=`;
    this._getSearchDataUrl = `${this._apiUrl}/api/v1/eroc/name/search`;
    this._getCheckFoxDataUrl = `${this._apiUrl}/api/v1/eroc/name/fix/has`;

    this._getComsubData = `${this._apiUrl}/api/v1/eroc/company/sub/category?id=`;
    this._getgetPaymentURL = `${this._apiUrl}/api/v1/eroc/name/payment`;
    this._getStatusCountUrl = `${this._apiUrl}/api/v1/eroc/status/count`;
    this._getMemberTitleUrl = `${this._apiUrl}/api/v1/eroc/member/title`;
    this._getNameReservationDataUrl = `${this._apiUrl}/api/v1/eroc/name/reservation/data?id=`;
    this._getResubmitDataUrl = `${this._apiUrl}/api/v1/eroc/name/re/submit`;
    this._getDownloadUrl = `${this._apiUrl}/api/v1/eroc/document/download`;
    this._getFileDestroyUrl = `${this._apiUrl}/api/v1/eroc/document/destroy`;
    this._getCheckSamePasswordUrl = `${this._apiUrl}/api/v1/eroc/changePassword`;

    /* ---------- Udara Madushan -----------*/
    this._incorporationData = `${this._apiUrl}/api/v1/company-incorporation-data`;
    this._incorporationDataStep1Submit = `${this._apiUrl}/api/v1/company-incorporation-data-step1`;
    this._incorporationDataStep2Submit = `${this._apiUrl}/api/v1/company-incorporation-data-step2`;
    this._incorporationDeleteStakeholder = `${this._apiUrl}/api/v1/company-incorporation-delete-stakeholder`;
    this._incorporationCheckNic = `${this._apiUrl}/api/v1/company-incorporation-check-nic`;
    this._incorporationFileUpload = `${this._apiUrl}/api/v1/file-upload`;
    this._incorporationPay = `${this._apiUrl}/api/v1/pay`;

    // 2018-09-03 Updated
    this._incorporationSecForDirDelete = `${this._apiUrl}/api/v1/remove-director-sec-position`;
    this._incorporationShForDirDelete = `${this._apiUrl}/api/v1/remove-director-sh-position`;
    this._incorporationShForSecDelete = `${this._apiUrl}/api/v1/remove-sec-sh-position`;

    // 2018-09-06 Updated
    this._getResubmitURL = `${this._apiUrl}/api/v1/re-submit`;

    // 2018-09-11 updated
    this._incorporationFileRemove = `${this._apiUrl}/api/v1/file-remove`;
    /* ---------- Udara Madushan -----------*/


    /* ---------- ravihansa 20180919-----------*/
    this._secretaryDataSubmit = `${this._apiUrl}/api/v1/secretary-data-submit`;
    this._secretaryData = `${this._apiUrl}/api/v1/secretary-data-load`;
    this._secretaryFirmDataSubmit = `${this._apiUrl}/api/v1/secretary-firm-data-submit`;
    this._secretaryPvtDataSubmit = `${this._apiUrl}/api/v1/secretary-pvt-data-submit`;
    this._secretaryFirmPartnerData = `${this._apiUrl}/api/v1/secretary-firm-data-load`;
    this._secretaryNaturalUpload = `${this._apiUrl}/api/v1/secretary-natural-upload-pdf`;
    this._secretaryNaturalUploadedDelete = `${this._apiUrl}/api/v1/secretary-natural-delete-pdf`;
    this._secretaryGetDownloadUrl = `${this._apiUrl}/api/v1/secretary-view-document`;

    /* ---------- thilan 20181010-----------*/
    this._societyDataSubmit = `${this._apiUrl}/api/v1/society-data-submit`;
    this._societyProfileData = `${this._apiUrl}/api/v1/society-profile-load`;
    this._societyPay = `${this._apiUrl}/api/v1/society-pay`;
    this._societyUpload = `${this._apiUrl}/api/v1/society-upload-pdf`;
    this._societyMemberData = `${this._apiUrl}/api/v1/society-member-data-load`;

    /*-------heshan-----------------*/
    this._societyGetDownloadUrl = `${this._apiUrl}/api/v1/society-view-document`;
    this._societyApplicantGetDownloadUrl = `${this._apiUrl}/api/v1/society-application-document`;

    /* ---------- sahani -----------*/
  
    this._getSocietySEUrlPages = `${this._apiUrl}/api/v1/eroc/name/search/society?page=`;


  }

  public getLoginAPI(): string {
    return this._getauthLogin;
  }
  public getRegisterAPI(): string {
    return this._getauthRegister;
  }

  public getLogoutAPI(): string {
    return this._getauthlogout;
  }

  public getCheckSamePasswordAPI(): string {
    return this._getCheckSamePasswordUrl;
  }

  public getResultAPI(): string {
    return this._getSEUrlPages;
  }
  public getCompanyTypeAPI(): string {
    return this._getcTypeUrl;
  }
  public getNameReceiveAPI(): string {
    return this._getcReceUrl;
  }

  public getActivationAPI(): string {
    return this._getauActivationUrl;
  }

  public getUserAPI(): string {
    return this._getauthUser;
  }

  public getDocFeildAPI(): string {
    return this._getdocAPI;
  }

  public checkEmailAPI(): string {
    return this._getcheckEmail;
  }

  public setfileUploadAPI(): string {
    return this._getuploadURL;
  }

  public getNameReceived(): string {
    return this._getReceivedUrl;
  }

  public getDocumentDownloadAPI(): string {
    return this._getDownloadUrl;
  }

  public getFileDestroyAPI(): string {
    return this._getFileDestroyUrl;
  }

  public getSearchData(): string {
    return this._getSearchDataUrl;
  }

  public getCheckFixDataAPI(): string {
    return this._getCheckFoxDataUrl;
  }

  public getSubdataAPI(): string {
    return this._getComsubData;
  }

  public getPaymentAPI(): string {
    return this._getgetPaymentURL;
  }
  public getStatusCountAPI(): string {
    return this._getStatusCountUrl;
  }

  public getMemberTitleAPI(): string {
    return this._getMemberTitleUrl;
  }

  public getNameReservationDataAPI(): string {
    return this._getNameReservationDataUrl;
  }

  public getResubmitDataAPI(): string {
    return this._getResubmitDataUrl;
  }

 
  /* ---------- Udara Madushan -----------*/
  public getIncorporationData() {
    return this._incorporationData;
  }
  public getIncorporationDataStep1Submit() {
    return this._incorporationDataStep1Submit;
  }
  public getIncorporationDataStep2Submit() {
    return this._incorporationDataStep2Submit;
  }
  public getIncorporationNICCheckURL() {
    return this._incorporationCheckNic;
  }

  public getIncorporationRemoveStakeholderURL() {
    return this._incorporationDeleteStakeholder;
  }

  public getFileUploadURL() {
    return this._incorporationFileUpload;
  }

  public incorparationPay() {
    return this._incorporationPay;
  }

  // 2018-09-03 updated
  public incorparationSecForDirDeleteURL() {
    return this._incorporationSecForDirDelete;
  }
  public incorparationShForDirDeleteURL() {
    return this._incorporationShForDirDelete;
  }

  public incorparationShForSecDeleteURL() {
    return this._incorporationShForSecDelete;
  }

  // 2018-09-06 update
  public incorparationResubmitURL() {
    return this._getResubmitURL;
  }

  // 2018-09-11 update
  public incorparationFileRemoveURL() {
    return this._incorporationFileRemove;
  }

  /* ---------- Udara Madushan -----------*/


  /* ---------- ravihansa 20180919-----------*/
  public getSecretaryDataSubmit() {
    return this._secretaryDataSubmit;
  }
  public getSecretaryFirmDataSubmit() {
    return this._secretaryFirmDataSubmit;
  }
  public getSecretaryPvtDataSubmit() {
    return this._secretaryPvtDataSubmit;
  }
  public getSecretaryData() {
    return this._secretaryData;
  }
  public getSecretaryFirmPartnerData() {
    return this._secretaryFirmPartnerData;
  }
  public getSecretaryNaturalFileUploadUrl() {
    return this._secretaryNaturalUpload;
  }
  public getSecretaryNaturalFileUploadedDelete() {
    return this._secretaryNaturalUploadedDelete;
  }

  public getSecretaryDocumentDownloadAPI(): string {
    return this._secretaryGetDownloadUrl;
  }

  /* ---------- thilan 20181010-----------*/
  public getSocietyDataSubmit() {
    return this._societyDataSubmit;
  }

  public getSocietyProfileData() {
    return this._societyProfileData;
  }

  public getSocietyPay() {
    return this._societyPay;
  }

  public getSocietyFileUploadUrl() {
    return this._societyUpload;
  }

  public getSocietyMemberData() {
    return this._societyMemberData;
  }

  /* ---------- heshan -----------*/
  public getSocietyDocumentDownloadAPI() {
    return this._societyGetDownloadUrl;
  }
    
  public getSocietyApplicationDownloadAPI() {
    return this._societyApplicantGetDownloadUrl;
  }

  /* ---------- sahani -----------*/
  public getResultSocietyAPI(): string {
    return this._getSocietySEUrlPages;
  }






}
