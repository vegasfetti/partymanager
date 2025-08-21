<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'rating',
        'user_id',
        'specialist_id',
        'status',
        'created_at',
        'updated_at',
    ];


    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
