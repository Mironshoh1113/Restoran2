<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Bot extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'token_hash',
        'token_encrypted',
        'username',
        'webhook_url',
        'webhook_secret',
        'webhook_status',
    ];

    protected $casts = [
        'token_encrypted' => 'encrypted',
        'webhook_secret' => 'encrypted',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    public static function findByToken(string $token): ?self
    {
        return static::where('token_hash', static::hashToken($token))->first();
    }
}
