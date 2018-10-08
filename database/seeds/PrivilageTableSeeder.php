<?php

use Illuminate\Database\Seeder;
use App\Models\AdminPermission;
class PrivilageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
		    array('name' => 'name-recommandation', 'display_name'=> 'Name Recommandation for approval','description' => 'Name Recommandation for approval'),
            array('name' => 'confirm-name-approval', 'display_name'=> 'Confirm Name approval','description' => 'Confirm Name approval'),
            array('name' => 'incorporation-recommandation', 'display_name'=> 'Recommandation for approval','description' => 'Recommandation for Incorporation approval'),
            array('name' => 'confirm-incorporation-approval', 'display_name'=> 'Confirm Incorporation approval','description' => 'Confirm Incorporation approval'),
            
            array('name' => 'tender-recommandation', 'display_name'=> 'Tender Recommandation for approval','description' => 'Tender Recommandation for approval'),
            array('name' => 'confirm-tender-recommandation', 'display_name'=> 'Confirm Tender Recommandation for approval','description' => 'Confirm Tender Recommandation for approval'),
			);
        $setting = AdminPermission::insert($data);
    }
}
