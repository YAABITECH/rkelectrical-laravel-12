<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('ulink')->unique();
            $table->text('mtit');
            $table->text('mdes');
            $table->string('image')->nullable();
            $table->string('ogimage')->nullable();
            $table->date('post_date')->nullable();
            $table->string('author')->nullable();
            $table->enum('status', ['active', 'draft', 'archive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
