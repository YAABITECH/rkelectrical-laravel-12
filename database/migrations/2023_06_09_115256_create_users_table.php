<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('ccode')->nullable();
            $table->string('phone')->nullable();
            $table->string('w_ccode')->nullable();
            $table->string('w_phone')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['Male', 'Female','Others'])->nullable();
            $table->string('clgname')->nullable();
            $table->string('course')->nullable();
            $table->date('year')->nullable();
            $table->string('degree')->nullable();
            $table->string('department')->nullable();
            $table->string('otp')->nullable();
            $table->string('salt')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
