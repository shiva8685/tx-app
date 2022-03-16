<?php

namespace App\Http\Controllers\apis;
use Config;
use App\TxAnonymousUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TxAnonymousUsersController extends Controller
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

      $token =  md5(date("Ymd").date("his").time());
        $response = array();

        $user = new TxAnonymousUsers();
        $res = $user->saveAnonoumusUser($request,$token);
        if($res == 'success'){
            $response['status'] = "success";
            $response['response'] = $token;
           
         }else{
            $response['status'] = "fail";
            $response['response'] = "Fail storing";
            
         }
     
       

echo json_encode($response);

//return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
//JSON_UNESCAPED_UNICODE);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TxAnonymousUsers  $txAnonymousUsers
     * @return \Illuminate\Http\Response
     */
    public function show(TxAnonymousUsers $txAnonymousUsers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TxAnonymousUsers  $txAnonymousUsers
     * @return \Illuminate\Http\Response
     */
    public function edit(TxAnonymousUsers $txAnonymousUsers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TxAnonymousUsers  $txAnonymousUsers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TxAnonymousUsers $txAnonymousUsers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TxAnonymousUsers  $txAnonymousUsers
     * @return \Illuminate\Http\Response
     */
    public function destroy(TxAnonymousUsers $txAnonymousUsers)
    {
        //
    }
}
