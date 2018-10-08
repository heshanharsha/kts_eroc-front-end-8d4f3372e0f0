<?php

use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
		    array('company_type_id' => $public->id, 'postfix' => 'PLC'),
		    array('company_type_id' => $public->id, 'postfix' => 'LTD'),
		    array('company_type_id' => $public->id, 'postfix' => 'LIMITED'),
		    array('company_type_id' => $public->id, 'postfix' => 'PUBLIC LIMITED COMPANY')
		    
            );
        $setting = CompanyPostfix::insert($data);
    }
}
