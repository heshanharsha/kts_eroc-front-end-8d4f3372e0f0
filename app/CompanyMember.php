<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyMember extends Model
{
    protected $table = 'company_members';


    public function address()
    {
        return $this->hasOne('App\Address');
    }

}
