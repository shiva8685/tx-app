<?php

namespace App;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class TxDbs extends Model
{
    //Table name
protected $table = 'txdbs';


public function getAllDbNames(){
    return DB::connection('mysql')->table('txdbs')->select('db_name','db_conn')->get();
}


public function getDB($country,$lang){
   // return Config::get('constants.DB_PREF').'tx_'.$country.'_lang_'.$lang; //localhost
    return Config::get('constants.DB_PREF').$lang; //live
}

public function getMainDB(){
    return Config::get('constants.DB_PREF').'tx_main_db';
}



}
