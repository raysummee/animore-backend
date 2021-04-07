<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetDailies extends Model
{
    use HasFactory;

    protected $fillable = [
        "task_name", "time", "week"
    ];

    protected $casts = [
        "time" => "datetime"
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
