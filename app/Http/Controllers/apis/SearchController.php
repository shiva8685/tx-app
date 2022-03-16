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
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     
    }

    public function getUserIsExistByUserName(Request $request){
        $response = array();
    
        $response = DB::connection('mysql_'.$request->lang)->table('tx_users')
        ->select([
            'user_country',
            'user_language',
            'user_id',
            'user_login_token'
             ])
        ->where([['user_hashtag_name', '=', request('username')]])->get();

        return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

public function getSearchUsersList(Request $request){

    $response = array();
    $search = new Search();
    $response = $search->getSearchUsers($request);
    
    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
    JSON_UNESCAPED_UNICODE);

}


public function getSearchVideosList(Request $request){

    $response = array();
    $search = new Search();
    $response = $search->getSearchVideos($request);
    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
    JSON_UNESCAPED_UNICODE);

}


public function getSearchSoundsList(Request $request){

    $response = array();
    $search = new Search();
    $response = $search->getSearchSounds($request);
    return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
    JSON_UNESCAPED_UNICODE);

}





    public function getTrendingSounds(Request $request)
    {
        $response = array();
        $search = new Search();
        $response = $search->fetchTrendingSounds($request);
        
        return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function getTrendingVideos(Request $request)
    {
        $response = array();
        $search = new Search();
        $response = $search->fetchTrendingVideos($request);
        
        return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }
    

    public function getTrendingSoundsSelection(Request $request)
    {
        $response = array();
        $search = new Search();
        $response = $search->fetchTrendingSoundsSelection($request);
        
        return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }





    public function getTrendingUsers(Request $request)
    {
        $response = array();
        $search = new Search();
        $response = $search->fetchTrendingUsers($request);
        
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
     * @param  \App\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function show(Search $search)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function edit(Search $search)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Search $search)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function destroy(Search $search)
    {
        //
    }
}
