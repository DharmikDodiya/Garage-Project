<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Change Password
     */
    public function changePassword(Request $request){
        $request->validate([
            'old_password'          => 'required',
            'new_password'          => 'required|same:password_confirmation',
            'password_confirmation' => 'required'
        ]);

        if(!Hash::check($request->old_password, auth()->user()->password)){
            return error("error", "Old Password Doesn't match!",type:'notfound');
        }
        User::find(auth()->user()->id)->update([
            'password'  =>Hash::make($request->new_password)
        ]);
        return success('password Change Successfully');
    }

    /**
     * User Profile
     */
    public function userProfile(){
        $user = Auth::user();

        if(isset($user)){
            return Success('user profile',$user);
        }
        return error('Not User Login',type:'unauthenticated');
    }

    public function logout(){
        $user = Auth::user()->token();
        $user->revoke();
        return success('logged out');
    }
}