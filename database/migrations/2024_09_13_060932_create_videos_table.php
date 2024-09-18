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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Lesson title
            $table->string('type'); // Lesson title
            $table->string('source'); // Lesson title
            $table->string('thumbnail')->nullable(); // Lesson title
            $table->longText('description')->nullable(); // Lesson title
            $table->unsignedBigInteger('skill_id'); // Foreign key to the skills table
            $table->unsignedBigInteger('topic_id')->nullable(); // Foreign key to the topics table (nullable)
            $table->text('tags')->nullable(); // Tags for the lesson
            $table->string('level')->nullable(); // Skill level for the lesson
            $table->integer('watch_time')->nullable(); // Read time in minutes (fixed from int to integer)
            $table->tinyInteger('is_free')->default(0); // Whether the lesson is free or not (0 = not free)
            $table->tinyInteger('status')->nullable()->default(1); // Lesson status (1 = active, 0 = inactive)
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('skill_id')->references('id')->on('skills'); // Reference to skills table
            $table->foreign('topic_id')->references('id')->on('topics'); // Reference to topics table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
