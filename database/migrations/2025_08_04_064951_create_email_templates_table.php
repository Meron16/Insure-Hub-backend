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
Schema::create('email_templates', function (Blueprint $table) {
    $table->id('template_id');
    $table->string('name');
    $table->string('subject');
    $table->text('body');
    $table->json('variables'); // ['first_name', 'policy_number', etc.]
    $table->timestamps();
    
    $table->unique('name');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
