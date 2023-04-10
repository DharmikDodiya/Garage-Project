<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Garage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ListingApiTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\WelcomeMailNotification;
use App\Notifications\EmailVerifyMailNotification;

class GarageController extends Controller
{
    use ListingApiTrait;
    /**
     * Create Garage
     */
    public function create(Request $request){
        $request->validate([
            'garage_name'       => 'required|string|unique:garages,garage_name',
            'address1'          => 'required',
            'address2'          => 'nullable',
            'zip_code'          => 'required|digits:6',
            'country_id'        => 'required|exists:countries,id|numeric',
            'state_id'          => 'required|exists:states,id|numeric',
            'city_id'           => 'required|exists:cities,id|numeric',
            'service_type_id'   => 'required|array|exists:service_types,id'
        ]);
        //dd($request);
        $owner_id = Auth::user()->id;
        //dd($owner_id);
        $garage = Garage::create($request->only(['garage_name','address1','address2','zip_code','country_id','state_id','city_id'])
        +[
            'owner_id'      => $owner_id,
        ]);
        $garage->serviceTypes()->attach($request->service_type_id);
        
        return success('Garage Create SuccessFully and Also Update User Type And Profile');
    }

    /**
     * Login Garage Owner All Garage
     */
    public function list(){
        $user_id = Auth::user()->id;
        $garage = Garage::where('owner_id',$user_id)->get();
        return success('Garage Details',$garage);
    }

    /**
     * Searching Garage Using 
     */
    public function searchingGarage(Request $request){
        $this->ListingValidation();
            $query = Garage::query()->has('serviceTypes');

            $request->validate([
                'service_type'      => 'nullable|exists:service_types,id'
            ]);

            $searchable_fields = ['garage_name','address1'];

            $data = $this->filterSearchPagination($query,$searchable_fields);
            return success('Garage List',[
                'Garage'         => $data['query']->get(),
                'count'          => $data['count']
            ]);
    }

    /**
     * Update Garage 
     */
    public function update(Request $request ,Garage $garage){
        $request->validate([
            'garage_name'       => 'string|max:40|unique:garages,garage_name,id'.$garage->id,
            'address1'          => 'string',
            'address2'          => 'string',
            'service_type_id'   => 'array|exists:service_types,id|exists:garage_service_types,service_type_id'
        ]);

        if($garage){
            $garage->update($request->only('garage_name','address1','address2'));
            $garage->serviceTypes()->syncWithoutDetaching($request->service_type_id);
            return success('Garage Updated SuccessFully',$garage);
        }
        return error('Garage Not Updated',type:'notfound');
    }

    /**
     * Get All Garage Details By garage id  
     */
    public function get($id){
        $garage = Garage::where('owner_id',Auth::user()->id)->with('user','serviceTypes')->find($id);
        if(isset($garage)){
            return success('Garage Details',$garage);
        }
        return error('garage Details Not Found');
    }


    /**
     * Delete Garage 
     */
    public function delete($id){
        $garage = Garage::where('owner_id',Auth::user()->id)->find($id);
        if($garage){
            $garage->delete();
            $garage->serviceTypes()->detach();
            return success('Garage Deleted SuccessFully');
        }
        return error('Garage Not Deleted',type:'notfound');
    }

    /**
     * Add Mechanic 
     */
    public function createMechanic(Request $request){
        $request->validate([
            'first_name'            => 'required|string|min:3|max:30',
            'last_name'             => 'required|string|min:3|max:30',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|max:12|same:password_confirmation',
            'type'                  => 'required|in:mechanic|string',
            'address1'              => 'required',
            'address2'              => 'nullable',
            'zip_code'              => 'required|numeric|digits:6',
            'phone'                 => 'required|numeric|digits:10|unique:users,phone',
            'profile_picture'       => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'city_id'               => 'required|exists:cities,id',
            'service_type_id'       => 'nullable|array|required_if:type,mechanic|exists:service_types,id',
            'password_confirmation' => 'required'
        ],[
            'in'        => 'The :attribute must be one of the following types: :values',
        ]);
        
        $user_id = Auth::user()->id;
        $garage = Garage::where('owner_id',$user_id)->first();
        // dd($garage->id);
        $garage_id = $garage->id;
        $profile_picture = $request->file('profile_picture')->storeAs('images', $request->profile_picture->getClientOriginalName());
        $mechanic = User::create($request->only([
            'first_name',
            'last_name',
            'email',
            'type',
            'address1',
            'address2',
            'zip_code',
            'phone',
            'city_id'
        ])+[
            'password'          => Hash::make($request->password),
            'profile_picture'   => $profile_picture,
            'garage_id'         => $garage_id,
            'token'             => Str::random(64),
        ]);
       
        $mechanic->serviceTypes()->attach($request->service_type_id);
       
        $mechanic->notify(new WelcomeMailNotification($mechanic));
        $mechanic->notify(new EmailVerifyMailNotification($mechanic));       
        $token = $mechanic->createToken('API Token')->accessToken; 
        return success('Mechanic Add SuccessFully',$mechanic);
    }
}
