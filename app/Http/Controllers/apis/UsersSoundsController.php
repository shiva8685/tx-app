<?php

namespace App\Http\Controllers\apis;
use DB;
use Config;
use App\UsersVideos;
use App\TxVideosTables;
use App\TxAnonymousUsers;
use App\TxUsers;
use App\UsersSounds;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;






class UsersSoundsController extends Controller
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

    
public function getUserSearchSoundsList(Request $request){

    $response = array();
    $search = new UsersSounds();
    $response = $search->getSearchSounds($request);
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
     * @param  \App\UsersSounds  $usersSounds
     * @return \Illuminate\Http\Response
     */
    public function show(UsersSounds $usersSounds)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UsersSounds  $usersSounds
     * @return \Illuminate\Http\Response
     */
    public function edit(UsersSounds $usersSounds)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UsersSounds  $usersSounds
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UsersSounds $usersSounds)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UsersSounds  $usersSounds
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsersSounds $usersSounds)
    {
        //
    }
}
