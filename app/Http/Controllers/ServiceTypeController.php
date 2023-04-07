<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;
use App\Traits\ListingApiTrait;

class ServiceTypeController extends Controller
{
    use ListingApiTrait;
    /**
    * Create ServiceType
    */
    public function create(Request $request){
        $request->validate([
            'service_name'      => 'required|string|unique:service_types,service_name',
        ]);
        $servicetype = ServiceType::create($request->only('service_name'));
        return success('ServiseType Created SuccessFully',$servicetype);
    }

    /**
     * list ServiceType
     */
    public function list(){
        $this->ListingValidation();
            $query = ServiceType::query();
    
            $searchable_fields = ['service_name'];
            $data = $this->filterSearchPagination($query,$searchable_fields);
            return success('ServiceType List',[
                'ServiceType'       => $data['query']->get(),
                'count'             => $data['count']
            ]);
    }

    /**
     * update ServiceType
     */
    public function update(Request $request,ServiceType $servicetype){
        $request->validate([
            'service_name'          => 'string|unique:service_types,service_name,id'.$servicetype->id,
        ]);
        if($servicetype){
            $servicetype->update($request->only('service_name'));
            return success('Your ServiceType Is Updated SuccessFully',$servicetype);
        }
        return error('Your ServiceType Is Not Updated',type:'notfound');
    }

    /**
     * delete ServiceType By Id
     */
    public function delete($id){
        $servicetype = ServiceType::find($id);
        if($servicetype){
            $servicetype->delete();
            return success('ServiceType Deleted Successfully');
        }
        return error('ServiceType Not Deleted',type:'notfound');
    }

    /**
     * Get ServiceType By Id
     */
    public function get($id){
        $servicetype = ServiceType::find($id);
        if($servicetype){
            return success('Get ServiceType Data By ID',$servicetype);
        }
        return error('Record Not Found',type:'notfound');
    }
}
