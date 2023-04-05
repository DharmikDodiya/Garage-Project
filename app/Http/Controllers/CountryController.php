<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use ListingApiTrait;

    /**
     * Create Country
     */
    public function create(Request $request){
        $request->validate([
            'country_name'      => 'required|string|unique:countries,country_name',
        ]);

        $country = Country::create($request->only('country_name'));
        return success('Country Created SuccessFully',$country);
    }

    /**
     * list Country
     */
    public function list(){
        $this->ListingValidation();
            $query = Country::query();
    
            $searchable_fields = ['country_name'];
            $data = $this->filterSearchPagination($query,$searchable_fields);
            return success('Country List',[
                'Country'       => $data['query']->get(),
                'count'          => $data['count']
            ]);
    }

    /**
     * update Country
     */
    public function update(Request $request,Country $id){
        $request->validate([
            'country_name'          => 'string|unique:countries,country_name,except,id',
        ]);
        if($id){
            $id->update($request->only('country_name'));
            return success('Your Country Is Updated SuccessFully',$id);
        }
        return error('Your Country Is Not Updated',type:'notfound');
    }

    /**
     * delete Country By Id
     */
    public function delete($id){
        $country = Country::find($id);
        if($country){
            $country->delete();
            return success('Country Deleted Successfully');
        }
        return error('Country Not Deleted');
    }

    /**
     * Get Country By Id
     */
    public function get($id){
        $country = Country::findOrFail($id);
        return success('Get Country Data By ID',$country);
    }
}
