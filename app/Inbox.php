<?php

namespace App;
use App\TxUsers;
use App\TxVideosTables;
use App\TxSoundsTables;
use App\UsersSounds;
use App\Chat;
use App\Paths;
use DB;
use App\TxDbs;
use Config;
use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    //
    public function getNewLikesCount($request){
        $dbInboxTb = 'user_'.$request->userId.'_inbox';
       
        $res =  DB::connection('mysql_'.$request->lang)->table($dbInboxTb)
        ->where([['msg_type', '=', 0]])
        ->whereDate('created_at', '=', date('Y-m-d'))
        ->orderBy('inbox_id', 'DESC')->count();
        return $res;

    }
    public function getNewCommentsCount($request){
        $dbInboxTb = 'user_'.$request->userId.'_inbox';
    
        $res = DB::connection('mysql_'.$request->lang)->table($dbInboxTb)
        ->whereIn('msg_type', ['1', '2','3','4'])
        ->whereDate('created_at', '=', date('Y-m-d'))
        ->orderBy('inbox_id', 'DESC')->count();
        return $res;

    }
    public function getNewRequestsCount($request){
        $dbInboxTb = 'user_'.$request->userId.'_inbox';
    
        $res = DB::connection('mysql_'.$request->lang)->table($dbInboxTb)
        ->where([['msg_type', '=', 5]])
        ->whereDate('created_at', '=', date('Y-m-d'))
        ->orderBy('inbox_id', 'DESC')->count();
        return $res;

    }

    public function getNewChatMessagesCount($request){
        $chatDB = 'user_'.$request->userId.'_chat';
    
        $res =  DB::connection('mysql_'.$request->lang)->table($chatDB)
        ->where([['chater_user_id_fk', '!=', $request->userId]])
        ->whereDate('created_at', '=', date('Y-m-d'))
        ->orderBy('chat_id', 'DESC')->count();
        return $res;

    }


public function saveLikeInbox($request){
    $dbInboxTb = 'user_'.$request->userId.'_inbox';
    
    $res =  DB::connection('mysql_'.$request->lang)->table($dbInboxTb)->insert(
        array('user_lang' => request('likerLang'),
            'user_country' => request('likerCountry'),
            'tb_serial_id_fk' => request('tbSerialId'),
            'video_holder_id' => request('userId'),
            'vid_lang' => request('lang'),
            'vid_country' => request('country'),
            'video_id' => request('userVideoIdFk'),
            'user_id' => request('likerIdFk'),
                                               //msg_type = default 0 for like video
            )
    );
return $res;

}


public function saveCommentInbox($request){

  
    $dbInboxTb = 'user_'.$request->userId.'_inbox';
    
    $res =  DB::connection('mysql_'.$request->lang)->table($dbInboxTb)->insert(
        array('user_lang' => request('commenterLang'),
            'user_country' => request('commenterCountry'),
            'tb_serial_id_fk' => request('tbSerialId'),
            'video_holder_id' => request('userId'),
            'vid_lang' => request('lang'),
            'vid_country' => request('country'),
            'video_id' => request('userVideoIdFk'),
            'user_id' => request('commenterIdFk'),
            'msg_type' => 1, // 1 for comment
            'msg' => request('comment')
           
            )
    );
return $res;

}


public function saveCommentLikeInbox($request){
    $txDB = new TxDbs();
    
    $dbInboxTb = $txDB->getDB($request->commentOwnerCountry,$request->commentOwnerLang).'.user_'.$request->commentOwnerId.'_inbox';
    
    $tb = 'user_'.$request->commentOwnerId.'_inbox';


    $res =  DB::connection('mysql_'.$request->commentOwnerLang)->table($tb)->insert(
        array('user_lang' => request('commentLikerLang'),
            'user_country' => request('commentLikerCountry'),
            'tb_serial_id_fk' => request('tbSerialId'),
            'video_holder_id' => request('userId'),
            'vid_lang' => request('lang'),
            'vid_country' => request('country'),
            'video_id' => request('userVideoIdFk'),
            'user_id' => request('commentLikerIdFk'),
            'msg_type' => 2, // 2 for comment like
            'comment_id' => request('commentId')
           
            )
    );
return $res;

}


