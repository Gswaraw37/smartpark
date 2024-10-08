<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockNumber extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function parkingfloor()
    {
        return $this->belongsTo(ParkingFloor::class, 'floor_id');
    }

    public function parkingsession()
    {
        return $this->hasMany(ParkingSession::class, 'spot_id');
    }

    public function approval()
    {
        return $this->hasMany(Approval::class, 'block_id');
    }

    public function vehicle()
    {
        return $this->hasMany(Vehicle::class, 'block_id');
    }
}
