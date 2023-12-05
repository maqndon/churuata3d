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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->references('id')->on('users');
            $table->string('title');
            $table->text('excerpt');
            $table->text('body');
            $table->string('slug')->unique();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['Published', 'Draft', 'Pending'])->default('Draft');
            $table->foreignId('related_product')->nullable()->references('id')->on('products');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
