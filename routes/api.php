<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotWebhookController;

Route::post('/bot/{token}/webhook', [BotWebhookController::class, 'handle']); 