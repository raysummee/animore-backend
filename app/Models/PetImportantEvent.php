<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetImportantEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "date_time"
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
