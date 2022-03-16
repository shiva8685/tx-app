<?php

namespace App;
use App\TxUsers;
use App\TxVideosTables;
use App\TxSoundsTables;
use App\UsersSounds;
use App\Paths;
use DB;
use Config;
use App\TxDbs;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    //


    public function fetchTrendingSounds($request){
        $txDB = new TxDbs();

    $response = array();
    //first checking user session or auth
    
        $db = $txDB->getDB($request->country,$request->lang);
        $soundsDB = 'users_sounds_'.$request->tbId;
    
        $favList =  DB::connection('mysql_'.$request->lang)->table($soundsDB)
    ->skip($request->count)->take(Config::get('constants.LIMIT_10'))
    ->orderBy('sound_total_used', 'DESC')
    ->get();

return $favList;
        }


    //trending sounds with videos 
public function fetchTrendingSoundsWithVideos($request){
    $txDB = new TxDbs();

 $response = array();

 $db =  $txDB->getDB($request->country,$request->lang);
 $videosDB =  $db.".users_videos_".$request->tbId;
 $soundsDB = $db.'.users_sounds_'.$request->tbId;
 //$userDB = $db.'.tx_users';
 $dbLikes = $db.'.user_'.$request->userId.'_likes';

 $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
 ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
 ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')
 ->leftjoin($dbLikes.' as dbl',
 function ($join) {
     $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk');
 });  
 

 $res = $query->select(
                                  //  DB::RAW('DISTINCT(dbv.sound_token)'),
  'dbu.user_id',
  'dbu.user_hashtag_name',
  'dbu.user_name',
  'dbu.user_profile_image',
  'dbu.user_login_token',
  'dbu.user_country',
  'dbu.user_language',
 // 'dbu.user_total_likes',
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
  'dbv.video_cover_photo',
  'dbv.video_comments_privacy_status',
  'dbv.video_visibility',
  'dbv.web_link',
  'whose_sound',
  'dbs.sound_mp3',
  'dbs.sound_file',
  'dbs.sound_token',
  'dbs.sound_total_used',
  'dbv.created_at',
  'dbs.video_tb_id',
  'dbl.liker_id_fk'

     ) 
       ->where([
        ['dbv.whose_sound', '=', 'original']
        ])
     ->take(Config::get('constants.LIMIT_10'))
     ->orderBy('dbs.sound_total_used', 'DESC')
     ->get();
    

return $res;
}


public function fetchTrendingSoundsSelection($request){
    $txDB = new TxDbs();
    
    $response = array();

    $db =  $txDB->getDB($request->country,$request->lang);
    $videosDB =  $db.".users_videos_".$request->tbId;
    $soundsDB = $db.'.users_sounds_'.$request->tbId;
   // $userDB = $db.'.tx_users';
    $dbLikes = $db.'.user_'.$request->userId.'_likes';
   
    $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')
    ->leftjoin($dbLikes.' as dbl',
    function ($join) {
        $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk')
             ->on('dbu.user_id', '=', 'dbl.liker_id_fk');
            
    });  

    $res = $query->select(
     'dbu.user_id',
     'dbu.user_hashtag_name',
     'dbu.user_name',
     'dbu.user_profile_image',
     'dbu.user_login_token',
     'dbu.user_country',
     'dbu.user_language',
    // 'dbu.user_total_likes',
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
     'dbv.video_cover_photo',
     'dbv.video_comments_privacy_status',
     'dbv.video_visibility',
     'dbv.web_link',
     'whose_sound',
     'dbs.sound_mp3',
     'dbs.sound_file',
     'dbs.sound_token',
     'dbs.sound_total_used',
     'dbv.created_at',
     'dbs.video_tb_id',
     'dbl.liker_id_fk'
        )
        ->where([
            ['dbv.video_visibility', '=', 'public']
            ])
         ->skip($request->count)->take(Config::get('constants.LIMIT_10'))
        ->orderBy('dbs.sound_total_used', 'DESC')
        ->get();
       // $response[$i] = $res;//str_replace('[]','',json_encode($res));
      //  $i++;
   
   
   
   return $res;
    
}




public function fetchTrendingVideos($request){
    $txDB = new TxDbs();
    
    $response = array();

    $db =  $txDB->getDB($request->country,$request->lang);
    $videosDB =  $db.".users_videos_".$request->tbId;
    $soundsDB = $db.'.users_sounds_'.$request->tbId;
   // $userDB = $db.'.tx_users';
    $dbLikes = $db.'.user_'.$request->userId.'_likes';
   
    $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')
    ->leftjoin($dbLikes.' as dbl',
    function ($join) {
        $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk')
             ->on('dbu.user_id', '=', 'dbl.liker_id_fk');
            
    });  

    $res = $query->select(
     'dbu.user_id',
     'dbu.user_hashtag_name',
     'dbu.user_name',
     'dbu.user_profile_image',
     'dbu.user_login_token',
     'dbu.user_country',
     'dbu.user_language',
    // 'dbu.user_total_likes',
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
     'dbv.video_cover_photo',
     'dbv.video_comments_privacy_status',
     'dbv.video_visibility',
     'dbv.web_link',
     'whose_sound',
     'dbs.sound_mp3',
     'dbs.sound_file',
     'dbs.sound_token',
     'dbs.sound_total_used',
     'dbv.created_at',
     'dbs.video_tb_id',
     'dbl.liker_id_fk'
        )
       
        ->take(Config::get('constants.LIMIT_10'))
        ->orderBy('dbv.video_total_views', 'DESC')
        ->get();
       // $response[$i] = $res;//str_replace('[]','',json_encode($res));
      //  $i++;
   
   
   
   return $res;
    
}



