<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function parkingsession()
    {
        return $this->hasMany(ParkingSession::class);
    }

    public function approval()
    {
        return $this->hasMany(Approval::class);
    }

    public function blocknumber()
    {
        return $this->belongsTo(BlockNumber::class, 'block_id');
    }
}
