<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingAccess extends Model
{
    use HasFactory;

    protected $table = 'parking_access';

    protected $fillable = ['license_plate', 'check_in', 'check_out'];
}
