<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use App\Notifications\WelcomeMailNotification;
use App\Notifications\EmailVerifyMailNotification;
use App\Notifications\ForgetPasswordNotification;

class AuthController extends Controller
{
    /**
     * Register Customer , Mechanic and Garage Owner
     */
    public function register(Request $request){
        $request->validate([
            'first_name'            => 'required|string|min:3|max:30',
            'last_name'             => 'required|string|min:3|max:30',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|max:12|same:password_confirmation',
            'type'                  => 'required|in:customer,mechanic,garage owner,admin|string',
            'billable_name'         => 'required_if:type,customer|string|min:3|max:40',
            'address1'              => 'required',
            'address2'              => 'nullable',
            'zip_code'              => 'required|numeric|digits:6',
            'phone'                 => 'required|numeric|digits:10|unique:users,phone',
            'profile_picture'       => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'city_id'               => 'required|exists:cities,id',
            'service_type_id'       => 'nullable|array|required_if:type,mechanic|exists:service_types,id',
            'garage_id'             => 'nullable|exists:garages,id',
            'password_confirmation' => 'required'
        ],[
            'in'        => 'The :attribute must be one of the following types: :values',
        ]);
        $profile_picture = $request->file('profile_picture')->storeAs('images', $request->profile_picture->getClientOriginalName());
        $user = User::create($request->only([
            'first_name',
            'last_name',
            'email',
            'type',
            'billable_name',
            'address1',
            'address2',
            'zip_code',
            'phone',
            'garage_id',
            'city_id'
        ])+[
            'password'          => Hash::make($request->password),
            'profile_picture'   => $profile_picture,
            'token'             => Str::random(64),
        ]);
        //dd($request->service_type_id);
        if($request->type == 'mechanic'){
            $user->serviceTypes()->attach($request->service_type_id);
        }
        $user->notify(new WelcomeMailNotification($user));
        $user->notify(new EmailVerifyMailNotification($user));       
        $token = $user->createToken('API Token')->accessToken; 
        return success('User Data Registered SuccessFUlly',$user);
    }

    public function verifyAccount($token){
        //dd($token);
        $verifyuser = User::where('token',$token)->first();

        if(!is_null($verifyuser)){
            $user = $verifyuser->user;
            $verifyuser->status = 1;
            $verifyuser->email_verified_at = now();
            $verifyuser->token = '';
            $verifyuser->save();

            return success('your email is verified now you can login');
        }
            return error('your email is already verified',type:'notfound');
    }

    public function login(Request $request){
        $request->validate([
            'email'     => 'required|exists:users,email',
            'password'  => 'required'
        ]);
        if(Auth::attempt(['email' => $request->email , 'password' => $request->password , 'status' => 1])){
            $user = User::where('email',$request->email)->first();

            $token = $user->createToken("API TOKEN")->accessToken;
            return success('you are Login Now',$token);
        }
            return error('email and password are not match',type:'notfound');   
    }

    public function forgetPassWord(Request $request){
        $request->validate([
            'email'     => 'required|exists:users,email'
        ]);

        $user = User::where('email',$request->email)->first();

        if(isset($user)){
            $token = Str::random(64);
            $domain = URL::to('/');
            $url = $domain.'/api/resetPassword?token='.$token."&email=".$request->email;

            $data['token']  = $token;
            $data['url']    = $url;
            $data['email']  = $request->email;
            $data['title']  = 'Password Reset';
            $data['body']   = 'please click below link to Reset your Password';

            $user->notify(new ForgetPasswordNotification($data));

            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $user = PasswordReset::updateOrCreate(
                ['email' => $request->email],
                [
                    'email'         => $request->email,
                    'token'         => $token,
                    'created_at'    => $datetime,
                ]
            );
            return success('send mail please check your mail',$token);
        }
        return error('Email Is Not Exists',type:'notfound');
    }

    public function forgetPasswordView(Request $request){
        $resetdata = PasswordReset::where('token',$request->token)->first();

        if(isset($resetdata)){
            return success('now you can chenge the password use this token',$request->token);
        }
        return error('you can Not Change The Password',type:'notfound');
    }

    public function resetPassword(Request $request){
        $request->validate([
            'password'      => 'required|same:password_confirmation',
            'token'         => 'required|exists:password_reset_tokens,token',
            'password_confirmation' => 'required'
        ]);

        $data = PasswordReset::where('token',$request->token)->first();
        if($data){
            $user = User::where('email',$data->email)->first();
            $user->update(['password' => Hash::make($request->password)]);
            $resetdata = PasswordReset::where('email',$request->token);
            $resetdata->update(['token' => '']);
            return success('your password change successfully');
        }
        return error('your Token Is Expired',type:'notfound');
    }
}
