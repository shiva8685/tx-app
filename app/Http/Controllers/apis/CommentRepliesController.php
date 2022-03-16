<?php

namespace App\Http\Controllers\apis;
use DB;
use Config;
use App\UsersVideos;
use App\TxVideosTables;
use App\TxAnonymousUsers;
use App\TxUsers;
use App\Likes;
use App\Comments;
use App\CommentReplies;
use App\CommentLikes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;




class CommentRepliesController extends Controller
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
       if($request->purpose == 'post-comment-reply'){

        $tb = 'user_'.$request->userId.'_comment_replies';

    $txUsers = new TxUsers;
    $checkExistUser = $txUsers->userIsExistByToken($request->commenterLang,$request->commenterToken);
    if($checkExistUser == 1){
    $txuser = new CommentReplies();
    $res = $txuser->storeComment($request);
if($res == 'posted'){
//send notification here
   $getlastCommentReplyId = DB::connection('mysql_'.$request->lang)->table($tb)->where([['comment_replier_id_fk', '=', $request->commentReplierId]])->orderBy('comment_reply_id', 'DESC')->first();

   $response['lastCommentId'] = $getlastCommentReplyId->comment_reply_id;
   $response['createAt'] = $getlastCommentReplyId->created_at;
    $response['status'] = "comment posted";

}else{
    $response['status'] = "comment not posted";

}
      
       }else{
        $response['status'] = "fail";
        $response['error'] = "Session expired";
       }

       return json_encode($response);
      

    }else if($request->purpose == 'post-comment-reply-like'){
      
        $txUsers = new TxUsers;
        $checkExistUser = $txUsers->userIsExistByToken($request->likerLang,$request->likerToken);
        if($checkExistUser == 1){
    $txuser = new CommentReplies();
    $res = $txuser->storeCommentReplyLike($request);
if($res == 1){
    $response['status'] = "success";
    $response['response'] = "liked";
}else{
    $response['status'] = "fail";
    $response['error'] = "Session expired";
}
return json_encode($response);

    }

    }else if($request->purpose == 'delete-comment-reply'){

        $txUsers = new TxUsers;
        $checkExistUser = $txUsers->userIsExistByToken($request->deleterLang,$request->deleterToken);
        if($checkExistUser == 1){
    $txuser = new CommentReplies();
    $res = $txuser->deleteCommentReply($request);
    if($res > 0){
      $response['status'] = "success";
      $response['response'] = "deleted";
    }else{
          $response['status'] = "fail";
          $response['response'] = "not deleted";
    }
}
else{
    $response['status'] = "fail";
    $response['error'] = "Session expired";
}

return json_encode($response);

    }


}

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $response = array();
       
        $txUsers = new TxUsers;
        $checkExistUser = $txUsers->userIsExistByToken($request->userAuthLang,$request->userAuthToken);
        if($checkExistUser == 1){

            $comment = new CommentReplies;
            if($request->commentReplyId == 0){
            $response = $comment->fetchComments($request);
            }else{
                $response = $comment->fetchRequiredComments($request);
            }
     
        }else{
            $response['status'] = "fail1";
            $response['error'] = "Unautherized user";
        }
    
        return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CommentReplies  $commentReplies
     * @return \Illuminate\Http\Response
     */
    public function edit(CommentReplies $commentReplies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CommentReplies  $commentReplies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommentReplies $commentReplies)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommentReplies  $commentReplies
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommentReplies $commentReplies)
    {
        //
    }
}
