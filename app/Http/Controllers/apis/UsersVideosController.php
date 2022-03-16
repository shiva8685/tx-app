<?php

namespace App\Http\Controllers\apis;
use DB;
use Config;
use App\UsersVideos;
use App\TxVideosTables;
use App\TxSoundsTables;
use App\TxAnonymousUsers;
use App\TxUsers;
use App\TxDbs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
  $tx = new TxUsers();
//return $tx->getDuetorDetails($request->duetor,6);
        //  DB::connection('mysql')->table('tx_sounds_tables')->where(['tb_serial_id'=> 1,'tb_lang'=> 'telugu'])->increment('tb_total_rows');

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';    
        return $ipaddress;
        




    }



    public function getVideosForHomeScreenForYou(Request $request){
        $response = array();
//first check

if(request('userType') == 'txuser'){

    //check txuser existense
  
    $txUsers = new TxUsers;
    $checkExistUser = $txUsers->getUserIfExistByToken($request->lang,$request->userToken);
    if(!$checkExistUser){
      $response['status'] = "fail1";
      $response['error'] = "Unautherized user";
  
  }else{
    $vid = new UsersVideos;
    $response = $vid->getForYouVideos($request,$checkExistUser->user_id);
  }

}else if(request('userType') == 'anonymous'){

 
        $txUsers = new TxAnonymousUsers;
        $userAnonymus = $txUsers->anonymousUserIsExist($request->lang,$request->userToken);
        if($userAnonymus == 1){
          $dummyViewerId = 'dummyid';
          $vid = new UsersVideos;
          $response = $vid->getForYouVideos($request,$dummyViewerId);
      
  }else{
    $response['status'] = "fail2";
    $response['error'] = "Unautherized user";
  }
}else{
//error
$response['status'] = "fail3";
$response['error'] = "Unautherized user";
}


            return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
             JSON_UNESCAPED_UNICODE);
    
    }



    public function getVideosForHomeScreenFollowing(Request $request){

    $response = array();
    $txUsers = new TxUsers;
    $checkExistUser = $txUsers->userIsExistByToken($request->lang,$request->userToken);
    if($checkExistUser == 1){
      $vid = new UsersVideos;
      $response = $vid->getForllowingVideos($request);

}else{
  //error
$response['status'] = "fail";
$response['error'] = "Unautherized user";
}

       

            return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
             JSON_UNESCAPED_UNICODE);
    
    }



public function getSingleUserVideos(Request $request){
  $response = array();
 
  $vid = new UsersVideos;
  
  $txUsers = new TxUsers;
  $checkExistUser = $txUsers->userIsExistByToken($request->lang,$request->userToken);
  if($checkExistUser == 1){

  $table = 'users_videos_'.$request->activeTbSerialId;

  $response = $vid->getUserVideosBasedOnUserToken($request,$table);

 if($response->isEmpty()){
  // return "empty";
   // check storage full previous table here and get again data from that table
 }

}else{
//error
$response['status'] = "fail";
$response['error'] = "Unautherized user";
}
   
        return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
         JSON_UNESCAPED_UNICODE);

}



public function getSingleUserLikedVideos(Request $request){
  $response = array();
 
$txUsers = new TxUsers;
  $checkExistUser = $txUsers->userIsExistByToken($request->lang,$request->userToken);
  if($checkExistUser == 1){
  $vid = new UsersVideos;
  $response = $vid->fetchUserVideosLikedVideos($request);


}else{
//error
$response['status'] = "fail";
$response['error'] = "Unautherized user";
}
   
        return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
         JSON_UNESCAPED_UNICODE);

}




