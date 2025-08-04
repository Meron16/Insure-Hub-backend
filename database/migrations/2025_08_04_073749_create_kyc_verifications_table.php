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
Schema::create('kyc_verifications', function (Blueprint $table) {
    $table->id('kyc_id');
    $table->foreignUuid('user_id')->constrained('users', 'user_id');
    $table->enum('document_type', ['passport', 'national_id', 'driver_license']);
    $table->string('document_number');
    $table->string('front_image_url');
    $table->string('back_image_url')->nullable();
    $table->string('selfie_with_doc_url');
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->text('rejection_reason')->nullable();
    $table->foreignUuid('verified_by')->nullable()->constrained('users', 'user_id');
    $table->timestamp('verified_at')->nullable();
    $table->date('expiry_date')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_verifications');
    }
};
