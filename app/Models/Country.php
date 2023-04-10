<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_name'
    ];
    
    protected $hidden =[
        'created_at',
        'updated_at'
    ];

    /**
     * Country ont to many relation to states
     */
    public function states(){
        return $this->hasMany(State::class);
    }

    /**
     * Get cities by country id using hasManyhrough
     */
    public function cities(){
        return $this->hasManyThrough(City::class,State::class,'country_id','state_id');
    }


}
