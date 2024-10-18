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
        Schema::create('assigned_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id'); // Foreign key to the skills table
            $table->unsignedBigInteger('user_id'); // Foreign key to the skills table
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('exam_id')->references('id')->on('exams'); // Reference to skills table
            $table->foreign('user_id')->references('id')->on('users'); // Reference to skills table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_exams');
    }
};
