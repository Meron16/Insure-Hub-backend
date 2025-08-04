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
Schema::create('provider_documents', function (Blueprint $table) {
    $table->id('document_id');
    $table->foreignId('provider_id')->constrained('providers', 'provider_id')->cascadeOnDelete();
    $table->enum('document_type', ['license', 'tax_certificate', 'incorporation_doc', 'proof_of_address']);
    $table->string('file_url');
    $table->timestamp('uploaded_at')->useCurrent();
    $table->date('expiry_date')->nullable();
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->timestamps();
    
    $table->index(['provider_id', 'document_type']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_documents');
    }
};
