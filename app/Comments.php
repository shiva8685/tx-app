<?php

namespace App;
use DB;
use Config;
use App\Inbox;
use App\TxDbs;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //

    public function storeComment($request){

        $dbvideoHolderCommentsTb = 'user_'.$request->userId.'_comments';
        
        $dbVideosTb = 'users_videos_'.$request->tbSerialId;
    
      
        $res =  DB::connection('mysql_'.$request->lang)->table($dbvideoHolderCommentsTb)->insert(
            array('commenter_lang' => request('commenterLang'),
                'commenter_country' => request('commenterCountry'),
                'tb_serial_id_fk' => request('tbSerialId'),
                'user_video_id_fk' => request('userVideoIdFk'),
                'user_id_fk' => request('userId'),
                'commenter_id_fk' => request('commenterIdFk'),
                'video_comment_msg' => request('comment')
                )
      );
  
      if($res > 0){
      
    $inbox = new Inbox();
    $inboxRes = $inbox->saveCommentInbox($request);


    DB::connection('mysql_'.$request->lang)->table($dbVideosTb)->where('user_video_id', $request->userVideoIdFk)->increment('video_total_comments');
    
          return "posted";
    
      }else{
          return "not posted";
      }
    
    
    }


    
public function fetchComments($request){
    $txDB = new TxDbs();
    $response = array();
    
    $dbCommentsTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_comments';
    $dbCommentLikesTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_comment_likes';
    $tb = 'user_'.$request->userId.'_comments';
   
                         $commentList = DB::connection('mysql_'.$request->lang)->table($tb)
                                         ->where([
                                          ['tb_serial_id_fk', '=', $request->tbSerialId],
                                          ['user_video_id_fk', '=', $request->videoId],
                                          ['user_id_fk', '=', $request->userId],
                                        ])
                                     ->skip($request->count)->take(Config::get('constants.COMMENTS_LIMIT_ONSCROLL'))
                                     ->orderBy('comment_id', 'DESC')
                                     ->get();


                    if ($commentList->count() > 0){
                      
                        $i = 0;
                        foreach($commentList as $comment){

                         $db =  $txDB->getDB($comment->commenter_country,$comment->commenter_lang);
                    
                         $query = DB::connection('mysql_'.$comment->commenter_lang)->table('tx_users as dbu')
                         ->join($dbCommentsTb.' as dbc', 'dbc.commenter_id_fk', 'dbu.user_id')
                         ->leftjoin($dbCommentLikesTb.' as dbcl', 'dbcl.comment_id_fk', 'dbc.comment_id');
                        // ->leftjoin($dbCommentLikesTb.' as dbcl2', 'dbcl2.comment_id_fk', 'dbc.user_id_fk');
  
                         $res = $query->select([
                             'dbu.user_id',
                             'dbu.user_hashtag_name',
                             'dbu.user_name',
                             'dbu.user_login_token',
                             'dbu.user_profile_image',
                             'dbc.comment_id',
                             'dbc.commenter_lang',
                             'dbc.commenter_country',
                             'dbc.tb_serial_id_fk',
                             'dbc.user_video_id_fk',
                             'dbc.user_id_fk',
                             'dbc.commenter_id_fk',
                             'dbc.video_comment_msg',
                             'dbc.comment_likes',
                             'dbc.total_comment_replies',
                             'dbc.created_at',
                             'dbcl.user_comment_liker_id_fk'
                           
                             ])
                             ->where([
                                ['dbc.comment_id', '=', $comment->comment_id],
                                ['dbu.user_id', '=', $comment->commenter_id_fk],
                                ['dbc.user_video_id_fk', '=', $comment->user_video_id_fk]
                                ])
                             //->orderBy('dbc.comment_id', 'ASC')
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
    
    $dbCommentsTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_comments';
    $dbCommentLikesTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_comment_likes';
   
    $tb = 'user_'.$request->userId.'_comments';
   
                         $commentList =   DB::connection('mysql_'.$request->lang)->table($tb)
                                         ->where([
                                          ['tb_serial_id_fk', '=', $request->tbSerialId],
                                          ['user_video_id_fk', '=', $request->videoId],
                                          ['user_id_fk', '=', $request->userId],
                                          ['comment_id', '<=', $request->commentId],
                                        ])
                                     ->skip($request->count)->take(Config::get('constants.COMMENTS_LIMIT_ONSCROLL'))
                                     ->orderBy('comment_id', 'DESC')
                                     ->get();


                    if ($commentList->count() > 0){
                      
                        $i = 0;
                        foreach($commentList as $comment){

                         $db =  $txDB->getDB($comment->commenter_country,$comment->commenter_lang);
                    
                         $query = DB::connection('mysql_'.$comment->commenter_lang)->table('tx_users as dbu')
                         ->join($dbCommentsTb.' as dbc', 'dbc.commenter_id_fk', 'dbu.user_id')
                         ->leftjoin($dbCommentLikesTb.' as dbcl', 'dbcl.comment_id_fk', 'dbc.comment_id');
                        // ->leftjoin($dbCommentLikesTb.' as dbcl2', 'dbcl2.comment_id_fk', 'dbc.user_id_fk');
  
                         $res = $query->select([
                             'dbu.user_id',
                             'dbu.user_hashtag_name',
                             'dbu.user_name',
                             'dbu.user_login_token',
                             'dbu.user_profile_image',
                             'dbc.comment_id',
                             'dbc.commenter_lang',
                             'dbc.commenter_country',
                             'dbc.tb_serial_id_fk',
                             'dbc.user_video_id_fk',
                             'dbc.user_id_fk',
                             'dbc.commenter_id_fk',
                             'dbc.video_comment_msg',
                             'dbc.comment_likes',
                             'dbc.total_comment_replies',
                             'dbc.created_at',
                             'dbcl.user_comment_liker_id_fk'
                           
                             ])
                             ->where([
                                ['dbc.comment_id', '=', $comment->comment_id],
                                ['dbu.user_id', '=', $comment->commenter_id_fk],
                                ['dbc.user_video_id_fk', '=', $comment->user_video_id_fk]
                                ])
                             //->orderBy('dbc.comment_id', 'ASC')
                             ->first();

                              
                             $response[$i] = $res;//str_replace('[]','',json_encode($res));
                             $i++;
                        } // foreach
                  
                      }
                  

   
return $response;

}




public function deleteComment($request){

    $dbvideoHolderCommentsTb = 'user_'.$request->userId.'_comments';
    
    $dbvideoHolderCommentsRepliesTb = 'user_'.$request->userId.'_comment_replies';
    

    $dbVideosTb = 'users_videos_'.$request->tbSerialId;

    $res = DB::connection('mysql_'.$request->lang)->table($dbvideoHolderCommentsRepliesTb)->where([
        ['comment_id_fk', '=', $request->commentId]
        ])->delete();

    $res = DB::connection('mysql_'.$request->lang)->table($dbvideoHolderCommentsTb)->where([
        ['comment_id', '=', $request->commentId],
        ['commenter_id_fk', '=', $request->deleterId]
        ])->delete();

       

if($res > 0){
    return DB::connection('mysql_'.$request->lang)->table($dbVideosTb)->where('user_video_id', $request->userVideoIdFk)->decrement('video_total_comments');
    
}else{
    return "not deleted";
}


}
    
    
    












}
