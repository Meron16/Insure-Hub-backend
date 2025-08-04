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
Schema::create('sent_emails', function (Blueprint $table) {
    $table->id('email_id');
    $table->foreignId('template_id')->constrained('email_templates', 'template_id');
    $table->string('recipient_email');
    $table->string('subject');
    $table->text('body');
    $table->enum('status', ['sent', 'delivered', 'failed']);
    $table->text('error_message')->nullable();
    $table->timestamps();
    
    $table->index(['recipient_email', 'created_at']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_emails');
    }
};
