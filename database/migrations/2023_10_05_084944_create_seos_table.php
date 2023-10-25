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
        Schema::create('seos', function (Blueprint $table) {
            $table->id();
            $table->enum('seoable_type', ['App\\\Models\\\Post', 'App\\\Models\\\Product'])->default('App\\\Models\\\Product');
            $table->unsignedBigInteger('seoable_id');
            $table->string('title');
            $table->string('meta_description');
            $table->string('meta_keywords');
            $table->timestamps();

            $table->index(['seoable_type', 'seoable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seos');
    }
};
