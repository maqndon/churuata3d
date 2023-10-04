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
        Schema::create('print_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('print_strength', ['hollow', 'low', 'medium', 'high', 'solid'])->default('low');
            $table->decimal('resolution')->default(0.2);
            $table->tinyInteger('infill')->unsigned()->constraint('infill', '<=', 100);
            $table->tinyInteger('top_layers')->unsigned();
            $table->tinyInteger('bottom_layers')->unsigned();
            $table->tinyInteger('walls')->unsigned();
            $table->tinyInteger('speed')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_settings');
    }
};
