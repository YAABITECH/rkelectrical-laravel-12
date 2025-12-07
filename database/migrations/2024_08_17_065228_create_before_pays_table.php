<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('before_pays', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('course_id')->nullable();
            $table->string('coursepack_id')->nullable();
            $table->string('test_id')->nullable();
            $table->string('amount', 8);;
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('before_pays');
    }
};
