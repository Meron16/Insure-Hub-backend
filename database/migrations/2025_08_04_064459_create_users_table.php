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
Schema::create('users', function (Blueprint $table) {
    $table->uuid('user_id')->primary();
    $table->string('full_name');
    $table->string('email')->unique();
    $table->string('phone_number')->unique();
    $table->string('password_hash');
    $table->string('confirm_password');
    $table->enum('role', ['admin', 'provider', 'user'])->default('user');
    $table->enum('account_status', ['pending_kyc', 'active', 'suspended', 'rejected'])->default('pending_kyc');
    $table->timestamp('last_login')->nullable();
    $table->string('password_reset_token')->nullable();
    $table->timestamp('password_reset_expires')->nullable();
    $table->boolean('two_factor_enabled')->default(false);
    $table->string('profile_picture_url')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
