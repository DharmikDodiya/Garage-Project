<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable =[
        'state_id',
        'city_name'
    ];

    protected $hidden =[
        'created_at',
        'updated_at'
    ];
    /**
     * city relation to state
     */

    public function state(){
        return $this->belongsTo(State::class);
    }

    /**
     * Get Country By City id hasOneThrough relation
     */
    public function country(){
        return $this->hasOneThrough(Country::class,State::class,'country_id','id');
    }
}
