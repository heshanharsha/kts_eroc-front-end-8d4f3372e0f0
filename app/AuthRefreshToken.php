<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthRefreshToken extends Model
{
    protected $table = 'oauth_refresh_tokens';

    public $timestamps = false;
}
