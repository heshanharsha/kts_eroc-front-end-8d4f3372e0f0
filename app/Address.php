<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Secretary;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'address1','address2','city','district','province','country','postcode'
    ];

    public function Secretary()
    {
        return $this->belongsTo('App\Secretary');
    }
}
