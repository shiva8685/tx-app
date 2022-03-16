<?php

namespace App\Http\Controllers\apis;
use DB;
use Config;
use App\UsersVideos;
use App\TxVideosTables;
use App\TxAnonymousUsers;
use App\Likes;
use App\TxUsers;
use App\Comments;
use App\CommentReplies;
use App\CommentLikes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LikesController extends Controller
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
       if($request->purpose == 'add-remove-like'){
     
        $txUsers = new TxUsers;
        $checkExistUser = $txUsers->userIsExistByTokenAndUserId($request->likerLang,$request->likerToken,$request->likerIdFk);
        if($checkExistUser == 1){
    $txuser = new Likes();
    $res = $txuser->addRemoveOneLike($request);
if($res == 'liked'){
//send notification here
    $response['status'] = "like added";
}else if($res == 'not liked'){
    $response['status'] = "like removed";
}else if($res == 'like removed'){
    $response['status'] = "like removed";
}

}else{
    $response['status'] = "fail";
    $response['error'] = "Unautherized user";
}
      
       }else{
        $response['status'] = "fail";
        $response['error'] = "Session expired";
       }

       return json_encode($response);
      




       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function show(Likes $likes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function edit(Likes $likes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Likes $likes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Likes $likes)
    {
        //
    }
}
