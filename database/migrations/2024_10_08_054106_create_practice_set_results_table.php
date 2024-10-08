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
        Schema::create('practice_set_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('practice_sets_id'); // The ID of the quiz
            $table->unsignedBigInteger('subcategory_id'); // The subcategory of the quiz
            $table->unsignedBigInteger('user_id'); // The ID of the user taking the quiz
            $table->string('uuid'); // Unique ID to track the quiz session
            $table->longText('questions'); // Store the quiz questions (JSON format for easy retrieval)
            $table->longText('correct_answers')->nullable(); // Store the user's answers (JSON format)
            $table->longText('answers')->nullable(); // Store the user's answers (JSON format)
            $table->string('exam_duration'); // Total exam duration (e.g., 30 mins)
            $table->string('point'); // Total points/marks
            $table->string('total_question'); // Total number of questions in the quiz
            $table->string('correct_answer')->nullable(); // Number of correct answers (updated after quiz completion)
            $table->string('incorrect_answer')->nullable(); // Number of incorrect answers (updated after quiz completion)
            $table->string('status')->default('ongoing'); // Status: 'ongoing', 'completed', 'failed'
            $table->timestamp('start_time'); // When the user started the quiz
            $table->timestamp('end_time')->nullable(); // When the user completed or abandoned the quiz
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('practice_sets_id')->references('id')->on('practice_sets'); 
            $table->foreign('subcategory_id')->references('id')->on('sub_categories'); 
            $table->foreign('user_id')->references('id')->on('users'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_set_results');
    }
};
