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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->bigInteger('chat_id');
            $table->enum('type', ['delivery', 'pickup']);
            $table->enum('status', ['new', 'accepted', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'canceled'])->default('new');
            $table->integer('subtotal')->default(0);
            $table->integer('delivery_fee')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('total')->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->json('delivery_address_json')->nullable();
            $table->timestamps();

            $table->index(['restaurant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
