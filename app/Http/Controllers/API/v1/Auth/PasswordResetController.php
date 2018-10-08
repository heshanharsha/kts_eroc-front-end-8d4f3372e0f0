<?php
namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use App\Http\Helper\_helper;

class PasswordResetController extends Controller
{
    use _helper;
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
            'message' => 'We can \'t find a user with that e-mail address.',
            'status' => false
        ], 200);

        $token = str_random(60);
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $token
            ]
        );

        if ($user && $passwordReset) {
            $link = env('FRONT_APP_URL', '') . '/activation?email=' . $user->email . '&token=' . $token;
            $this->ship($user->email, $link);
        }
        return response()->json([
            'message' => 'We have e-mailed your password reset link!',
            'status' => true
        ]);
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response()->json([
            'message' => 'This password reset token is invalid.',
            'status' => false
        ], 200);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.',
                'status' => false
            ], 200);
        }
        return response()->json($passwordReset);
    }
    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if (!$passwordReset)
            return response()->json([
            'message' => 'This password reset token is invalid.', 'status' => false
        ], 200);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
            'message' => 'We can\'t find a user with that e-mail address.', 'status' => false
        ], 200);
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();

        //comment out because of localhost mail issue
        $user->notify(new PasswordResetSuccess($passwordReset));
        
       // return response()->json($user);
        return response()->json([
            'message' => 'Successfully reset your password.',
            'status' => true,
            'user' => $user
        ], 200);
    }

}