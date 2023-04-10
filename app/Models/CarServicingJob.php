<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarServicingJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_servicing_id',
        'mechanic_id',
        'status',
        'service_type_id'
    ];
}