public function getUserProfileVideosForViewers(Request $request){

  $response = array();
  //first check
  if(request('viewerType') == 'txuser'){
  
  
    $table = 'users_videos_'.$request->activeTbSerialId;
      //check txuser existense
      $checkTxExistUser = DB::connection('mysql_'.$request->viewerLang)->table('tx_users')->where([['user_login_token', '=', request('viewerToken')]])->first();

      if(!$checkTxExistUser){
  //error
  $response['status'] = "fail1";
  $response['error'] = "Unautherized user";
    }else{
      $vid = new UsersVideos;
    
      $response = $vid->fetchProfileVideosForViewers($request,$table,$checkTxExistUser->user_id);
    }
  
  }else if(request('viewerType') == 'anonymous'){

  
    $table = 'users_videos_'.$request->activeTbSerialId;
   
    $checkExistAnonymus = DB::connection('mysql_'.$request->viewerLang)->table('tx_anonymous_users')->where([['anonumous_user', '=', request('viewerToken')]])->first();
  
    if(!$checkExistAnonymus){
        //error
        $response['status'] = "fail2";
  $response['error'] = "Unautherized user";
    }else{
      $dbLikes = Config::get('constants.MAIN_DB_PREF').'tx_main_db.anonymous_user_dummy_likes';
      $dummyUserId = "23xyzabc834";

      $vid = new UsersVideos;
      $response = $vid->fetchProfileVideosForViewers($request,$table,$dummyUserId);
    }
  }else{
  //error
  $response['status'] = "fail3";
  $response['error'] = "Unautherized user";
  }
  
         
              return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
               JSON_UNESCAPED_UNICODE);


}



public function getUserProfilePopularVideosForViewers(Request $request){

  $response = array();
  //first check
  if(request('viewerType') == 'txuser'){
  
  
    $table = 'users_videos_'.$request->activeTbSerialId;
      //check txuser existense
      $checkTxExistUser = DB::connection('mysql_'.$request->viewerLang)->table('tx_users')->where([['user_login_token', '=', request('viewerToken')]])->first();

      if(!$checkTxExistUser){
  //error
  $response['status'] = "fail1";
  $response['error'] = "Unautherized user";
    }else{
      $vid = new UsersVideos;
    
      $response = $vid->fetchProfilePopularVideosForViewers($request,$table,$checkTxExistUser->user_id);
    }
  
  }else if(request('viewerType') == 'anonymous'){

  
    $table = 'users_videos_'.$request->activeTbSerialId;
   
    $checkExistAnonymus = DB::connection('mysql_'.$request->viewerLang)->table('tx_anonymous_users')->where([['anonumous_user', '=', request('viewerToken')]])->first();
  
    if(!$checkExistAnonymus){
        //error
        $response['status'] = "fail2";
  $response['error'] = "Unautherized user";
    }else{
      $dbLikes = Config::get('constants.MAIN_DB_PREF').'tx_main_db.anonymous_user_dummy_likes';
      $dummyUserId = "23xyzabc834";

      $vid = new UsersVideos;
      $response = $vid->fetchProfilePopularVideosForViewers($request,$table,$dummyUserId);
    }
  }else{
  //error
  $response['status'] = "fail3";
  $response['error'] = "Unautherized user";
  }
  
         
              return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
               JSON_UNESCAPED_UNICODE);


}









public function getOriginalSoundVideo(Request $request){

  $response = array();
  $activeTbSerialId = 1;
  //first check
  $token = strtok($request->soundToken, "-");
 
  while ($token !== false)
     {
     $activeTbSerialId = $token;
     break;
     }


  if(request('viewerType') == 'txuser'){


      //check txuser existense
      $checkTxExistUser = DB::connection('mysql_'.$request->viewerLang)->table('tx_users')->where([['user_login_token', '=', request('viewerToken')]])->first();
    if(!$checkTxExistUser){
  //error
  $response['status'] = "fail1";
  $response['error'] = "Unautherized user";
    }else{
      $vid = new UsersVideos;
     // return "exist";

     $txDB = new TxDbs();

     $dbLikes = $txDB->getDB($request->viewerCountry,$request->viewerLang).'.user_'.$checkTxExistUser->user_id.'_likes';

      $response = $vid->fetchOriginalSoundVideo($request,$dbLikes,$activeTbSerialId);
    }
  
  }else if(request('viewerType') == 'anonymous'){

  
    $checkExistAnonymus = DB::connection('mysql_'.$request->viewerLang)->table('tx_anonymous_users')->where([['anonumous_user', '=', request('viewerToken')]])->first();
    if(!$checkExistAnonymus){
        //error
        $response['status'] = "fail2";
  $response['error'] = "Unautherized user";
    }else{
      $dbLikes = Config::get('constants.MAIN_DB_PREF').'tx_main_db.anonymous_user_dummy_likes';
    
      $vid = new UsersVideos;
      $response = $vid->fetchOriginalSoundVideo($request,$dbLikes,$activeTbSerialId);
    }
  }else{
  //error
  $response['status'] = "fail3";
  $response['error'] = "Unautherized user";
  }
  
         
  
              return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
               JSON_UNESCAPED_UNICODE);





}









