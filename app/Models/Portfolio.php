<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'specialist_id',
        'created_at',
        'updated_at',
    ];

    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }


    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->image)) {
                return false; // отменяет создание записи
            }
        });
    }
}
