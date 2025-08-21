<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecImage extends Model
{
    use HasFactory;


    protected $table = 'spec_images';

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
}
