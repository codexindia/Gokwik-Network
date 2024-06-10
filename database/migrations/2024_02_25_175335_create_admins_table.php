<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('avatar')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('status')->nullable();

            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::table('admins')->insert([
            'name' => 'Masth Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345654'),
        ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
