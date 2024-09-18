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
        Schema::create('printing_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('nozzle_size')->default(0.4);
            $table->tinyInteger('min_hot_bed_temp')->unsigned();
            $table->tinyInteger('max_hot_bed_temp')->unsigned();
            $table->timestamps();
            $table->unique(['name', 'nozzle_size']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printing_materials');
    }
};
