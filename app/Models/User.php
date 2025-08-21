<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'email_verified_at',
        'created_at',
        'updated_at',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function specialists()
    {
        return $this->hasMany(Specialist::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function smartOrders()
    {
        return $this->hasMany(SmartOrder::class);
    }
    public function smartOrderSpecialists()
    {
        return $this->hasMany(SmartOrderSpecialist::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
