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
        Schema::create('site_setting_social_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_setting_id')->constrained()->onDelete('cascade');
            $table->foreignId('social_media_id')->unique->constrained()->onDelete('cascade');
            $table->string('url')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_setting_social_media');
    }
};
