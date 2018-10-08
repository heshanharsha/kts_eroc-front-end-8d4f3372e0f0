<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SecretaryDocument;

class Documents extends Model
{

    protected $table = 'documents';


    public function SecretaryDocument()
    {
        return $this->hasMany('App\SecretaryDocument');
    }

    
}
