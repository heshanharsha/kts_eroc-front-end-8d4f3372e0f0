export interface IRegister {
  title: string;
  firstname: string;
  lastname: string;
  otherName: string;
  nic: string;
  passportid: string;
  passportIssueCountry: string;
  occupation: string;
  mobileNumber: number;
  telephoneNumber: number;
}

export interface IAddress {
  address01: string;
  address02: string;
  city: string;
  district: string;
  province: string;
  country: string;
  postCode: number;
}

export interface ICredential {
  email: string;
  password: string;
  password_confirmation: string;
}

// tslint:disable-next-line:class-name
export class onIReg {
  details: IRegister;
  address: Array<IAddress>;
}



// tslint:disable-next-line:class-name
export class onIRegWithCred {
  registerData: onIReg;
  credential: ICredential;
}