public function saveCommentReplyInbox($request){
    $txDB = new TxDbs();
    
    $dbInboxTb = $txDB->getDB($request->commentOwnerCountry,$request->commentOwnerLang).'.user_'.$request->commentOwnerId.'_inbox';
    $tb = 'user_'.$request->commentOwnerId.'_inbox';



    $res =   DB::connection('mysql_'.$request->commentOwnerLang)->table($tb)->insert(
        array('user_lang' => request('commenterLang'),
            'user_country' => request('commenterCountry'),
            'tb_serial_id_fk' => request('tbSerialId'),
            'video_holder_id' => request('userId'),
            'vid_lang' => request('lang'),
            'vid_country' => request('country'),
            'video_id' => request('userVideoIdFk'),
            'user_id' => request('commentReplierId'),
            'msg_type' => 3, // 3 for comment reply
            'msg' => request('comment'),
            'comment_id' => request('commentIdFk')
           
            )
    );
return $res;

}



public function saveCommentReplyLikeInbox($request){
    $txDB = new TxDbs();
    
    $dbInboxTb = $txDB->getDB($request->commentOwnerCountry,$request->commentOwnerLang).'.user_'.$request->commentOwnerId.'_inbox';
   
    $tb = 'user_'.$request->commentOwnerId.'_inbox';

    $res = DB::connection('mysql_'.$request->commentOwnerLang)->table($tb)->insert(
        array('user_lang' => request('likerLang'),
            'user_country' => request('likerCountry'),
            'tb_serial_id_fk' => request('tbSerialId'),
            'video_holder_id' => request('userId'),
            'vid_lang' => request('lang'),
            'vid_country' => request('country'),
            'video_id' => request('userVideoIdFk'),
            'user_id' => request('likerId'),
            'msg_type' => 4, // 4 for comment reply like
            'comment_id' => request('commentId'),
            'comment_reply_id' => request('commentReplyId')
           
            )
    );
return $res;

}

public function saveFollowUserInbox($request){
    $txDB = new TxDbs();
    
    $dbInboxTb = $txDB->getDB($request->profilerCountry,$request->profilerLang).'.user_'.$request->profilerId.'_inbox';
    
    $tb = 'user_'.$request->profilerId.'_inbox';

    $res =  DB::connection('mysql_'.$request->profilerLang)->table($tb)->insert(
        array('user_lang' => request('lang'),
            'user_country' => request('country'),
            'tb_serial_id_fk' => 1, // 1 is dummy value there is no use but we have set to default tb serial id
            'video_holder_id' => request('myId'), // dummy 
            'vid_lang' => request('lang'), //dummy no use
            'vid_country' => request('country'), //dummy no use
            'user_id' => request('myId'),
            'msg_type' => 5 // 5 for someone follow you back to follow
           
            )
    );
return $res;

}

public function saveFollowBackUserInbox($request){
   
  
   $dbInboxMyTb = 'user_'.$request->myId.'_inbox';

    DB::connection('mysql_'.$request->lang)->table($dbInboxMyTb)  
    ->where([
        ['user_id', '=', $request->profilerId],
        ['msg_type', '=', 5]
        ])
    ->update(array('msg_type' => 6));


    $dbInboxProfilerTb = 'user_'.$request->profilerId.'_inbox';
 
    $res =   DB::connection('mysql_'.$request->profilerLang)->table($dbInboxProfilerTb)->insert(
        array('user_lang' => request('lang'),
            'user_country' => request('country'),
            'tb_serial_id_fk' => 1, // 1 is dummy value there is no use but we have set to default tb serial id
            'video_holder_id' => request('profilerId'), // dummy 
            'vid_lang' => request('profilerLang'), //dummy no use
            'vid_country' => request('profilerCountry'), //dummy no use
            'user_id' => request('myId'),
            'msg_type' => 6 // 5 for someone follow you back to follow
           
            )
    );


}



