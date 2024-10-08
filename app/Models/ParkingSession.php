<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSession extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function blocknumber()
    {
        return $this->belongsTo(BlockNumber::class, 'spot_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'session_id');
    }

    public function approval()
    {
        return $this->hasOne(Approval::class);
    }
}
