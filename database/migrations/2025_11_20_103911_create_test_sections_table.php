<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_id')->index();
            $table->string('name');
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->foreign('test_id')->references('id')->on('test_exams')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('test_sections');
    }
};
