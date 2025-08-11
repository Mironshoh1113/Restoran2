<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'name', 'polygon_geojson', 'radius_m', 'min_total', 'fee'
    ];

    protected $casts = [
        'polygon_geojson' => 'array',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
