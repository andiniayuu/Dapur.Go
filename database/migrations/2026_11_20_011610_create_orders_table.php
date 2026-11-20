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

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('order_code')->unique();

            $table->date('event_date');
            $table->time('event_time')->nullable();

            $table->string('customer_name');
            $table->string('customer_phone');

            $table->string('delivery_type')->default('delivery');
            $table->text('delivery_address')->nullable();
            $table->text('notes')->nullable();

            $table->decimal('subtotal', 12, 2);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2);

            $table->string('status')->default('pending_payment');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