public function saveDuetStitchVideoInbox($request,$vidId){

    $tx = new TxUsers();
    $tbId = $tx->getDuetorDetails($request->dueter_Hash_Id,1);
    $originalVideoId = $tx->getDuetorDetails($request->dueter_Hash_Id,2);
    $originalVidUserId = $tx->getDuetorDetails($request->dueter_Hash_Id,3);
    $originalVidUserCountry = $tx->getDuetorDetails($request->dueter_Hash_Id,4);
    $originalVidUserLang = $tx->getDuetorDetails($request->dueter_Hash_Id,5);

    $dbInboxTb = 'user_'.$originalVidUserId.'_inbox';
    
    $res =  DB::connection('mysql_'.$originalVidUserLang)->table($dbInboxTb)->insert(
        array('user_lang' => request('lang'),
        'user_country' => request('country'),
        'tb_serial_id_fk' => $tbId, 
        'video_holder_id' => request('user_id_fk'), 
        'vid_lang' => request('lang'), 
        'vid_country' => request('country'), 
        'video_id' => $vidId,
        'user_id' => request('user_id_fk'),
        'msg_type' => 7 // 7 for stitch videos,stitch comment videos,duet videos
       
        )
           
    );
//return $res;

}




   

public function fetchInboxMessages($request){

    $response = array();
    $txDB = new TxDbs();

    $dbInboxTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_inbox';
    $inboxTB = 'user_'.$request->userId.'_inbox';
                         $commentsList =  DB::connection('mysql_'.$request->lang)->table($inboxTB)
                                        ->where([
                                      ['msg_type', '<=', 7]
                                     ])
                                
                                    ->skip($request->count)->take(Config::get('constants.LIMIT_10'))
                                     ->orderBy('inbox_id', 'DESC')
                                     ->get();
//return $commentsList;

                    if ($commentsList->count() > 0){
                      
                        $i = 0;
                        foreach($commentsList as $tb){
                            
                        $inBoxuserDB =  $txDB->getDB($tb->user_country,$tb->user_lang);
                       
                        $userVidDB =  $txDB->getDB($tb->vid_country,$tb->vid_lang);
                       

                         $videosDB = $userVidDB.".users_videos_".$tb->tb_serial_id_fk;
                
                        
                         $commentsTB =   $userVidDB.".user_".$tb->video_holder_id."_comments";
                         $commentsReplyTB =   $userVidDB.".user_".$tb->video_holder_id."_comment_replies";
                
            
                         $query = DB::connection('mysql_'.$tb->user_lang)->table('tx_users as dbu')
                        ->join($dbInboxTb.' as dbibox', 'dbibox.user_id', 'dbu.user_id')
                  
                         ->leftjoin($videosDB.' as dbv', 'dbv.user_video_id', '=','dbibox.video_id')
                    
                        ->leftjoin($commentsTB.' as dbc', 'dbc.comment_id', '=', 'dbibox.comment_id')
						 ->leftjoin($commentsReplyTB.' as dbcr', 'dbcr.comment_reply_id', '=', 'dbibox.comment_reply_id');
                     
                         $res = $query->select([
                            'dbu.user_id',
                            'dbu.user_hashtag_name',
                            'dbu.user_name',
                            'dbu.user_login_token',
                            'dbu.user_profile_image',
                            'dbu.user_language',
                            'dbu.user_country',
                            
                            'dbibox.tb_serial_id_fk', 
                            'dbibox.vid_lang', 
                            'dbibox.vid_country',
                            'dbibox.msg_type', 
                            'dbibox.msg', 
                            'dbibox.comment_id', 
                            'dbibox.comment_reply_id', 
                             'dbibox.created_at',
							   'dbc.video_comment_msg',
							  /*
                           comment sender details end 
                         */
                             'dbv.user_video_id',
                             'dbv.video_cover_photo',
                             'dbv.video_visibility',
                           
                             'dbcr.cmnt_reply_msg'
                           
                           
                             ])
                            ->where([
                                ['dbibox.msg_type', '<=', 7],
                                ['dbibox.inbox_id', '=', $tb->inbox_id]
                                ])
                             ->orderBy('dbibox.inbox_id', 'DESC')
                             ->first();
                            

                             $response[$i] = $res;//str_replace('[]','',json_encode($res));
                             $i++;
                            
                       } // foreach
                  
                          

                      }
                  
                      return $response;



}


