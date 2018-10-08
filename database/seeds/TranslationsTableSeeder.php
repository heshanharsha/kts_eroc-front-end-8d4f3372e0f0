<?php

use Illuminate\Database\Seeder;
use App\Models\Translation;

class TranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
		    array('key' => '01','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '02','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '03','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '04','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '05','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '06','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '07','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '08','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '09','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '10','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '11','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '12','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '13','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '14','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '15','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '16','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '17','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '18','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '19','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '20','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '21','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '22','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '23','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '24','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '25','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '26','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '27','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '28','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '29','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '30','english' => '', 'sinhala'=> '', 'tamil' => ''),
            array('key' => '31','english' => '', 'sinhala'=> '', 'tamil' => ''),
            
		    array('key' => 'JANUARY','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => 'FEBRUARY','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => 'MARCH','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => 'APRIL','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => 'MAY','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => 'JUNE','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => 'JULY','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => 'AUGUST','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => 'SEPTEMBER','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => 'OCTOBER','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => 'NOVEMBER','english' => '', 'sinhala'=> '', 'tamil' => ''),
            array('key' => 'DECEMBER','english' => '', 'sinhala'=> '', 'tamil' => ''),
            
		    array('key' => '2015','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '2016','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '2017','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '2018','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '2019','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '2020','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '2021','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '2022','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '2023','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '2024','english' => '', 'sinhala'=> '', 'tamil' => ''),
		    array('key' => '2025','english' => '', 'sinhala'=> '', 'tamil' => ''),
            
		    
			);
        $setting = Translation::insert($data);
    }
}
