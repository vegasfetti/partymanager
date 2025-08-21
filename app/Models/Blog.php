<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'slug',
        'image',
        'text',
        'published_at',
        'meta_title',
        'meta_description',
        'created_at',
        'updated_at',
    ];



    protected static function booted()
    {
        // При обновлении
        static::updating(function (Blog $blog) {
            // 1. Обработка превью-картинки
            if ($blog->isDirty('image')) {
                $old = $blog->getOriginal('image');
                if ($old && $old !== 'no-photo.png') {
                    Storage::disk('public')->delete($old);
                }

                if (empty($blog->image)) {
                    $blog->image = 'no-photo.png';
                }
            }

            // 2. Обработка картинок внутри текста
            $oldText = $blog->getOriginal('text');
            $newText = $blog->text;

            preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/i', $oldText, $matchesOld);
            $oldImages = $matchesOld[1] ?? [];

            preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/i', $newText, $matchesNew);
            $newImages = $matchesNew[1] ?? [];

            foreach ($oldImages as $src) {
                $path = str_replace(url('/storage/'), '', $src);
                if (!in_array($src, $newImages) && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        });

        // При удалении записи
        static::deleted(function (Blog $blog) {
            // Удаляем превью
            if ($blog->image && $blog->image !== 'no-photo.png') {
                Storage::disk('public')->delete($blog->image);
            }

            // Удаляем все картинки из текста
            preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/i', $blog->text, $matches);
            $images = $matches[1] ?? [];
            foreach ($images as $src) {
                $path = str_replace(url('/storage/'), '', $src);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        });
    }



}