public function fetchInboxLikes($request){
    $txDB = new TxDbs();
    
    $response = array();

    $dbInboxTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_inbox';
   
    $tb = 'user_'.$request->userId.'_inbox';

                         $commentsList =  DB::connection('mysql_'.$request->lang)->table($tb)
                                        ->where([
                                      ['msg_type', '=', 0]
                                     ])
                                     ->skip($request->count)->take(Config::get('constants.LIMIT_10'))
                                     ->orderBy('inbox_id', 'DESC')
                                     ->get();

//return $commentsList;

                    if ($commentsList->count() > 0){
                      
                        $i = 0;
                        foreach($commentsList as $tb){
 
                        $inBoxuserDB =  $txDB->getDB($tb->user_country,$tb->user_lang);
                        
                         $vidDB =  $txDB->getDB($tb->vid_country,$tb->vid_lang);
                         $videosDB =  $vidDB.".users_videos_".$tb->tb_serial_id_fk;
                
                        
                         $commentsDB =  $vidDB.".user_".$tb->video_holder_id."_comments";
                         $commentsReplyDB =  $vidDB.".user_".$tb->video_holder_id."_comment_replies";
                
                       
                         $query = DB::connection('mysql_'.$tb->user_lang)->table('tx_users as dbu')
                         ->join($dbInboxTb.' as dbibox', 'dbibox.user_id', 'dbu.user_id')
                         ->leftjoin($videosDB.' as dbv', 'dbv.user_video_id', 'dbibox.video_id')
                         ->leftjoin($commentsDB.' as dbc', 'dbc.comment_id', 'dbibox.comment_id')
                         ->leftjoin($commentsReplyDB.' as dbcr', 'dbcr.comment_reply_id', 'dbibox.comment_reply_id');
                        
                         $res = $query->select([
                            'dbu.user_id',
                            'dbu.user_hashtag_name',
                            'dbu.user_name',
                            'dbu.user_login_token',
                            'dbu.user_profile_image',
                            'dbu.user_language',
                            'dbu.user_country',
                            'dbibox.tb_serial_id_fk', 
                            'dbibox.vid_lang', 
                            'dbibox.vid_country',
                            'dbibox.msg_type', 
                            'dbibox.msg', 
                            'dbibox.comment_id', 
                            'dbibox.comment_reply_id', 
                           /* comment sender details end */
                        
                             'dbv.user_video_id',
                             'dbv.video_cover_photo',
                             'dbv.video_visibility',
                             'dbc.video_comment_msg',
                             'dbcr.cmnt_reply_msg',
                             'dbibox.created_at', 
                             ])
                            ->where([
                               // ['dbibox.msg_type', '=', 0],
                                ['dbibox.inbox_id', '=', $tb->inbox_id]
                                ])
                             ->orderBy('dbibox.inbox_id', 'DESC')
                             ->first();

                             $response[$i] = $res;//str_replace('[]','',json_encode($res));
                             $i++;
                            
                       } // foreach
                  
                      }
                  
                      return $response;

}

public function fetchInboxComments($request){
    $txDB = new TxDbs();
    
    $response = array();
    
    $dbInboxTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_inbox';
    $tb = 'user_'.$request->userId.'_inbox';

                         $commentsList =  DB::connection('mysql_'.$request->lang)->table($tb)
                                     ->whereIn('msg_type', ['1', '2','3','4'])
                                     ->skip($request->count)->take(Config::get('constants.LIMIT_10'))
                                     ->orderBy('inbox_id', 'DESC')
                                     ->get();

//return $commentsList;

                    if ($commentsList->count() > 0){
                      
                        $i = 0;
                        foreach($commentsList as $tb){
                            
                        $inBoxuserDB =  $txDB->getDB($tb->user_country,$tb->user_lang);
                       
                         $vidDB =   $txDB->getDB($tb->vid_country,$tb->vid_lang);
                         $videosDB =  $vidDB.".users_videos_".$tb->tb_serial_id_fk;
                
                        
                         $commentsDB =  $vidDB.".user_".$tb->video_holder_id."_comments";
                         $commentsReplyDB =  $vidDB.".user_".$tb->video_holder_id."_comment_replies";
                
                       
                         $query = DB::connection('mysql_'.$tb->user_lang)->table('tx_users as dbu')
                         ->join($dbInboxTb.' as dbibox', 'dbibox.user_id', 'dbu.user_id')
                         ->leftjoin($videosDB.' as dbv', 'dbv.user_video_id', 'dbibox.video_id')
                         ->leftjoin($commentsDB.' as dbc', 'dbc.comment_id', 'dbibox.comment_id')
                         ->leftjoin($commentsReplyDB.' as dbcr', 'dbcr.comment_reply_id', 'dbibox.comment_reply_id');
                        
                         $res = $query->select([
                            'dbu.user_id',
                            'dbu.user_hashtag_name',
                            'dbu.user_name',
                            'dbu.user_login_token',
                            'dbu.user_profile_image',
                            'dbu.user_language',
                            'dbu.user_country',
                            'dbibox.tb_serial_id_fk', 
                            'dbibox.vid_lang', 
                            'dbibox.vid_country',
                            'dbibox.msg_type', 
                            'dbibox.msg', 
                            'dbibox.comment_id', 
                            'dbibox.comment_reply_id', 
                           /* comment sender details end */
                        
                             'dbv.user_video_id',
                             'dbv.video_cover_photo',
                             'dbv.video_visibility',
                             'dbc.video_comment_msg',
                             'dbcr.cmnt_reply_msg',
                             'dbibox.created_at', 
                           
                             ])
                            ->where([
                                ['dbibox.inbox_id', '=', $tb->inbox_id]
                                ])
                             ->orderBy('dbibox.inbox_id', 'DESC')
                             ->first();

                             $response[$i] = $res;//str_replace('[]','',json_encode($res));
                             $i++;
                            
                       } // foreach
                  
                          

                      }
                  
                      return $response;



}


