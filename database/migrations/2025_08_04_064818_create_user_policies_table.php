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
Schema::create('user_policies', function (Blueprint $table) {
    $table->id('user_policy_id');
    $table->foreignUuid('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
    $table->foreignId('policy_id')->constrained('policies', 'policy_id');
    $table->string('policy_number')->unique();
    $table->date('start_date');
    $table->date('end_date');
    $table->enum('status', ['active', 'expired', 'cancelled', 'pending_payment'])->default('pending_payment');
    $table->enum('payment_frequency', ['monthly', 'quarterly', 'yearly', 'one-time']);
    $table->date('next_payment_date')->nullable();
    $table->date('last_payment_date')->nullable();
    $table->decimal('total_premium', 12, 2);
    $table->decimal('paid_amount', 12, 2)->default(0);
    $table->enum('payment_method', ['credit_card', 'bank_transfer', 'mobile_money']);
    $table->boolean('auto_renew')->default(false);
    $table->text('cancellation_reason')->nullable();
    $table->string('signed_document_url')->nullable();
    $table->timestamps();
    
    $table->index(['user_id', 'status']);
    $table->index(['policy_id', 'status']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_policies');
    }
};
