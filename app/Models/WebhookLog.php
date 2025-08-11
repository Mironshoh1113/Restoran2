<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'bot_id', 'update_id', 'event', 'payload', 'http_status', 'error_message'
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
