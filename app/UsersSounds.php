<?php

namespace App;
use App\TxUsers;
use App\TxDbs;
use App\TxVideosTables;
use App\TxSoundsTables;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class UsersSounds extends Model
{
    //


    public function saveAudioSoundFile($request,$audio_name_mp3,$sound_file_info){

$tb = 'users_sounds_'.$request->tbId;
//store sound file in users_sounds_1 table

$soundToken = $request->tbId.'-'.date("Ymd").date("hism");

$usTBRes = DB::connection('mysql_'.$request->lang)->table($tb)->insert(
    array('sound_token' => $soundToken,
       // 'video_token' => $request->video_token, //removed deleted no need
        'sound_duration' => $request->soundDuration,
        'sound_mp3' => $audio_name_mp3,
        'sound_file' => $sound_file_info,
        'video_tb_id' => $request->tbId
       
        )
);

if($usTBRes > 0){
    $tbSounds = new TxSoundsTables();
    
   $tbSounds->updateTotalTableRows($request);
    return $soundToken;
}else{
    return "fail";
}

    }


public function updateThisSoundTotalUsedBy($request){

    $tb = 'users_sounds_'.$request->tbId;
    DB::connection('mysql_'.$request->lang)->table($tb)->where('sound_token', $request->sound_token)->increment('sound_total_used');//->update(array('total_used' => $totalRows));

    // suppose decrese one number user decrement(total_used);

}

public function checkupdateThisSoundTotalUsedBy(){

    $soundDB = Config::get('constants.DB_PREF')."tx_in_lang_telugu.users_sounds_1";//.$request->tbId;
    DB::table($soundDB)->where('sound_token', '1-2020090203450209')->increment('sound_total_used');//->update(array('total_used' => $totalRows));


}



public function getSearchSounds($request){
  
    $txDB = new TxDbs();
    
    $db =  $txDB->getDB($request->country,$request->lang);
    $videosDB =  $db.".users_videos_".$request->tbId;
    $soundsDB = $db.'.users_sounds_'.$request->tbId;
   
    $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token');
   
    $res = $query->select(
        'dbs.user_sound_id',
        'dbs.sound_token',
        'dbs.sound_duration',
        'dbs.sound_mp3',
        'dbs.sound_file',
        'dbs.video_tb_id',
        'dbs.sound_total_used'
   
        ) 
          ->where([
           ['dbv.video_info', 'like', '%' . $request->keywords . '%']
           ])
         //->orWhere('dbs.sound_file', 'like', '%' . $request->keywords . '%')
         ->skip($request->count)->take(Config::get('constants.LIMIT_10'))
        // ->orderBy('dbs.sound_total_used', 'DESC')
        ->get();
       
return $res;

  }


} //class
