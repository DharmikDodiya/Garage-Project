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
}
