<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coffee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained('products')->cascadeOnDelete();
            $table->foreignId('origin_id')->nullable()->constrained('origins')->nullOnDelete();
            $table->string('process')->nullable()->index();
            $table->string('roast_level')->nullable()->index();
            $table->string('variety')->nullable();
            $table->string('producer')->nullable();
            $table->smallInteger('altitude_masl')->nullable();
            $table->tinyInteger('acidity')->nullable();
            $table->tinyInteger('body')->nullable();
            $table->tinyInteger('sweetness')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coffee_profiles');
    }
};
