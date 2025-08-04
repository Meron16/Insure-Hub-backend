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
Schema::create('transactions', function (Blueprint $table) {
    $table->id('transaction_id');
    $table->foreignUuid('user_id')->constrained('users', 'user_id');
    $table->foreignId('policy_id')->constrained('policies', 'policy_id');
    $table->foreignId('user_policy_id')->constrained('user_policies', 'user_policy_id');
    $table->decimal('amount', 12, 2);
    $table->enum('payment_method', ['credit_card', 'bank_transfer', 'mobile_money', 'wallet']);
    $table->json('payment_method_details')->nullable(); // Encrypted in model
    $table->string('transaction_reference')->unique();
    $table->enum('status', ['success', 'failed', 'pending', 'refunded'])->default('pending');
    $table->text('failure_reason')->nullable();
    $table->string('invoice_url')->nullable();
    $table->timestamps();
    
    $table->index(['user_id', 'status']);
    $table->index(['user_policy_id', 'created_at']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
