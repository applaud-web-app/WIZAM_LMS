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
        Schema::create('quiz_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quizzes_id');
            $table->string('schedule_type'); 
            $table->string('start_date'); 
            $table->string('start_time'); 
            $table->string('end_date')->nullable(); 
            $table->string('end_time')->nullable(); 
            $table->string('grace_period')->nullable(); 
            $table->string('user_groups')->nullable(); 
            $table->tinyInteger('status')->nullable()->default(1); 
            $table->timestamps();

            $table->foreign('quizzes_id')->references('id')->on('quizzes'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_schedules');
    }
};
