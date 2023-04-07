<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;
    protected $fillable =[
        'service_name'
    ];

    protected $hidden =[
        'created_at',
        'updated_at'
    ];

    /**
     * Many To Many Relation On Users
     */
    public function users(){
        return $this->belongsToMany(User::class);
    }

    /**
     * Many To Many Relation On Garages
     */
    public function garages(){
        return $this->belongsToMany(Garage::class);
    }
}
