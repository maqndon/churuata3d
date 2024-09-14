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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->enum('commentable_type', ['App\\\Models\\\Post', 'App\\\Models\\\Product'])->default('App\\\Models\\\Product');
            $table->unsignedBigInteger('commentable_id');
            $table->text('comment');
            $table->timestamps();
            $table->unique(['tagable_type', 'tagable_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
