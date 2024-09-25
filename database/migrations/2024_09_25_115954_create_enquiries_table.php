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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('study_mode',50)->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('hear_by',50)->nullable();
            $table->text('message')->nullable();
            $table->string('contact_me'); 
            $table->tinyInteger('accept_condition'); 
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('sub_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
