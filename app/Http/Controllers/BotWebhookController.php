<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\WebhookLog;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class BotWebhookController extends Controller
{
    public function handle(Request $request, string $token)
    {
        $bot = Bot::findByToken($token);
        if (!$bot) {
            return response()->json(['ok' => false, 'error' => 'Bot not found'], Response::HTTP_NOT_FOUND);
        }

        $secretFromHeader = $request->header('X-Telegram-Bot-Api-Secret-Token');
        $expectedSecret = $bot->webhook_secret ? decrypt($bot->getRawOriginal('webhook_secret')) : null;
        if ($expectedSecret && $secretFromHeader !== $expectedSecret) {
            WebhookLog::create([
                'restaurant_id' => $bot->restaurant_id,
                'bot_id' => $bot->id,
                'event' => 'unauthorized',
                'payload' => $request->all(),
                'http_status' => Response::HTTP_FORBIDDEN,
                'error_message' => 'Invalid secret token',
            ]);
            return response()->json(['ok' => false], Response::HTTP_FORBIDDEN);
        }

        $payload = $request->all();

        $updateType = collect($payload)->keys()->first();
        if ($updateType === 'message') {
            $text = data_get($payload, 'message.text', '');
            $chatId = data_get($payload, 'message.chat.id');
            if (is_string($text) && str_starts_with($text, '/start')) {
                $webAppUrl = rtrim(config('app.url'), '/') . '/w/' . $bot->restaurant->slug;
                app(TelegramService::class)->sendMessage($bot, [
                    'chat_id' => $chatId,
                    'text' => "Assalomu alaykum! \nMenyuni ko‘rish uchun quyidagi tugmani bosing.",
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [[
                            ['text' => 'Menyu', 'web_app' => ['url' => $webAppUrl]],
                        ]]
                    ]),
                ]);
            }
        }

        WebhookLog::create([
            'restaurant_id' => $bot->restaurant_id,
            'bot_id' => $bot->id,
            'update_id' => data_get($payload, 'update_id'),
            'event' => $updateType,
            'payload' => $payload,
            'http_status' => 200,
        ]);

        return response()->json(['ok' => true]);
    }
} 