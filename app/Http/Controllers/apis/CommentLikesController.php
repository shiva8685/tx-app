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




class CommentLikesController extends Controller
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
        if($request->purpose == 'comment-like'){
 
             $txUsers = new TxUsers;
             $checkExistUser = $txUsers->userIsExistByTokenAndUserId($request->commentLikerLang,$request->commentLikerToken,$request->commentLikerIdFk);
             if($checkExistUser == 1){
     $txuser = new CommentLikes();
     $res = $txuser->storeCommentLike($request);
 if($res == 'liked'){
 //send notification here
     $response['status'] = "success";
     $response['response'] = "liked";
 
 }else if($res == 'deleted'){
     $response['status'] = "success";
     $response['response'] = "deleted";
 
 }else{
    $response['status'] = "Error";
 }
       
        }else{
         $response['status'] = "fail";
         $response['error'] = "Session expired";
        }
 
        return json_encode($response);
       
 
     }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CommentLikes  $commentLikes
     * @return \Illuminate\Http\Response
     */
    public function show(CommentLikes $commentLikes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CommentLikes  $commentLikes
     * @return \Illuminate\Http\Response
     */
    public function edit(CommentLikes $commentLikes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CommentLikes  $commentLikes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommentLikes $commentLikes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommentLikes  $commentLikes
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommentLikes $commentLikes)
    {
        //
    }
}
