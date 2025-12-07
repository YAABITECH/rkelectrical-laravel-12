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
        Schema::create('practice_subtopics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('topic_id');
            $table->string('ulink')->nullable();
            $table->integer('priority')->default(0);
            $table->string('mtit');
            $table->text('mdes');
            $table->string('image')->nullable();
            $table->string('ogimage')->nullable();
            $table->enum('status', ['hidden', 'waiting', 'public'])->default('hidden');
            $table->date('launch_datetime')->nullable();
            $table->timestamps();

            $table->foreign('topic_id')->references('id')->on('practice_topics')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('practice_subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_subtopics');
    }
};
