<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Block;

class ParkingFloor extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function blocknumber()
    {
        return $this->hasMany(BlockNumber::class);
    }
}
