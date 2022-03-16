<?php

namespace App;
use DB;
use Config;
use App\Inbox;
use App\TxDbs;
use Illuminate\Database\Eloquent\Model;

class CommentReplies extends Model
{
    //


    public function storeComment($request){

        $dbvideoHolderCommentsTb = 'user_'.$request->userId.'_comment_replies';
       
        $dbCommentsTb = 'user_'.$request->userId.'_comments';
        
  
        $res =  DB::connection('mysql_'.$request->lang)->table($dbvideoHolderCommentsTb)->insert(
            array('comment_replier_lang' => request('commenterLang'),
                'comment_replier_country' => request('commenterCountry'),
                'tb_serial_id_fk' => request('tbSerialId'),
                'user_video_id_fk' => request('userVideoIdFk'),
                'user_id_fk' => request('userId'),
                'comment_id_fk' => request('commentIdFk'),
                'comment_replier_id_fk' => request('commentReplierId'),
                'cmnt_reply_msg' => request('comment')
                )
      );
  
      if($res > 0){
       
        $inbox = new Inbox();
        $inboxRes = $inbox->saveCommentReplyInbox($request);
    
        DB::connection('mysql_'.$request->lang)->table($dbCommentsTb)->where('comment_id', $request->commentIdFk)->increment('total_comment_replies');
      
          return "posted";
    
      }else{
          return "not posted";
      }
    
    
    }

    public function storeCommentReplyLike($request){

        $dbvideoHolderCommentsTb = 'user_'.$request->userId.'_comment_replies';
    
        $inbox = new Inbox();
        $inboxRes = $inbox->saveCommentReplyLikeInbox($request);
    
        return  DB::connection('mysql_'.$request->lang)->table($dbvideoHolderCommentsTb)->where('comment_reply_id', $request->commentReplyId)->increment('comment_likes');
    

    }



    public function fetchComments($request){
        $txDB = new TxDbs();
        $response = array();
        
        $dbCommentsDb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_comment_replies';
        $tb = 'user_'.$request->userId.'_comment_replies';
      
        $commentList =  DB::connection('mysql_'.$request->lang)->table($tb)
                         ->where([
                            //['tb_serial_id_fk', '=', $request->tbSerialId],
                           // ['user_video_id_fk', '=', $request->videoId],
                            //['user_id_fk', '=', $request->userId],
                            ['comment_id_fk', '=', $request->commentIdFk]
                                ])
                        ->skip($request->count)->take(Config::get('constants.COMMENTS_LIMIT_ONSCROLL'))
                        ->orderBy('comment_reply_id', 'DESC')
                        ->get();
     
                        if ($commentList->count() > 0){
                          
                            $i = 0;
                            foreach($commentList as $comment){
    
                             $db =  $txDB->getDB($comment->comment_replier_country,$comment->comment_replier_lang);
                        
                             $query =DB::connection('mysql_'.$comment->comment_replier_lang)->table('tx_users as dbu')
                             ->join($dbCommentsDb.' as dbc', 'dbc.comment_replier_id_fk', 'dbu.user_id');
                            
                             $res = $query->select([
                                 'dbu.user_hashtag_name',
                                 'dbu.user_name',
                                 'dbu.user_login_token',
                                 'dbu.user_profile_image',
                                 'dbc.comment_reply_id',
                                 'dbc.comment_replier_lang',
                                 'dbc.comment_replier_country',
                                 'dbc.tb_serial_id_fk',
                                 'dbc.user_video_id_fk',
                                 'dbc.user_id_fk',
                                 'dbc.comment_id_fk',
                                 'dbc.comment_replier_id_fk',
                                 'dbc.cmnt_reply_msg',
                                 'dbc.comment_likes',
                                 'dbc.created_at'
                               
                                 ])
                                 ->where([
                                    ['dbc.comment_reply_id', '=', $comment->comment_reply_id],
                                  //  ['dbu.user_id', '=', $comment->comment_replier_id_fk],
                                  //  ['dbc.user_video_id_fk', '=', $comment->user_video_id_fk]
                                    ])
                                 ->orderBy('dbc.comment_reply_id', 'DESC')
                                 ->first();
    
                                  
                                 $response[$i] = $res;//str_replace('[]','',json_encode($res));
                                 $i++;
                              
                            } // foreach
                      
                          }
                      
   
    
    
    return $response;
    
    }



    