public function getUseThisSoundVideos(Request $request){

  $response = array();
  //first check
  if(request('viewerType') == 'txuser'){
  
   
    $table = 'users_videos_'.$request->activeTbSerialId;
      //check txuser existense
      $checkTxExistUser = DB::connection('mysql_'.$request->viewerLang)->table('tx_users')->where([['user_login_token', '=', request('viewerToken')]])->first();
    if(!$checkTxExistUser){
  //error
  $response['status'] = "fail1";
  $response['error'] = "Unautherized user";
    }else{
      $vid = new UsersVideos;
     // return "exist";
  
      $response = $vid->fetchUseThisSoundVideos($request,$table,$checkTxExistUser->user_id);
    }
  
  }else if(request('viewerType') == 'anonymous'){

  
    $table = 'users_videos_'.$request->activeTbSerialId;
    $checkExistAnonymus = DB::connection('mysql_'.$request->viewerLang)->table('tx_anonymous_users')->where([['anonumous_user', '=', request('viewerToken')]])->first();
    if(!$checkExistAnonymus){
        //error
        $response['status'] = "fail2";
  $response['error'] = "Unautherized user";
    }else{
    
      $dummyId = "dummyid";
      $vid = new UsersVideos;
      $response = $vid->fetchUseThisSoundVideos($request,$table,$dummyId);
    }
  }else{
  //error
  $response['status'] = "fail3";
  $response['error'] = "Unautherized user";
  }
  
         
  
              return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
               JSON_UNESCAPED_UNICODE);


}


