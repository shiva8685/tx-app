<?php

namespace App;
use DB;
use App\Inbox;
use Config;
use App\TxDbs;
use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    //





public function addRemoveOneLike($request){
    $txDB = new TxDbs();
   
    $dbvideoHolderLikesTb =  $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_likes';
    
    $dbLikerTb =  $txDB->getDB($request->likerCountry,$request->likerLang).'.user_'.$request->likerIdFk.'_likes';
   
    $dbUsersTb = $txDB->getDB($request->country,$request->lang).'.tx_users';
    $dbVideosTb = $txDB->getDB($request->country,$request->lang).'.users_videos_'.$request->tbSerialId;

   
    $checkAlreadyLiked = DB::connection('mysql_'.$request->lang)->table('user_'.$request->userId.'_likes')
    ->where([
        ['tb_serial_id_fk', '=', $request->tbSerialId],
        ['user_video_id_fk', '=', $request->userVideoIdFk],
        ['user_id_fk', '=', $request->userId],
        ['liker_id_fk', '=', $request->likerIdFk]
        ])
    ->first();

if(!$checkAlreadyLiked){
    $res = DB::connection('mysql_'.$request->lang)->table('user_'.$request->userId.'_likes')->insert(
        array('liker_lang' => request('likerLang'),
            'liker_country' => request('likerCountry'),
            'tb_serial_id_fk' => request('tbSerialId'),
            'user_video_id_fk' => request('userVideoIdFk'),
            'user_id_fk' => request('userId'),
            'liker_id_fk' => request('likerIdFk')
           
            )
  );
  if($request->userId != $request->likerIdFk){
  $res =  DB::connection('mysql_'.$request->likerLang)->table('user_'.$request->likerIdFk.'_likes')->insert(
    array('liker_lang' => request('lang'),
        'liker_country' => request('country'),
        'tb_serial_id_fk' => request('tbSerialId'),
        'user_video_id_fk' => request('userVideoIdFk'),
        'user_id_fk' => request('userId'),
        'liker_id_fk' => request('likerIdFk')
       
        )
);
  }
  if($res > 0){
   
    $inbox = new Inbox();
    $inboxRes = $inbox->saveLikeInbox($request);

    DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_id', $request->userId)->increment('user_total_likes');
    DB::connection('mysql_'.$request->lang)->table('users_videos_'.$request->tbSerialId)->where('user_video_id', $request->userVideoIdFk)->increment('video_total_likes');

      return "liked";

  }else{
      return "not liked";
  }
}else{
    //already liked so remove like
   $res = DB::connection('mysql_'.$request->lang)->table('user_'.$request->userId.'_likes')->where([
        ['tb_serial_id_fk', '=', $request->tbSerialId],
        ['user_video_id_fk', '=', $request->userVideoIdFk],
        ['user_id_fk', '=', $request->userId],
        ['liker_id_fk', '=', $request->likerIdFk]
        ])->delete();
if($request->userId != $request->likerIdFk){
        $res = DB::connection('mysql_'.$request->likerLang)->table('user_'.$request->likerIdFk.'_likes')->where([
            ['tb_serial_id_fk', '=', $request->tbSerialId],
            ['user_video_id_fk', '=', $request->userVideoIdFk],
            ['user_id_fk', '=', $request->userId],
            ['liker_id_fk', '=', $request->likerIdFk]
            ])->delete();
        }

        if($res > 0){

            DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_id', $request->userId)->decrement('user_total_likes');
            DB::connection('mysql_'.$request->lang)->table('users_videos_'.$request->tbSerialId)->where('user_video_id', $request->userVideoIdFk)->decrement('video_total_likes');
       
            return "like removed";
        }

}



}



}













