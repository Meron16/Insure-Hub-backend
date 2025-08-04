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

Schema::create('policies', function (Blueprint $table) {
    $table->id('policy_id');
    $table->foreignId('provider_id')->constrained('providers', 'provider_id');
    $table->foreignId('category_id')->constrained('insurance_categories', 'category_id');
    $table->string('title');
    $table->text('description');
    $table->decimal('premium_amount', 10, 2);
    $table->decimal('coverage_limit', 12, 2);
    $table->decimal('deductible_amount', 10, 2);
    $table->integer('duration_days');
    $table->enum('renewal_type', ['automatic', 'manual', 'none']);
    $table->integer('grace_period_days')->default(0);
    $table->boolean('is_active')->default(true);
    $table->boolean('requires_medical_check')->default(false);
    $table->integer('min_age')->nullable();
    $table->integer('max_age')->nullable();
    $table->boolean('approved_by_admin')->default(false);
    $table->timestamp('approval_date')->nullable();
    $table->string('terms_and_conditions_url')->nullable();
    $table->timestamps();
});
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
