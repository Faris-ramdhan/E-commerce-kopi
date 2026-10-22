<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wholesale_leads', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('business_type')->nullable(); // Cafe, Resto, Hotel, Reseller, dll
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone', 20);
            $table->string('city')->nullable();
            $table->integer('estimated_monthly_kg')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new')->index(); // new|contacted|negotiating|won|lost
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('internal_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wholesale_leads');
    }
};
