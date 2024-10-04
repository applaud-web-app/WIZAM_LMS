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
        Schema::create('practice_sets', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('slug'); 
            $table->unsignedBigInteger('subCategory_id'); // Foreign key to the skills table
            $table->unsignedBigInteger('skill_id'); // Foreign key to the skills table
            $table->longText('description')->nullable(); 
            $table->tinyInteger('allow_reward')->default(1);
            $table->tinyInteger('reward_popup')->default(1);
            $table->string('point_mode')->default('automatic');
            $table->string('points')->nullable();
            $table->tinyInteger('is_free')->default(1); // Whether the lesson is free or not (0 = not free)
            $table->tinyInteger('status')->nullable()->default(1); // Lesson status (1 = active, 0 = inactive)
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('subCategory_id')->references('id')->on('sub_categories'); // Reference to skills table
            $table->foreign('skill_id')->references('id')->on('skills'); // Reference to skills table
        });

     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_sets');
    }
};
