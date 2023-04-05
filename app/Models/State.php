<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_id',
        'state_name'
    ];

    protected $hidden =[
        'created_at',
        'updated_at'
    ];

    /**
     * State one to many relationship on cities
     */
    public function cities(){
        return $this->hasMany(City::class);
    }

    /**
     * State relation To country
     */

    public function state(){
        return $this->belongsTo(Country::class);
    }
}
