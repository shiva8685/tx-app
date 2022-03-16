<?php

namespace App;
use DB;
use App\TxDbs;
use App\Paths;
use App\TxAnonymousUsers;
use App\Inbox;
use Image;
use Config;
use Illuminate\Database\Eloquent\Model;

class TxUsers extends Model
{
//Table name
protected $table = 'tx_users';


public function userIsExistByToken($lang,$token){

    $checkExistUser = DB::connection('mysql_'.$lang)->table('tx_users')->where([['user_login_token', '=', $token]])->first();
      
    if(!$checkExistUser){
        return 0;
    }else{
        return 1;
    }

}


public function getUserIfExistByToken($lang,$token){

    $checkExistUser = DB::connection('mysql_'.$lang)->table('tx_users')->where([['user_login_token', '=', $token]])->first();
    
    return $checkExistUser;
    

}



public function userIsExistByTokenAndUserId($lang,$token,$userId){

    $checkExistUser = DB::connection('mysql_'.$lang)->table('tx_users')
    ->where([
        ['user_login_token', '=', $token],
        ['user_id', '=', $userId]
        ])->first();
      
    if(!$checkExistUser){
        return 0;
    }else{
        return 1;
    }

}


public function checkUserExistByLoginId($lang,$loginId){

    $user = DB::connection('mysql_'.$lang)->table('tx_users')->where([['user_login_id', '=', $loginId]])->first();
  
    if(!$user){
        return 0;
    }else{
        return 1;
    }

}


public function checkUserExist($request,$db){

    $user = DB::connection('mysql_'.$lang)->table('tx_users')->where([['user_login_id', '=', $request->user_login_id]])->first();
  
    if(!$user){
        return "not exist";
    }else{
        return "exist";
    }

}


public function checkUserLogin($request){

    $user = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([
        ['user_login_id', '=', $request->user_login_id],
        ['user_password', '=', md5($request->user_password.$request->lang)],
        ])->first();
  
   return $user;

}

public function getSingleMyProfileDetails($request){
    $txDB = new TxDbs();

    $db = $txDB->getDB($request->country,$request->lang);
    $output = DB::connection('mysql_'.$request->lang)->table('tx_users')->select(
        'user_id',
        'user_hashtag_name',
        'user_name',
        'user_login_id',
        'gender',
        'user_dp_type',
        'user_profile_video',
        'about_user',
        'user_instagram_link',
        'user_youtube_link',
        'user_twitter_link',
        'user_fb_link',
        'user_total_following',
        'user_total_followers',
        'user_total_likes',
        'total_videos',
        'user_profile_image',
        'user_country',
        'user_language',
        'user_dob'
        )
    ->where([['user_login_token', '=', $request->userToken]])->get();
 
    
    return $output;



}




public function getDuetorDetails($duetor,$index){

  $token = strtok($duetor, ",");
  $i = 1;
  while ($token !== false)
     {
       if($index == $i){
        return $token;
       }else if($index == $i){
        return $token;
       }else if($index == $i){
        return $token;
       }else if($index == $i){
        return $token;
       }else if($index == $i){
        return $token;
       }
       else if($index == $i){
        return $token;
       }
       $token = strtok(",");
     $i++;
    }
}


public function getSingleUserProfileDetails($request){
    $response = array();
    $txDB = new TxDbs();

    $db = $txDB->getDB($request->country,$request->lang);
    
    $viewerdb = $txDB->getDB($request->viewerCountry,$request->viewerLang);
    
    $followUnFollowDbTb = $db.'.user_'.$request->profilerId.'_follow_unfollow_friends';

   
    $query = DB::connection('mysql_'.$request->lang)->table('tx_users as dbu')
    ->leftjoin($followUnFollowDbTb.' as dbfuf',
    function ($join) {
        $checkUserFollowing = request('viewerId');
        $viewerLang = request('viewerLang');

        $join->on('dbu.user_country', '=', 'dbfuf.fuf_country');
        $join->where('dbu.user_language', '=', $viewerLang);
        $join->where('dbfuf.follower_user_id_fk', '=', $checkUserFollowing);
        $join->orwhere('dbfuf.following_user_id_fk', '=', $checkUserFollowing);
       // ->where('dbfuf.fuf_user_id_fk', '=', $checkUserFollowing);

    });
   // ->leftjoin($followUnFollowDbTb.' as dbfuf', 'dbfuf.fuf_user_id_fk', 'dbu.user_id');

    $output = $query->select(
        'user_id',
        'user_hashtag_name',
        'user_name',
        'user_login_id',
        'gender',
        'user_dp_type',
        'user_profile_video',
        'about_user',
        'user_instagram_link',
        'user_youtube_link',
        'user_twitter_link',
        'user_fb_link',
        'user_total_following',
        'user_total_followers',
        'total_videos',
        'user_total_likes',
        'user_profile_image',
        'user_country',
        'user_language',
        'user_dob',
        'gender',
        'dbfuf.fuf_id',  
        'dbfuf.fuf_user_type',
        'dbfuf.follower_user_id_fk',
        'dbfuf.following_user_id_fk',
        'dbfuf.who_is_first_following'  
        )
    ->where([
        ['user_login_token', '=', $request->userToken]
    ])->first();
 
    $response[0] = $output;
    
    return $response;



}



public function storeNewAccount($request){
//store defalt hashtag or user unique hashid change any time

$default_hashtag = str_replace(" ","_",$request->name).substr($request->login_id,1,2).date("d").date("m");

$dbName = "mysql_".$request->lang;
$user = new TxUsers;
$user->setConnection($dbName);



//$userAnonymus = TxAnonymousUsers::where([['anonumous_user', '=', request('anonymous_id')]])->first();
$checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_id', '=', $request->login_id]])->first();
  $encryptedPassword = md5(request('password').request('lang'));
if(!$checkExistUser){
    $user->user_name = request('name');
    $user->user_hashtag_name = $default_hashtag;
    $user->user_login_id = request('login_id');
    $user->user_password = $encryptedPassword;
    $user->user_login_token = md5(request('login_id'));
    $user->gender = request('gender');
    $user->login_provider = request('provider');
    $user->login_provider_uid = request('p_uid');
    $user->login_type = request('login_type');
    $user->user_country = request('country');
    $user->user_language = request('lang');
    $user->user_dob = request('dob');
    $user->user_firebase_token = request('firebase_token');
    if($user->save()){
        //TxAnonymousUsers::where('anonumous_user',request('anonymous_id'))->delete();
       return 'success';
      
    }else{
        return 'insert fail';
       
    }
 
}else{
    return 'user exist';
}


  
}

public function updateHashTagName($request){

    return DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_login_token', $request->userToken)
     ->update(array(
         'user_hashtag_name' => $request->hashTagName
 
     ));
     
}


public function updateUserDatails($request){
    $txDB = new TxDbs();
    
    $dbtb = $txDB->getDB($request->country,$request->lang).".tx_users";

   return DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_login_token', $request->userToken)
    ->update(array(
        'user_name' => $request->userName,
        'user_hashtag_name' => $request->userHashTag,
        'about_user' => $request->bio,
        'user_instagram_link' => $request->instaId,
        'user_youtube_link' => $request->youtubeId,
        'user_twitter_link' => $request->twitterId,
        'user_fb_link' => $request->fbId

    ));
    

}

public function updatePrivacySafety($request){

    $dbtb = "tx_".$request->country."_lang_".$request->lang.".tx_users";

   return DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_login_token', $request->userToken)
    ->update(array(
        'private_account' => $request->privateAccount,
        'find_me' => $request->findMe,
        'post_comments' => $request->comments,
        'duet_with' => $request->duet,
        'react_to_me' => $request->react,
        'messages_me' => $request->message
     

    ));
    

}



public function updatePassword($request){
    $txDB = new TxDbs();

    $dbtb = $txDB->getDB($request->country,$request->lang).".tx_users";
   
   $encryptedPassword = md5($request->newPass.$request->lang); // add user id to password

   return DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_login_token', $request->userToken)
    ->update(array(
        'user_password' => $encryptedPassword

    ));
    

}



public function updateProfilePhoto($request,$photo_name){
    $txDB = new TxDbs();
    
    $dbtb = $txDB->getDB($request->country,$request->lang).'.tx_users';

    /*
    if($request->hasfile('file')){
    $imageName = $request->file('file');

    $new_name = 'ZAPFITT-IMG1-'.date("Ymd").date("his").time().'.'. $imageName->getClientOriginalExtension();

    $imageName->storeAs('public/test', $new_name);


//Resize og image and store
$pathForOgImage = storage_path()."/app/public/test/".$new_name;//public_path('storage/test-small/'.$new_name);
$imgToSave = Image::make($pathForOgImage)->resize(100,120,function($constraint){$constraint->aspectRatio();});
$imgToSave->save($pathForOgImage);


    }
*/
    $photo = base64_decode(request('profile_photo'));
   // $photo_name = $photoName;
    
    $path = new Paths();
    $target_dir = $path->getProfilePhotoPath($request->country,$request->lang).$photo_name;
    $target_dir_small = $path->getProfileSmallPhotoPath($request->country,$request->lang).$photo_name;
  
    if(file_put_contents($target_dir,$photo) && file_put_contents($target_dir_small,$photo)){

        $imgToSave = Image::make($target_dir_small)->resize(Config::get('constants.PROFILE_PHOTO_WIDTH'),Config::get('constants.PROFILE_PHOTO_HEIGHT'),function($constraint){$constraint->aspectRatio();});
        $imgToSave->save($target_dir_small);
    
        return DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_login_token', $request->userToken)
    ->update(array(
        'user_profile_image' => $photo_name
    ));

    }else{
        return 0;
    }
}


public function checkingUserHashTag($request){
            $res = "";

            $checkHashName = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_hashtag_name', '=', $request->username]])->first();
          if(!$checkHashName){
            $res = "not exist";
          }
           else{
            $res  = "exist";
            }
            return $res;
    }


public function resetPassword($request){
    $txDB = new TxDbs();
    
    $dbtb = $txDB->getDB($request->country,$request->lang).".tx_users";

    return DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_login_id', $request->user_login_id)
     ->update(array(
         'user_password' => md5($request->new_password.$request->lang)
 
     ));
     
}


public function followUser($request){
    $txDB = new TxDbs();
    
    $profilerDB = $txDB->getDB($request->profilerCountry,$request->profilerLang);
    $myDB = $txDB->getDB($request->country,$request->lang);

    $profilerrdbtb = $txDB->getDB($request->profilerCountry,$request->profilerLang).'.tx_users';

    $mydbtb =  $txDB->getDB($request->country,$request->lang).'.tx_users';
   

    $profilerFollowUnFollowDbTb = "user_".$request->profilerId."_follow_unfollow_friends";

    $myFollowUnFollowDbTb = "user_".$request->myId."_follow_unfollow_friends";

  //  $profilerTotalFollowers = DB::table($profilerrdbtb)->select('user_total_followers')->where([['user_id', '=', $request->profilerId]]) ->first();
    
   // $myTotalFollowing = DB::table($mydbtb)->select('user_total_following')->where([['user_id', '=', $request->myId]]) ->first();
    
      

      
    $res =  DB::connection('mysql_'.$request->lang)->table($myFollowUnFollowDbTb)->insert(
        array('fuf_lang' => request('profilerLang'),
            'fuf_country' => request('profilerCountry'),
            'following_user_id_fk' => request('profilerId'),
            'fuf_user_type' => request('follow_type'),  
            'who_is_first_following' => request('myId'),
            
            )
  );

  $res =  DB::connection('mysql_'.$request->profilerLang)->table($profilerFollowUnFollowDbTb)->insert(
    array('fuf_lang' => request('lang'),
        'fuf_country' => request('country'),
        'follower_user_id_fk' => request('myId'),
        'fuf_user_type' => request('follow_type'),
        'who_is_first_following' => request('myId')
       
        )
);

  if($res > 0){
   

    DB::connection('mysql_'.$request->profilerLang)->table('tx_users')->where('user_id', $request->profilerId)->increment('user_total_followers');
    DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_id', $request->myId)->increment('user_total_following');
    
     $inbox = new Inbox();
     $inboxRes = $inbox->saveFollowUserInbox($request);
 
      return 1;

  }else{
      return 0;
  }



}


public function followBack($request){
    $txDB = new TxDbs();
    
  
    $profilerDB = $txDB->getDB($request->profilerCountry,$request->profilerLang);
    $myDB = $txDB->getDB($request->country,$request->lang);


    $profilerrdbtb = $txDB->getDB($request->profilerCountry,$request->profilerLang).'.tx_users';

    $mydbtb = $txDB->getDB($request->country,$request->lang).'.tx_users';
   

    $profilerFollowUnFollowDbTb = "user_".$request->profilerId."_follow_unfollow_friends";

    $myFollowUnFollowDbTb = "user_".$request->myId."_follow_unfollow_friends";

   
    $res = DB::connection('mysql_'.$request->lang)->table($myFollowUnFollowDbTb) // 124
  ->where([
    ['fuf_lang', '=', $request->profilerLang],
    ['fuf_country', '=', $request->profilerCountry],
    ['follower_user_id_fk', '=', $request->profilerId]
    ])
  ->update(array('following_user_id_fk' => request('profilerId'),'fuf_user_type' => request('follow_type')));


$res =  DB::connection('mysql_'.$request->profilerLang)->table($profilerFollowUnFollowDbTb) //139
->where([
    ['fuf_lang', '=', $request->lang],
    ['fuf_country', '=', $request->country],
    ['following_user_id_fk', '=', $request->myId]

    ])
->update(array('follower_user_id_fk' => request('myId'),'fuf_user_type' => request('follow_type')));


  if($res > 0){
   
  // No need for follow back
     DB::connection('mysql_'.$request->profilerLang)->table('tx_users')->where('user_id', $request->profilerId)->increment('user_total_followers');
   
     DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_id', $request->myId)->increment('user_total_following');

     $inbox = new Inbox();
     $inboxRes = $inbox->saveFollowBackUserInbox($request);
 


      return 1;

  }else{
      return 0;
  }



}




public function removeFriendsShipFromFollowing($request){
    $txDB = new TxDbs();
    
  
    $profilerDB = $txDB->getDB($request->profilerCountry,$request->profilerLang);
    $myDB = $txDB->getDB($request->country,$request->lang);

    $profilerrdbtb = $profilerDB.'.tx_users';

    $mydbtb = $myDB.'.tx_users';
   
    $profilerFollowUnFollowDbTb = "user_".$request->profilerId."_follow_unfollow_friends";

    $myFollowUnFollowDbTb = "user_".$request->myId."_follow_unfollow_friends";

    $res = DB::connection('mysql_'.$request->lang)->table($myFollowUnFollowDbTb) //139 viewer id
  ->where([
    ['fuf_lang', '=', $request->profilerLang],
    ['fuf_country', '=', $request->profilerCountry],
    ['following_user_id_fk', '=', $request->profilerId]
    ])
  ->update(array('follower_user_id_fk' => 0,'fuf_user_type' => request('follow_type')));


$res =  DB::connection('mysql_'.$request->profilerLang)->table($profilerFollowUnFollowDbTb) // 124 profiler id
->where([
    ['fuf_lang', '=', $request->lang],
    ['fuf_country', '=', $request->country],
    ['follower_user_id_fk', '=', $request->myId]
    ])
->update(array('following_user_id_fk' => 0,'fuf_user_type' => request('follow_type')));


  if($res > 0){
   
  // No need for follow back
  DB::connection('mysql_'.$request->profilerLang)->table('tx_users')->where('user_id', $request->profilerId)->decrement('user_total_following');
   
  DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_id', $request->myId)->decrement('user_total_followers');

     $inbox = new Inbox();
     $inboxRes = $inbox->removeFriendShip($request);


      return 1;

  }else{
      return 0;
  }



}



public function removeFriendsShipFromFollower($request){
    $txDB = new TxDbs();
    
  
    $profilerDB = $txDB->getDB($request->profilerCountry,$request->profilerLang);
    $myDB = $txDB->getDB($request->country,$request->lang);

    $profilerrdbtb = $profilerDB.'.tx_users';

    $mydbtb = $myDB.'.tx_users';
   

    $profilerFollowUnFollowDbTb = "user_".$request->profilerId."_follow_unfollow_friends";

    $myFollowUnFollowDbTb = "user_".$request->myId."_follow_unfollow_friends";

   
    
    $res = DB::connection('mysql_'.$request->lang)->table($myFollowUnFollowDbTb) // 124 viewer
  ->where([
    ['fuf_lang', '=', $request->profilerLang],
    ['fuf_country', '=', $request->profilerCountry],
    ['follower_user_id_fk', '=', $request->profilerId]
    ])
  ->update(array('following_user_id_fk' => 0,'fuf_user_type' => request('follow_type')));


$res = DB::connection('mysql_'.$request->profilerLang)->table($profilerFollowUnFollowDbTb) // 139 proifler
->where([
    ['fuf_lang', '=', $request->lang],
    ['fuf_country', '=', $request->country],
    ['following_user_id_fk', '=', $request->myId]
    ])
->update(array('follower_user_id_fk' => 0,'fuf_user_type' => request('follow_type')));

  if($res > 0){
   
  // No need for follow back
  DB::connection('mysql_'.$request->profilerLang)->table('tx_users')->where('user_id', $request->profilerId)->decrement('user_total_followers');
   
  DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_id', $request->myId)->decrement('user_total_following');

      return 1;

  }else{
      return 0;
  }



}






public function unFollowUser($request){

    $txDB = new TxDbs();
    
  
    $profilerDB = $txDB->getDB($request->profilerCountry,$request->profilerLang);
    $myDB = $txDB->getDB($request->country,$request->lang);

    $profilerrdbtb = $profilerDB.'.tx_users';

    $mydbtb = $myDB.'.tx_users';
   

    $profilerFollowUnFollowDbTb = "user_".$request->profilerId."_follow_unfollow_friends";

    $myFollowUnFollowDbTb = "user_".$request->myId."_follow_unfollow_friends";

  
  
    $res = DB::connection('mysql_'.$request->lang)->table($myFollowUnFollowDbTb)->where([
        ['fuf_country', '=', request('profilerCountry')],
        ['fuf_lang', '=', request('profilerLang')],
        ['following_user_id_fk', '=', request('profilerId')]
        ])->delete();
  
        $res = DB::connection('mysql_'.$request->profilerLang)->table($profilerFollowUnFollowDbTb)->where([
            ['fuf_country', '=', request('country')],
            ['fuf_lang', '=', request('lang')],
            ['follower_user_id_fk', '=', request('myId')]
            ])->delete();

 
  if($res > 0){
   

    DB::connection('mysql_'.$request->profilerLang)->table('tx_users')->where('user_id', $request->profilerId)->decrement('user_total_followers');
    DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_id', $request->myId)->decrement('user_total_following');

      return 1;

  }else{
      return 0;
  }



}



public function fetchProfilerFollowers($request){
    $txDB = new TxDbs();
    

    $usersDbTb = $txDB->getDB($request->country,$request->lang).'.tx_users';
    $MainDbTb = $txDB->getDB($request->country,$request->lang);

    $followUnFollowDbTb = $MainDbTb.".user_".$request->userId."_follow_unfollow_friends";

    $tb = "user_".$request->userId."_follow_unfollow_friends";
    $response = array();

    $followerList =  DB::connection('mysql_'.$request->lang)->table($tb)
                                     ->where([
                                          ['follower_user_id_fk', '>', 0]
                                         ]) 
                                     ->skip($request->count)->take(Config::get('constants.COMMENTS_LIMIT_ONSCROLL'))
                                     ->orderBy('fuf_id', 'DESC')
                                     ->get();


                          
                    if ($followerList->count() > 0){
                      
                        $i = 0;
                        foreach($followerList as $fList){

                         $db =  $txDB->getDB($fList->fuf_country,$fList->fuf_lang);
                        

                         $query = DB::connection('mysql_'.$fList->fuf_lang)->table('tx_users as dbu')
                         ->join($followUnFollowDbTb.' as dbfwrs', 'dbfwrs.follower_user_id_fk', 'dbu.user_id');


                         $res =  $query->select([
                             'dbu.user_id',
                             'dbu.user_hashtag_name',
                             'dbu.user_name',
                             'dbu.user_login_token',
                             'dbu.user_profile_image',
                             'dbu.user_country',
                             'dbu.user_language',
                             'dbu.user_total_following',
                             'dbu.user_total_followers',
                             'dbu.user_total_likes',
                             'dbfwrs.follower_user_id_fk',
                             'dbfwrs.fuf_user_type'
                        
                           
                             ])
                             ->where([
                                ['dbu.user_id', '=', $fList->follower_user_id_fk]
                                ])
                            // ->orderBy('comment_id', 'ASC')
                             ->first();

                              
                             $response[$i] = $res;//str_replace('[]','',json_encode($res));
                             $i++;
                        } // foreach
                  
                      }
                  

   
return $response;
           
}


public function fetchProfilerFollowing($request){

    $txDB = new TxDbs();
    

    $usersDbTb = $txDB->getDB($request->country,$request->lang).'.tx_users';
    $MainDbTb = $txDB->getDB($request->country,$request->lang);

    $followUnFollowDbTb = $MainDbTb.".user_".$request->userId."_follow_unfollow_friends";
    $tb = "user_".$request->userId."_follow_unfollow_friends";

    $response = array();

    $followerList =  DB::connection('mysql_'.$request->lang)->table($tb)
                                     ->where([
                                           ['following_user_id_fk', '>', 0]
                                         ]) 
                                     ->skip($request->count)->take(Config::get('constants.COMMENTS_LIMIT_ONSCROLL'))
                                     ->orderBy('fuf_id', 'DESC')
                                     ->get();


                          
                    if ($followerList->count() > 0){
                      
                        $i = 0;
                        foreach($followerList as $fList){

                         $db =  $txDB->getDB($fList->fuf_country,$fList->fuf_lang);
                         

                         $query = DB::connection('mysql_'.$fList->fuf_lang)->table('tx_users as dbu')
                         ->join($followUnFollowDbTb.' as dbfwrs', 'dbfwrs.following_user_id_fk', 'dbu.user_id');


                         $res =  $query->select([
                             'dbu.user_id',
                             'dbu.user_hashtag_name',
                             'dbu.user_name',
                             'dbu.user_login_token',
                             'dbu.user_profile_image',
                             'dbu.user_country',
                             'dbu.user_language',
                             'dbu.user_total_following',
                             'dbu.user_total_followers',
                             'dbu.user_total_likes',
                             'dbfwrs.following_user_id_fk',
                             'dbfwrs.fuf_user_type'
                           
                           
                             ])
                             ->where([
                                ['dbu.user_id', '=', $fList->following_user_id_fk]
                                ])
                            // ->orderBy('comment_id', 'ASC')
                             ->first();

                              
                             $response[$i] = $res;//str_replace('[]','',json_encode($res));
                             $i++;
                        } // foreach
                  
                      }
                  

   
return $response;
           
}





}
