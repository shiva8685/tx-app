<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersSounds1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_telugu')->create('users_sounds_1', function (Blueprint $table) {
            $table->bigIncrements('user_sound_id');
            $table->string('sound_token',300)->default("e");
           // $table->string('video_token',191)->default("e"); //removed
            $table->integer('sound_duration',10)->default(0);
            $table->string('sound_mp3',191)->default("e");
            $table->string('sound_file',600)->default("e");
            $table->integer('video_tb_id')->default(0);
            $table->bigInteger('total_used')->default(0);
            $table->timestamp('created_at')->useCurrent();//->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_sounds_1');
    }
}
