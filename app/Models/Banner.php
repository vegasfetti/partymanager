<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link',
        'is_promo',
        'status',
        'type',
        'city_id',
        'created_at',
        'updated_at',
    ];


    public function city()
    {
        return $this->belongsTo(City::class);
    }

    protected static function booted()
    {
        // При обновлении картинки
        static::updating(function ($model) {
            if ($model->isDirty('image')) {
                $old = $model->getOriginal('image');
                if ($old && $old !== 'banner_upload/no-photo.png') {
                    Storage::disk('public')->delete($old);
                }

                // Если очистили картинку → ставим дефолт
                if (empty($model->image)) {
                    $model->image = 'banner_upload/no-photo.png';
                }
            }
        });

        // При удалении записи
        static::deleted(function ($model) {
            if ($model->image && $model->image !== 'banner_upload/no-photo.png') {
                Storage::disk('public')->delete($model->image);
            }
        });
    }
}
