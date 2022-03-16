<?php

namespace App;
use DB;
use Config;
use App\Inbox;
use App\TxDbs;
use Illuminate\Database\Eloquent\Model;

class CommentLikes extends Model
{
    //






    public function storeCommentLike($request){
        $txDB = new TxDbs();

        $dbvideoHolderCommentLikesTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_comment_likes';
      
        $commentLikestb = 'user_'.$request->userId.'_comment_likes';
        $commentstb = 'user_'.$request->userId.'_comments';
    
        $dbCommentsTb = $txDB->getDB($request->country,$request->lang).'.user_'.$request->userId.'_comments';
    
      //checkking if already liked that comment or not
      
       $isLikedAlready = DB::connection('mysql_'.$request->lang)->table($commentLikestb)->where([
          ['comment_id_fk', '=', $request->commentId],
          ['user_comment_liker_id_fk', '=', $request->commentLikerIdFk]
          ]) ->first();
    
          if($isLikedAlready){
//remove like

$res = $this->deleteLike($commentLikestb,$commentstb,$request);
if($res > 0){
    return "deleted";

}else{
    return "not deleted";
}

          }else{
//add like
  $res = $this->saveLike($commentLikestb,$commentstb,$request);
  if($res > 0){

    $inbox = new Inbox();
    $inboxRes = $inbox->saveCommentLikeInbox($request);

     return "liked";
  
 }else{
     return "not liked";
 }


          }
      

    
    
    }


private function saveLike($commentLikesTb,$commentsTb,$request){

      
    $res =  DB::connection('mysql_'.$request->lang)->table($commentLikesTb)->insert(
        array('comment_liker_lang' => request('commentLikerLang'),
            'comment_liker_country' => request('commentLikerCountry'),
            'tb_serial_id_fk' => request('tbSerialId'),
            'user_video_id_fk' => request('userVideoIdFk'),
            'user_id_fk' => request('userId'),
            'comment_id_fk' => request('commentId'),
            'user_comment_liker_id_fk' => request('commentLikerIdFk'),
            )
  );

  DB::connection('mysql_'.$request->lang)->table($commentsTb)->where('comment_id', $request->commentId)->increment('comment_likes');

  return $res;  



}


private function deleteLike($commentLikesTb,$commentsTb,$request){
   
    DB::connection('mysql_'.$request->lang)->table($commentsTb)->where('comment_id', $request->commentId)->decrement('comment_likes');

 $res =DB::connection('mysql_'.$request->lang)->table($commentLikesTb)->where([
    ['comment_id_fk', '=', $request->commentId]
    ])->delete();

return $res;

}



}
