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
Schema::create('claims', function (Blueprint $table) {
    $table->id('claim_id');
    $table->foreignId('user_policy_id')->constrained('user_policies', 'user_policy_id');
    $table->foreignId('provider_id')->constrained('providers', 'provider_id');
    $table->string('claim_number')->unique();
    $table->decimal('claim_amount', 12, 2);
    $table->decimal('approved_amount', 12, 2)->nullable();
    $table->text('description');
    $table->date('incident_date');
    $table->string('incident_location');
    $table->enum('status', ['pending', 'approved', 'rejected', 'under_review', 'paid']);
    $table->foreignUuid('processed_by')->nullable()->constrained('users', 'user_id');
    $table->timestamp('processed_at')->nullable();
    $table->text('rejection_reason')->nullable();
    $table->date('payment_date')->nullable();
    $table->enum('claim_type', ['medical', 'property', 'liability', 'other']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
