<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Address;
use SecretaryFirmPartner;

class SecretaryFirm extends Model
{

    protected $table = 'secretary_firm';

    public function Address()
    {
        return $this->belongsTo('App\Address');
    }

    
    public function SecretaryFirmPartner()
    {
        return $this->hasMany('App\SecretaryFirmPartner');
    }

    
}
