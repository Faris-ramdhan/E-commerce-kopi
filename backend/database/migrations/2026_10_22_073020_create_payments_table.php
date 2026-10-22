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
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('gateway'); // midtrans, xendit, manual, etc.
            $table->string('method')->nullable(); // qris, bca_va, gopay, etc.
            $table->string('external_id')->nullable()->index();
            $table->string('status')->default('pending'); // pending|paid|failed|expired|refunded
            $table->unsignedBigInteger('amount');
            $table->json('payload')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
