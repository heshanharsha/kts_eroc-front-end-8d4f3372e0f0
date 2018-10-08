<?php

use Illuminate\Database\Seeder;
use App\Setting;
use App\SettingType;
class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$common_status = SettingType::where('key','COMMON_STATUS')->first();
        $data = array(
		    array('setting_type_id' => $common_status->id,'key'=>'COMMON_STATUS_ACTIVE','value' => 'Active',),
		    array('setting_type_id'=> $common_status->id,'key'=>'COMMON_STATUS_DEACTIVE','value' =>'Deactive'),
		    array('setting_type_id'=> $common_status->id,'key'=>'COMMON_STATUS_PENDING','value' =>'Pending')
			);
        $setting = Setting::insert($data);
		
		$company_types = SettingType::where('key','COMPANY_TYPES')->first();
        $data = array(
		    array('setting_type_id' => $company_types->id, 'key'=>'COMPANY_TYPE_PRIVATE','value' => 'Private'),
		    array('setting_type_id' => $company_types->id, 'key'=>'COMPANY_TYPE_PUBLIC','value' => 'Public'),
		    array('setting_type_id'=> $company_types->id, 'key'=>'COMPANY_TYPE_GUARANTEE_32','value' =>'Guarantee (Licensed under section 32)'),
		    array('setting_type_id'=> $company_types->id, 'key'=>'COMPANY_TYPE_GUARANTEE_34','value' =>'Guarantee (Licensed under section 34)'),
		    array('setting_type_id'=> $company_types->id, 'key'=>'COMPANY_TYPE_OVERSEAS','value' =>'Overseas'),
		    array('setting_type_id'=> $company_types->id, 'key'=>'COMPANY_TYPE_OFFSHORE','value' =>'Offshore'),
		    array('setting_type_id'=> $company_types->id, 'key'=>'COMPANY_TYPE_UNLIMITED','value' =>'Unlimited')
			);
		$setting = Setting::insert($data);
		
        $company_status = SettingType::where('key','COMPANY_STATUS')->first();
        $data = array(
			array('setting_type_id' => $company_status->id, 'key'=>'COMPANY_NAME_PENDING','value' => 'Name Pending Approval'),
			array('setting_type_id' => $company_status->id, 'key'=>'COMPANY_NAME_RULES_VERIFICATION','value' => 'Name Rule Verification'),
			// array('setting_type_id' => $company_status->id, 'key'=>'COMPANY_NAME_DOC_VERIFICATION','value' => 'Document Verification'),
			array('setting_type_id' => $company_status->id, 'key'=>'COMPANY_NAME_RECOMMEND_FOR_APPROVAL','value' => 'Name Recommend for Approval'),
			array('setting_type_id' => $company_status->id, 'key'=>'COMPANY_NAME_NOT_RECOMMEND_FOR_APPROVAL','value' => 'Name Not Recommend for Approval'),
			array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_NAME_REQUEST_TO_RESUBMIT','value' =>'Name Request to Re-Submit'),
		    array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_NAME_RESUBMITTED','value' =>'Name Re-Submission'),
		    array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_NAME_APPROVED','value' =>'Name Approved'),
			array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_NAME_REJECTED','value' =>'Name Rejected'),
			array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_NAME_EXPIRED','value' =>'Company Name Expired.'),
			array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_NAME_CANCELED','value' =>'Company Name Canceled.'),
			array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_STATUS_PENDING','value' =>'Incorporation Pending'),
			array('setting_type_id' => $company_status->id, 'key'=>'COMPANY_STATUS_RECOMMEND_FOR_APPROVAL','value' => 'Incorporation Recommend for Approval'),
			array('setting_type_id' => $company_status->id, 'key'=>'COMPANY_STATUS_NOT_RECOMMEND_FOR_APPROVAL','value' => 'Incorporation Not Recommend for Approval'),
		    array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_STATUS_REQUEST_TO_RESUBMIT','value' =>'Incorporation Request to Re-Submit'),
		    array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_STATUS_RESUBMITTED','value' =>'Incorporation Re-Submission'),
		    array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_STATUS_APPROVED','value' =>'Incorporation Approved'),
		    array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_STATUS_REJECTED','value' =>'Incorporation Rejected'),
		    array('setting_type_id'=> $company_status->id, 'key'=>'COMPANY_DISABLE','value' =>'Company Disable')
			);
        $setting = Setting::insert($data);

        
        $rule_types = SettingType::where('key','RULES_TYPES')->first();
        $data = array(
		    array('setting_type_id' => $rule_types->id, 'key'=>'RULE_TYPE_GOV','value' => 'Goverment Reserved Names'),
		    array('setting_type_id' => $rule_types->id, 'key'=>'RULE_TYPE_RESTRICTED','value' => 'Restricted Words'),
		    array('setting_type_id'=> $rule_types->id, 'key'=>'RULE_TYPE_SPECIAL_PERMISION','value' =>'Special Permision Required Words.'),
		    array('setting_type_id'=> $rule_types->id, 'key'=>'RULE_TYPE_NAME_SUITABILITY','value' =>'Name Suitability Rules.'),
		    array('setting_type_id'=> $rule_types->id, 'key'=>'RULE_TYPE_OMIT_WORDS','value' =>'Omit words.'),
		    array('setting_type_id'=> $rule_types->id, 'key'=>'RULE_TYPE_GROUPS','value' =>'Established Groups.'),
			);
		$setting = Setting::insert($data);

		$company_objective = SettingType::where('key','COMPANY_OBJECTIVE')->first();
        $data = array(
		    array('setting_type_id' => $company_objective->id, 'key'=>'COM_OBJ_1','value' => 'Mining and Quarrying'),
		    array('setting_type_id' => $company_objective->id, 'key'=>'COM_OBJ_2','value' => 'Manufacturing'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_3','value' =>'Electricity, gas, steam and air conditioning supply'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_4','value' =>'Water Supply; sewerage, waste management and remediation activities'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_5','value' =>'Construction'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_6','value' =>'Wholesale and retail trade; repair of motor vehicles and motor cycles'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_7','value' =>'Transportation and storage'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_8','value' =>'Accommodation and food service activities'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_9','value' =>'Information and Communication'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_10','value' =>'Financial and Insurance Activities'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_11','value' =>'Real estate activities'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_12','value' =>'Professional, Scientific and technical activities'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_13','value' =>'Administrative and support service activities'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_14','value' =>'Public administration and defence; compulsory social security'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_15','value' =>'Education'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_16','value' =>'Human health and social work activities'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_17','value' =>'Arts, entertainment and recreation'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_18','value' =>'Other service activities'),
			array('setting_type_id'=> $company_objective->id, 'key'=>'COM_OBJ_19','value' =>'Activities of households as employers, undifferentiated goods'),
			);
		$setting = Setting::insert($data);

		$company_reg = SettingType::where('key','COMPANY_REGISTRATION_PROCESS')->first();
        $data = array(
		    array('setting_type_id' => $company_reg->id, 'key'=>'NAME_REGISTRATION','value' => 'Name Registration'),
		    array('setting_type_id' => $company_reg->id, 'key'=>'COMPANY_REGISTRATION','value' => 'Company Registration'),
			);
		$setting = Setting::insert($data);
		
		
		$company_reg = SettingType::where('key','COMPANY_SUB_STATUS')->first();
        $data = array(
		    array('setting_type_id' => $company_reg->id, 'key'=>'NAME_CHECK','value' => 'Name Check'),
		    array('setting_type_id' => $company_reg->id, 'key'=>'DOCUMENTS_CHECK','value' => 'Documents Check'),
			);
		$setting = Setting::insert($data);
		
		$doc_status = SettingType::where('key','DOCUMENT_STATUS')->first();
        $data = array(
		    array('setting_type_id' => $doc_status->id, 'key'=>'DOCUMENT_PENDING','value' => 'Document Pending'),
		    array('setting_type_id' => $doc_status->id, 'key'=>'DOCUMENT_APPROVED','value' => 'Document Approved'),
			array('setting_type_id' => $doc_status->id, 'key'=>'DOCUMENT_REQUEST_TO_RESUBMIT','value' => 'Document Request to Resubmit'),
			array('setting_type_id' => $doc_status->id, 'key'=>'DOCUMENT_REQUESTED','value' => 'Additional Document Requested'),
			array('setting_type_id' => $doc_status->id, 'key'=>'DOCUMENT_UPLOADED','value' => 'Document uploaded'),
			array('setting_type_id' => $doc_status->id, 'key'=>'DOCUMENT_DELETED','value' => 'Document Deleted')
			);
		$setting = Setting::insert($data);

		$approve_reject = SettingType::where('key','APPROVED_REJECTED_STATUS')->first();
        $data = array(
		    array('setting_type_id' => $approve_reject->id, 'key'=>'STATUS_APPROVED','value' => 'Approved'),
		    array('setting_type_id' => $approve_reject->id, 'key'=>'STATUS_REJECTED','value' => 'Rejected'),
			);
		$setting = Setting::insert($data);

		$pass_fail = SettingType::where('key','PASS_FAIL_STATUS')->first();
        $data = array(
		    array('setting_type_id' => $pass_fail->id, 'key'=>'STATUS_PASS','value' => 'Pass'),
		    array('setting_type_id' => $pass_fail->id, 'key'=>'STATUS_FAIL','value' => 'Fail'),
			);
		$setting = Setting::insert($data);

		$payment = SettingType::where('key','PAYMENTS')->first();
        $data = array(
		    array('setting_type_id' => $payment->id, 'key'=>'NAME_RESERVATION_FEE','value' => '2000'),
		    array('setting_type_id' => $payment->id, 'key'=>'PRIVATE_COMPANY_REGISTRATION_FEE','value' => '4000'),
		    array('setting_type_id' => $payment->id, 'key'=>'PUBLIC_COMPANY_REGISTRATION_FEE','value' => '20000'),
		    array('setting_type_id' => $payment->id, 'key'=>'UNLIMITED_COMPANY_REGISTRATION_FEE','value' => '15000'),
		    array('setting_type_id' => $payment->id, 'key'=>'GURANTEE_COMPANY_REGISTRATION_FEE','value' => '30000'),
		    array('setting_type_id' => $payment->id, 'key'=>'OFFSHORE_COMPANY_REGISTRATION_FEE','value' => '150000'),
			array('setting_type_id' => $payment->id, 'key'=>'OVERSEAS_COMPANY_REGISTRATION_FEE','value' => '60000'),
			
			array('setting_type_id' => $payment->id, 'key'=>'ANNUAL_RETURN_FEE','value' => '60000'),
			// array('setting_type_id' => $payment->id, 'key'=>'REGISTRATION_OF_INDIVIUAL_AS_COMPANY_SECRETARIES_FEE','value' => '5000'),
			// array('setting_type_id' => $payment->id, 'key'=>'SOLE_PROPRITERSHIP_OR_PARTNERSHIP_AS_COMPANY_SECRETARIES_FEE','value' => '10000'),
			// array('setting_type_id' => $payment->id, 'key'=>'LIMITED_COMPANIES_AS_COMPANY_SECRETARIES_FEE','value' => '10000'),

			array('setting_type_id' => $payment->id, 'key'=>'REGISTRATION_OF_INDIVIUAL_AS_AUDITORS','value' => '6000'),
			array('setting_type_id' => $payment->id, 'key'=>'REGISTRATION_OF_PARTNERSHIP_AS_AUDITORS','value' => '15000'),

			array('setting_type_id' => $payment->id, 'key'=>'REGISTRATION_OF_SOCIETIES','value' => '3000'),
			array('setting_type_id' => $payment->id, 'key'=>'AMENDMENTS_TO_THE_CONSTITUTION_OF_SOCIETIES','value' => '1500'),
			array('setting_type_id' => $payment->id, 'key'=>'ISSUING_CERTIFIED_COPIES_OF_SOCIETIES','value' => '500'),
			array('setting_type_id' => $payment->id, 'key'=>'ANNUAL_RETURN_OF_SOCIETIES','value' => '500'),
			array('setting_type_id' => $payment->id, 'key'=>'SEARCH_FEE_OF_SOCIETY_FILE','value' => '200'),
			
		    array('setting_type_id' => $payment->id, 'key'=>'GOV_VAT_PERCENTAGE','value' => '15'),
			);
		$setting = Setting::insert($data);
		
		$designation_type = SettingType::where('key','COMPANY_DESIGNATION_TYPE')->first();
        $data = array(
		    array('setting_type_id' => $designation_type->id, 'key'=>'DERECTOR','value' => 'Director'),
		    array('setting_type_id' => $designation_type->id, 'key'=>'SECRETARY','value' => 'Secretary'),
		    array('setting_type_id' => $designation_type->id, 'key'=>'SHAREHOLDER','value' => 'Shareholder')
			);
		$setting = Setting::insert($data);

		$name_title = SettingType::where('key','NAME_TITLE')->first();
        $data = array(
		    array('setting_type_id' => $name_title->id, 'key'=>'TITLE_REV','value' => 'Rev'),
		    array('setting_type_id' => $name_title->id, 'key'=>'TITLE_MR','value' => 'Mr'),
		    array('setting_type_id' => $name_title->id, 'key'=>'TITLE_MRS.','value' => 'Mrs'),
		    array('setting_type_id' => $name_title->id, 'key'=>'TITLE_MISS','value' => 'Miss'),
		    array('setting_type_id' => $name_title->id, 'key'=>'TITLE_DR.','value' => 'Dr')
			);
		$setting = Setting::insert($data);

		$no_incre = SettingType::where('key','NUMBER_INCREMENTS')->first();
        $data = array(
		    array('setting_type_id' => $no_incre->id, 'key'=>'COMPANY_REGISTRATION_NEXT_NO','value' => '11111'),
		    array('setting_type_id' => $no_incre->id, 'key'=>'PAYMENT_REF_NO','value' => '22222')
			);
		$setting = Setting::insert($data);

		$roc_desi_type = SettingType::where('key','ROC_DESIGNATION_TYPE')->first();
        $data = array(
		    array('setting_type_id' => $roc_desi_type->id, 'key'=>'ROC_REGISTRAR','value' => 'Registrar of Companies','value_si' => 'සමාගම් රෙජිස්ටාර්','value_ta' => 'நிறுவனங்களின் பதிவாளர்'),
		    array('setting_type_id' => $roc_desi_type->id, 'key'=>'ROC_REGISTRAR_ATTENDING','value' => 'Registrar of Companies (Attending Duties)','value_si' => 'සමාගම් රෙජිස්ටාර් (රාජකාරී ඉටුකිරීම)','value_ta' => 'நிறுவனங்களின் பதிவாளர் (கடமைகளில் கலந்துகொள்வது)')
			);
		$setting = Setting::insert($data);

		$tender_status = SettingType::where('key','TENDER_STATUS')->first();
        $data = array(
		    array('setting_type_id' => $tender_status->id, 'key'=>'TENDER_PENDING','value' => 'Tender Pending'),
		    array('setting_type_id' => $tender_status->id, 'key'=>'TENDER_RECOMMEND_FOR_APPROVAL','value' => 'Tender Recommend for approval'),
		    array('setting_type_id' => $tender_status->id, 'key'=>'TENDER_NOT_RECOMMEND_FOR_APPROVAL','value' => 'Tender Not Recommend for approval'),
		    array('setting_type_id' => $tender_status->id, 'key'=>'TENDER_APPROVED','value' => 'Tender Approved'),
		    array('setting_type_id' => $tender_status->id, 'key'=>'TENDER_REJECTED','value' => 'Tender Rejected'),
		    array('setting_type_id' => $tender_status->id, 'key'=>'TENDER_RESUBMIT','value' => 'Tender Resubmit'),
		    array('setting_type_id' => $tender_status->id, 'key'=>'TENDER_EXPIRED','value' => 'Tender Expired'),
		    array('setting_type_id' => $tender_status->id, 'key'=>'TENDER_CANCELED','value' => 'Tender Canceled'),
			);
		$setting = Setting::insert($data);

		$tender_applicants = SettingType::where('key','TENDER_APPLICANTS')->first();
        $data = array(
		    array('setting_type_id' => $tender_applicants->id, 'key'=>'TENDER_TENDERER','value' => 'Tenderer'),
		    array('setting_type_id' => $tender_applicants->id, 'key'=>'TENDER_NOMINEE','value' => 'Nominee'),
		    array('setting_type_id' => $tender_applicants->id, 'key'=>'TENDER_AGENT','value' => 'Agent'),
		    array('setting_type_id' => $tender_applicants->id, 'key'=>'TENDER_PARTNERSHIP','value' => 'Partnership'),
		    array('setting_type_id' => $tender_applicants->id, 'key'=>'TENDER_PROPRIETORSHIP','value' => 'Proprietorship'),
		    array('setting_type_id' => $tender_applicants->id, 'key'=>'TENDER_JOIN_VENTURE','value' => 'Joint venture'),
		    array('setting_type_id' => $tender_applicants->id, 'key'=>'TENDER_COMPANY_PUBLIC','value' => 'Public / PLC Company'),
		    array('setting_type_id' => $tender_applicants->id, 'key'=>'TENDER_COMPANY_PRIVATE','value' => 'Private Company'),
			);
		$setting = Setting::insert($data);

		$tender_types = SettingType::where('key','TENDER_TYPES')->first();
        $data = array(
		    array('setting_type_id' => $tender_types->id, 'key'=>'OPEN_TENDER','value' => 'Open Tender'),
		    array('setting_type_id' => $tender_types->id, 'key'=>'CLOSE_TENDER','value' => 'Close Tender')
			);
		$setting = Setting::insert($data);

		$tender_member_types = SettingType::where('key','TENDER_FIRM_MEMBERS_TYPES')->first();
        $data = array(
		    array('setting_type_id' => $tender_member_types->id, 'key'=>'TENDER_MEMBER_DIRECTORS','value' => 'Tender Directors'),
		    array('setting_type_id' => $tender_member_types->id, 'key'=>'TENDER_MEMBER_SHAREHOLDERS','value' => 'Tender Shareholders'),
		    array('setting_type_id' => $tender_member_types->id, 'key'=>'TENDER_MEMBER_PARTNERS','value' => 'Tender Partners')
			);
		$setting = Setting::insert($data);

		$secretary_status = SettingType::where('key','SECRETARY_STATUS')->first();
        $data = array(
		    array('setting_type_id' => $secretary_status->id, 'key'=>'SECRETARY_PENDING','value' => 'Secretary Pending'),
		    array('setting_type_id' => $secretary_status->id, 'key'=>'SECRETARY_RECOMMEND_FOR_APPROVAL','value' => 'Secretary Recommend for approval'),
		    array('setting_type_id' => $secretary_status->id, 'key'=>'SECRETARY_NOT_RECOMMEND_FOR_APPROVAL','value' => 'Secretary Not Recommend for approval'),
		    array('setting_type_id' => $secretary_status->id, 'key'=>'SECRETARY_APPROVED','value' => 'Secretary Approved'),
		    array('setting_type_id' => $secretary_status->id, 'key'=>'SECRETARY_REJECTED','value' => 'Secretary Rejected'),
		    array('setting_type_id' => $secretary_status->id, 'key'=>'SECRETARY_RESUBMIT','value' => 'Secretary Resubmit'),
		    array('setting_type_id' => $secretary_status->id, 'key'=>'SECRETARY_CANCELED','value' => 'Secretary Canceled'),
			);
		$setting = Setting::insert($data);

		$secretary_doc_type = SettingType::where('key','SECRETARY_DOC_TYPES')->first();
        $data = array(
		    array('setting_type_id' => $secretary_doc_type->id, 'key'=>'SECRETARY_CERTIFICATE','value' => 'Certificate'),
		    array('setting_type_id' => $secretary_doc_type->id, 'key'=>'SECRETARY_REGISTRATION_FORM','value' => 'Registration form'),
			);
		$setting = Setting::insert($data);

		$comment_type = SettingType::where('key','COMMENT_TYPES')->first();
        $data = array(
		    array('setting_type_id' => $comment_type->id, 'key'=>'COMMENT_INTERNAL','value' => 'Internal Comment'),
		    array('setting_type_id' => $comment_type->id, 'key'=>'COMMENT_EXTERNAL','value' => 'External Comment'),
			);
		$setting = Setting::insert($data);

		$token_expireType = SettingType::where('key','TOKEN_EXPIRE_TYPES')->first();
        $data = array(
		    array('setting_type_id' => $token_expireType->id, 'key'=>'TOKEN_EXPIRE','value' => 'Expire Token'),
		    array('setting_type_id' => $token_expireType->id, 'key'=>'TOKEN_NOT_EXPIRE','value' => 'Not Expire Token'),
			);
		$setting = Setting::insert($data);
		
    }
}
