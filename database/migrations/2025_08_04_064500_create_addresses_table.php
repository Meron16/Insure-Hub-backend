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
Schema::create('addresses', function (Blueprint $table) {
    $table->id('address_id');
    $table->foreignUuid('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
    $table->enum('address_type', ['home', 'work', 'billing']);
    $table->string('street_address');
    $table->string('city');
    $table->string('state_province');
    $table->string('postal_code');
    $table->string('country', 2); // ISO country codes
    $table->boolean('is_default')->default(false);
    $table->timestamps();
    
    $table->index(['user_id', 'address_type']);
});
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
