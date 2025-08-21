<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'price',
        'specialist_id',
        'created_at',
        'updated_at',

    ];

    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }
}
