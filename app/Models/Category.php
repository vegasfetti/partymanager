<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'created_at',
        'updated_at'
    ];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function specialists()
    {
        return $this->hasMany(Specialist::class);
    }


    // удалит фотку при удалении
    protected static function booted()
    {
        // При обновлении картинки
        static::updating(function ($model) {
            if ($model->isDirty('image')) {
                $old = $model->getOriginal('image');
                if ($old && $old !== 'blog_upload/no-photo.png') {
                    Storage::disk('public')->delete($old);
                }

                // Если очистили картинку → ставим дефолт
                if (empty($model->image)) {
                    $model->image = 'blog_upload/no-photo.png';
                }
            }
        });

        // При удалении записи
        static::deleted(function ($model) {
            if ($model->image && $model->image !== 'blog_upload/no-photo.png') {
                Storage::disk('public')->delete($model->image);
            }
        });
    }

}
