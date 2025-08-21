<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payment_id',
        'user_id',
        'data',
        'status',
    ];

    protected $casts = [
        'data' => 'array', // автоматически преобразует JSON в массив и обратно
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
