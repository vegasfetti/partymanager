<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'social_network',
        'current_date',
        'comment',
        'status',
        'user_id',
        'created_at',
        'updated_at',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function SmartOrderSpecialists()
    {
        return $this->hasMany(SmartOrderSpecialist::class);
    }

    ////
    public function specialists()
    {
        return $this->hasMany(SmartOrderSpecialist::class);
    }
}


