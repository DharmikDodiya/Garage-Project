<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Register Customer , Mechanic and Garage Owner
     */
    public function register(Request $request){
        $request->validate([
            'first_name'        => 'required|string|min:3|max:30',
            'last_name'         => 'required|string|min:3|max:30',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:8|max:12',
            'type'              => 'required|in:customer,mechanic,garage owner,admin|string',
            'billable_name'     => 'required|string|min:3|max:40',
            'address1'          => 'required',
            'address2'          => 'nullable',
            'zip_code'          => 'required|numeric|min:6|max:6',
            'phone'             => 'required|numeric|min:10|max:10|unique:users,phone',
            'profile_picture'   => 'required|image|mimes:jpg,png,jpeg,gif|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
            'city_id'           => 'required|exists:cities,id',
            'service_type_id'   => 'nullable|required_if:type,mechanic|exists:service_types,id',
            'garage_id'         => 'nullable|exists:garages,id'
        ]);

        $path = $request->file('profile_picture')->storeAs('images', $request->profile_picture->getClientOriginalName());
        $user = User::create([
            'first_name'
        ]);
    }
}
