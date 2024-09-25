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
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id'); // Foreign key to the skills table
            $table->longText('solution')->nullable(); 
            $table->tinyInteger('video_enable')->default(0); // 0 (NO)
            $table->string('video_type')->nullable(); 
            $table->string('video_source')->nullable(); 
            $table->longText('hint')->nullable(); 
            $table->string('attachment_type')->nullable();
            $table->string('attachment_video_type')->nullable();
            $table->string('attachment_source')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('question_id')->references('id')->on('questions'); // Reference to skills table
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solutions');
    }
};
