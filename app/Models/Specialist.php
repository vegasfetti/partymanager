<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Specialist extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_link',
        'price',
        'price_type',
        'phone',
        'email',
        'vkontacte',
        'telegram',
        'website',
        'price_text',
        'experience',
        'subject_type',
        'is_contract',
        'promoted_until',
        'documents_verified_at',
        'skills',
        'equipment',
        'languages',
        'status',
        'city_id',
        'user_id',
        'category_id',
        'subcategory_id',
        'created_at',
        'updated_at',
    ];




    protected $casts = [
        'documents_verified_at' => 'datetime',
    ];



    public function getPriceTypeLabel(): string
    {
        return match ($this->price_type) {
            'per_hour' => 'за час',
            'per_day' => 'за день',
            'per_service' => 'за услугу',
            default => 'за услугу',
        };
    }


    public function getExperienceLabel(): string
    {
        return match ($this->experience) {
            'less_than_1' => 'Менее 1 года',
            '1_3_years' => 'От 1 до 3 лет',
            '3_5_years' => 'От 3 до 5 лет',
            'more_than_5' => 'Более 5 лет',
            default => 'не указано',
        };
    }

    public function getVisitCount(): string
    {
        return $this->visits()->count();
    }

    public function getRating(): string
    {
        $rating = $this->reviews()
            ->where('status', 'verify')
            ->avg('rating');
        return $rating ? sprintf("%.1f", $rating) : '-';
    }


    public function getSubjectTypeLabel(): string
    {
        return match ($this->subject_type) {
            'individual' => 'Частное лицо',
            'company' => 'Компания',
            default => 'не указано',
        };
    }


    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'on_moderation' => 'На модерации',
            'verify' => 'Верифицирован',
            'canceled' => 'Отменен',
            default => 'не указано',
        };
    }

    public function getIsContractLabel(): string
    {
        return match ($this->is_contract) {
            1 => 'Да',
            0 => 'Нет',
            default => 'не указано',
        };
    }


    public function smartOrders()
    {
        return $this->belongsToMany(SmartOrder::class, 'smart_order_specialist');
    }

    public function smartOrderSpecialists()
    {
        return $this->hasMany(SmartOrderSpecialist::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function specImages()
    {
        return $this->hasMany(SpecImage::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }



    protected static function booted()
    {
        // Специалист
        static::updating(function ($model) {
            if ($model->isDirty('specImages')) {
                foreach ($model->specImages as $img) {
                    if (Storage::disk('public')->exists($img->image)) {
                        Storage::disk('public')->delete($img->image);
                    }
                }
            }
            if ($model->isDirty('portfolios')) {
                foreach ($model->portfolios as $img) {
                    if (Storage::disk('public')->exists($img->image)) {
                        Storage::disk('public')->delete($img->image);
                    }
                }
            }
        });

        static::deleted(function ($model) {
            foreach ($model->specImages as $img) {
                if ($img->image !== 'spec_upload/no-photo.png') {
                    Storage::disk('public')->delete($img->image);
                }
            }
            foreach ($model->portfolios as $img) {
                if ($img->image !== 'portfolio_upload/no-photo.png') {
                    Storage::disk('public')->delete($img->image);
                }
            }
        });
    }

}


