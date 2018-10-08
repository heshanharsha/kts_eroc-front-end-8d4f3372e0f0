<?php

namespace App\Http\Controllers\API\v1\Auth;


use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\v1\Auth\Access\AuthorizesToken;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Route;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Users;
use App\People;
use App\Address;
use App\TokenIssues;
use Illuminate\Contracts\Encryption\Encrypter;
use DateTime;

class RegisterController extends Controller
{
    use AuthorizesToken;

     /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */
    // private $client;

    // public function __construct()
    // {
    //     $this->client = Client::where('password_client', 1)->first();
    // }
    /**
     * Get a validator for an incoming registration request
     *
     * @return \Illuminate\Http\Response
     */
    protected function Validator(array $data)
    {
        // return Validator::make($data, [
        //     'first_name' => 'required|max:50',
        //     'last_name' => 'required|max:50',
        //     'email' => 'required|email|max:255|unique:users',
        //     'password' => 'required|min:3|confirmed',
        // ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \Illuminate\Http\Response
     */
    protected function create(array $data)
    {
        \DB::beginTransaction();

        try {
            foreach ($data['registerData']['address'] as $key => $value) {
                if ($value['address01'] != null) {
                    $addNumber[$key] = Address::create([
                        'address1' => $value['address01'],
                        'address2' => $value['address02'],
                        'city' => $value['city'],
                        'district' => $value['district'],
                        'province' => $value['province'],
                        'country' => $value['country'],
                        'postcode' => $value['postCode']
                    ]);
                }
            }

            $member = People::create([
                'title' => $data['registerData']['details']['title'],
                'first_name' => $data['registerData']['details']['firstname'],
                'last_name' => $data['registerData']['details']['lastname'],
                'other_name' => $data['registerData']['details']['otherName'],
                'address_id' => $addNumber[0]->id,
                'foreign_address_id' => (empty($addNumber[1]) ? : $addNumber[1]->id),
                'nic' => $data['registerData']['details']['nic'],
                'passport_no' => empty($data['registerData']['details']['passportid']) ? : $data['registerData']['details']['passportid'],
                'passport_issued_country' => empty($data['registerData']['details']['passportIssueCountry']) ? : $data['registerData']['details']['passportIssueCountry'],
                'telephone' => $data['registerData']['details']['telephoneNumber'],
                'mobile' => $data['registerData']['details']['mobileNumber'],
                'occupation' => $data['registerData']['details']['occupation'],
                'email' => $data['credential']['email']
            ]);
           
            // $storagePath = 'user/'.$member->id.'/avater';
            // $path = $request->file('inputFile')->storeAs($storagePath,uniqid(),'sftp');
   
            // People::where('id',$member->id)->update(['profile_pic' =>  $path ]);

            $user = User::create([
                'people_id' => $member->id,
                'email' => $data['credential']['email'],
                'password' => bcrypt($data['credential']['password_confirmation']),
                'is_activation' => $this->settings('COMMON_STATUS_ACTIVE', 'key')->id,
            ]);
            // 'is_activation' => $this->settings('COMMON_STATUS_PENDING', 'key')->id,
            \DB::commit();
        } catch (\ErrorException $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Unauthorised request'], 401);
        }

        return $user;
    }

    public function register(Request $request)
    {
        try {

            $password = $request->credential['password'];

            $user = $this->create($request->all());

            if ($user) {

                $request->request->add([
                    'email' => $user->email,
                    'password' => $password,
                    'reg' => true
                ]);

                $this->requestLink($user->email);

                return  $this->getLogUserCredentials($request);;
            }
            return response()->json(['error' => 'Unauthorised request'], 401);
        } catch (\ErrorException $e) {
            return response()->json(['error' => 'Error : ' . $e->getMessage()], 401);
        }
    }

    public function requestLink($email)
    {
        $email = $this->clearEmail($email);
        $verifyToken = TokenIssues::where('email', $email)->first();
        if (is_null($verifyToken)) {
            $activation_token = str_random(64);
            $token = TokenIssues::create([
                'email' => $email,
                'token' => $activation_token
            ]);
        } else {
            $verifyToken->updated_at = date('Y-m-d H:i:s');
            $verifyToken->save();

            $activation_token = $verifyToken->token;
        }

        $link = env('FRONT_APP_URL', '') . '/#/user/activation?email=' . $email . '&token=' . $activation_token;
        $this->ship($email, $link);
    }


    public function verifyAccount(Request $request)
    {

        $email = $request->email;
        $token = $request->token;

        $verifyToken = TokenIssues::where('email', $email)->where('token', $token)->first();
        if ($verifyToken) {
            $interval = (new DateTime($verifyToken->updated_at))->diff(new DateTime());
            $minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + ($interval->i);

            if ($minutes < 10) {
                if (!is_null($verifyToken)) {
                    $user = User::where('email', $email)->where('is_activation', $this->settings('COMMON_STATUS_PENDING', 'key')->id)->first();
                    if ($user->is_activation == $this->settings('COMMON_STATUS_ACTIVE', 'key')->id) {
                        return response()->json(['warning' => 'User are already activated!'], 200);
                    } else {
                        $user->is_activation = $this->settings('COMMON_STATUS_ACTIVE', 'key')->id;
                        $user->save();
                        $verifyToken->delete();

                        return response()->json(['success' => 'Successfully Activated your Account. Please Login'], 200);
                    }

                } else {
                    return response()->json(['error' => 'Unauthorised request'], 401);
                }
            } else {
                $verifyToken->delete();
                return response()->json(['error' => 'Your password reset link has been expired.'], 401);
            }
        } else {
            return response()->json(['error' => 'Please request activation link'], 401);
        }

    }

    public function checkExisitsEmail(Request $request)
    {
        try {
            return User::where('email', $request->email)->where('is_activation', $this->settings('COMMON_STATUS_ACTIVE', 'key')->id)->first();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
