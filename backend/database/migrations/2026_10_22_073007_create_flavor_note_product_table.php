<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flavor_note_product', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('flavor_note_id')->constrained('flavor_notes')->cascadeOnDelete();
            $table->primary(['product_id', 'flavor_note_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flavor_note_product');
    }
};
