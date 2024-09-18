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
        Schema::create('skills', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('section_id'); // Foreign key to sections table
            $table->string('name');
            $table->text('description')->nullable(); // Allows up to 65,535 characters
            $table->tinyInteger('status')->default(1)->index(); // Status with default value and indexed
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint
            $table->foreign('section_id')
                ->references('id')
                ->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
