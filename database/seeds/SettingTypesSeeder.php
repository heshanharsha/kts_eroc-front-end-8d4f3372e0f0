<?php

use Illuminate\Database\Seeder;
use App\SettingType;

use Illuminate\Support\Facades\DB;
class SettingTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Truncating Setting table');
        DB::statement('TRUNCATE eroc_setting_types, eroc_settings');

    	$data = array(
		    array('key'=>'COMMON_STATUS','name'=>'Common Status','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
		    array('key'=>'COMPANY_STATUS','name'=>'Company Status','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'COMPANY_TYPES','name'=>'Company Types','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'COMPANY_OBJECTIVE','name'=>'Company Objective','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'COMPANY_REGISTRATION_PROCESS','name'=>'Registration Process','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'COMPANY_SUB_STATUS','name'=>'Company Sub Status','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'RULES_TYPES','name'=>'Rules Types','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'DOCUMENT_STATUS','name'=>'Document Status','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'APPROVED_REJECTED_STATUS','name'=>'Approved or Rejected Status','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'PASS_FAIL_STATUS','name'=>'Pass or fail Status','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'NAME_TITLE','name'=>'Name Title','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'PAYMENTS','name'=>'Payments','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'COMPANY_DESIGNATION_TYPE','name'=>'Company designation type','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'NUMBER_INCREMENTS','name'=>'Increments','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'ROC_DESIGNATION_TYPE','name'=>'ROC designation type','is_tri_lang' => 'yes','output'=>'array','is_hidden' => 'no'),
            array('key'=>'TENDER_STATUS','name'=>'Tender Status','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'TENDER_APPLICANTS','name'=>'Tender Applicants','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'TENDER_TYPES','name'=>'Tender Types','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'TENDER_FIRM_MEMBERS_TYPES','name'=>'Tender application firm members types','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'yes'),
            array('key'=>'SECRETARY_STATUS','name'=>'Secretary Types','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'SECRETARY_DOC_TYPES','name'=>'Secretary Document Types','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'COMMENT_TYPES','name'=>'Comment Types','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'no'),
            array('key'=>'TOKEN_EXPIRE_TYPES','name'=>'Token Expire Types','is_tri_lang' => 'no','output'=>'array','is_hidden' => 'yes'),
			);
        $setting = SettingType::insert($data);
    }
}
