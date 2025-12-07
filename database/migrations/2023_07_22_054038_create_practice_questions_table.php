<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practice_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('subtopic_id');
            $table->text('question');
            $table->text('option1')->nullable();
            $table->text('option2')->nullable();
            $table->text('option3')->nullable();
            $table->text('option4')->nullable();
            $table->enum('question_type', ['Choice', 'Multi-choice', 'Numerical']);
            $table->string('answer1');
            $table->string('answer2')->nullable();
            $table->integer('mark');
            $table->integer('duration')->default(0);
            $table->integer('priority')->default(0);
            $table->enum('status', ['hidden', 'public'])->default('hidden');
            $table->text('solution')->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('practice_subjects')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('practice_topics')->onDelete('cascade');
            $table->foreign('subtopic_id')->references('id')->on('practice_subtopics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_questions');
    }
};
