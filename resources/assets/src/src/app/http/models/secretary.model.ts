/*--------- ravihansa------------------*/

export interface ISecretaryData {

    registeredUser: boolean;  // to check applicant already user in roc...

    nic: string;
    loggedInUser: string;  //to get logged in user id, using his email...

    id: number;
    title: string;
    firstname: string;
    lastname: string;
    othername: string;
    residentialLocalAddress1: string;
    residentialLocalAddress2: string;
    residentialProvince: string;
    residentialDistrict: string;
    residentialCity: string;
    businessName: string;
    businessLocalAddress1: string;
    businessLocalAddress2: string;
    businessProvince: string;
    businessDistrict: string;
    businessCity: string;
    subClauseQualified: string;

    pQualification: string;
    eQualification: string;
    wExperience: string;
    isUnsoundMind: string;
    isInsolventOrBankrupt: string;
    reason1: string;
    isCompetentCourt: string;
    reason2: string;
    workHis: any;
}


export interface ISecretaryLoad {
    nic: string;
}

export interface ISecretaryWorkHistoryData {
    id: number;
    companyName: string;
    position: string;
    from: string;
    to: string;

}


export interface ISecretaryDataFirm {
    id: number;
    name: string;
    registrationNumber: string;
    businessLocalAddress1: string;
    businessLocalAddress2: string;
    businessProvince: string;
    businessDistrict: string;
    businessCity: string;
    isUndertakeSecWork: string;

    isUnsoundMind: string;
    isInsolventOrBankrupt: string;
    reason1: string;
    isCompetentCourt: string;
    reason2: string;
    firmPartners: any;
}


export interface ISecretaryFirmPartnerData {
    id: number;
    name: string;
    residentialAddress: string;
    citizenship: string;
    whichQualified: string;
    pQualification: string;

}

export interface IDeletePdfIndividual {
    documentId: number;
}












/*--------- ravihansa------------------*/