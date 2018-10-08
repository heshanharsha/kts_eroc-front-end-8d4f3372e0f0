<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Secretary;
use Documents;

class SecretaryDocument extends Model
{
  
    protected $table = 'secretary_documents';

    public function Secretary()
    {
        return $this->belongsTo('App\Secretary');
    }


    public function Documents()
    {
        return $this->hasMany('App\Documents');
    }

}
