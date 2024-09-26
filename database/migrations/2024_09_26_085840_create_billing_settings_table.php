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
        Schema::create('billing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->string('address')->nullable();
            $table->string('city_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('country_id')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->boolean('enable_invoicing')->default(false); 
            $table->string('invoice_prefix')->nullable(); 
            $table->string('tax_name')->nullable();
            $table->boolean('enable_tax')->default(false); 
            $table->enum('tax_amount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->string('tax_type')->nullable();
            $table->boolean('enable_additional_tax')->default(false); 
            $table->string('additional_tax_name')->nullable();
            $table->enum('additional_tax_amount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('additional_tax_amount', 10, 2)->nullable();
            $table->string('additional_tax_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_settings');
    }
};
