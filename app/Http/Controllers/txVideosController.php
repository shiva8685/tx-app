<?php

namespace App\Http\Controllers;

use App\txVideos;
use DB;
use Config;
use Illuminate\Http\Request;

class txVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = DB::table(Config::get('constants.MAIN_DB_PREF').'tx_main_db.users as dbu as dbu')->join('tx_in_lang_telugu.tx_videos as dbv', 'dbv.user_id', '=', 'dbu.id');        
        $output = $query->select(['dbu.id','dbu.name','dbu.email','dbv.user_id','dbv.about'])

->get();

return $output;
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
     * @param  \App\txVideos  $txVideos
     * @return \Illuminate\Http\Response
     */
    public function show(txVideos $txVideos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\txVideos  $txVideos
     * @return \Illuminate\Http\Response
     */
    public function edit(txVideos $txVideos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\txVideos  $txVideos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, txVideos $txVideos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\txVideos  $txVideos
     * @return \Illuminate\Http\Response
     */
    public function destroy(txVideos $txVideos)
    {
        //
    }
}
