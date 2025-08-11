<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'phone', 'address', 'plan', 'timezone', 'status',
    ];

    public function bot()
    {
        return $this->hasOne(Bot::class);
    }

    public function settings()
    {
        return $this->hasOne(RestaurantSetting::class);
    }
}
