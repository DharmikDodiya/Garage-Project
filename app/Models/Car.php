<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    /**
     * Fillable Property
     */
    protected $fillable =[
        'owner_id',
        'company_name',
        'model_name',
        'manufacturing_year'
    ];  

    /**
     * car relation to carservicing
     */
    public function carServicings(){
        return $this->belongsToMany(ServiceType::class,'car_servicings','car_id','service_type_id')->withPivot('garage_id');
    }
 
    /**
     * Car Relation To User
     */
    public function user(){
        return $this->belongsTo(User::class,'owner_id');
    }


    // public function carServicingJobs()
    // {
    //     return $this->hasManyThrough(CarServicingJob::class,'car_servicings','car_id','car_service_type');
    // }
}
