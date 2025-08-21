<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartOrderSpecialist extends Model
{
    use HasFactory;

    // smart_order_id 	specialist_id 	
    protected $fillable = [
        'smart_order_id',
        'specialist_id',
        'status',
        'comment',
        'created_at',
        'updated_at',
    ];

    public function specialists()
    {
        return $this->belongsToMany(Specialist::class, 'smart_order_specialist');
    }

    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }

    public function smartOrder()
    {
        return $this->belongsTo(SmartOrder::class);
    }
}
