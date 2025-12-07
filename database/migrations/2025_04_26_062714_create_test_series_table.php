<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('head_id')->nullable()->index();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->boolean('is_parent')->default(false)->index();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('url_slug')->nullable();
            $table->string('ulink')->unique()->nullable();
            $table->string('image')->nullable();
            $table->integer('test_count')->default(0);
            $table->boolean('is_paid')->default(false);
            $table->integer('fees')->default(0);
            $table->boolean('is_index')->default(false);
            $table->string('mtit')->nullable();
            $table->text('mdes')->nullable();
            $table->string('ogimage')->nullable();
            $table->integer('priority')->default(0);
            $table->enum('status', ['draft', 'upcoming', 'active', 'archive'])->default('draft');
            $table->boolean('featured')->default(false);
            $table->dateTime('launch_at')->nullable();
            $table->dateTime('expire_at')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('test_series')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_series');
    }
};
