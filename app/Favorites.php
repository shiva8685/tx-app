<?php

namespace App;
use DB;
use Config;
use App\UsersVideos;
use App\TxVideosTables;
use App\TxAnonymousUsers;
use App\TxUsers;
use App\UsersSounds;
use App\TxDbs;
use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    //


public function addFavoriteItem($request){

    $favDB = "user_".$request->userId."_favorites";

    $res =  DB::connection('mysql_'.$request->lang)->table($favDB)->insert(
        array('fav_lang' => request('favLang'),
            'fav_country' => request('favCountry'),
            'fav_type' => request('favType'),
            'tb_serial_id_fk' => request('tbId'),
            'fav_token_id' => request('favTokenId')
   
            )
  );

return $res;
    
}


public function deleteFavSound($request){
  $favDB = "user_".$request->userId."_favorites";

  $res = DB::connection('mysql_'.$request->lang)->table($favDB)->where([
    ['fav_id', '=', $request->favId]
    ])->delete();


return $res;
  

}

public function deleteFavVideo($request){
  $favDB = "user_".$request->userId."_favorites";

  $res = DB::connection('mysql_'.$request->lang)->table($favDB)->where([
    ['fav_lang', '=', $request->favLang],
    ['fav_country', '=', $request->favCountry],
    ['fav_type', '=', "2"],
    ['tb_serial_id_fk', '=', $request->tbId],
    ['fav_token_id', '=', $request->favTokenId],
    ])->delete();


return $res;
  

}






public function getFavoriteSongs($request){
  $txDB = new TxDbs();
    $response = array();
    //first checking user session or auth
    
      
        $dbFav = 'user_'.$request->userId.'_favorites';
    
        $favList =  DB::connection('mysql_'.$request->lang)->table($dbFav)
        ->where([
         ['fav_type', '=', $request->favType]
       ])
    ->skip($request->count)->take(Config::get('constants.VIDEOS_LIMIT_ONSCROLL'))
    ->orderBy('fav_id', 'DESC')
    ->get();


//return $favList;

if ($favList->count() > 0){
                      
    $i = 0;
    foreach($favList as $fav){

     $db =  $txDB->getDB($fav->fav_country,$fav->fav_lang);
    // $dbVideos =  $db.".users_videos_".$fav->tb_id;
     $soundsDB = $txDB->getDB($fav->fav_country,$fav->fav_lang).'.users_sounds_'.$fav->tb_serial_id_fk;

     $query = DB::connection('mysql_'.$fav->fav_lang)->table($dbFav.' as dbf')
     ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbf.fav_token_id');
  
     $res = $query->select([
        'dbf.fav_id',
        'dbs.sound_token',
        'dbs.sound_duration',
        'dbs.sound_mp3',
        'dbs.sound_file',
        'dbs.video_tb_id',
        'dbs.sound_total_used'
         ])
         ->where([
              ['dbs.sound_token', '=', $fav->fav_token_id]
            ])
         ->orderBy('dbf.fav_id', 'DESC')
         ->first();
         $response[$i] = $res;//str_replace('[]','',json_encode($res));
         $i++;
    } // foreach
   
  }
  return $response;
}


public function getFavoriteVideos($request){
  $txDB = new TxDbs();
  $response = array();
  //first checking user session or auth
  
      $db = $txDB->getDB($request->country,$request->lang);
      $dbFav = 'user_'.$request->userId.'_favorites';
  
      $favList =  DB::connection('mysql_'.$request->lang)->table($dbFav)
      ->where([
       ['fav_type', '=', $request->favType]
     ])
  ->skip($request->count)->take(Config::get('constants.VIDEOS_LIMIT_ONSCROLL'))
  ->orderBy('fav_id', 'DESC')
  ->get();


//return $favList;

if ($favList->count() > 0){
                    
  $i = 0;
  foreach($favList as $fav){

   $db = $txDB->getDB($fav->fav_country,$fav->fav_lang);
   $videosDB =  $db.".users_videos_".$fav->tb_serial_id_fk;
   $soundsDB = $db.'.users_sounds_'.$fav->tb_serial_id_fk;
   $userDB = $db.'.tx_users';
   $dbLikes = $db.'.user_'.$request->userId.'_likes';

   $query = DB::connection('mysql_'.$fav->fav_lang)->table($dbFav.' as dbf')
   ->join($videosDB.' as dbv', 'dbv.user_video_id', 'dbf.fav_token_id')
   ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')
   ->join($userDB.' as dbu', 'dbu.user_id', 'dbv.user_id_fk')
   ->leftjoin($dbLikes.' as dbl',
   function ($join) {
       $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk');
   });  
   

   $res = $query->select(
   
   
    'dbu.user_id',
        'dbu.user_hashtag_name',
        'dbu.user_name',
        'dbu.user_profile_image',
        'dbu.user_login_token',
        'dbu.user_country',
        'dbu.user_language',
        'dbv.user_video_id',
        'dbv.video_token',
        'dbv.video_info',
        'dbv.video_file',
        'dbv.video_size',
        'dbv.video_duration',
        'dbv.video_width',
        'dbv.video_height',
        'dbv.video_zoom',
        'dbv.video_id_fk',
        'dbv.dueter_hash_id',
        'dbv.video_type',
        'dbv.video_total_likes',
        'dbv.video_total_comments',
        'dbv.video_total_views',
        'dbv.video_total_shares',
        'dbv.video_cover_photo',
        'dbv.video_comments_privacy_status',
        'dbv.video_visibility',
        'dbv.web_link',
        'dbv.whose_sound',
        'dbs.sound_mp3',
        'dbs.sound_file',
        'dbs.sound_token',
        'dbs.sound_total_used',
        'dbv.created_at',
        'dbf.tb_serial_id_fk',
        'dbl.liker_id_fk',
       )
       ->where([
        ['dbv.user_video_id', '=', $fav->fav_token_id]
      ])
       ->orderBy('dbf.fav_id', 'DESC')
       ->first();
       $response[$i] = $res;//str_replace('[]','',json_encode($res));
       $i++;
  } // foreach
 
}
return $response;
}








}
