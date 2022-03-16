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


class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      
    }





    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCommentReplies(Request $request)
    {
       /* not using */

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
       if($request->purpose == 'post-comment'){

      
        $tb = 'user_'.$request->userId.'_comments';
            $txUsers = new TxUsers;
            $checkExistUser = $txUsers->userIsExistByTokenAndUserId($request->commenterLang,$request->commenterToken,$request->commenterIdFk);
            if($checkExistUser == 1){
    $txuser = new Comments();
    $res = $txuser->storeComment($request);
if($res == 'posted'){
//send notification here
$getlastCommentId = DB::connection('mysql_'.$request->lang)->table($tb)->where([['commenter_id_fk', '=', $request->commenterIdFk]])->orderBy('comment_id', 'DESC')->first();

   $response['lastCommentId'] = $getlastCommentId->comment_id;
   $response['createAt'] = $getlastCommentId->created_at;
    $response['status'] = "comment posted";

}else{
    $response['status'] = "comment not posted";

}
      
       }else{
        $response['status'] = "fail";
        $response['error'] = "Session expired";
       }

       return json_encode($response);
      

    }else if($request->purpose == 'delete-comment'){

      
        $txUsers = new TxUsers;
        $checkExistUser = $txUsers->userIsExistByToken($request->deleterLang,$request->deleterToken);
        if($checkExistUser == 1){
    $txuser = new Comments();
    $res = $txuser->deleteComment($request);
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

            $comment = new Comments;
            if($request->commentId == 0){
              
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
     * @param  \App\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function edit(Comments $comments)
    {
        
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comments $comments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
    * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       
}

}
