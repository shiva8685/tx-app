<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class TxVideosTables extends Model
{
    //Table name
protected $table = 'tx_videos_tables';



public function getActiveTableId($request){
    
    $tb = DB::connection('mysql')->table('tx_videos_tables')->select('tb_serial_id','tb_name','tb_total_rows')
    ->where([
      ['tb_storage_status', '=', 0],
      ['tb_country', '=', $request->country],
      ['tb_lang', '=', $request->lang],
      ['tb_active_status', '=', 'active']
    ])->take(1)->first();

return $tb;

}



public function storeTables($tbName,$tbCountry,$tbLang){

    $dbName = 'mysql';
    $txtbs = new TxVideosTables;
    $txtbs->setConnection($dbName);

    $txtbs->tb_name = $tbName;
    $txtbs->tb_country = $tbCountry;
    $txtbs->tb_lang = $tbLang;
    if($txtbs->save()){
        return "success";
    }else{
        return "fail";
    }


}


public function updateTotalTableRows($request){

    //DB::connection('mysql')->table('tx_videos_tables')->where('tb_serial_id', $tbid)->update(array('tb_total_rows' => $totalRows));
    DB::connection('mysql')->table('tx_videos_tables')->where(['tb_serial_id'=> $request->tbId,'tb_lang'=> $request->lang])->increment('tb_total_rows');

}





}
