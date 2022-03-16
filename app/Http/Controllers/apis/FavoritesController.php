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
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class FavoritesController extends Controller
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

        if(request('purpose') == "add-favorites"){

            $fav = new Favorites();
            $res = $fav->addFavoriteItem($request);

if($res > 0){
    $response['status'] = "success";

}else{
    $response['status'] = "fail";

}

        }else if($request->purpose == 'delete-sound'){
            $fav = new Favorites();
            $res = $fav->deleteFavSound($request);
            if($res > 0){
                $response['status'] = "success";
            }else{
                $response['status'] = "fail";
            }
        
        }else if($request->purpose == 'delete-video'){
            $fav = new Favorites();
            $res = $fav->deleteFavVideo($request);
            if($res > 0){
                $response['status'] = "success";
            }else{
                $response['status'] = "fail";
            }
        
        }
                

        return json_encode($response);

    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
    
        $response = array();

if($request->favType == 1){
    $fav = new Favorites();
    $response = $fav->getFavoriteSongs($request);

}else if($request->favType == 2){
    $fav = new Favorites();
    $response = $fav->getFavoriteVideos($request);

}
        return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Favorites  $favorites
     * @return \Illuminate\Http\Response
     */
    public function edit(Favorites $favorites)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Favorites  $favorites
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Favorites $favorites)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
     
        
    }
}