public function fetchInboxRequests($request){
    $txDB = new TxDbs();
    $response = array();
    
    $dbInboxTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_inbox';
    $tb = 'user_'.$request->userId.'_inbox';

                         $commentsList =  DB::connection('mysql_'.$request->lang)->table($tb)
                         ->where([
                            ['msg_type', '=', 5]
                           ])
                                     ->skip($request->count)->take(Config::get('constants.LIMIT_10'))
                                     ->orderBy('inbox_id', 'DESC')
                                     ->get();

//return $commentsList;

                    if ($commentsList->count() > 0){
                      
                        $i = 0;
                        foreach($commentsList as $tb){
 
                        $inBoxuserDB =  $txDB->getDB($tb->user_country,$tb->user_lang);
                       
                         $vidDB =  $txDB->getDB($tb->vid_country,$tb->vid_lang);
                         $videosDB =  $vidDB.".users_videos_".$tb->tb_serial_id_fk;
                
                        
                         $commentsDB =  $vidDB.".user_".$tb->video_holder_id."_comments";
                         $commentsReplyDB =  $vidDB.".user_".$tb->video_holder_id."_comment_replies";
                
                       
                          $query =  DB::connection('mysql_'.$tb->user_lang)->table('tx_users as dbu')
                         ->join($dbInboxTb.' as dbibox', 'dbibox.user_id', 'dbu.user_id')
                         ->leftjoin($videosDB.' as dbv', 'dbv.user_video_id', 'dbibox.video_id')
                         ->leftjoin($commentsDB.' as dbc', 'dbc.comment_id', 'dbibox.comment_id')
                         ->leftjoin($commentsReplyDB.' as dbcr', 'dbcr.comment_reply_id', 'dbibox.comment_reply_id');
                        
                         $res = $query->select([
                            'dbu.user_id',
                            'dbu.user_hashtag_name',
                            'dbu.user_name',
                            'dbu.user_login_token',
                            'dbu.user_profile_image',
                            'dbu.user_language',
                            'dbu.user_country',
                            'dbibox.tb_serial_id_fk', 
                            'dbibox.vid_lang', 
                            'dbibox.vid_country',
                            'dbibox.msg_type', 
                            'dbibox.msg', 
                            'dbibox.comment_id', 
                            'dbibox.comment_reply_id', 
                           /* comment sender details end */
                        
                             'dbv.user_video_id',
                             'dbv.video_cover_photo',
                             'dbv.video_visibility',
                             'dbc.video_comment_msg',
                             'dbcr.cmnt_reply_msg',
                             'dbibox.created_at', 
                           
                             ])
                            ->where([
                                ['dbibox.inbox_id', '=', $tb->inbox_id]
                                ])
                             ->orderBy('dbibox.inbox_id', 'DESC')
                             ->first();

                             $response[$i] = $res;//str_replace('[]','',json_encode($res));
                             $i++;
                            
                       } // foreach
                  
                          

                      }
                  
                      return $response;



}








}
