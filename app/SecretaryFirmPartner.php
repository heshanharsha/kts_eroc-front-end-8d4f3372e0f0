<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SecretaryFirm;

class SecretaryFirmPartner extends Model
{
    
    protected $table = 'secretary_firm_partners';
    public $timestamps = false;

    public function SecretaryFirm()
    {
        return $this->hasMany('App\SecretaryFirm');
    }


}
