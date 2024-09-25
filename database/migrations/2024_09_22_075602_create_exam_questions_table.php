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
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id'); // Foreign key to the skills table
            $table->unsignedBigInteger('section_id'); // Foreign key to the skills table
            $table->unsignedBigInteger('question_id'); // Foreign key to the skills table
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('question_id')->references('id')->on('questions'); // Reference to skills table
            $table->foreign('section_id')->references('id')->on('sections'); // Reference to skills table
            $table->foreign('exam_id')->references('id')->on('exams'); // Reference to skills table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
