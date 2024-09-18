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
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index(); // Foreign key to sections table
            $table->string('name');
            $table->string('type')->index();
            $table->text('description')->nullable(); // Allows up to 65,535 characters
            $table->tinyInteger('status')->default(1)->index(); // Status with default value and indexed
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('category_id')
            ->references('id')
            ->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
