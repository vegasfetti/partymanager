<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function specialists()
    {
        return $this->hasMany(Specialist::class);
    }
}
