<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'veterinary_id',
        'store_id',
        "role",
        "phone",
        "dob",
        "image"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'datetime'
    ];


    public function pet()
    {
        return $this->hasMany(Pet::class);
    }

    public function veterinary()
    {
        return $this->belongsTo(Veterinary::class);
    }

    public function veterinaryAdmin()
    {
        return $this->hasOne(Veterinary::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function storeAdmin()
    {
        return $this->hasOne(Store::class);
    }
}
