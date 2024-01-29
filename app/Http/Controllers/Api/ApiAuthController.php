<?php 

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email',
        ]);

        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
        ];

        $user = User::create($userData);
        $token = $user->createToken(Config::get('app.name'))->plainTextToken;

        //Send Verification Email 
        $user->sendEmailVerificationNotification();
        
        return ApiResponse::success(['user' => $user,'token' => $token], 'User Registred successfully. And Sent Verification Email.', 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, ['email' => 'required|email:rfc,dns','password' => 'required',]);

        $user = User::whereEmail($request->email)->first();
        if(!$user->hasVerifiedEmail())
        {
            return ApiResponse::failed('User not verified!','', 400);    
        }
        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponse::failed('Invalid credentials. Please try again.','', 422);        }

        $token = $user->createToken(Config::get('app.name'))->plainTextToken;

        return ApiResponse::success(['user' => $user,'token' => $token], 'User Loggedin successfully', 201);
        
    }

    public function sendVerificationEmail(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        if(!$user)
        {
            return ApiResponse::failed('User Not Found with '.$request->email);
        }
        if($user->hasVerifiedEmail())
        {
            return ApiResponse::success(['user' => $user], 'Already Verified.', 200);
        }
        //Send Verification Email 
        $user->sendEmailVerificationNotification();
        return ApiResponse::success(['user' => $user], 'Sent Verification Email.', 200);        
    }

    public function sendPasswordResetEmail(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        if(!$user)
        {
            return ApiResponse::failed('User Not Found with '.$request->email);
        }
        $token = app(PasswordBroker::class)->createToken($user);
        //$user->update(['password_reset_token' => $token]);
        $user->sendPasswordResetNotification($token);
        return ApiResponse::success(['user' => $user], 'Sent Reset Link Email.', 200);
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
    }
}