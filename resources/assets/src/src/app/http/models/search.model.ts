export interface ISearch {
  criteria: number;
  searchtext: string;
  companyType: number;
  token: string;
}

export class IHas {
  availableData: IHasMeny;
  notHasData: IHasMeny;
}

export class IHasMeny {
  available: boolean;
  data: Array<any>;
  meta: IPaginate;
}

export class ISeResult {
  id: object;
  name: object;
  postfix: string;
  registration_no: string;
}

export class IPaginate {
  last_page: string;
  per_page: string;
  total: string;
}

export class ICompanyType {
  id: number;
  value: string;
}

export interface INamereceive {
  email: string;
  object: string;
  englishName: string;
  sinhalaName: string;
  tamilname: string;
  postfix: string;
  abreviations: string;
}

