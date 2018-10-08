<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Address;
use SecretaryWorkingHistory;
use SecretaryDocument;
use People;
use User;

class Secretary extends Model
{
    protected $table = 'secretaries';

    public function Address()
    {
        return $this->belongsTo('App\Address');
    }

    public function SecretaryWorkingHistory()
    {
        return $this->hasMany('App\SecretaryWorkingHistory');
    }

    public function SecretaryDocument()
    {
        return $this->hasMany('App\SecretaryDocument');
    }

    public function People()
    {
        return $this->belongsTo('App\People');
    }
    public function User()
    {
        return $this->belongsTo('App\User');
    }

}
