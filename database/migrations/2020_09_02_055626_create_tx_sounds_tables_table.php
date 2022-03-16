<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxSoundsTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_sounds_tables', function (Blueprint $table) {
            $table->bigIncrements('tb_id');
            $table->bigInteger('tb_serial_id')->default(0);
            $table->string('tb_name')->default("empty");
            $table->string('tb_country')->default("empty");
            $table->string('tb_lang')->default("empty");
            $table->integer('tb_storage_status')->default(0); //0 means table having empty space and 1 means table is full no space for insert records
            $table->string('tb_active_status')->default("healthy"); 
            $table->bigInteger('tb_total_rows')->default(0);
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
        Schema::dropIfExists('tx_sounds_tables');
    }
}
