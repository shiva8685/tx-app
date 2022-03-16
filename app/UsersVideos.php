<?php

namespace App;
use App\TxUsers;
use App\TxVideosTables;
use App\TxSoundsTables;
use App\UsersSounds;
use App\Paths;
use App\TxDbs;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class UsersVideos extends Model
{
  //Table name
protected $table = 'users_videos';


public function getForYouVideos($request,$viewerId){

    $txDB = new TxDbs();

        $videosDB = $txDB->getDB($request->country,$request->lang).'.users_videos_'.$request->tbSerialId;
        $soundsDB = $txDB->getDB($request->country,$request->lang).'.users_sounds_'.$request->tbSerialId;
      if($viewerId == 'dummyid'){
        $followDB = $txDB->getDB($request->country,$request->lang).'.user_dummy_follow_unfollow_friends';
      }else{
        $followDB = $txDB->getDB($request->country,$request->lang).'.user_'.$viewerId.'_follow_unfollow_friends';

    }
       
        
        $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
        ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')    
        ->join($soundsDB.' as dbs','dbs.sound_token', 'dbv.sound_token')
        ->leftjoin($followDB.' as dbf', 'dbf.following_user_id_fk', 'dbu.user_id');

        $output = $query->select(
        'dbu.user_id',
        'dbu.user_hashtag_name',
        'dbu.user_name',
        'dbu.user_profile_image',
        'dbu.user_login_token',
        'dbu.user_country',
        'dbu.user_language',
        'dbu.user_total_likes',
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
        'whose_sound',
        'dbs.sound_mp3',
        'dbs.sound_file',
        'dbs.sound_token',
        'dbs.sound_total_used',
        'dbv.created_at',
        'dbf.following_user_id_fk'
        //DB::raw("(SELECT GROUP_CONCAT(user_video_id_fk) from $db.'.user_124_likes')")
      //  DB::table($db.'.user_'.dbu.user_id.'_likes')->select('user_video_id_fk')->first();
        )
    
        ->where([
        ['dbv.video_visibility', '=', 'public'],
        ['dbv.video_file', '!=', 'empty.mp4']
        ])
       // ->skip(14)->take(1) 
        ->skip($request->count)->take(Config::get('constants.LIMIT_5'))
        ->orderBy('dbv.user_video_id', 'DESC')
        ->inRandomOrder()
        ->get();
        
        return $output;
    
    
    }
    


 public function getForllowingVideos($request){

        $txDB = new TxDbs();
    
            $videosDB = $txDB->getDB($request->country,$request->lang).'.users_videos_'.$request->tbSerialId;
            $soundsDB = $txDB->getDB($request->country,$request->lang).'.users_sounds_'.$request->tbSerialId;
         
     $followDB = $txDB->getDB($request->country,$request->lang).'.user_'.$request->uid.'_follow_unfollow_friends';
      
            
            $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
            ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')    
            ->join($soundsDB.' as dbs','dbs.sound_token', 'dbv.sound_token')
            ->join($followDB.' as dbf', 'dbf.following_user_id_fk', 'dbu.user_id');
    
            $output = $query->select(
            'dbu.user_id',
            'dbu.user_hashtag_name',
            'dbu.user_name',
            'dbu.user_profile_image',
            'dbu.user_login_token',
            'dbu.user_country',
            'dbu.user_language',
            'dbu.user_total_likes',
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
            'whose_sound',
            'dbs.sound_mp3',
            'dbs.sound_file',
            'dbs.sound_token',
            'dbs.sound_total_used',
            'dbv.created_at',
            'dbf.following_user_id_fk'
            //DB::raw("(SELECT GROUP_CONCAT(user_video_id_fk) from $db.'.user_124_likes')")
          //  DB::table($db.'.user_'.dbu.user_id.'_likes')->select('user_video_id_fk')->first();
            )
        
            ->where([
            ['dbv.video_visibility', '=', 'public'],
            ['dbv.video_file', '!=', 'empty.mp4']
            ])
              ->skip($request->count)->take(Config::get('constants.LIMIT_5'))
           //->orderBy('dbv.user_video_id', 'DESC')
           ->inRandomOrder()
           //->distinct()
           ->get();
            
            return $output;
        
        
        }








    
public function getForllowingVideos1($request){
    

    $txDB = new TxDbs();

    
    $myFollowTB = 'user_'.$request->uid.'_follow_unfollow_friends';
$dbLikes = $txDB->getDB($request->country,$request->lang).'.user_'.$request->uid.'_likes';
  

    $response = array();

    $follwingList =  DB::connection('mysql_'.$request->lang)->table($myFollowTB)
                                     ->where([
                                          ['following_user_id_fk', '!=', 0] // 2 means friends
                                         ]) 
                                     ->take(Config::get('constants.LIMIT_5'))
                                     ->orderBy('fuf_id', 'DESC')
                                    // ->inRandomOrder()
                                     ->get();

//return $follwingList;

                                     if ($follwingList->count() > 0){
                      
                                        $i = 0;
                                        foreach($follwingList as $fList){

    $videosDB = $txDB->getDB($fList->fuf_country,$fList->fuf_lang).'.users_videos_'.$request->tbSerialId;
    $soundsDB = $txDB->getDB($fList->fuf_country,$fList->fuf_lang).'.users_sounds_'.$request->tbSerialId;
   // $dbLikes = $txDB->getDB($fList->fuf_country,$fList->fuf_lang).'.user_'.$fList->following_user_id_fk.'_likes';
 // return $videosDB.' '.$soundsDB.' '.$dbLikes;
    $query = DB::connection('mysql_'.$fList->fuf_lang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs','dbs.sound_token', 'dbv.sound_token')

    ->leftjoin($dbLikes.' as dbl',
    function ($join) {
        $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk')
             ->on('dbu.user_id', '=', 'dbl.user_id_fk');
            
    });  

    $res = $query->select([
        'dbu.user_id',
        'dbu.user_hashtag_name',
        'dbu.user_name',
        'dbu.user_profile_image',
        'dbu.user_login_token',
        'dbu.user_country',
        'dbu.user_language',
        'dbu.user_total_likes',
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
        'whose_sound',
        'dbs.sound_mp3',
        'dbs.sound_file',
        'dbs.sound_token',
        'dbs.sound_total_used',
        'dbv.created_at',
        'dbl.liker_id_fk'

        ])
    ->where([
        ['dbv.video_visibility', '=', 'public'],
        ['dbv.video_file', '!=','empty.mp4'],
        ['dbu.user_id', '=',$fList->following_user_id_fk],
        ])
       ->take(Config::get('constants.LIMIT_5'))
       ->inRandomOrder()
        ->orderBy('dbv.user_video_id', 'DESC')
        //->distinct()
        ->get();

                              
       // $response[$i] = $res;//str_replace('[]','',json_encode($res));
      //  $i++;
   } // foreach

 }

return $res;
    
}



public function fetchSingleVideo($request){

 

    $txDB = new TxDbs();

    $videosDB = $txDB->getDB($request->country,$request->lang).'.users_videos_'.$request->tbSerialId;
    $soundsDB = $txDB->getDB($request->country,$request->lang).'.users_sounds_'.$request->tbSerialId;
    $dbLikes = $txDB->getDB($request->viewerCountry,$request->viewerLang).'.user_'.$request->viewerId.'_likes';
  



    $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')

    ->leftjoin($dbLikes.' as dbl',
    function ($join) {
        $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk')
             ->on('dbu.user_id', '=', 'dbl.liker_id_fk');
            
    });  
    

    $output = $query->select([
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
        'dbv.whose_sound',
        'dbv.web_link',
        'dbs.sound_mp3',
        'dbs.sound_file',
        'dbs.sound_token',
        'dbs.sound_total_used',
        'dbv.created_at',
        'dbl.liker_id_fk', // for is liked video or not, if  liker id is liked that video return liker id otherwise null
        //'dbl.liker_lang',
        //'dbl.liker_country'
        
        ])
    ->where([
    ['dbv.user_video_id', '=', $request->videoId],
    ['dbv.video_visibility', '=', 'public'],
    ['dbv.video_file', '!=', 'empty.mp4']
    ])
    ->get();
    
    return $output;


}








public function getUserVideosBasedOnUserToken($request,$tb){

    $txDB = new TxDbs();

    $videosDB = $txDB->getDB($request->country,$request->lang).'.users_videos_'.$request->activeTbSerialId;
    $soundsDB = $txDB->getDB($request->country,$request->lang).'.users_sounds_'.$request->activeTbSerialId;
    $dbLikes = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_likes';
  
    $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')

    ->leftjoin($dbLikes.' as dbl',
    function ($join) {
        $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk')
             ->on('dbu.user_id', '=', 'dbl.liker_id_fk');
            
    });  
    
    $output = $query->select([
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
        'dbl.liker_id_fk' // for is liked video or not, if  liker id is liked that video return liker id otherwise null
        //'dbl.liker_lang',
        //'dbl.liker_country'
        
        ])
    ->where([
      //  ['dbu.user_id', '=', $request->userId],
        ['dbv.user_id_fk', '=', $request->userId],
        ['dbv.video_visibility', '=', $request->visibleStatus],
        ['dbv.video_file', '!=', 'empty.mp4']
        ])
        ->skip($request->count)->take(Config::get('constants.VIDEOS_LIMIT_ONSCROLL'))
        ->orderBy('dbv.user_video_id', 'DESC')//->distinct()
        ->get();
    
    return $output;//-->count();


}





public function fetchUserVideosLikedVideos($request){
    $response = array();
    //first checking user session or auth
    
    $txDB = new TxDbs();

    $dbLikes = 'user_'.$request->userId.'_likes';
    $dbLikesDB = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_likes';
    
        $likesList =  DB::connection('mysql_'.$request->lang)->table($dbLikes)
        ->where([
         ['liker_id_fk', '=', $request->userId]
       ])
    ->skip($request->count)->take(Config::get('constants.VIDEOS_LIMIT_ONSCROLL'))
    ->orderBy('like_id', 'DESC')
    ->get();


//return $likesList;


if ($likesList->count() > 0){
                      
    $i = 0;
    foreach($likesList as $like){

     $videosDB = $txDB->getDB($like->liker_country,$like->liker_lang).'.users_videos_'.$like->tb_serial_id_fk;
     $soundsDB = $txDB->getDB($like->liker_country,$like->liker_lang).'.users_sounds_'.$like->tb_serial_id_fk;
    
     $query = DB::connection('mysql_'.$like->liker_lang)->table('tx_users as dbu')
     ->join($dbLikesDB.' as dbl', 'dbl.user_id_fk', 'dbu.user_id')
     ->leftjoin($videosDB.' as dbv', 'dbv.user_video_id', 'dbl.user_video_id_fk')
     ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token');
    // ->leftjoin($dbCommentLikesTb.' as dbcl2', 'dbcl2.comment_id_fk', 'dbc.user_id_fk');

     $res = $query->select([
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
        'dbl.liker_id_fk',
        'dbl.tb_serial_id_fk'
       
         ])
         ->where([
              ['dbv.user_video_id', '=', $like->user_video_id_fk]
          //  ['dbv.user_video_id', '=', $comment->commenter_id_fk],
         //   ['dbc.user_video_id_fk', '=', $comment->user_video_id_fk]
            ])
         //->orderBy('dbc.comment_id', 'ASC')
         ->first();
         $response[$i] = $res;//str_replace('[]','',json_encode($res));
         $i++;
    } // foreach
   
  }
  return $response;

    
    
    }






public function fetchProfileVideosForViewers($request,$table,$viewerId){

   $txDB = new TxDbs();

   $videosDB = $txDB->getDB($request->country,$request->lang).'.users_videos_'.$request->activeTbSerialId;
   $soundsDB = $txDB->getDB($request->country,$request->lang).'.users_sounds_'.$request->activeTbSerialId;
 
   if($request->viewerType == 'txuser'){
    $dbLikes = $txDB->getDB($request->viewerCountry,$request->viewerLang).'.user_'.$viewerId.'_likes';

   }else{
    $dbLikes = Config::get('constants.MAIN_DB_PREF').'tx_main_db.anonymous_user_dummy_likes';
    $dummyUserId = "23xyzabc834";
   }
  // return $videosDB." ".$soundsDB." ".$dbLikes ;
    $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')
  ->leftjoin($dbLikes.' as dbl',
  function ($join)  use ($viewerId){
      $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk')
      ->where([
        ['dbl.liker_id_fk', '=', $viewerId]
        ]);
         
  });  
    
    $output = $query->select(
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
    'dbv.video_total_shares',
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
    'dbl.liker_id_fk'


    )

    ->where([
    ['dbv.user_id_fk', '=', $request->userId],
    ['dbv.video_visibility', '=', 'public'],
    ['dbv.video_file', '!=', 'empty.mp4']
    ])
   
    ->skip($request->count)->take(Config::get('constants.VIDEOS_LIMIT_ONSCROLL'))
    ->orderBy('dbv.user_video_id', 'DESC')
    ->get();
    
    return $output;


}



public function fetchProfilePopularVideosForViewers($request,$table,$viewerId){

    $txDB = new TxDbs();
 
    $videosDB = $txDB->getDB($request->country,$request->lang).'.users_videos_'.$request->activeTbSerialId;
    $soundsDB = $txDB->getDB($request->country,$request->lang).'.users_sounds_'.$request->activeTbSerialId;
  
    if($request->viewerType == 'txuser'){
     $dbLikes = $txDB->getDB($request->viewerCountry,$request->viewerLang).'.user_'.$viewerId.'_likes';
 
    }else{
     $dbLikes = $txDB->getDB($request->country,$request->lang).'.anonymous_user_dummy_likes';
     $dummyUserId = "23xyzabc834";
    }
   // return $videosDB." ".$soundsDB." ".$dbLikes ;
     $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
     ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
     ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')
   ->leftjoin($dbLikes.' as dbl',
   function ($join)  use ($viewerId){
       $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk')
       ->where([
         ['dbl.liker_id_fk', '=', $viewerId]
         ]);
          
   });  
     
     $output = $query->select(
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
     'dbv.video_total_shares',
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
     'dbl.liker_id_fk'
 
 
     )
 
     ->where([
     ['dbv.user_id_fk', '=', $request->userId],
     ['dbv.video_visibility', '=', 'public'],
     ['dbv.video_file', '!=', 'empty.mp4']
     ])
    
     ->skip($request->count)->take(Config::get('constants.VIDEOS_LIMIT_ONSCROLL'))
     ->orderBy('dbv.video_total_views', 'DESC')
     ->get();
     
     return $output;
 
 
 }





//This is not used
public function fetchProfileVideosForAnonymousViewers($request){

    $db = $txDB->getDB($request->country,$request->lang);


    $txDB = new TxDbs();

    $videosDB = $txDB->getDB($request->country,$request->lang).'.users_videos_'.$request->tbSerialId;
    $soundsDB = $txDB->getDB($request->country,$request->lang).'.users_sounds_'.$request->tbSerialId;
   
    $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token');

    $output = $query->select(
    'dbu.user_id',
    'dbu.user_hashtag_name',
    'dbu.user_name',
    'dbu.user_profile_image',
    'dbu.user_login_token',
    'dbu.user_country',
    'dbu.user_language',
    'dbu.user_total_likes',
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
    'whose_sound',
    'dbs.sound_mp3',
    'dbs.sound_file',
    'dbs.sound_token',
    'dbs.sound_total_used',
    'dbv.created_at'

    )

    ->where([
    ['dbv.video_visibility', '=', 'public'],
    ['dbv.video_file', '!=', 'empty.mp4']
    ])
    ->skip($request->count)->take(Config::get('constants.VIDEOS_LIMIT_ONSCROLL'))
    ->orderBy('dbv.user_video_id', 'DESC')
    ->get();
    
    return $output;

}




public function fetchUseThisSoundVideos($request,$table,$viewerId){

  
    $txDB = new TxDbs();

    $videosDB = $txDB->getDB($request->viewerCountry,$request->viewerLang).'.users_videos_'.$request->activeTbSerialId;
    $soundsDB = $txDB->getDB($request->viewerCountry,$request->viewerLang).'.users_sounds_'.$request->activeTbSerialId;
   
    if($viewerId == 'dummyid'){
        $dbLikes = Config::get('constants.MAIN_DB_PREF').'tx_main_db.anonymous_user_dummy_likes';
    }else{
        $dbLikes =  $txDB->getDB($request->viewerCountry,$request->viewerLang).'.user_'.$viewerId.'_likes';

    }
   
    $query = DB::connection('mysql_'.$request->viewerLang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')

  ->leftjoin($dbLikes.' as dbl',
  function ($join) {
      $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk');
          
  });  
  
    
    $output = $query->select(
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
    'whose_sound',
    'dbs.sound_mp3',
    'dbs.sound_file',
    'dbs.sound_token',
    'dbs.sound_total_used',
    'dbv.created_at',
    'dbl.liker_id_fk'

    )

    ->where([
    ['dbv.sound_token', '=', $request->soundToken],
    ['dbv.video_visibility', '=', 'public'],
    ['dbv.video_file', '!=', 'empty.mp4']
    ])
    ->skip($request->count)->take(Config::get('constants.VIDEOS_LIMIT_ONSCROLL'))
    ->orderBy('dbv.user_video_id', 'DESC')
    ->get();
    
    return $output;


}




public function fetchOriginalSoundVideo($request,$dbLikes,$activeTbSerialId){

    
    $txDB = new TxDbs();
    $videosDB = $txDB->getDB($request->soundHolderCountry,$request->soundHolderLang).'.users_videos_'.$activeTbSerialId;
    $soundsDB = $txDB->getDB($request->soundHolderCountry,$request->soundHolderLang).'.users_sounds_'.$activeTbSerialId;
  

    $query =  DB::connection('mysql_'.$request->soundHolderLang)->table('tx_users as dbu')
    ->join($videosDB.' as dbv', 'dbv.user_id_fk', 'dbu.user_id')
    ->join($soundsDB.' as dbs', 'dbs.sound_token', 'dbv.sound_token')

  ->leftjoin($dbLikes.' as dbl',
  function ($join) {
      $join->on('dbv.user_video_id', '=', 'dbl.user_video_id_fk');
          
  });  
  
    
    $output = $query->select(
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
    'whose_sound',
    'dbs.sound_mp3',
    'dbs.sound_file',
    'dbs.sound_token',
    'dbs.sound_total_used',
    'dbv.created_at',
    'dbl.liker_id_fk'

    )

    ->where([
    ['dbv.sound_token', '=', $request->soundToken],
    ['dbv.whose_sound', '=', 'original'],
   // ['dbv.video_visibility', '=', 'public'],
    ['dbv.video_file', '!=', 'empty.mp4']
    ])
    ->get();
    
    return $output;


}

public function deleteEmptyRow($request){
    $tb = 'users_videos_'.$request->tbId;
  DB::connection('mysql_'.$request->lang)->table($tb)->where('video_file', 'empty.mp4')->delete();

}



public function saveCoverPhoto($request,$photo_name){

  //  $target_dir = storage_path()."/app/public/videos/".$imgName.jpg;
$tb = 'users_videos_'.$request->tbId;
      
    $tempToken = request('user_id_fk').date("Ymd").date("hism");

$checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_token', '=', $request->user_login_token]])->first();

$photo = request('file');


$path = new Paths();
//$target_dir = $path->getStoredCoverPhotosPath($request->country,$request->lang,$request->tbId).$photo_name; //volley base64
$target_dir = $path->getStoredCoverPhotosFilePath($request->country,$request->lang,$request->tbId); // retrofit file photo

//$target_dir = storage_path()."/app/public/cover-photos/".$photo_name;
if($photo->storeAs($target_dir, $photo_name)){
//if(file_put_contents($target_dir,base64_decode($photo))){

    if($checkExistUser){

      $res =  DB::connection('mysql_'.$request->lang)->table($tb)->insert(
              array('video_token' => md5(request('video_token')),
                  'video_info' => request('video_info'),
                  'video_size' => request('video_size'),
                  'video_duration' => request('video_duration'),
                  'video_width' => request('video_width'),
                  'video_height' => request('video_height'),
                  'video_zoom' => request('video_zoom'),
                  'user_id_fk' => request('user_id_fk'),
                  'video_id_fk' => request('video_id_fk'),
                  'dueter_hash_id' => request('dueter_Hash_Id'),
                  'video_type' => request('video_type'),
                  'video_cat' => request('video_cat'),
                  'web_link' => request('web_link'),
                   'video_cover_photo' => $photo_name,
                   'video_visibility' => request('video_visibility'),
                   'video_comments_privacy_status' => request('video_comments_privacy_status'),
                   'whose_sound' => request('whose_sound'),
                   'client_info' => request('client_info')
                   //'sound_holder_info' => request('sound_holder_info')
                  )
        );


        if(request('video_type') == Config::get('constants.DUET') || request('video_type') == Config::get('constants.STITCHV') || request('video_type') == Config::get('constants.STITCHC')){
            $getLastId = DB::connection('mysql_'.$request->lang)->table($tb)->where([['user_id_fk', '=', $request->user_id_fk]])->orderBy('user_video_id', 'DESC')->first();
              
            $inbox = new Inbox();
            $inbox->saveDuetStitchVideoInbox($request,$getLastId->user_video_id);
        }

        if($res > 0){
            DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_login_token',$request->user_login_token)->increment('total_videos');

            return "saved";
    
        }else{
            return "not saved";
        }


}else{
    return "user not exist";
}

}//photo stored
else{
    return "Photo not stored";
}



}


public function saveSoundFile($request){
   $tb = 'users_videos_'.$request->tbId;
$tempToken = date("Ymd").date("hism");

$audio = $request->file('file');
$audio_name_mp3 = $request->video_token.".mp3";
$sound_file_info = $request->soundName;

$path = new Paths();
$target_dir = $path->getStoredSoundsPath($request->country,$request->lang,$request->tbId);
//in-telugu-sounds (directory)
//$target_dir = $request->country."-".$request->lang."-sounds";

if($audio->storeAs($target_dir, $audio_name_mp3)){

//store sound file in users_sounds_1 table
          $us = new UsersSounds();
          $usRes = $us->saveAudioSoundFile($request,$audio_name_mp3,$sound_file_info);

if($usRes == "fail"){
    return "fail";
}else{

    $res = DB::connection('mysql_'.$request->lang)->table($tb)->where('video_token', $request->video_token)->update(array('sound_token' => $usRes));

    if($res > 0){
return "updated";
    }else{
return "fail";
    }

}

}else{
    return "sound-store-fail";
}


}


public function saveVideoFile($request){
    $tb = 'users_videos_'.$request->tbId;
    $tempToken = date("Ymd").date("hism");
   
$video = $request->file('file');
$video_name = $request->videoName.".mp4";

$path = new Paths();
$target_dir = $path->getStoredVideosPath($request->country,$request->lang,$request->tbId);
//in-telugu-videos (directory)
if($video->storeAs($target_dir, $video_name)){
    $res = DB::connection('mysql_'.$request->lang)->table($tb)->where('video_token', $request->video_token)->update(array('video_file' => $video_name));
    if($res > 0){

//increament total videos in users table at user id
return "updated";
    }else{
return "fail";
    }

}else{
    return "video-store-fail";
}


}



public function saveCoverPhotoWithSound($request,$photo_name){
    $txDB = new TxDbs();
    $tb = 'users_videos_'.$request->tbId;
    //  $target_dir = storage_path()."/app/public/videos/".$imgName.jpg;
  
      $tempToken = request('user_id_fk').date("Ymd").date("hism");
     
      $dbtb = $txDB->getDB($request->country,$request->lang).'.tx_users';
   
      $checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_token', '=', $request->user_login_token]])->first();
      

$photo = request('file');

$path = new Paths();
//$target_dir = $path->getStoredCoverPhotosPath($request->country,$request->lang,$request->tbId).$photo_name; //volley base64
$target_dir = $path->getStoredCoverPhotosFilePath($request->country,$request->lang,$request->tbId); // retrofit file photo

//$target_dir = storage_path()."/app/public/cover-photos/".$photo_name;
if($photo->storeAs($target_dir, $photo_name)){
      if($checkExistUser){
      $res =  DB::connection('mysql_'.$request->lang)->table($tb)->insert(
        array('video_token' => md5(request('video_token')),
            'video_info' => request('video_info'),
            'video_size' => request('video_size'),
            'video_duration' => request('video_duration'),
            'video_width' => request('video_width'),
            'video_height' => request('video_height'),
            'video_zoom' => request('video_zoom'),
            'user_id_fk' => request('user_id_fk'),
            'video_id_fk' => request('video_id_fk'),
            'dueter_hash_id' => request('dueter_Hash_Id'),
            'video_type' => request('video_type'),
            'video_cat' => request('video_cat'),
            'web_link' => request('web_link'),
             'video_cover_photo' => $photo_name,
             'video_visibility' => request('video_visibility'),
             'video_comments_privacy_status' => request('video_comments_privacy_status'),
             'sound_token' => request('sound_token'),
             'whose_sound' => request('whose_sound'),
             'client_info' => request('client_info')
            )
  );

  //increament sound used by +1
  $usounds = new UsersSounds();
  $usounds->updateThisSoundTotalUsedBy($request);
  DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_login_token',$request->user_login_token)->increment('total_videos');

if(request('video_type') == Config::get('constants.DUET') || request('video_type') == Config::get('constants.STITCHV') || request('video_type') == Config::get('constants.STITCHC')){
    $getLastId = DB::connection('mysql_'.$request->lang)->table($tb)->where([['user_id_fk', '=', $request->user_id_fk]])->orderBy('user_video_id', 'DESC')->first();
      
    $inbox = new Inbox();
    $inbox->saveDuetStitchVideoInbox($request,$getLastId->user_video_id);
}

  if($res > 0){
      return "saved";

  }else{
      return "not saved";
  }

  }else{
      return "user not exist";
  }
  
  }//photo stored
  else{
      return "Photo not stored";
  }
  
  }
  

public function addOneViewToVideo($request){

    $dbVideos = 'users_videos_'.$request->tbId;
      
    return  DB::connection('mysql_'.$request->lang)->table($dbVideos)->where('user_video_id', $request->videoId)->increment('video_total_views');

}


public function addOneShareToVideo($request){

    $dbVideos = 'users_videos_'.$request->tbId;
      
    return  DB::connection('mysql_'.$request->lang)->table($dbVideos)->where('user_video_id', $request->videoId)->increment('video_total_shares');

}


public function videoPrivacySettings($request){

    $vidTB = 'users_videos_'.$request->tbId;
   
    return DB::connection('mysql_'.$request->lang)->table($vidTB)->where('user_video_id', $request->videoId)
    ->update(array(
        'video_visibility' => $request->visibility,
        'video_comments_privacy_status' => $request->commentPrivacy
    ));

}


public function deleteVideo($request){

    $vidTB = 'users_videos_'.$request->tbId;
   
    return DB::connection('mysql_'.$request->lang)->table($vidTB)->where('user_video_id', $request->videoId)
    ->update(array(
        'video_visibility' => 'deleted'
    ));

}




}
