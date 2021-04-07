<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "bread", "dob", "image", "type"
    ];

    protected $casts = [
        "dob" => "date"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function importantDate()
    {
        return $this->hasMany(PetImportantEvent::class);
    }

    public function dailies()
    {
        return $this->hasMany(PetDailies::class);
    }

}
