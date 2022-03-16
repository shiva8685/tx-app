<?php


namespace App\Http\Controllers\apis;
use DB;
use Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\TxUsers;
use App\TxDbs;
use App\TxAnonymousUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TxUsersController extends Controller
{
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getMyProfileDetails(Request $request){
        $response = array();
          $vid = new TxUsers;

      $txUsers = new TxUsers;
      $checkExistUser = $txUsers->userIsExistByToken($request->lang,$request->userToken);
      if($checkExistUser == 1){
        $response = $vid->getSingleMyProfileDetails($request);
      
      }else{
      //error
      $response['status'] = "fail";
      $response['error'] = "Unautherized user";
      }
         
              return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
               JSON_UNESCAPED_UNICODE);
      
      }
      

    public function getUserProfileDetails(Request $request){
        $response = array();
        $vid = new TxUsers;


if($request->viewerType == 'txuser'){
   
    $txUsers = new TxUsers;
    $checkExistUser = $txUsers->userIsExistByToken($request->viewerLang,$request->viewerToken);
    if($checkExistUser == 1){
      $response = $vid->getSingleUserProfileDetails($request);
    
    }else{
    //error
    $response['status'] = "fail 1";
    $response['error'] = "Unautherized user";
    }
       


}else{
    
    $txUsers = new TxAnonymousUsers;
    $userAnonymus = $txUsers->anonymousUserIsExist($request->viewerLang,$request->viewerToken);
    if($userAnonymus == 1){
  
        $response = $vid->getSingleUserProfileDetails($request);
      
      }else{
      //error
      $response['status'] = "fail 2";
      $response['error'] = "Unautherized user";
      }
         

}

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
      
        $response = array();

        if(request('purpose') == "new-account"){
        
  
$txUsers = new TxAnonymousUsers;
$userAnonymus = $txUsers->anonymousUserIsExist($request->lang,$request->anonymous_id);
if($userAnonymus == 1){

    $txUser = new TxUsers();
    $res = $txUser->storeNewAccount($request);
 //return $res;
           $user = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_id', '=', request('login_id')]])->first();
           
           if(!$user){
           // return "user not exist ".$res;
            $response['status'] = "fail";
            $response['error'] = "User not exist";
            return json_encode($response);
           }
           else{
           // return $user;
          // $output = $this->createTables($request,$user);

 //  return "Output ".$output;
   $response['status'] = 'success';
   $response['userId'] = $user->user_id;
   $response['userName'] = $user->user_name;
   $response['userLoginToken'] = $user->user_login_token;
   $response['userHashTag'] = $user->user_hashtag_name;
   $response['userLoginId'] = $user->user_login_id;
   $response['password'] = $user->user_password;
   $response['userProfilePhoto'] = $user->user_profile_image;
   $response['userProfileVideo'] = $user->user_profile_video;
       $response['userDpType'] = $user->user_dp_type;
       $response['aboutUser'] = $user->about_user;
       return json_encode($response);

           }
}else{
    $response['status'] = "fail";
    $response['error'] = "Session expired";
    return json_encode($response);

        } 
    
    } // new account

    if(request('purpose') == "create-tables"){
       
        $user = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_id', '=', request('uid')]])->first();
           
          if($user){

            if(!Schema::connection('mysql_'.request('lang'))->hasTable('user_'.request('uid').'_likes')){
         $this->createLikeTables($request,$user);
                $response['status'] = "success";
                $response['msg'] = " Likes success ".request('uid');
            } else{
                $response['status'] = "error";
                $response['msg'] = "error creating likes table ".request('uid');
            }

            if(!Schema::connection('mysql_'.request('lang'))->hasTable('user_'.request('uid').'_follow_unfollow_friends')){
                $this->createFollowUnFollowTables($request,$user);
                       $response['status'] = "success";
                $response['msg'] = " create FollowU nFollow  Tables success ".request('uid');
                   } else{
                       $response['status'] = "error";
                       $response['msg'] = "error creating FollowU nFollow table ".request('uid');
                   }

                   if(!Schema::connection('mysql_'.request('lang'))->hasTable('user_'.request('uid').'_chat')){
                    $this->createChattingTable($request,$user);
                           $response['status'] = "success";
                    $response['msg'] = " create Chatting Tables success ".request('uid');
                       } else{
                           $response['status'] = "error";
                           $response['msg'] = "error creating chatting table ".request('uid');
                       }


            if(!Schema::connection('mysql_'.request('lang'))->hasTable('user_'.request('uid').'_comments')){
                $this->createCommentTables($request,$user);
              
                $response['status'] = "success";
                $response['msg'] = "Comments success ".request('uid');
                   } else{
                    $response['status'] = "error";
                    $response['msg'] = "error creating comments table ".request('uid');
                }

                   if(!Schema::connection('mysql_'.request('lang'))->hasTable('user_'.request('uid').'_comment_likes')){
            
                    $this->createCommentLikesTables($request,$user);
                  
                    $response['status'] = "success";
                    $response['msg'] = " cmnt Likes success ".request('uid');
                       } else{
                        $response['status'] = "error";
                        $response['msg'] = "error creating comment likes table ".request('uid');
                    }

                    
                    if(!Schema::connection('mysql_'.request('lang'))->hasTable('user_'.request('uid').'_favorites')){
        
                        $this->createFavoritesTables($request,$user);
                        $response['status'] = "success";
                        $response['msg'] = " favorites success ".request('uid');
                           } else{
                            $response['error'] = "exist";
                            $response['msg'] = "error creating favorites table ".request('uid');
                        }
           
                        if(!Schema::connection('mysql_'.request('lang'))->hasTable('user_'.request('uid').'_inbox')){
        
                            $this->createInboxTables($request,$user);
                            $response['status'] = "success";
                            $response['msg'] = " inbox success ".request('uid');
                               } else{
                                $response['error'] = "exist";
                                $response['msg'] = "error creating inbox table ".request('uid');
                            }
               

                       if(!Schema::connection('mysql_'.request('lang'))->hasTable('user_'.request('uid').'_comment_replies')){
        
                        $this->createCommentRepleyTables($request,$user);
                        $response['status'] = "success";
                        $response['msg'] = " comnt replies success ".request('uid');
                           } else{
                            $response['status'] = "success";
                          // $response['status'] = "success";
                            $response['msg'] = "error creating replies table ".request('uid');
                        }
           
         
        } else{

            $response['status'] = "fail";
            $response['error'] = "User not exist";
          //  return json_encode($response);
        }
return json_encode($response);
       
 //return response()->json($response." result", 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
         //JSON_UNESCAPED_UNICODE);

    }//end create tables

    if(request('purpose') == "db-checking"){
        $txDB = new TxDbs();

        $db = $txDB->getDB($request->country,$request->lang);


        if(Schema::connection('mysql_'.request('lang'))->hasTable('user_'.request('uid').'_comment_replies')){
            $user = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_id', '=', request('uid')]])->first();

            DB::connection('mysql_'.$request->lang)->table('tx_anonymous_users')->where('anonumous_user',request('anonymous_id'))->delete();

            $response['status'] = 'success';
            $response['userId'] = $user->user_id;
            $response['userName'] = $user->user_name;
            $response['userLoginToken'] = $user->user_login_token;
            $response['userHashTag'] = $user->user_hashtag_name;
            $response['userLoginId'] = $user->user_login_id;
            $response['password'] = $user->user_password;
            $response['userProfilePhoto'] = $user->user_profile_image;
            $response['userProfileVideo'] = $user->user_profile_video;
                $response['userDpType'] = $user->user_dp_type;
                $response['aboutUser'] = $user->about_user;
                return json_encode($response);
         }else{
            $response['status'] = 'fail'; // tables not found
            return json_encode($response);
         }


    }//tables existense checking


        if(request('purpose') == "exist-user-check"){
  
    $user = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_id', '=', $request->user_login_id]])->first();

if($user){
  
    $response['status'] = "exist";
    $response['userId'] = $user->user_id;
    $response['userName'] = $user->user_name;
    $response['userLoginToken'] = $user->user_login_token;
    $response['userHashTag'] = $user->user_hashtag_name;
    $response['userLoginId'] = $user->user_login_id;
    $response['password'] = $user->user_password;
    $response['userProfilePhoto'] = $user->user_profile_image;
    $response['userProfileVideo'] = $user->user_profile_video;
        $response['userDpType'] = $user->user_dp_type;
        $response['aboutUser'] = $user->about_user;

 DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_login_id', $request->user_login_id)->update(array('user_firebase_token' => $request->firebase_token));

    
}else{

    $response['status'] = "not exist";

}

return json_encode($response);

    }


    
    if(request('purpose') == "login-checking"){
     
        $txUsers = new TxAnonymousUsers;
        $userAnonymus = $txUsers->anonymousUserIsExist($request->lang,$request->anonymous_id);
        if($userAnonymus == 0){
            $response['status'] = "fail";
            $response['error'] = "Unauthorized user";
            return json_encode($response);
        }

        $txuser = new TxUsers;
        $user = $txuser->checkUserLogin($request);

       
if(!$user){
$response['status'] = "not exist";
}else{

$response['status'] = "exist";
$response['userId'] = $user->user_id;
$response['userName'] = $user->user_name;
$response['userLoginToken'] = $user->user_login_token;
$response['userHashTag'] = $user->user_hashtag_name;
$response['userLoginId'] = $user->user_login_id;
$response['password'] = $user->user_password;
$response['userProfilePhoto'] = $user->user_profile_image;
$response['userProfileVideo'] = $user->user_profile_video;
    $response['userDpType'] = $user->user_dp_type;
    $response['aboutUser'] = $user->about_user;

    DB::connection('mysql_'.$request->lang)->table('tx_users')->where('user_login_id', $request->user_login_id)->update(array('user_firebase_token' => $request->firebase_token));

    DB::connection('mysql_'.$request->lang)->table('tx_anonymous_users')->where([['anonumous_user', '=', $request->anonymous_id] ])->delete();

    
}

return json_encode($response);

}

if(request('purpose') == "sending-otp-to-email"){
    $txDB = new TxDbs();

    $db = $txDB->getDB($request->country,$request->lang);

    $userAnonymus = DB::connection('mysql_'.$request->lang)->table('tx_anonymous_users')->where([['anonumous_user', '=', request('anonymous_id')]])->first();
   
    if(!$userAnonymus){
        $response['status'] = "fail";
        $response['error'] = "Unauthorized user";
        return json_encode($response);
    }


    $checkExistUser =  DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_id', '=', $request->email]])->first();
    if(!$checkExistUser){
        $response['status'] = "fail";
        $response['error'] = "Unauthorized You are not our user";
        return json_encode($response);
    }



//otp created
$otpNumber = mt_rand(100000, 999999);
  
//$otpToEncode = base64_encode($otpNumber);
//$otpbase64ToMd5 =  md5($otpToEncode);

    if($this->sendOtpToEmail($request->email,$otpNumber) == 200){
      
$response['status'] = "success";
$response['response'] = $otpNumber;

     }else{
$response['status'] = "Error";
$response['response'] = "Sending Otp failed";
     }

return json_encode($response);

}


if(request('purpose') == "sending-otp-to-phone"){
    $txDB = new TxDbs();

        $db = $txDB->getDB($request->country,$request->lang);

    $userAnonymus =  DB::connection('mysql_'.$request->lang)->table('tx_anonymous_users')->where([['anonumous_user', '=', request('anonymous_id')]])->first();
   
    if($userAnonymus){
      
        $checkExistUser =  DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_id', '=', $request->phone]])->first();
        if($checkExistUser){
            $response['status'] = "success";
            $response['response'] = "User is valid";
            return json_encode($response);
        }else{
            $response['status'] = "fail";
            $response['error'] = "Unauthorized user";
            return json_encode($response);
        }



    }else{
        $response['status'] = "fail";
        $response['error'] = "Unauthorized user";
        return json_encode($response);
        
    }


  



//otp created
$otpNumber = 767512;//mt_rand(100000, 999999);
  
//$otpToEncode = base64_encode($otpNumber);
//$otpbase64ToMd5 =  md5($otpToEncode);

    if($this->sendOtpToPhone($request->phone,$otpNumber) == 200){
      
$response['status'] = "success";
$response['response'] = $otpNumber;

     }else{
        $response['status'] = "Error";
$response['response'] = "Sending Otp failed";
     }

return json_encode($response);

}


if(request('purpose') == "reset-password"){
  
    $userAnonymus =  DB::connection('mysql_'.$request->lang)->table('tx_anonymous_users')->where([['anonumous_user', '=', request('anonymous_id')]])->first();
   
    if($userAnonymus){
      
        $checkExistUser =  DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_id', '=', $request->user_login_id]])->first();
        if($checkExistUser){

            $txuser = new TxUsers();
            $res = $txuser->resetPassword($request);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = "reset successfully...";
        return json_encode($response);
    }else{
        $response['status'] = "fail";
        $response['error'] = "Password not reset try again";
        return json_encode($response);
    }

          
        }else{
            $response['status'] = "fail";
            $response['error'] = "Unauthorized user";
            return json_encode($response);
        }



    }else{
        $response['status'] = "fail";
        $response['error'] = "Unauthorized user";
        return json_encode($response);
        
    }


}



    if(request('purpose') == "update-profile-details"){
     
$checkExistUser =  DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_token', '=', $request->userToken]])->first();

if($checkExistUser){
    $txUser = new TxUsers();
    $res = $txUser->updateUserDatails($request);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = "updated";
    }


}else{

$response['status'] = "fail";
$response['error'] = "Session expired";


}
return json_encode($response);
    }

    if(request('purpose') == "update-profile-photo"){
        $photoName = $request->userId."_".$request->lang."_".date("Ymd").date("hism").".png";
      
$checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_token', '=', $request->userToken]])->first();

if($checkExistUser){
    $txUser = new TxUsers();
    $res = $txUser->updateProfilePhoto($request,$photoName);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = $photoName;
    }else{
        $response['status'] = "fail";
        $response['response'] = "not updated";
    }
}else{

    $response['status'] = "fail";
    $response['error'] = "Session expired";
    
}
return json_encode($response);

    }


    if(request('purpose') == "update-hashtag-name"){
      
       
$checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_token', '=', $request->userToken]])->first();

if($checkExistUser){
    $txUser = new TxUsers();
    $res = $txUser->updateHashTagName($request);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = "updated";
    }else{
        $response['status'] = "fail";
        $response['response'] = "not updated";
    }
}else{

    $response['status'] = "fail";
    $response['error'] = "Session expired";
    
}
return json_encode($response);

    }


    
    if(request('purpose') == "update-privacy-safety"){
        $photoName = $request->userId."-".date("Ymd").date("hism").".png";
      
$checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_token', '=', $request->userToken]])->first();

if($checkExistUser){
    $txUser = new TxUsers();
    $res = $txUser->updatePrivacySafety($request);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = "updated";
    }else{
        $response['status'] = "fail";
        $response['response'] = "not updated";
    }
}else{

    $response['status'] = "fail";
    $response['error'] = "Session expired";
    
}
return json_encode($response);

    }


    if(request('purpose') == "update-passwrd"){
        $photoName = $request->userId."-".date("Ymd").date("hism").".png";
      
$checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([
    ['user_login_token', '=', $request->userToken],
    ['user_id', '=', $request->userId],
    ['user_login_id', '=', $request->userLoginId]
    
    ])->first();

if($checkExistUser){
    $txUser = new TxUsers();
    $res = $txUser->updatePassword($request);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = "updated";
    }else{
        $response['status'] = "fail";
        $response['response'] = "not updated";
    }
}else{

    $response['status'] = "fail";
    $response['error'] = "Session expired";
    
}
return json_encode($response);

    }



    if(request('purpose') == "follow-user"){
    
$checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([
    ['user_login_token', '=', $request->myToken],
    ['user_id', '=', $request->myId]
    ])->first();

if($checkExistUser){
    $txUser = new TxUsers();
    $res = $txUser->followUser($request);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = "saved";
    }else{
        $response['status'] = "fail";
        $response['response'] = "not saved";
    }
}else{

    $response['status'] = "fail";
    $response['error'] = "Session expired";
    
}
return json_encode($response);

    }

    
    if(request('purpose') == "follow-back"){
    
        
$checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([
    ['user_login_token', '=', $request->myToken],
    ['user_id', '=', $request->myId]
    ])->first();

if($checkExistUser){
    $txUser = new TxUsers();
    $res = $txUser->followBack($request);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = "saved";
    }else{
        $response['status'] = "fail";
        $response['response'] = "not saved";
    }
}else{

    $response['status'] = "fail";
    $response['error'] = "Session expired";
    
}
return json_encode($response);

    }



    if(request('purpose') == "unfollow-user"){
    
    
$checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([
    ['user_login_token', '=', $request->myToken],
    ['user_id', '=', $request->myId]
    ])->first();

if($checkExistUser){
    $txUser = new TxUsers();
    $res = $txUser->unFollowUser($request);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = "saved";
    }else{
        $response['status'] = "fail";
        $response['response'] = "not saved";
    }
}else{

    $response['status'] = "fail";
    $response['error'] = "Session expired";
    
}
return json_encode($response);

    }


    

    if(request('purpose') == "follow-back-fromfriend"){
    
        
$checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([
    ['user_login_token', '=', $request->myToken],
    ['user_id', '=', $request->myId]
    ])->first();

if($checkExistUser){



    $txUser = new TxUsers();

if($request->whoIsFollowFirst == 'this_person_follow'){
    // so here first send follow(button clicker) follower remove his friendship
    $res = $txUser->removeFriendsShipFromFollowing($request);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = "saved";
    }else{
        $response['status'] = "fail";
        $response['response'] = "not saved";
    }
}else{
    // this person follow back
    // so here who is following him remove his friendship
    $res = $txUser->removeFriendsShipFromFollower($request);
    if($res > 0){
        $response['status'] = "success";
        $response['response'] = "saved";
    }else{
        $response['status'] = "fail";
        $response['response'] = "not saved";
    }
}

  




}else{

    $response['status'] = "fail";
    $response['error'] = "Session expired";
    
}
return json_encode($response);

    }


    }


