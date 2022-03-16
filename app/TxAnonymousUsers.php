<?php

namespace App;
use DB;
use Config;
use App\TxDbs;
use Illuminate\Database\Eloquent\Model;

class TxAnonymousUsers extends Model
{
   //Table name
protected $table = 'tx_anonymous_users';


public function saveAnonoumusUser($request,$token){

      $dbName = 'mysql_'.strtolower(request('lang'));
      $user = new TxAnonymousUsers;
      $user->setConnection($dbName);
    
      $user->anonumous_user = $token;
      $user->anonumous_user_firebase_token = request('firebase_token');
      $user->anonumous_user_country = request('country');
      $user->anonumous_user_lang = request('lang');

      if($user->save()){
         return "success";
      }else{
         return "fail";
      }


}


public function anonymousUserIsExist($lang,$token){
   
   $userAnonymus = DB::connection('mysql_'.$lang)->table('tx_anonymous_users')->where([['anonumous_user', '=', $token]])->first();

   if(!$userAnonymus){
       return 0;
   }else{
       return 1;
   }

}





}
