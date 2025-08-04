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
Schema::create('system_settings', function (Blueprint $table) {
    $table->id('setting_id');
    $table->string('key')->unique();
    $table->text('value');
    $table->text('description')->nullable();
    $table->foreignUuid('updated_by')->nullable()->constrained('users', 'user_id');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
