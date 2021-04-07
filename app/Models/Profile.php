<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        "role", "phone", "dob", "image"
    ];

    protected $casts = [
        'dob' => 'datetime',
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
