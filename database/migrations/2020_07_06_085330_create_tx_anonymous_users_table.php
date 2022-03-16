<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxAnonymousUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_anonymous_users', function (Blueprint $table) {
            $table->bigIncrements('tx_anonymous_id');
            $table->string('anonumous_user')->default("empty");
            $table->string('anonumous_user_country')->default("empty");
            $table->string('anonumous_user_firebase_token')->default("empty");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tx_anonymous_users');
    }
}
