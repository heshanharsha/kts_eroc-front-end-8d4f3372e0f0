<?php

namespace App\Http\Controllers\API\v1\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\People;
use App\Http\Resources\Users;
use App\Http\Helper\_helper;

class UserController extends Controller
{
  use _helper;
  
  public function getUser(Array $request)
  {
    return People::where('email', $request->email)->first();
  }
}
