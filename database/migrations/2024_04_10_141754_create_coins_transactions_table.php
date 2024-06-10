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
        Schema::create('coins_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->decimal('coin',10,4);
            $table->enum('transaction_type',['debit','credit','refund']);
            $table->string('description')->nullable();
            $table->string('transaction_id')->unique();
            $table->enum('status',['success','failed','pending']);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coins_transactions');
    }
};
