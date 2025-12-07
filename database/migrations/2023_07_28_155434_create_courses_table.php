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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('photo')->default('zdefault.jpg');
            $table->string('preview')->nullable();
            $table->longText('description');
            $table->integer('fees');
            $table->string('coursedur');
            $table->string('classdur');
            $table->string('metatitle');
            $table->text('metadesc');
            $table->string('url');
            $table->unsignedBigInteger('course_pack');
            $table->timestamps();

            $table->foreign('course_pack')->references('id')->on('course_packs');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
