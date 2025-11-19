<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->string('status')->default('Новая');
            $table->string('customer_name');
            $table->text('address');
            $table->string('payment_method');
            $table->string('delivery_method');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('promo_code')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};