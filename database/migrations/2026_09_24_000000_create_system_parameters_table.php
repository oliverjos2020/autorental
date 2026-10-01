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
        Schema::create('system_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('param_key')->unique()->comment('Unique parameter key, e.g. PAYSTACK_SECRET_KEY');
            $table->text('param_value')->nullable()->comment('The parameter value');
            $table->string('param_group')->default('General')->comment('Grouping label, e.g. Payment Gateway, System');
            $table->text('description')->nullable()->comment('Human-readable description of what this parameter does');
            $table->boolean('is_secret')->default(false)->comment('If true, mask the value in the UI');
            $table->boolean('is_active')->default(true)->comment('Whether this parameter is active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_parameters');
    }
};
