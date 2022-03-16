<?php

namespace App\Http\Controllers\apis;
use DB;
use Config;
use App\TxVideosTables;
use App\TxSoundsTables;
use App\TxAnonymousUsers;
use App\TxUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TxVideosTablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tb = TxVideosTables::select('tb_name')->where([['tb_storage_status', '=', 0]])->take(1)->first();

        return $tb->tb_name;

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
        if($request->purpose == 'storing-tables'){

            $tb = new TxVideosTables();

            for($i = 1;$i<= 100;$i++){

                 $tbName = 'users_videos_'.$i;

                $res = $tb->storeTables($tbName,$request->tbcountry,$request->tblang);
            }

            return $res;
         
        }

        if($request->purpose == 'get-active-table-serial-id'){

            $tbV = new TxVideosTables();   
            $videosTbIdExist = $tbV->getActiveTableId($request);

            $tbS = new TxSoundsTables();   
            $soundsTbIdExist = $tbS->getActiveTableId($request);
                  
//return $videosTbIdExist;
if(!$videosTbIdExist || !$soundsTbIdExist){
    $response['status'] = "fail";
}else {
  
    $response['status'] = "ok";
    $response['vid_tb_serial_id'] = $videosTbIdExist->tb_serial_id;
    $response['sound_tb_serial_id'] = $soundsTbIdExist->tb_serial_id;
}

                  
                  return  json_encode($response);

        }





    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TxVideosTables  $txVideosTables
     * @return \Illuminate\Http\Response
     */
    public function show(TxVideosTables $txVideosTables)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TxVideosTables  $txVideosTables
     * @return \Illuminate\Http\Response
     */
    public function edit(TxVideosTables $txVideosTables)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TxVideosTables  $txVideosTables
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TxVideosTables $txVideosTables)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TxVideosTables  $txVideosTables
     * @return \Illuminate\Http\Response
     */
    public function destroy(TxVideosTables $txVideosTables)
    {
        //
    }
}
