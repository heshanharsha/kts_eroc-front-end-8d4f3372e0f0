<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokenIssues extends Model
{
    protected $table = 'token_issues';

    protected $primaryKey = 'email';

    protected $fillable = [
        'email', 'token'
    ];
}
