<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_name', 255);
            $table->bigInteger('buyer_phone');
            $table->string('buyer', 255);
            $table->integer('amount');
            $table->string('currency', 255);
            $table->integer('fees');
            $table->string('shorturl', 255);
            $table->string('payment_id', 255);
            $table->string('payment_request_id', 255);
            $table->string('purpose', 255);
            $table->string('status', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment');
    }
};
