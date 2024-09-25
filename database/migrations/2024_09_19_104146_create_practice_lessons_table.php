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
        Schema::create('practice_lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skill_id'); // Foreign key to the skills table
            $table->unsignedBigInteger('subcategory_id'); // Foreign key to the skills table
            $table->unsignedBigInteger('lesson_id'); // Foreign key to the skills table
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('subcategory_id')->references('id')->on('sub_categories'); // Reference to skills table
            $table->foreign('skill_id')->references('id')->on('skills'); // Reference to skills table
            $table->foreign('lesson_id')->references('id')->on('lessons'); // Reference to skills table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_lessons');
    }
};
