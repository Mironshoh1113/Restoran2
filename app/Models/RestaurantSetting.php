<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'working_hours_json',
        'delivery_enabled',
        'pickup_enabled',
        'delivery_radius_m',
        'delivery_min_total',
        'delivery_fee',
        'payment_providers_json',
    ];

    protected $casts = [
        'working_hours_json' => 'array',
        'payment_providers_json' => 'array',
        'delivery_enabled' => 'boolean',
        'pickup_enabled' => 'boolean',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
