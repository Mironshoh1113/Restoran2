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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('menu_categories')->cascadeOnDelete();
            $table->string('name');
            $table->integer('price'); // in tiyin
            $table->string('sku')->nullable();
            $table->json('options_json')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['restaurant_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
