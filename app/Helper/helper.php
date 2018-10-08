<?php

namespace App\Http\Helper;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Cache;
use App\Rule;
use App\Company;
use App\Setting;
use Mail;
use App\Mail\VerificationMail;

trait _helper
{

    function genarateCompanyId()
    {
        $id = Date('ymd') . rand(1000, 9999);
        //Check is exists.
        $is_exists = Company::where('id', $id)->count();
        if ($is_exists > 0) {
        //Regenarate new id.
            $id = $this->genarateCompanyId();
        }
        return $id;
    }

	function settings($key,$type="type")
	{
		static $settings;
		if($type == 'key'){
			$data = array();
			$settings = Cache::remember('settings_keys', 24*60, function() {
				$data = array();
				$sett = Setting::leftJoin('setting_types','settings.setting_type_id','=','setting_types.id')
				//->where('setting_types.output','string')
				->select('setting_types.key as type','settings.id','settings.key','settings.value','settings.value_si','settings.value_ta')
				->get();
				foreach ($sett as $value) {
					$data[$value->key] = (object) ['id' =>$value->id,'value' => $value->value,'value_si' => $value->value_si, 'value_ta' => $value->value_ta];
				}
				return $data;
			});
		} else if($type == 'id') {
			$settings = Cache::remember('settings_by_id', 24*60, function() {
				$data = array();
				$sett = Setting::select('settings.id','settings.key','settings.value','settings.value_si','settings.value_ta')->get();
				foreach ($sett as $value) {
					$data[$value->id] = (object) ['id' =>$value->id,'key' => $value->key,'value' => $value->value,'value_si' => $value->value_si, 'value_ta' => $value->value_ta];
				}
				return $data;
			});
		} else {
			$settings = Cache::remember('settings_types', 24*60, function() {
				$data = array();
				$sett = Setting::leftJoin('setting_types','settings.setting_type_id','=','setting_types.id')
				->where('setting_types.output','array')
				->select('setting_types.key as type','settings.id','settings.key','settings.value','settings.value_si','settings.value_ta')
				->get();
				foreach ($sett as $value) {
					$data[$value->type][] = (object) ['id' =>$value->id,'value' => $value->value,'value_si' => $value->value_si, 'value_ta' => $value->value_ta];
				}
				return $data;
			});
		}
		return is_array($settings) ? !empty($settings[$key]) ? $settings[$key] : "" : "";
	}

    public function ship($email, $link){
        return Mail::to($email)->send(new VerificationMail($link));
    }

    public function clearEmail($email){
        return preg_replace('/"+/', '', $email);
    }

}
