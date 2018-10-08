<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Secretary;

class SecretaryWorkingHistory extends Model
{

    protected $table = 'secretary_working_history';
    public $timestamps = false;

    public function Secretary()
    {
        return $this->belongsTo('App\Secretary');
    }
    
}
