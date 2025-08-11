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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('chat_id');
            $table->string('first_name')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('last_order_at')->nullable();
            $table->json('tags_json')->nullable();
            $table->timestamps();

            $table->unique(['restaurant_id', 'chat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
