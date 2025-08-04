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
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id('log_id');
    $table->foreignUuid('user_id')->nullable()->constrained('users', 'user_id');
    $table->string('action');
    $table->string('entity_type');
    $table->unsignedBigInteger('entity_id');
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();
    $table->string('ip_address', 45);
    $table->text('user_agent')->nullable();
    $table->timestamps();
    
    $table->index(['entity_type', 'entity_id']);
    $table->index(['user_id', 'created_at']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
