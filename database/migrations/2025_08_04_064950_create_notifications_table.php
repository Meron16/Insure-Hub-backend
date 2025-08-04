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
Schema::create('notifications', function (Blueprint $table) {
    $table->id('notification_id');
    $table->foreignUuid('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
    $table->string('title');
    $table->text('message');
    $table->enum('type', ['policy_approved', 'claim_update', 'payment_reminder', 'kyc_status', 'system_alert']);
    $table->boolean('is_read')->default(false);
    $table->enum('related_entity_type', ['policy', 'claim', 'payment', 'user'])->nullable();
    $table->unsignedBigInteger('related_entity_id')->nullable();
    $table->string('action_url')->nullable();
    $table->timestamps();
    
    $table->index(['user_id', 'is_read']);
    $table->index(['related_entity_type', 'related_entity_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
