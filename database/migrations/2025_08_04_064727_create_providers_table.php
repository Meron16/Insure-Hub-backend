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
Schema::create('providers', function (Blueprint $table) {
    $table->id('provider_id');
    $table->foreignUuid('user_id')->constrained('users', 'user_id');
    $table->string('company_name');
    $table->string('license_number');
    $table->string('website_url')->nullable();
    $table->string('contact_person');
    $table->string('contact_email');
    $table->string('contact_phone');
    $table->boolean('is_approved')->default(false);
    $table->timestamp('approval_date')->nullable();
    $table->decimal('rating', 3, 2)->nullable();
    $table->integer('years_in_business')->nullable();
    $table->text('description')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