    public function fetchRequiredComments($request){
        $txDB = new TxDbs();
        
        $response = array();
        
        $dbCommentsTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_comment_replies';
       
        $tb = 'user_'.$request->userId.'_comment_replies';
    
        $commentList =  DB::connection('mysql_'.$request->lang)->table($tb)
                         ->where([
                            //['tb_serial_id_fk', '=', $request->tbSerialId],
                           // ['user_video_id_fk', '=', $request->videoId],
                            ['comment_reply_id', '=', $request->commentReplyId],
                            ['comment_id_fk', '=', $request->commentIdFk]
                                ])
                        ->skip($request->count)->take(Config::get('constants.COMMENTS_LIMIT_ONSCROLL'))
                        ->orderBy('comment_reply_id', 'DESC')
                        ->get();
     
                        if ($commentList->count() > 0){
                          
                            $i = 0;
                            foreach($commentList as $comment){
    
                             $db =  $txDB->getDB($comment->comment_replier_country,$comment->comment_replier_lang);
                        
                             $query = DB::connection('mysql_'.$comment->comment_replier_lang)->table('tx_users as dbu')
                             ->join($dbCommentsTb.' as dbc', 'dbc.comment_replier_id_fk', 'dbu.user_id');
                            
                             $res = $query->select([
                                 'dbu.user_hashtag_name',
                                 'dbu.user_name',
                                 'dbu.user_login_token',
                                 'dbu.user_profile_image',
                                 'dbc.comment_reply_id',
                                 'dbc.comment_replier_lang',
                                 'dbc.comment_replier_country',
                                 'dbc.tb_serial_id_fk',
                                 'dbc.user_video_id_fk',
                                 'dbc.user_id_fk',
                                 'dbc.comment_id_fk',
                                 'dbc.comment_replier_id_fk',
                                 'dbc.cmnt_reply_msg',
                                 'dbc.comment_likes',
                                 'dbc.created_at'
                               
                                 ])
                                 ->where([
                                    ['dbc.comment_reply_id', '=', $comment->comment_reply_id],
                                  //  ['dbu.user_id', '=', $comment->comment_replier_id_fk],
                                  //  ['dbc.user_video_id_fk', '=', $comment->user_video_id_fk]
                                    ])
                                 ->orderBy('dbc.comment_reply_id', 'DESC')
                                 ->first();
    
                                  
                                 $response[$i] = $res;//str_replace('[]','',json_encode($res));
                                 $i++;
                              
                            } // foreach
                      
                          }
                      
   
    
    
    return $response;
    
    }

    




    public function deleteCommentReply($request){

        $dbvideoHolderCommentsTb = 'user_'.$request->userId.'_comments';
        
        $dbvideoHolderCommentsRepliesTb = 'user_'.$request->userId.'_comment_replies';
        
    
      //  $commentReplies = DB::table($dbvideoHolderCommentsTb)->select('total_comment_replies')->where([['comment_id', '=', $request->commentId]]) ->first();
    
        $res = DB::connection('mysql_'.$request->lang)->table($dbvideoHolderCommentsRepliesTb)->where([
            ['comment_reply_id', '=', $request->commentReplyId]
            ])->delete();
    
    
    if($res > 0){
        return DB::connection('mysql_'.$request->lang)->table($dbvideoHolderCommentsTb)->where('comment_id', $request->commentId)->decrement('total_comment_replies');
        
    }else{
        return "not deleted";
    }
    
    
    }
        







}