public function checkhashtag(Request $request){
    $response = array();
  
  //  $checkExistUser = DB::connection('mysql_'.$request->lang)->table('tx_users')->where([['user_login_token', '=', $request->userToken]])->first();
  
        $txUser = new TxUsers();
        $res = $txUser->checkingUserHashTag($request);
        if($res == 'exist'){
            $response['status'] = "success";
            $response['response'] = "exist";
        }else{
            $response['status'] = "success";
            $response['response'] = "not exist";
        }
    
    return json_encode($response);
   
 
 
}



public function getUserFollowersList(Request $request){
    $response = array();

$txUser = new TxUsers();
$response = $txUser->fetchProfilerFollowers($request);


    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
         JSON_UNESCAPED_UNICODE);
}

public function getUserFollowingList(Request $request){
    $response = array();

$txUser = new TxUsers();
$response = $txUser->fetchProfilerFollowing($request);


    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
         JSON_UNESCAPED_UNICODE);
}




    /**
     * Display the specified resource.
     *
     * @param  \App\TxUsers  $txUsers
     * @return \Illuminate\Http\Response
     */
    public function show(TxUsers $txUsers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TxUsers  $txUsers
     * @return \Illuminate\Http\Response
     */
    public function edit(TxUsers $txUsers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TxUsers  $txUsers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TxUsers $txUsers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TxUsers  $txUsers
     * @return \Illuminate\Http\Response
     */
    public function destroy(TxUsers $txUsers)
    {
        //
    }




public function createLikeTables($request,$user){
         
          //Creating separate likes table for every new user
        Schema::connection('mysql_'.request('lang'))->create('user_'.$user->user_id.'_likes', function($table)
            {
                $table->bigIncrements('like_id');
                $table->string('liker_lang',50); 
                $table->string('liker_country',50);
                $table->bigInteger('tb_serial_id_fk')->default(0); 
                $table->index('tb_serial_id_fk'); 
              //  $table->bigInteger('tb_serial_id_fk')->unsigned();  
              //  $table->foreign('tb_serial_id_fk')->references('tb_id')->on(Config::get('constants.MAIN_DB_PREF').'tx_main_db.tx_videos_tables')->onDelete('cascade'); 
               
                $table->bigInteger('user_video_id_fk')->default(0); 
                $table->bigInteger('user_id_fk')->default(0);
                //$table->string('liker_hash_id')->default('empty');
                $table->bigInteger('liker_id_fk')->default(0);
                //$table->foreign('liker_id_fk')->references('user_id')->on('tx_'.request('country').'_lang_'.request('lang').'.tx_users')->onDelete('cascade'); 
               
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            });

            

}



public function createFollowUnFollowTables($request,$user){
         
    //Follow and Unfollow and friends table
  Schema::connection('mysql_'.request('lang'))->create('user_'.$user->user_id.'_follow_unfollow_friends', function($table)
      {
          $table->bigIncrements('fuf_id');
          $table->string('fuf_lang',50); 
          $table->string('fuf_country',50); 
          $table->bigInteger('following_user_id_fk')->default(0); 
          $table->bigInteger('follower_user_id_fk')->default(0);
          $table->Integer('fuf_user_type')->default(0);  // 1 means follow 2 means friends
          $table->bigInteger('who_is_first_following')->default(0);  // storing first send follow requester id
          $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      });

}


public function createChattingTable($request,$user){
        
  Schema::connection('mysql_'.request('lang'))->create('user_'.$user->user_id.'_chat', function($table)
      {
          $table->bigIncrements('chat_id');
          $table->bigInteger('chat_no')->default(0);
          $table->string('chater_lang',50); 
          $table->string('chater_country',50); 
          $table->bigInteger('chater_user_id_fk')->default(0);
          $table->bigInteger('sender_id')->default(0);
          $table->string('message',650)->default('e');  // e means empty
          $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      });

      

}


public function createCommentTables($request,$user){
 //Creating separate comments table for every new user
 Schema::connection('mysql_'.request('lang'))->create('user_'.$user->user_id.'_comments', function($table)
 {
     $table->bigIncrements('comment_id');
     $table->string('commenter_lang',50); 
     $table->string('commenter_country',50); 
     $table->bigInteger('tb_serial_id_fk')->default(0); 
     $table->index('tb_serial_id_fk');
   //  $table->bigInteger('tb_serial_id_fk')->unsigned();  
     //$table->foreign('tb_serial_id_fk')->references('tb_id')->on(Config::get('constants.MAIN_DB_PREF').'tx_main_db.tx_videos_tables')->onDelete('cascade'); 
    
     $table->bigInteger('user_video_id_fk')->default(0); 
     $table->bigInteger('user_id_fk')->default(0);
    // $table->string('commenter_hash_id',191)->default("empty"); 
     $table->bigInteger('commenter_id_fk')->default(0); 
    // $table->foreign('commenter_id_fk')->references('user_id')->on('tx_'.request('country').'_lang_'.request('lang').'.tx_users')->onDelete('cascade'); 
     $table->string('video_comment_msg',650); 
     $table->bigInteger('comment_likes')->default(0); 
     $table->integer('total_comment_replies')->default(0); 
     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
 });

 


}

public function createCommentLikesTables($request,$user){
  //Creating separate comments liked table for every new user
  Schema::connection('mysql_'.request('lang'))->create('user_'.$user->user_id.'_comment_likes', function($table) use ($user)
  {
      $table->bigIncrements('comment_likes_id');
      $table->string('comment_liker_lang',50); 
      $table->string('comment_liker_country',50); 
      $table->bigInteger('tb_serial_id_fk')->default(0); 
      $table->index('tb_serial_id_fk');
     // $table->bigInteger('tb_serial_id_fk')->unsigned();  
     // $table->foreign('tb_serial_id_fk')->references('tb_id')->on(Config::get('constants.MAIN_DB_PREF').'tx_main_db.tx_videos_tables')->onDelete('cascade'); 
               
      $table->bigInteger('user_video_id_fk')->default(0); 
      $table->bigInteger('user_id_fk')->default(0);
    //  $table->string('commenter_hash_id',191)->default("empty"); 
      $table->bigInteger('comment_id_fk')->default(0);  
   
      $table->bigInteger('user_comment_liker_id_fk')->default(0); 
     // $table->foreign('user_comment_liker_id_fk')->references('user_id')->on('tx_'.request('country').'_lang_'.request('lang').'.tx_users')->onDelete('cascade'); 

      $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
  });



}







public function createFavoritesTables($request,$user){
    //Creating separate comments replies table for every new user
    Schema::connection('mysql_'.request('lang'))->create('user_'.$user->user_id.'_favorites', function($table) use ($user)
    {
        $table->bigIncrements('fav_id');
        $table->string('fav_lang',50); 
        $table->string('fav_country',50); 
        $table->integer('fav_type')->default(0);   // 1 means audio, 2 means video, 3 means image etc...
        $table->integer('tb_serial_id_fk')->default(0); 
        $table->string('fav_token_id',300)->default("e");  // is any of one video id,sound id etc...
        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
    });
   
   
   }

   

   public function createInboxTables($request,$user){
    //Creating separate comments liked table for every new user
    Schema::connection('mysql_'.request('lang'))->create('user_'.$user->user_id.'_inbox', function($table) use ($user)
    {
        $table->bigIncrements('inbox_id');
        $table->string('user_lang',50)->default('e');
        $table->string('user_country',50)->default('e');
        $table->bigInteger('tb_serial_id_fk')->default(0); 
        $table->index('tb_serial_id_fk');

       // $table->bigInteger('tb_serial_id_fk')->unsigned();  
        //$table->foreign('tb_serial_id_fk')->references('tb_id')->on(Config::get('constants.MAIN_DB_PREF').'tx_main_db.tx_videos_tables')->onDelete('cascade'); 
      
        $table->bigInteger('video_holder_id')->default(0); 
        $table->index('video_holder_id');
        $table->string('vid_lang',50)->default('e'); 
        $table->string('vid_country',50)->default('e');
        $table->bigInteger('video_id')->default(0); 
        $table->index('video_id');
        $table->bigInteger('user_id')->default(0);
        $table->index('user_id');
        $table->integer('msg_type')->default(0);  // 0 for liked,1 for comment,2 for comment like,3 for comment reply,4 for comment reply like,5 for follow request,6 for follow back(friends)
        $table->string('msg',650)->default('e');
        $table->bigInteger('comment_id')->default(0);
        $table->bigInteger('comment_reply_id')->default(0);
        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      
    });
  
  
  
  }
  




public function createCommentRepleyTables($request,$user){
 //Creating separate comments replies table for every new user
 Schema::connection('mysql_'.request('lang'))->create('user_'.$user->user_id.'_comment_replies', function($table) use ($user)
 {
     $table->bigIncrements('comment_reply_id');
     $table->string('comment_replier_lang',50); 
     $table->string('comment_replier_country',50); 
     $table->bigInteger('tb_serial_id_fk')->default(0); 
     $table->index('tb_serial_id_fk');
    // $table->foreign('tb_serial_id_fk')->references('tb_id')->on(Config::get('constants.MAIN_DB_PREF').'tx_main_db.tx_videos_tables')->onDelete('cascade'); 
    
     $table->bigInteger('user_video_id_fk')->default(0); 
     $table->bigInteger('user_id_fk')->default(0);
    // $table->string('commenter_hash_id',191)->default("empty"); 
     $table->bigInteger('comment_id_fk')->default(0); 
     $table->bigInteger('comment_replier_id_fk')->default(0); 
     //$table->foreign('comment_replier_id_fk')->references('user_id')->on('tx_'.request('country').'_lang_'.request('lang').'.tx_users')->onDelete('cascade'); 
     $table->string('cmnt_reply_msg',650); 
     $table->bigInteger('comment_likes')->default(0); 
     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
 });


}




public function sendOtpToEmail($email,$otpNumber){

//return 200;

   
ini_set("sendmail_from", Config::get('constants.WEBSITE_APP_NAME'));  
//ini_set( 'display_errors', 0 );
$from = "funfida.com";
$to = $email; 
$storeotp=$otpNumber;
$subject = $otpNumber." IS YOUR OTP "; 

$msg=$otpNumber." - IS YOUR OTP NUMBER";
$message ="<center><font color='#167B8E' size='5'><b><i>Welcom&emsp;$to</i></b></font><br/><p style='font-size:18px'> Funfida </p><font color='blue' size='5'><br/>Your OTP Number is<br/><br/>$otpNumber</font></center>";  

$headers = "From:" . $from;
$headers .= "\r\n";  
$headers .= "Content-type: text/html;charset=UTF-8 \r\n";  

$result = mail ($to,$subject,$message,$headers);  

if($result == true ){  
 
return 200; // OTP sent
  
}else{
    return 404; // OTP not sent
}



}



}
