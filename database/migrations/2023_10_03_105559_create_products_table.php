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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('licence_id')->references('id')->on('licences')->onDelete('cascade');
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('excerpt');
            $table->text('body');
            $table->integer('stock')->nullable();
            $table->float('price')->nullable();
            $table->float('sale_price')->nullable();
            $table->enum('status', ['Published', 'Draft'])->default('Draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_downloadable')->default(true);
            $table->boolean('is_free')->default(true);
            $table->boolean('is_printable')->default(true);
            $table->boolean('is_parametric')->default(false);
            $table->string('related_parametric')->nullable();
            $table->unsignedInteger('downloads')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
