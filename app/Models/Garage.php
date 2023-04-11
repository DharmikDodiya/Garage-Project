<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garage extends Model
{
    use HasFactory;
    /**
     * Fillable Data
     */
    protected $fillable =[
        'garage_name',
        'address1',
        'address2',
        'zip_code',
        'city_id',
        'country_id',
        'state_id',
        'owner_id',
    ];

    /**
     * Hidden Data
     */
    protected $hidden =[
        'created_at',
        'updated_at'
    ];

    /**
     * Garage Many To Many Relation On ServiceType
     */
    public function serviceTypes(){
        return $this->belongsToMany(ServiceType::class,'garage_service_types','garage_id','service_type_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'owner_id');
    }
}
