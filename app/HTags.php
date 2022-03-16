<?php

namespace App;
use DB;
use Config;
use App\UsersVideos;
use App\TxVideosTables;
use App\TxAnonymousUsers;
use App\TxUsers;
use App\UsersSounds;
use App\TxDbs;
use Illuminate\Database\Eloquent\Model;


class HTags extends Model
{
    //

public function getTags($request){

    return DB::connection('mysql_'.$request->lang)->table('htags')
           ->select('htag_id','hashtags','htag_used')
           ->where([
            ['hashtags', 'like', '%' . $request->tags . '%']
            ])
           ->get();


}


public function saveHashTags($request){

    return  DB::connection('mysql_'.$request->lang)->table('htags')->insert(
        array('hashtags' => request('tags'),
        'htag_used' => 1  )
    );

    

}



}
