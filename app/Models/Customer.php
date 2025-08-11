<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'chat_id', 'first_name', 'phone', 'last_order_at', 'tags_json'
    ];

    protected $casts = [
        'tags_json' => 'array',
        'last_order_at' => 'datetime',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
