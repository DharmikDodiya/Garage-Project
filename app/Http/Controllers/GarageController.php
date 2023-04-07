<?php

namespace App\Http\Controllers;

use App\Models\Garage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ListingApiTrait;

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
            $query = Garage::query();



            $searchable_fields = ['city_id'];
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

}
