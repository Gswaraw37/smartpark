<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function parkingsession()
    {
        return $this->belongsTo(ParkingSession::class, 'session_id');
    }
}
