<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingType extends Model
{
    protected $table = 'setting_types';
    protected $primaryKey = "id";
    public $timestamps = false;

}
