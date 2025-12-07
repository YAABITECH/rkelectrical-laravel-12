<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username', 32)->unique();
            $table->string('password');
            $table->string('name', 64);
            $table->string('admin_hash');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::table('admins')->insert([
            'username' => 'admin',
            'password' => Hash::make('PSLQcvb45@SDK'),
            'name' => 'Admin',
            'admin_hash' => Str::random(32),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
