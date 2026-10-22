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
            $table->string('order_number')->unique(); // KOPI-YYYYMMDD-0001
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('status')->default('pending_payment')->index();
            $table->string('payment_status')->default('pending')->index();

            // Snapshot alamat
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->string('shipping_province');
            $table->string('shipping_city');
            $table->string('shipping_district')->nullable();
            $table->string('shipping_postal_code');
            $table->text('shipping_address');

            // Finansial (selalu integer Rupiah)
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount_total')->default(0);
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->unsignedBigInteger('grand_total');

            // Voucher & Pengiriman
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->string('voucher_code')->nullable();
            $table->string('courier')->nullable();
            $table->string('courier_service')->nullable();
            $table->integer('total_weight_grams')->default(0);
            $table->string('tracking_number')->nullable()->index();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->text('customer_note')->nullable();
            $table->text('internal_note')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
