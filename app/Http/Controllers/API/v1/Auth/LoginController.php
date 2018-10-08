<?php

namespace App\Http\Controllers\API\v1\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AuthRefreshToken;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\v1\Auth\Access\AuthorizesToken;

class LoginController extends Controller
{
    use AuthorizesToken;

    // private $client;

    // public function __construct()
    // {
    //     $this->client = Client::where('password_client', 1)->first();
    // }

    protected function validateLogin(array $request)
    {
        return Validator::make($request, [
            $this->username() => 'required',
            'password' => 'required',
        ]);
    }

    public function authenticate(Request $request)
    {
        $this->validateLogin($request->all())->validate();

        $request->request->add(['reg' => true]);
        
        return $this->getLogUserCredentials($request);

    }

    protected function refreshTokenValidate(array $request)
    {
        return Validator::make($request, [
            'refresh_token' => 'required',
        ]);
    }

    public function refresh(Request $request)
    {
        $this->refreshTokenValidate($request->all())->validate();

        return $this->issueToken($request, 'refresh_token');
    }

    public function logout()
    {
        $accessToken = Auth::user()->token();

     //   Auth::logout();

        AuthRefreshToken::where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        $accessToken->revoke();

        return response()->json($this->prepareResult(200, 'success', [], "User logout"), 200);
    }

}
