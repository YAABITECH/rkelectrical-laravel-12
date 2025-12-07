<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('subname', 64);
            $table->string('fees', 8);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('course_pack');
    }
};