public function getSingleVideo(Request $request){
  $response = array();
  $vid = new UsersVideos;
  $response = $vid->fetchSingleVideo($request);

  return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
  JSON_UNESCAPED_UNICODE);

}







    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        

      if(request('purpose') == 'upload-cover-photo'){

        $videos = new UsersVideos();
        $videos->deleteEmptyRow($request);
        
        $txVT = new TxVideosTables();
      //  $tbVid = $txVT->getActiveTableId($request);

      
        $tempToken = request('user_id_fk').date("Ymd").date("hism");
        $photo_name = "CP".$tempToken.".png";
      
        $res = $videos->saveCoverPhoto($request,$photo_name);
        
        if($res == "saved"){
          $output = array(
            'token'  => md5(request('video_token')),
            'cover_photo'  => $photo_name,
            'status'   => "ok"
           );
        
            $txVT->updateTotalTableRows($request);
        }else if($res == "not saved"){
             $output = array(
              'token'  => "Data not saved please try again",
              'cover_photo'  => $photo_name,
              'status'  => "fail"
           );
        }else if($res == "user not exist"){
          $output = array(
            'token'  => "UnAutherized user",
            'cover_photo'  => $photo_name,
            'status'  => "Access Denied !"
        );
     }else if($res == "Photo not stored"){
      $output = array(
        'token'  => "Cover photo not saved try again",
        'cover_photo'  => $photo_name,
        'status'  => "fail"
  
    );
 }
        
       return response()->json($output, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);

      }

     else if(request('purpose') == 'upload-sound-file'){
   
     
        $videos = new UsersVideos();
        $res = $videos->saveSoundFile($request);
        if($res == "updated"){

          $output = array(
            'message'  => "Sound file stored...",
            'status'   => "ok"
           );

        }else if($res == "sound-store-fail"){
             $output = array(
              'message'   => "audio not stored please try again",
              'status'  => "fail"
           );
        }else{
          $output = array(
            'message'   => "Sound file not saved please try again",
            'status'  => "fail"
         );
        }

        return response()->json($output, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);



      }

     else if(request('purpose') == 'upload-video-file'){
     
  
        $videos = new UsersVideos();
        $res = $videos->saveVideoFile($request);
        if($res == "updated"){
          $output = array(
            'message'  => "video file stored...",
            'status'   => "ok"
           );

        }else if($res == "video-store-fail"){
             $output = array(
              'message'   => "video not stored please try again",
              'status'  => "fail"
           );
        }else{
          $output = array(
            'message'   => "video file not saved please try again",
            'status'  => "fail"
         );
        }

        return response()->json($output, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);



      }


      else if(request('purpose') == 'upload-cover-photo-with-sound-name'){
        $txVT = new TxVideosTables();
        $videos = new UsersVideos();
        $videos->deleteEmptyRow($request);
        $tempToken = request('user_id_fk').date("Ymd").date("hism");
        $photo_name = "CP".$tempToken.".png";
        $res = $videos->saveCoverPhotoWithSound($request,$photo_name);
        if($res == "saved"){
          $output = array(
            'token'  => md5(request('video_token')),
            'cover_photo'  => $photo_name,
            'status'   => "ok"
           );
        
            $txVT->updateTotalTableRows($request);
        }else if($res == "not saved"){
             $output = array(
              'token'  => "Data not saved please try again",
              'cover_photo'  => $photo_name,
              'status'  => "fail"
           );
        }else if($res == "user not exist"){
          $output = array(
            'token'  => "UnAutherized user",
            'cover_photo'  => $photo_name,
            'status'  => "Access Denied !"
        );
     }else if($res == "Photo not stored"){
      $output = array(
        'token'  => "Cover photo not saved try again",
        'cover_photo'  => $photo_name,
        'status'  => "fail"
  
    );
 }
        
       return response()->json($output, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);

      }


      else if(request('purpose') == 'video-view-add-one'){
        $response = array();
       
        $videos = new UsersVideos();
        $res = $videos->addOneViewToVideo($request);
        if($res > 0){
          $response['status'] = "success";

        }else{
          $response['status'] = "fail";


        }
       return json_encode($response);

      }
      
      else if(request('purpose') == 'video-share-add-one'){
        $response = array();
       
        $videos = new UsersVideos();
        $res = $videos->addOneShareToVideo($request);
        if($res > 0){
          $response['status'] = "success";

        }else{
          $response['status'] = "fail";


        }
       return json_encode($response);

      }
      

      else if(request('purpose') == 'video-privacy'){
        $response = array();
       
        $videos = new UsersVideos();
        $res = $videos->videoPrivacySettings($request);
        if($res > 0){
          $response['status'] = "success";

        }else{
          $response['status'] = "fail";


        }
       return json_encode($response);

      }



      else if(request('purpose') == 'video-delete'){
        $response = array();
       
        $videos = new UsersVideos();
        $res = $videos->deleteVideo($request);
        if($res > 0){
          $response['status'] = "success";

        }else{
          $response['status'] = "fail";


        }
       return json_encode($response);

      }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UsersVideos  $usersVideos
     * @return \Illuminate\Http\Response
     */
    public function show(UsersVideos $usersVideos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UsersVideos  $usersVideos
     * @return \Illuminate\Http\Response
     */
    public function edit(UsersVideos $usersVideos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UsersVideos  $usersVideos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UsersVideos $usersVideos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UsersVideos  $usersVideos
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsersVideos $usersVideos)
    {
        //
    }
}
