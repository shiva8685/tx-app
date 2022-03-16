<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::connection('mysql_telugu')->create('users_videos', function (Blueprint $table) {
        $table->bigIncrements('user_video_id');
        $table->string('video_token')->default("empty");
        $table->string('video_info',650)->default("empty");
        $table->string('video_file')->default("empty.mp4");
        $table->integer('video_size')->default(0);
        $table->integer('video_duration')->default(0);
        $table->integer('video_width')->default(0);
        $table->integer('video_height')->default(0);
        $table->integer('video_zoom')->default(0); // 0(no zoom), 1(zoom) shwoing video in exoplayer
        $table->bigInteger('user_id_fk')->unsigned(); 
        $table->bigInteger('video_id_fk')->default(0);
        $table->string('dueter_hash_id')->default("empty");
        $table->string('video_type')->default("normal"); //normal,duet,stitch
        $table->bigInteger('video_total_likes')->default(0);
        $table->bigInteger('video_total_comments')->default(0);
        $table->bigInteger('video_total_views')->default(0);
        $table->string('video_cover_photo')->default("empty.png");
        $table->string('video_visibility')->default("public");
        $table->string('video_msg_privacy_status')->default("public");
        $table->string('video_comments_privacy_status')->default("public");
        $table->string('whose_sound')->default("empty");
        $table->string('sound_holder_info',650)->default("e"); // e for empty-> storing original sound holder info -> userid,country,lang,photoName,tableId,videoName
        $table->string('sound_token')->default("e");
        $table->foreign('user_id_fk')->references('user_id')->on('tx_main_db.tx_users')->onDelete('cascade'); 
        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
   
    
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_telugu')->dropIfExists('users_videos');
    }
}
