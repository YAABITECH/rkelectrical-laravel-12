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
        Schema::create('practices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->unsignedBigInteger('subtopic_id')->nullable();
            $table->integer('attended')->default(0);
            $table->integer('answered')->default(0);
            $table->integer('right_answer')->default(0);
            $table->integer('wrong_answer')->default(0);
            $table->integer('duration')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('practices');
    }
};
