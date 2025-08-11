<?php

namespace App\Services;

use App\Models\Bot;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage(Bot $bot, array $params): bool
    {
        $token = $bot->token_encrypted; // decrypted via cast
        $resp = Http::asForm()->post("https://api.telegram.org/bot{$token}/sendMessage", $params);
        return $resp->ok() && (bool) data_get($resp->json(), 'ok');
    }
} 