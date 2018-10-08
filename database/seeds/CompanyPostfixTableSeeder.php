<?php

use Illuminate\Database\Seeder;
use App\Models\CompanyPostfix;
use App\Models\Setting;

class CompanyPostfixTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $private = Setting::where('key','COMPANY_TYPE_PRIVATE')->first();
        $data = array(
		    array('company_type_id' => $private->id, 'postfix' => '(PVT) LTD', 'postfix_si' => '(ප්ව්ට්) ල්ට්ඩ්', 'postfix_ta' => '(பிவிடி) எல்டிடி'),
		    array('company_type_id' => $private->id, 'postfix' => '(PRIVATE) LIMITED', 'postfix_si' => '(ප්රිවැටේ) ලිමිටෙඩ්', 'postfix_ta' => '(பிரைவேட்) லிமிடெட்')
		    
			);
        $setting = CompanyPostfix::insert($data);
        
        $public = Setting::where('key','COMPANY_TYPE_PUBLIC')->first();
        $data = array(
		    array('company_type_id' => $public->id, 'postfix' => 'PLC', 'postfix_si' => '(PVT) LTD', 'postfix_ta' => '(PVT) LTD'),
		    array('company_type_id' => $public->id, 'postfix' => 'LTD', 'postfix_si' => '(PVT) LTD', 'postfix_ta' => '(PVT) LTD'),
		    array('company_type_id' => $public->id, 'postfix' => 'LIMITED', 'postfix_si' => '(PVT) LTD', 'postfix_ta' => '(PVT) LTD'),
		    array('company_type_id' => $public->id, 'postfix' => 'PUBLIC LIMITED COMPANY', 'postfix_si' => '(PVT) LTD', 'postfix_ta' => '(PVT) LTD')
		    
            );
        $setting = CompanyPostfix::insert($data);
        
        $guarantee32 = Setting::where('key','COMPANY_TYPE_GUARANTEE_32')->first();
        $data = array(
		    array('company_type_id' => $guarantee32->id, 'postfix' => 'LTD', 'postfix_si' => '(PVT) LTD', 'postfix_ta' => '(PVT) LTD'),
		    array('company_type_id' => $guarantee32->id, 'postfix' => 'LIMITED', 'postfix_si' => '(PVT) LTD', 'postfix_ta' => '(PVT) LTD'),
		    array('company_type_id' => $guarantee32->id, 'postfix' => 'GUARANTEE', 'postfix_si' => '(PVT) LTD', 'postfix_ta' => '(PVT) LTD'),
		    array('company_type_id' => $guarantee32->id, 'postfix' => '(GUARANTEE) LIMITED', 'postfix_si' => '(PVT) LTD', 'postfix_ta' => '(PVT) LTD'),
		    
			);    
        $setting = CompanyPostfix::insert($data);
    }
}
