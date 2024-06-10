<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('popup_banners', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('button_text');
            $table->string('action_link');
            $table->enum('visibility',[0,1]);
            $table->timestamps();
        });
        DB::table('popup_banners')->insert([
            [
                
                "image" => "not_set",
                "button_text" => "button_text",
                "action_link" => "action_link",
                "visibility" => "0",
              ],
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popup_banners');
    }

};
