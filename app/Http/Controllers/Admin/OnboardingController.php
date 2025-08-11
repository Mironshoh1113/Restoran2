<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    public function show()
    {
        return view('admin.onboarding');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'bot_token' => 'required|string',
        ]);

        $restaurant = Restaurant::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'plan' => 'free',
            'timezone' => 'Asia/Tashkent',
            'status' => 'active',
        ]);

        $secret = Str::random(32);
        $webhookUrl = rtrim(config('app.url'), '/') . '/api/bot/' . $data['bot_token'] . '/webhook';

        $bot = $restaurant->bot()->create([
            'token_hash' => Bot::hashToken($data['bot_token']),
            'token_encrypted' => $data['bot_token'],
            'webhook_url' => $webhookUrl,
            'webhook_secret' => $secret,
            'webhook_status' => 'pending',
        ]);

        $setWebhookResponse = Http::asForm()->post("https://api.telegram.org/bot{$data['bot_token']}/setWebhook", [
            'url' => $webhookUrl,
            'secret_token' => $secret,
            'allowed_updates' => json_encode(['message','callback_query','chat_member','my_chat_member']),
        ]);

        $ok = $setWebhookResponse->ok() && (bool) data_get($setWebhookResponse->json(), 'ok');
        $bot->update(['webhook_status' => $ok ? 'active' : 'failed']);

        return back()->with('status', $ok ? 'Webhook set successfully' : ('Webhook failed: ' . ($setWebhookResponse->body())));
    }
} 