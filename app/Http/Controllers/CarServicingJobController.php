<?php

namespace App\Http\Controllers;

use App\Models\CarServicing;
use App\Models\CarServicingJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarServicingJobController extends Controller
{
    /**
     * Create CarServicing Job
     */
    public function create(Request $request){
        $request->validate([
            'car_servicing_id'      => 'required|numeric|exists:car_servicings,id',
            'mechanic_id'           => 'required|numeric|exists:users,id',
            'status'                => 'in:pending'
        ],[
            'in'        => 'The :attribute must be one of the following types: :values',
        ]);

        $mechanic = User::where('id',$request->mechanic_id)->first();
        $car_servicing = CarServicing::where([['id',$request->car_servicing_id],['garage_id',Auth::user()->garage_id]])->first();

        if($mechanic->type == 'mechanic' && $car_servicing){
            $carservicingjob = CarServicingJob::where([['car_servicing_id',$request->car_servicing_id],['mechanic_id',$request->mechanic_id]])->first();
            if(!is_null($carservicingjob)){
                return error('Car Servicing Job Already Exists',type:'notfound');
            }
            $car_servicing_job = CarServicingJob::create($request->only(['car_servicing_id','mechanic_id','status'])
            +[
                'service_type_id'   => $car_servicing->service_type_id,
            ]);
            return success('Car Servicing Job Created SuccessFully',$car_servicing_job);
        }
        else{
            return error('Mechanic Not Found',type:'notfound');
        }
    }

    /**
     * List Garage CarServicing Jobs
     */
    public function list(){
        $car_servicing_job_list = CarServicingJob::where('mechanic_id',Auth::user()->id)->get();
        if(isset($car_servicing_job_list)){
            return success('Car Service Job List',$car_servicing_job_list);
       }
       return error('Car service Jobs Not Found',type:'notfound');
    }

    /**
     * Update CarServicing Job
     */
    public function update(Request $request , $id){
        $request->validate([
            'status'        => 'in:pending,inprogress,complete,delivered'
        ],
        [
            'in'    => 'The :attribute must be one of the following types: :values',
        ]);

        $mechanic = User::where('id',Auth::user()->id)->first();
        $car_servicing_job = CarServicingJob::where('mechanic_id',$mechanic->id)->find($id);
        if(isset($car_servicing_job)){
            $car_servicing_job->update($request->only('status'));
            return success('Car Servicing Job Updated SuccessFully',$car_servicing_job);
        }
        return error('Car Servicing Job Status Not Updated');
    }
}
