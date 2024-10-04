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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('slug'); 
            $table->unsignedBigInteger('subcategory_id'); // Foreign key to the skills table
            $table->unsignedBigInteger('quiz_type_id')->nullable(); // Foreign key to the skills table
            $table->longText('description')->nullable(); 
            $table->string('duration_mode')->default('automatic');
            $table->string('duration')->nullable();
            $table->string('point_mode')->default('automatic');
            $table->string('point')->nullable();
            $table->tinyInteger('negative_marking')->default(0);
            $table->string('negative_marking_type')->nullable();
            $table->tinyInteger('negative_marks')->nullable();
            $table->string('pass_percentage')->default(60);
            $table->tinyInteger('shuffle_questions')->default(0);
            $table->tinyInteger('restrict_attempts')->default(0);
            $table->string('total_attempts')->nullable();
            $table->tinyInteger('disable_finish_button')->default(0);
            $table->tinyInteger('question_view')->default(1);
            $table->tinyInteger('hide_solutions')->default(0);
            $table->tinyInteger('leaderboard')->default(1);
            $table->tinyInteger('is_public')->default(1)->index();
            $table->tinyInteger('is_free')->default(0); // Whether the lesson is free or not (0 = not free)
            $table->tinyInteger('status')->nullable()->default(1); // Lesson status (1 = active, 0 = inactive)
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('subcategory_id')->references('id')->on('sub_categories'); // Reference to skills table
            $table->foreign('quiz_type_id')->references('id')->on('quiz_types'); // Reference to skills table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
