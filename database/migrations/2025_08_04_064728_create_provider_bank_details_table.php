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
Schema::create('provider_bank_details', function (Blueprint $table) {
    $table->id('bank_id');
    $table->foreignId('provider_id')->constrained('providers', 'provider_id')->cascadeOnDelete();
    $table->string('account_name');
    $table->string('account_number');
    $table->string('bank_name');
    $table->string('branch_code')->nullable();
    $table->string('swift_code');
    $table->string('iban')->nullable();
    $table->char('currency', 3)->default('USD'); // ISO currency codes
    $table->boolean('is_verified')->default(false);
    $table->timestamp('verified_at')->nullable();
    $table->timestamps();
    
    $table->unique(['provider_id', 'account_number']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_bank_details');
    }
};
