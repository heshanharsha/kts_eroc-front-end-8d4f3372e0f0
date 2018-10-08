<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    protected $primaryKey = "id";

    protected $fillable = [
        'id','type_id','name','name_si','name_ta','postfix','abbreviation_desc','address_id',
        'email','objective','status','created_by'
    ];
}
