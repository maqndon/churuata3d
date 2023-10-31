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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->enum('imageable_type', ['App\\\Models\\\Post', 'App\\\Models\\\Product'])->default('App\\\Models\\\Product');
            $table->unsignedBigInteger('imageable_id');
            $table->string('images_names');
            $table->string('metadata');
            $table->timestamps();

            $table->index(['imageable_type', 'imageable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