public function fetchTrendingUsers($request){
    $txDB = new TxDbs();
    
    $response = array();

    $db = $txDB->getDB($request->country,$request->lang);
  //  $userDB = $db.'.tx_users';
 
    $res = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')->select(
        'user_id',
        'user_hashtag_name',
        'user_name',
        'user_login_id',
        'user_login_token',
        'gender',
        'user_dp_type',
        'user_profile_video',
        'about_user',
        'user_total_following',
        'user_total_followers',
        'user_total_likes',
        'total_videos',
        'user_profile_image',
        'user_country',
        'user_language',
        )
        ->take(Config::get('constants.LIMIT_10'))
        ->orderBy('user_total_likes', 'DESC')
        ->get();
       // $response[$i] = $res;//str_replace('[]','',json_encode($res));
      //  $i++;
   
   
   
   return $res;
    
}




public function getSearchUsers($request){

    $txDB = new TxDbs();
    
    $db =  $txDB->getDB($request->country,$request->lang);
    //$userDB = $db.'.tx_users';
 
    $res = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')->select(
        'user_id',
        'user_hashtag_name',
        'user_name',
        'user_login_id',
        'user_login_token',
        'gender',
        'user_dp_type',
        'user_profile_video',
        'about_user',
        'user_total_following',
        'user_total_followers',
        'user_total_likes',
        'total_videos',
        'user_profile_image',
        'user_country',
        'user_language',
        )
        ->take(Config::get('constants.LIMIT_10'))
        ->where('user_hashtag_name', 'like', '%' . $request->keywords . '%')
        ->orWhere('user_name', 'like', '%' . $request->keywords . '%')    
        ->get();

        return $res;

}

public function getSearchVideos($request){
    $txDB = new TxDbs();
    
    $db =  $txDB->getDB($request->country,$request->lang);
    $videosDB =  $db.".users_videos_".$request->tbId;
    $soundsDB = $db.'.users_sounds_'.$request->tbId;
    //$userDB =   $db.'.tx_users';
    if($request->userId == Config::get('constants.DUMMY_USERID')){
        $dbLikes = $db.'.user_'.$request->userId.'_likes';
    }else{
        $dbLikes = $db.'.anonymous_user_dummy_likes';
    }
   
   
    $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')
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
    // 'dbu.user_total_likes',
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
     'dbv.video_cover_photo',
     'dbv.video_comments_privacy_status',
     'dbv.video_visibility',
     'dbv.web_link',
     'whose_sound',
     'dbs.sound_mp3',
     'dbs.sound_file',
     'dbs.sound_token',
     'dbs.sound_total_used',
     'dbv.created_at',
     'dbs.video_tb_id',
     'dbl.liker_id_fk'
   
        )
    
        ->where('dbv.video_info', 'like', '%' . $request->keywords . '%')
        //->orWhere('dbs.sound_file', 'like', '%' . $request->keywords . '%')    
        ->orWhere('dbu.user_name', 'like', '%' . $request->keywords . '%')    
        ->skip($request->count)->take(Config::get('constants.LIMIT_10'))
        ->orderBy('dbv.user_video_id', 'DESC')
        ->get();
       // $response[$i] = $res;//str_replace('[]','',json_encode($res));
      //  $i++;
   
   
   
   return $res;


}

public function getSearchSounds($request){

    $txDB = new TxDbs();
    
    $db =  $txDB->getDB($request->country,$request->lang);
    $videosDB =  $db.".users_videos_".$request->tbId;
    $soundsDB = $db.'.users_sounds_'.$request->tbId;
    //$userDB = $db.'.tx_users';
    if($request->userId == Config::get('constants.DUMMY_USERID')){
        $dbLikes = $db.'.user_'.$request->userId.'_likes';
    }else{
        $dbLikes = $db.'.anonymous_user_dummy_likes';
    }
   
    $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')
    ->leftjoin($dbLikes.' as dbl',
    function ($join) {
        $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk');
    });  
    
   
    $res = $query->select(
                                     //  DB::RAW('DISTINCT(dbv.sound_token)'),
     'dbu.user_id',
     'dbu.user_hashtag_name',
     'dbu.user_name',
     'dbu.user_profile_image',
     'dbu.user_login_token',
     'dbu.user_country',
     'dbu.user_language',
    // 'dbu.user_total_likes',
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
     'dbv.video_cover_photo',
     'dbv.video_comments_privacy_status',
     'dbv.video_visibility',
     'dbv.web_link',
     'whose_sound',
     'dbs.sound_mp3',
     'dbs.sound_file',
     'dbs.sound_token',
     'dbs.sound_total_used',
     'dbv.created_at',
     'dbs.video_tb_id',
     'dbl.liker_id_fk'
   
        ) 
          ->where([
           ['dbv.whose_sound', '=', 'original'],
           ['dbs.sound_file', 'like', '%' . $request->keywords . '%']
           ])
        // ->orWhere('dbs.sound_file', 'like', '%' . $request->keywords . '%')
         ->skip($request->count)->take(Config::get('constants.LIMIT_10'))
        ->orderBy('dbs.sound_total_used', 'DESC')
        ->get();
       
return $res;


}


}
