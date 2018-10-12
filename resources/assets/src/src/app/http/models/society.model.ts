export interface ISocietyData {
   
    id: number;
    name_of_society: number;
    place_of_office: number;
    whole_of_the_objects: number;
    funds: number;
    terms_of_admission: number;
    condition_under_which_any: number;
    fines_and_foreitures: number;
    mode_of_holding_meetings: number;
    manner_of_rules: number;
    investment_of_funds: number;
    keeping_accounts: number;
    audit_of_the_accounts: number;
    annual_returns: number;
    number_of_members: number;
    inspection_of_the_books: number;
    disputes_manner: number;
    case_of_society: number;
    email: string;
    appointment_and_removal_committee: number;
    
}

export interface IPresident {
    id: number;
    type: number;
    firstname: string;
    lastname: string;
    province: string;
    district: string;
    city: string;
    localAddress1: string;
    localAddress2: string;
    postcode: string;
    nic: string;
    designation_type: number;
    contact_number: number;
  
  }
  export interface ISecretary {
    id: number;
    type: number;
    firstname: string;
    lastname: string;
    province: string;
    district: string;
    city: string;
    localAddress1: string;
    localAddress2: string;
    postcode: string;
    nic: string;
    designation_type: number;
    contact_number: number;
  
  }
  export interface ITreasurer {
    id: number;
    type: number;
    firstname: string;
    lastname: string;
    province: string;
    district: string;
    city: string;
    localAddress1: string;
    localAddress2: string;
    postcode: string;
    nic: string;
    designation_type: number;
    contact_number: number;
  
  }
