<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Secretary;

class People extends Model
{
    protected $table = 'people';

    protected $fillable = [
        'id','title','first_name','last_name','other_name','profile_pic','address_id','foreign_address_id','nic','passport_no','passport_issued_country',
        'telephone','mobile','email','occupation','status'
    ];


    public function Secretary()
    {
        return $this->belongsTo('App\Secretary');
    }



}
