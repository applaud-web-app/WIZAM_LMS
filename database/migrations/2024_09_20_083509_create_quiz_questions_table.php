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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quizzes_id'); // Foreign key to the skills table
            $table->unsignedBigInteger('question_id'); // Foreign key to the skills table
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('question_id')->references('id')->on('questions'); // Reference to skills table
            $table->foreign('quizzes_id')->references('id')->on('quizzes'); // Reference to skills table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
