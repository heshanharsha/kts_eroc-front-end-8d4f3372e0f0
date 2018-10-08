<?php

namespace App\Http\Controllers\API\v1\Auth\Access;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Auth;
use phpseclib\Crypt\Hash;
use App\People;
use App\Http\Helper\_helper;
trait AuthorizesToken
{
    use _helper;
    
    protected function username()
    {
        return 'email';
    }

    public function getAccessTokenWithUser($user)
    {
        return $scopes = [
            'user' => $user,
            'accessToken' => Auth::user()->createToken('eRoc App')->accessToken,
        ];
    }

    public function getLogUserCredentials(Request $request)
    {
        if(!isset($request->reg)){
            $request->request->add( [
                'is_activation' => $this->settings('COMMON_STATUS_ACTIVE', 'key')->id
            ]);
            $credentials = array('email', 'password','is_activation');
        }else{
            $credentials = array('email', 'password');
        }
      
        $credentials = $request->only($credentials);
       
        if (Auth::attempt($credentials)) {

            $user = $this->getUser();

            return response()->json($this->prepareResult(200, $this->getAccessTokenWithUser($user), [], "User Verified"), 200);

        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function getUser()
    {
        return People::leftjoin('users','users.people_id','=','people.id')->where('users.email', Auth::user()->email)->first();
    }

    private function prepareResult($status, $data, $errors, $msg)
    {
        return ['status' => $status, 'data' => $data, 'message' => $msg, 'errors' => $errors];
    }
}
