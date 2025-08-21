<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'slug',
        'created_at',
        'updated_at',
    ];

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    public function specialists()
    {
        return $this->hasMany(Specialist::class);
    }
}
