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
        Schema::create('student_exam_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('questions'); // For larger data
            $table->longText('answers')->nullable(); // For larger data
            $table->string('exam_duration');
            $table->string('point');
            $table->string('negative_marking');
            $table->string('pass_percentage');
            $table->string('total_question');
            $table->string('correct_answer');
            $table->string('incorrect_answer');
            $table->string('status');
            $table->timestamp('submit_at');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('exam_id')->references('id')->on('exams'); 
            $table->foreign('subcategory_id')->references('id')->on('sub_categories'); 
            $table->foreign('user_id')->references('id')->on('users'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exam_results');
    }
};
