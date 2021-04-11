<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VetBook extends Model
{
    use HasFactory;

    protected $fillable = [
        "pet_id", "veterinary_id", "onDate", "status"
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function veterinary()
    {
        return $this->belongsTo(Veterinary::class);
    }
}
