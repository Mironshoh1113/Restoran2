<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('bot_id')->nullable()->constrained('bots')->nullOnDelete();
            $table->string('update_id')->nullable();
            $table->string('event')->nullable();
            $table->json('payload');
            $table->integer('http_status')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['restaurant_id', 'bot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
