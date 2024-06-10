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
        Schema::create('verfication_codes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('phone');
            $table->string('otp');
            $table->timestamp('expire_at')->nullable();
            $table->json('temp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verfication_codes');
    }
};
