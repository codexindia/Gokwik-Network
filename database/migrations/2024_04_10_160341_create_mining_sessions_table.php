<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mining_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->bigInteger('user_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('coin',10,4);
            $table->enum('status',['running','closed','cancelled'])->default('running');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mining_sessions');
    }
};
