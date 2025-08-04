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
Schema::create('insurance_categories', function (Blueprint $table) {
    $table->id('category_id');
    $table->string('name');
    $table->text('description');
    $table->string('icon_url')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->unique('name');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_categories');
    }
};
