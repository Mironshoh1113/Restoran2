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
        Schema::create('restaurant_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->json('working_hours_json')->nullable();
            $table->boolean('delivery_enabled')->default(true);
            $table->boolean('pickup_enabled')->default(true);
            $table->unsignedInteger('delivery_radius_m')->nullable();
            $table->integer('delivery_min_total')->default(0);
            $table->integer('delivery_fee')->default(0);
            $table->json('payment_providers_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_settings');
    }
};
