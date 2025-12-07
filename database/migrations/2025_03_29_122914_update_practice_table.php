<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->text('right_answer_question_ids')->nullable()->after('right_answer');
            $table->text('wrong_answer_question_ids')->nullable()->after('wrong_answer');
        });
    }

    public function down()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->dropColumn(['right_answer_question_ids', 'wrong_answer_question_ids']);
        });
    }
};
