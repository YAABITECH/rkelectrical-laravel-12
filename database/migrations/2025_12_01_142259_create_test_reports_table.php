<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_reports', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('exam_id')->index();
            $table->boolean('done')->default(false);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->integer('attempt')->default(1);

            $table->integer('total_questions');
            $table->integer('total_duration');
            $table->integer('total_marks');
            $table->integer('attended')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('positive_marks')->nullable();
            $table->integer('negative_marks')->nullable();
            $table->integer('marks')->nullable();
            $table->json('meta_data')->nullable();

            $table->foreign('exam_id')->references('id')->on('test_exams')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_reports');
    }
};
