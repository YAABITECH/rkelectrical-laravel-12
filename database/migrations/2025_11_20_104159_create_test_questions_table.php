<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->longText('question');
            $table->json('options')->nullable();
            $table->enum('question_type', ['Choice', 'Multi-choice', 'Numerical']);
            $table->string('answer')->nullable();
            $table->string('answer2')->nullable();
            $table->decimal('mark', 8, 2)->default(1);
            $table->decimal('negative_mark', 8, 2)->default(0);
            $table->integer('duration')->default(0);
            $table->integer('priority');
            $table->longText('solution')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable();
            $table->timestamps();

            $table->unique(['exam_id','priority']);
            $table->foreign('exam_id')->references('id')->on('test_exams')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('test_sections')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('test_questions');
    }
};
