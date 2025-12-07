<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('series_id')->index();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->text('instruction')->nullable();
            $table->integer('total_questions')->default(0);
            $table->integer('marks')->default(0);
            $table->integer('duration')->default(0);
            $table->integer('attempt_limit')->default(1);
            $table->enum('difficulty', ['easy', 'medium', 'hard', 'mixed'])->nullable();
            $table->integer('priority')->default(0);
            $table->enum('status', ['draft', 'upcoming', 'active', 'archive'])->default('draft');
            $table->boolean('is_demo')->default(false);
            $table->boolean('has_negative_marks')->default(false);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();

            $table->foreign('series_id')->references('id')->on('test_series')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_exams');
    }
};
