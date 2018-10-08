/* ---------------------- Udara Madushan -------------------------*/
import { IDirectors, ISecretories, IShareHolders } from './stakeholder.model';

/* ---------------------- Udara Madushan -------------------------*/
export interface IIncorporationData {
    companyId: string;
}
/* ---------------------- Udara Madushan -------------------------*/
export interface IIncorporationDataStep1Data {
    companyId: string;
    companyType: number;
    address1: string;
    address2: string;
    city: string;
    district: string;
    province: string;
    email: string;
    objective: string;
}


/* ---------------------- Udara Madushan -------------------------*/
export interface IIncorporationMembers {
    companyId: string;
    directors: IDirectors;
    secretories: ISecretories;
    shareholders: IShareHolders;
    action?: string;
}

/* ---------------------- Udara Madushan -------------------------*/

export interface IcompanyInfo {
    abbreviation_desc:  string;
    address_id: number;
    created_at: string;
    created_by: number;
    email: string;
    id: number;
    name: string;
    name_si: string;
    name_ta: string;
    objective: string;
    postfix: string;
    status: number;
    type_id: number;
    updated_at: string;
}

export interface IcompanyAddress {
    address1: string;
    address2: string;
    city: string;
    country: string;
    created_at: string;
    district: string;
    id: number;
    postcode: string;
    province: string;
    updated_at: string;

}

export interface IcompanyType {
    id: number;
    key: string;
    value: string;
    value_si: string;
    value_ta: string;
}
export interface IcompnayTypesItem {
    company_type_id: number;
    id: number;
    postfix: string;
}
export interface IcompanyObjective {
    id: number;
    value: string;
    value_si: string;
    value_ta: string;
}
export interface IloginUserAddress {
    address1: string;
    address2: string;
    city: string;
    country: string;
    created_at: string;
    district: string;
    id: number;
    postcode: string;
    province: string;
    updated_at: string;
}

export interface IloginUser {
    address_id: number;
    created_at: string;
    dob: string;
    email: string;
    first_name: string;
    last_name: string;
    foreign_address_id: number;
    id: number;
    is_srilankan: string;
    mobile: string;
    nic: string;
    occupation: string;
    other_name: string;
    passport_issued_country: string;
    passport_no: string;
    profile_pic: string;
    sex: string;
    status: number;
    telephone: string;
    title: string;
    updated_at: string;

}

export interface IcoreShareGroup {
    group_id: number;
    group_name: string;
}


export interface Icountry {
    id: number;
    name: string;
    status: number;
}
