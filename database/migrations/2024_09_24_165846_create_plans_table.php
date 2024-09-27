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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('category_id'); // Category field
            $table->string('name'); // Plan name field
            $table->string('price_type'); // Price type (e.g., monthly, yearly)
            $table->integer('duration')->nullable(); // Duration field, optional
            $table->decimal('price', 10, 2)->default(0); // Price field with two decimal places
            $table->integer('discount')->nullable(); // Discount field, optional
            $table->boolean('feature_access'); // Feature access flag (0 or 1)
            $table->text('features')->nullable(); 
            $table->tinyInteger('popular')->default(0); 
            $table->tinyInteger('status')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
