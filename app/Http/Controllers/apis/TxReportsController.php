<?php

namespace App\Http\Controllers\apis;
use DB;
use Config;
use App\UsersVideos;
use App\TxVideosTables;
use App\TxSoundsTables;
use App\TxAnonymousUsers;
use App\TxUsers;
use App\TxDbs;
use App\TxReports;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TxReportsController extends Controller
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
        $txUsers = new TxUsers;
        $checkExistUser = $txUsers->getUserIfExistByToken($request->reporterLang,$request->reporterToken);
        if(!$checkExistUser){
          $response['status'] = "fail1";
          $response['error'] = "Unautherized user";
      
      }else{
       
if($request->purpose == 'video'){
    $rep = new TxReports;
    $res = $rep->saveVideoReport($request);
}else{
    $rep = new TxReports;
    $res = $rep->saveUserReport($request);
}
      


if($res > 0){
    $response['status'] = "success";
}else{
    $response['status'] = "fail";
}

      }
       return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
             JSON_UNESCAPED_UNICODE);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TxReports  $txReports
     * @return \Illuminate\Http\Response
     */
    public function show(TxReports $txReports)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TxReports  $txReports
     * @return \Illuminate\Http\Response
     */
    public function edit(TxReports $txReports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TxReports  $txReports
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TxReports $txReports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TxReports  $txReports
     * @return \Illuminate\Http\Response
     */
    public function destroy(TxReports $txReports)
    {
        //
    }
}
