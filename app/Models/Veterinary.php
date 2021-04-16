<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinary extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "available", "contact_no", "email", "desc", "star", "location"
    ];

    public function users()
    {
       return $this->hasMany(User::class);
    }

    public function vetBooks()
    {
        return $this->hasMany(VetBook::class);
    }
}
