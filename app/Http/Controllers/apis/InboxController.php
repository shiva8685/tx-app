<?php

namespace App\Http\Controllers\apis;

use App\Search;
use DB;
use Config;
use App\UsersSounds;
use App\UsersVideos;
use App\TxVideosTables;
use App\TxAnonymousUsers;
use App\TxUsers;
use App\Inbox;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class InboxController extends Controller
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


    public function getInboxNotifications(Request $request){
        $response = array();
        $inbox = new Inbox();
        $likes = $inbox->getNewLikesCount($request);
        $response['likes'] = $likes;
        $comments = $inbox->getNewCommentsCount($request);
        $response['comments'] = $comments;
        $users = $inbox->getNewRequestsCount($request);
        $response['requests'] = $users;
        $msgs = $inbox->getNewChatMessagesCount($request);
        $response['messages'] = $msgs;
      
      
        $response['status'] = "success";


        return json_encode($response);
       
    }


public function getInboxMessages(Request $request){

    $response = array();
    $inbox = new Inbox();
    $response = $inbox->fetchInboxMessages($request);

    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
    JSON_UNESCAPED_UNICODE);
}


public function getInboxComments(Request $request){
    $response = array();
    $inbox = new Inbox();
    $response = $inbox->fetchInboxComments($request);

    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
    JSON_UNESCAPED_UNICODE);
}


public function getInboxRequests(Request $request){

    $response = array();
    $inbox = new Inbox();
    $response = $inbox->fetchInboxRequests($request);

    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
    JSON_UNESCAPED_UNICODE);
}

public function getInboxLikes(Request $request){
    $response = array();
    $inbox = new Inbox();
    $response = $inbox->fetchInboxLikes($request);

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
        //
    }

    /**
     * Display the specified resource.
     *
      * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    
     */
    public function show(Request $request)
    {
         
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function edit(Inbox $inbox)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inbox $inbox)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inbox $inbox)
    {
        //
    }
}
