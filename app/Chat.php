<?php

namespace App;
use DB;
use Config;
use App\TxDbs;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //

public function storeChatMessages($request,$chatNo){

$myChatDBTB = 'user_'.$request->my_id.'_chat';

$chaterDBTB = 'user_'.$request->chater_id.'_chat';

//$senderId = request('my_id');

    $res =  DB::connection('mysql_'.$request->my_lang)->table($myChatDBTB)->insert(
        array(
            'chat_no' => $chatNo,
           // 'my_lang' => request('my_lang'),
          //  'my_country' => request('my_country'),
           // 'my_user_id_fk' => request('my_id'),
            'chater_lang' => request('chater_lang'),
            'chater_country' => request('chater_country'),
            'chater_user_id_fk' => request('chater_id'), 
            'sender_id' => request('my_id'), 
            'message' => request('message'),
           
            )
  );


  $res =   DB::connection('mysql_'.$request->chater_lang)->table($chaterDBTB)->insert(
    array(
        'chat_no' => $chatNo,
        //'my_lang' => request('my_lang'),
      //  'my_country' => request('my_country'),
       // 'my_user_id_fk' => request('chater_id'),
        'chater_lang' => request('my_lang'),
        'chater_country' => request('my_country'),
        'chater_user_id_fk' => request('my_id'),  
        'sender_id' => request('my_id'), 
        'message' => request('message'),
       
        )
);

if($res > 0){

    return 1;
}else{
    return 0;
}

}


public function deleteChatMessageForMe($request){

    $myChatDBTB = 'user_'.$request->my_id.'_chat';
   
    $res = DB::connection('mysql_'.$request->my_lang)->table($myChatDBTB)->where([
        ['chat_no', '=', request('chat_no')]
        ])->delete();

        if($res > 0){
            return 1;
        }else{
            return 0;
        }


}


public function deleteChatMessageForEveryOne($request){

    $myChatDBTB = 'user_'.$request->my_id.'_chat';
    
    $chaterDBTB = 'user_'.$request->chater_id.'_chat';

    $myRes = DB::connection('mysql_'.$request->my_lang)->table($myChatDBTB)->where([
        ['chat_no', '=', request('chat_no')]
        ])->delete();

        $chaterRes = DB::connection('mysql_'.$request->chater_lang)->table($chaterDBTB)->where([
            ['chat_no', '=', request('chat_no')]
            ])->delete();

            if($myRes > 0 || $chaterRes > 0){
                return 1;
            }else{
                return 0;
            }
  

}






public function getMessages($request){
   
    $myChatDBTB = 'user_'.$request->myId.'_chat';
    
   // $chaterdb = "tx_".$request->chater_country."_lang_".$request->chater_lang;
   // $chaterDBTB = $chaterdb.'.user_'.$request->chater_id.'_chat';
    
    $output = DB::connection('mysql_'.$request->myLang)->table($myChatDBTB)->select(
        'chat_id',
        'chat_no',
        'chater_user_id_fk',
        'sender_id',
        'message',
        'created_at'
        )
    ->where([
        ['chater_lang', '=', $request->chaterLang],
        ['chater_country', '=', $request->chaterCountry],
        ['chater_user_id_fk', '=', $request->chaterId],
       // ['my_lang', '=', $request->myLang],
       // ['my_country', '=', $request->myCountry],
       // ['my_user_id_fk', '=', $request->myId],
    
        ])
    ->skip($request->count)->take(100)//->take(Config::get('constants.COMMENTS_LIMIT_ONSCROLL'))
    ->orderBy('chat_id', 'DESC')
        ->get();
 
    return $output;


}







public function getNewMessages($request){
  
    $myChatDBTB = 'user_'.$request->myId.'_chat';
    

    $output =  DB::connection('mysql_'.$request->myLang)->table($myChatDBTB)->select(
        'chat_id',
        'chat_no',
        'chater_user_id_fk',
        'sender_id',
        'message',
        'created_at'
        )
    ->where([
        ['chater_lang', '=', $request->chaterLang],
        ['chater_country', '=', $request->chaterCountry],
        ['chater_user_id_fk', '=', $request->chaterId]
       // ['chat_no', '>', $request->lastChatId],
       // ['my_country', '=', $request->myCountry],
       // ['my_user_id_fk', '=', $request->myId],
    
        ])
     ->skip(0)->take(100)//->take(Config::get('constants.COMMENTS_LIMIT_ONSCROLL'))
     ->orderBy('chat_id', 'DESC')
        ->get();
 
    return $output;


}


public function getAllChatUsers($request){
    $txDB = new TxDbs();

  
    $MyDbTb = $txDB->getDB($request->country,$request->lang);

    $myChatDBTB = $MyDbTb.'.user_'.$request->userId.'_chat';
    

    $friendsTb = "user_".$request->userId."_follow_unfollow_friends";

    $response = array();

    $friendsList =  DB::connection('mysql_'.$request->lang)->table($friendsTb)
                                     ->where([
                                          ['fuf_user_type', '=', 2] // 2 means friends
                                         ]) 
                                     ->skip($request->count)->take(Config::get('constants.COMMENTS_LIMIT_ONSCROLL'))
                                     ->orderBy('fuf_id', 'DESC')
                                     ->get();


            // return  $friendsList;

                    if ($friendsList->count() > 0){
                      
                        $i = 0;
                        foreach($friendsList as $fList){

                         $db =  $txDB->getDB($fList->fuf_country,$fList->fuf_lang);
                         $friendsDbTb = $db.".user_".$fList->follower_user_id_fk."_follow_unfollow_friends";

                         $query = DB::connection('mysql_'.$fList->fuf_lang)->table('tx_users as dbu')
                       //  ->join($friendsDbTb.' as dbfwrs', 'dbfwrs.follower_user_id_fk', 'dbu.user_id')
                         ->join($myChatDBTB.' as dbc', 'dbc.chater_user_id_fk', 'dbu.user_id');

                         $res =  $query->select([
                             'dbu.user_id',
                             'dbu.user_hashtag_name',
                             'dbu.user_name',
                             'dbu.user_login_token',
                             'dbu.user_profile_image',
                             'dbu.user_country',
                             'dbu.user_language',
                             'dbu.gender',
                             'dbc.chat_id',
                             'dbc.message',
                             'dbc.created_at'
                           
                        
                           
                             ])
                             ->where([
                                ['dbc.chater_user_id_fk', '=', $fList->follower_user_id_fk]
                                ])
                             ->orderBy('dbc.chat_id', 'DESC')
                             ->first();

                              
                             $response[$i] = $res;//str_replace('[]','',json_encode($res));
                             $i++;
                        } // foreach
                  
                      }
                  

   
return $response;


}

}
