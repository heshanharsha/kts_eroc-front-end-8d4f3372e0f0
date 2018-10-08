import { IAddress } from './register.model';

export interface INames {
  id: string;
  name: string;
  name_si: string;
  name_ta: string;
  postfix: string;
  abbreviation_desc: string;
  email: string;
  created_at: string;
  updated_at: string;
  status: string;
  address1: string;
  address2: string;
  city: string;
  district: string;
  province: string;
  country: string;
  postCode: number;
  key: string;
  documents: Array<IDocuments>;
}

export interface IDocuments {
  company_id: string;
  created_at: string;
  document_id: string;
  file_token: string;
  id: string;
  no_of_pages: string;
  updated_at: string;
  document_group_id:  string;
  name:  string;
  description: string;
}


export interface IStatusCount {
  all: number;
  pending: number;
  approval: number;
  submited: number;
  rejected: number;
  canceled: number;
}


export interface IReSubmit {
  refId: number;
  companyName: string;
  sinhalaName: string;
  tamileName: string;
  abbreviation_desc: string;
}
