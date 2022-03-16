<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_users', function (Blueprint $table) {
        
        
        
            $table->bigIncrements('user_id');
            $table->string('user_hashtag_name')->default("empty");
            $table->string('user_name')->default("empty");
            $table->string('user_login_id')->default("empty");
            $table->string('user_password')->default("empty");
            $table->string('user_login_token')->default("empty");
            $table->string('gender')->default("empty");
            $table->string('login_provider_uid')->default("empty");
            $table->string('login_provider')->default("empty");
            $table->string('login_type')->default("empty");
            $table->string('user_profile_image')->default("empty");
            $table->string('user_profile_video')->default("empty");
            $table->string('user_dp_type')->default("photo");
            $table->string('about_user')->default("empty");
            $table->string('user_instagram_link')->default("empty");
            $table->string('user_youtube_link')->default("empty");
            $table->string('user_twitter_link')->default("empty");
            $table->string('user_fb_link')->default("empty");
            $table->string('user_country')->default("empty");
            $table->string('user_language')->default("empty");
            $table->bigInteger('total_videos')->default(0);
            $table->bigInteger('user_total_likes')->default(0);
            $table->bigInteger('user_total_followers')->default(0);
            $table->bigInteger('user_total_following')->default(0);
            $table->string('user_firebase_token')->default("empty");
            $table->string('user_dob');
            $table->Integer('account_status')->default(0);   // 0 for public account and 1 for suspended and 2 for deleted(block)
            $table->Integer('private_account')->default(0);   // 0 for private account disable and 1 for private account enable
            $table->Integer('find_me')->default(1);   // 1 for enabled and 0 for disabled
            $table->Integer('post_comments')->default(0);   // 0 for everyone, 1 for Followers, 2 for Friends
            $table->Integer('duet_with')->default(0);   // 0 for everyone, 1 for Followers, 2 for Friends
            $table->Integer('react_to_me')->default(0);   // 0 for everyone, 1 for Followers, 2 for Friends
            $table->Integer('messages_me')->default(0);   // 0 for everyone, 1 for Followers, 2 for Friends
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
        Schema::dropIfExists('tx_users');
    }
}
