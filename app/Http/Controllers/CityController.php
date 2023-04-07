<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use ListingApiTrait;
    /**
     * Create City
     */
    public function create(Request $request){
        $request->validate([
            'city_name'        => 'required|string|unique:cities,city_name',
            'state_id'        => 'required|exists:states,id'
        ]);

        $city = City::create($request->only('city_name','state_id'));
        return success('City Created SuccessFully',$city);
    }

    /**
     * list City
     */
    public function list(){
        $this->ListingValidation();
            $query = City::query();
    
            $searchable_fields = ['city_name'];
            $data = $this->filterSearchPagination($query,$searchable_fields);
            return success('State List',[
                'City'          => $data['query']->get(),
                'count'          => $data['count']
            ]);
    }

    /**
     * update City
     */
    public function update(Request $request,City $city){
        $request->validate([
            'city_name'          => 'string|unique:cities,city_name,id'.$city->id,
        ]);
        if($city){
            $city->update($request->only('city_name'));
            return success('Your City Is Updated SuccessFully',$city);
        }
        return error('Your City Is Not Updated',type:'notfound');
    }

    /**
     * delete City By Id
     */
    public function delete($id){
        $city = City::find($id);
        if($city){
            $city->delete();
            return success('City Deleted Successfully');
        }
        return error('City Not Deleted');
    }

    /**
     * Get State By Id
     */
    public function get($id){
        $city = City::find($id);
        if($city){
        return success('Get City Data By ID',$city);
        }
        return error('Record Not Found',type:'notfound');
    }

}
