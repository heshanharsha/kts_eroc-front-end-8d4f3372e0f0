/* ---------------------- Udara Madushan -------------------------*/
export interface IDirector {
  type: string;
  title: string;
  firstname: string;
  lastname: string;
  province: string;
  district: string;
  city: string;
  localAddress1: string;
  localAddress2: string;
  postcode: string;

  nic: string;
  passport: string;
  country: string;
  share: number;
  date: string;
  occupation: string;

  phone: string;
  mobile: string;
  email: string;

  id: number;

  isSec?: boolean; // is secretory
  isSecEdit?: boolean;
  isShareholder?: boolean;
  isShareholderEdit?: boolean;

  shareType?: string;
  noOfSingleShares?: number;
  coreGroupSelected?: number;
  coreShareGroupName?: string;
  coreShareValue?: number;

  shareTypeEdit?: string;
  noOfSingleSharesEdit?: number;
  coreGroupSelectedEdit?: number;
  coreShareGroupNameEdit?: string;
  coreShareValueEdit?: number;

  showEditPaneForDirector: number;
  directors_as_sec?: number;
  directors_as_sh?: number;

}

/* ---------------------- Udara Madushan -------------------------*/
export interface IDirectors {
  directors: Array<IDirector>;
}

/* ---------------------- Udara Madushan -------------------------*/
export interface ISecretory {
  id: number;
  type: string;
  title: string;
  firstname: string;
  lastname: string;

  province: string;
  district: string;
  city: string;
  localAddress1: string;
  localAddress2: string;
  postcode: string;

  nic: string;
  passport: string;
  country: string;
  share: number;
  date: string;
  occupation: string;

  isReg: boolean;
  regDate: string;
  phone: string;
  mobile: string;
  email: string;

  isShareholder?: boolean;
  isShareholderEdit?: boolean;

  sec_as_sh?: number;
  sec_sh_comes_from_director?: boolean;

  shareType?: string; // single or core
  noOfSingleShares?: number;
  coreGroupSelected?: number;
  coreShareGroupName?: string;
  coreShareValue?: number;

  shareTypeEdit?: string; // single or core
  noOfSingleSharesEdit?: number;
  coreGroupSelectedEdit?: number;
  coreShareGroupNameEdit?: string;
  coreShareValueEdit?: number;

  secType?: string; // natural or firm
  secCompanyFirmId?: string;
  pvNumber?: string;
  firm_name?: string;
  firm_province?: string;
  firm_district?: string;
  firm_city?: string;
  firm_localAddress1?: string;
  firm_localAddress2?: string;
  firm_postcode?: string;

  savedSec?: string;

  firm_info?: IfirmInfo;

  showEditPaneForSec: number;
  forAddress1?: string;
  forAddress2?: string;
  forAddress3?: string;
}

export interface IfirmInfo {
  registration_no?: string;
  name?: string;
  address?: IformInfoAddress;
}

export interface IformInfoAddress {
  province?: string;
  district?: string;
  city?: string;
  address1?: string;
  address2?: string;
  postcode?: string;
}

/* ---------------------- Udara Madushan -------------------------*/
export interface ISecretories {
  secs: Array<ISecretory>;
}

/* ---------------------- Udara Madushan -------------------------*/
export interface IShareHolder {
  type: string;
  title: string;
  firstname: string;
  lastname: string;

  province: string;
  district: string;
  city: string;
  localAddress1: string;
  localAddress2: string;
  postcode: string;


  nic: string;
  passport: string;
  country: string;
  share: number;
  date: string;
  occupation: string;
  phone: string;
  mobile: string;
  email: string;
  id: number;

  shareholderType?: boolean;

  shareholderFirmCompanyisForiegn?: boolean;
  pvNumber?: string;
  firm_name?: string;
  firm_province?: string;
  firm_district?: string;
  firm_city?: string;
  firm_localAddress1?: string;
  firm_localAddress2?: string;
  firm_postcode?: string;
  shareType: string;
  noOfShares: number;
  coreGroupSelected?: number;
  coreShareGroupName?: string;
  showEditPaneForSh: number;
  shareRow?: IShareRow;
}


export interface IShareRow {
  type?: string;
  no_of_shares?: string;
  name?: string;
}

/* ---------------------- Udara Madushan -------------------------*/
export interface IShareHolders {
  shs: Array<IShareHolder>;
}

/* ---------------------- Udara Madushan -------------------------*/
export interface INICchecker {
  companyId: string;
  nic: string;
  memberType: number;
}

/* ---------------------- Udara Madushan -------------------------*/
export interface IStakeholderDelete {
  userId: number;
}

/* ---------------------- Udara Madushan -------------------------*/
export interface ISecForDirDelete {
  userId: number;
  companyId: string;
}
/* ---------------------- Udara Madushan -------------------------*/
export interface IShForDirDelete {
  userId: number;
  companyId: string;
}

/* ---------------------- Udara Madushan -------------------------*/
export interface IShForSecDelete {
  userId: number;
  companyId: string;
}
/* ---------------------- Udara Madushan -------------------------*/
export interface IFileRemove {
  companyId: number;
  docTypeId: number;
  userId?: number;
}
