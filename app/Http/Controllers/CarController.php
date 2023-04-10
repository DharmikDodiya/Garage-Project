<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    /**
     * Car Create
     */
    public function create(Request $request){
        $request->validate([
            'company_name'          => 'required|string|max:30',
            'model_name'            => 'required|string|max:40',
            'manufacturing_year'    => 'required|numeric',
        ]);
        
        $owner_id = Auth::user()->id;
        $car = Car::create($request->only(['company_name','model_name','manufacturing_year'])
        +[
            'owner_id'      => $owner_id
        ]);
        
        return success('Car Created SucceFully',$car);
    }

    /**
     * Update Cars
     */
    public function update(Request $request ,$id){
        $request->validate([
            'company_name'          => 'nullable|string|max:30',
            'model_name'            => 'nullable|string|max:40',
            'manufacturing_year'    => 'nullable|numeric',
            'garage_id'             => 'exists:garages,id',
            'service_type_id'       => 'array|exists:service_types,id'
        ]);
        //dd($request);
        $car = Car::where('owner_id',Auth::user()->id)->find($id);
        //dd($car);
        if(isset($car)){
            $car->update($request->only('company_name','model_name','manufacturing_year'));
            $car->carServicings()->attach($request->service_type_id,$request->only('garage_id'));
            return success('Car Updated SuccessFully',$car);
        }
        return error('Car Not Found',type:'notfound');
    }

    /**
     * Delete Car
     */
    public function delete($id){
        $car = Car::where('owner_id',Auth::user()->id)->find($id);
        if(isset($car)){
            $car->delete();
            $car->carServicings()->detach();
            return success('Car Deleted SuccessFully');
        }
        return error('Car Not Found',type:'notfound');
    }

    /**
     * List Car
     */
    public function list(){
        $car = Car::where('owner_id',Auth::user()->id)->get();
        return success('Car List',$car);
    }

    /**
     * Get Car
     */
    public function get($id){
        $car = Car::where('owner_id',Auth::user()->id)->with('user')->find($id);
        if(isset($car)){
            return success('Car Details',$car);
        }
        return error('Car not found',type:'notfound');
    }

}
