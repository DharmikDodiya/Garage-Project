<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class StateController extends Controller
{
    use ListingApiTrait;

    /**
     * Create State
     */
    public function create(Request $request){
        $request->validate([
            'state_name'        => 'required|string|unique:states,state_name',
            'country_id'        => 'required|exists:countries,id'
        ]);
        
        $state = State::create($request->only('state_name','country_id'));
        return success('State Created SuccessFully',$state);
    }

    /**
     * list State
     */
    public function list(){
        $this->ListingValidation();
            $query = State::query();
    
            $searchable_fields = ['state_name'];
            $data = $this->filterSearchPagination($query,$searchable_fields);
            return success('State List',[
                'State'          => $data['query']->get(),
                'count'          => $data['count']
            ]);
    }

    /**
     * update State
     */
    public function update(Request $request,State $state){
        $request->validate([
            'state_name'          => 'string|unique:states,state_name,id'.$state->id,
        ]);
        if($state){
            $state->update($request->only('state_name'));
            return success('Your State Is Updated SuccessFully',$state);
        }
        return error('Your State Is Not Updated',type:'notfound');
    }

    /**
     * delete State By Id
     */
    public function delete($id){
        $state = State::find($id);
        if($state){
            $state->delete();
            return success('State Deleted Successfully');
        }
        return error('State Not Deleted');
    }

    /**
     * Get State By Id
     */
    public function get($id){
        $state = State::find($id);
        if($state){
        return success('Get State Data By ID',$state);
       }
       return error('Rocord Not Found',type:'notfound');
    }
}
