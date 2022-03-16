<?php

namespace App\Http\Controllers\apis;

use DB;
use Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\TxUsers;
use App\TxDbs;
use App\Chat;
use App\TxAnonymousUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ChatController extends Controller
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
        if($request->purpose == 'friends-chattting'){

            $response = array();
            $chat = new Chat;
  
          
        $txUsers = new TxUsers;
        $checkExistUser = $txUsers->userIsExistByToken($request->my_lang,$request->myToken);
        if($checkExistUser == 1){
            $chatNo = date("Ymd").date("hism");
          $res = $chat->storeChatMessages($request,$chatNo);
        if($res > 0){
            $response['status'] = "success";
            $response['response'] = $chatNo;
        }else{
            $response['status'] = "fail";
            $response['error'] = "message storing failed";
        }


        }else{
        //error
        $response['status'] = "fail";
        $response['error'] = "Unautherized user";
        }

        return json_encode($response);

        }


        if($request->purpose == 'chat-delete-for-me'){

            $response = array();
            $chat = new Chat;
  
      $txUsers = new TxUsers;
      $checkExistUser = $txUsers->userIsExistByToken($request->my_lang,$request->myToken);
        if($checkExistUser == 1){
          $res = $chat->deleteChatMessageForMe($request);

        if($res > 0){
            $response['status'] = "success";
            $response['response'] = "deleted chat for me";
        }else{
            $response['status'] = "fail";
            $response['error'] = "chat for me delete failed";
        }


        }else{
        //error
        $response['status'] = "fail";
        $response['error'] = "Unautherized user";
        }

        return json_encode($response);

        }


        if($request->purpose == 'chat-delete-for-everyone'){

            $response = array();
            $chat = new Chat;
  
        
     
        $txUsers = new TxUsers;
        $checkExistUser = $txUsers->userIsExistByToken($request->my_lang,$request->myToken);
        if($checkExistUser == 1){
          $res = $chat->deleteChatMessageForEveryOne($request);
        if($res > 0){
            $response['status'] = "success";
            $response['response'] = "chat for everyone deleted";
        }else{
            $response['status'] = "fail";
            $response['error'] = "chat for everyone delete failed";
        }


        }else{
        //error
        $response['status'] = "fail";
        $response['error'] = "Unautherized user";
        }

        return json_encode($response);

        }


    }


    public function getNewChat(Request $request){

        $response = array();
        $chat = new Chat;

    
        $txUsers = new TxUsers;
        $checkExistUser = $txUsers->userIsExistByToken($request->myLang,$request->myToken);
        if($checkExistUser == 1){

     $response = $chat->getNewMessages($request);
  
    }else{
    //error
    $response['status'] = "fail";
    $response['error'] = "Unautherized user";
    }

    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
    JSON_UNESCAPED_UNICODE);


    }

    
    public function getChatUsers(Request $request){

        $response = array();
        $chat = new Chat;

      
    $txUsers = new TxUsers;
        $checkExistUser = $txUsers->userIsExistByToken($request->lang,$request->userToken);
        if($checkExistUser == 1){
     $response = $chat->getAllChatUsers($request);
  
    }else{
    //error
    $response['status'] = "fail";
    $response['error'] = "Unautherized user";
    }

    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
    JSON_UNESCAPED_UNICODE);


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
        $chat = new Chat;

      
    $txUsers = new TxUsers;
        $checkExistUser = $txUsers->userIsExistByToken($request->myLang,$request->myToken);
        if($checkExistUser == 1){
     $response = $chat->getMessages($request);
  
    }else{
    //error
    $response['status'] = "fail";
    $response['error'] = "Unautherized user";
    }

    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
    JSON_UNESCAPED_UNICODE);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
