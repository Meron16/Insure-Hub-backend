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
Schema::create('refunds', function (Blueprint $table) {
    $table->id('refund_id');
    $table->foreignId('transaction_id')->constrained('transactions', 'transaction_id');
    $table->decimal('amount', 12, 2);
    $table->text('reason');
    $table->foreignUuid('processed_by')->constrained('users', 'user_id');
    $table->timestamp('processed_at')->useCurrent();
    $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
    $table->string('reference_number')->unique();
    $table->timestamps();
    
    $table->index(['transaction_id', 'status']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
