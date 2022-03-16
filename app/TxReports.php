<?php


namespace App;
use DB;
use Config;
use App\UsersVideos;
use App\TxVideosTables;
use App\TxSoundsTables;
use App\TxAnonymousUsers;
use App\TxUsers;
use App\TxDbs;
use Illuminate\Database\Eloquent\Model;

class TxReports extends Model
{
    

    public function saveVideoReport($request){

        $res =  DB::connection('mysql_'.$request->lang)->table('tx_video_reports')->insert(
            array('video_id' => request('videoId'),
                'video_holder_id' => request('videoOwnerId'),
                'video_holder_country' => request('country'),
                'video_holder_lang' => request('lang'),
                'tb_serial_id_fk' => request('tbId'),
                'reporter_id' => request('reporterId'),
                'reporter_country' => request('reporterCountry'),
                'reporter_lang' => request('reporterLang'),
                'report_msg' => request('reportMsg')
            
                )
      );

    
          return $res;

    }


    public function saveUserReport($request){

        $res =  DB::connection('mysql_'.$request->lang)->table('tx_user_reports')->insert(
            array('profiler_id' => request('profilerId'),
                'profiler_country' => request('country'),
                'profiler_lang' => request('lang'),
                'reporter_id' => request('reporterId'),
                'reporter_country' => request('reporterCountry'),
                'reporter_lang' => request('reporterLang'),
                'report_msg' => request('reportMsg')
            
                )
      );

    
          return $res;

    }






}
