<?php


namespace App\Http\Controllers\apis;
use DB;
use Config;
use App\UsersVideos;
use App\TxVideosTables;
use App\TxAnonymousUsers;
use App\Likes;
use App\Comments;
use App\CommentReplies;
use App\CommentLikes;
use App\Favorites;
use App\HTags;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class HTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *   * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = array();

        $tags = new HTags();
        $response = $tags->getTags($request);
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
        //if hashtags are aleady exist then increament 1 count otherwise insert that new hashtag
        $response = array();
      if($request->purpose == 'save-hashtags'){
        $tags = new HTags();
        $checkExistHashTag = DB::connection('mysql_'.$request->lang)->table('htags')->where([['hashtags', '=', $request->tags]])->first();
  
        if(!$checkExistHashTag){
            $res = $tags->saveHashTags($request);
            if($res > 0){
                $response['status'] = "success";
            }else{
                $response['status'] = "not saved"; 
            }
        }else{
            DB::connection('mysql_'.$request->lang)->table('htags')->where('hashtags', $request->tags)->increment('htag_used');
            $response['status'] = "success";
            
        }
    }else{
        $response['status'] = "Unauthorized user!";
    }
        return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HTags  $hTags
     * @return \Illuminate\Http\Response
     */
    public function show(HTags $hTags)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HTags  $hTags
     * @return \Illuminate\Http\Response
     */
    public function edit(HTags $hTags)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HTags  $hTags
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HTags $hTags)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HTags  $hTags
     * @return \Illuminate\Http\Response
     */
    public function destroy(HTags $hTags)
    {
        //
    }
}
