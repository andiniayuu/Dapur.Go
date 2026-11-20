<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('method')->default('bank_transfer');
            $table->decimal('amount', 12, 2);

            $table->string('proof_path')->nullable();

            $table->string('status')
                ->default('waiting_verification');

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
