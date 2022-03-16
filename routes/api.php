<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    //return $request->user();
});


//tx videos tables
Route::get('tx/avid/v1/admin/tx-tables', 'apis\TxVideosTablesController@index');

Route::post('tx/avid/v1/admin/tx-tables/store', 'apis\TxVideosTablesController@store');
//gettting active table serial ID
Route::post('tx/avid/v1/tx-tables/get-active-table-serial-id', 'apis\TxVideosTablesController@store');


Route::post('tx/avid/v1/anonymous-user/save', 'apis\TxAnonymousUsersController@store');

Route::post('tx/avid/v1/new-user/save', 'apis\TxUsersController@store');
Route::post('tx/avid/v1/user/existing-check', 'apis\TxUsersController@store');
Route::post('tx/avid/v1/user/login-check', 'apis\TxUsersController@store');
Route::post('tx/avid/v1/new-user/db', 'apis\TxUsersController@store');
Route::post('tx/avid/v1/new-user/db-checking', 'apis\TxUsersController@store');
Route::get('tx/avid/v1/my-profile-details/{country}/{lang}/{userToken}', 'apis\TxUsersController@getMyProfileDetails');
Route::get('tx/avid/v1/user-profile-details/{country}/{lang}/{userToken}/{profilerId}/{viewerLang}/{viewerCountry}/{viewerType}/{viewerToken}/{viewerId}', 'apis\TxUsersController@getUserProfileDetails');
Route::get('tx/avid/v1/user/validate-userprofilelink/{country}/{lang}/{userToken}/{username}', 'apis\TxUsersController@checkhashtag');
Route::post('tx/avid/v1/update-user-profile-details/save', 'apis\TxUsersController@store');
Route::post('tx/avid/v1/update-user-hashtag/save', 'apis\TxUsersController@store');
Route::post('tx/avid/v1/update-user-profile-photo/save', 'apis\TxUsersController@store');
Route::post('tx/avid/v1/user/update-pwd/save', 'apis\TxUsersController@store');
//privacy and safetry
Route::post('tx/avid/v1/user/privacy-safety/save', 'apis\TxUsersController@store');

//forgot password
Route::post('tx/avid/v1/user/forgot-pass/send-otp-email', 'apis\TxUsersController@store');
Route::post('tx/avid/v1/user/forgot-pass/varify-phonenumber', 'apis\TxUsersController@store');
Route::post('tx/avid/v1/user/forgot-pass/reset-password', 'apis\TxUsersController@store');


//follow unfollow and friends storeing
Route::post('tx/avid/v1/user/follow-unfollow/save', 'apis\TxUsersController@store');

//follow back
Route::post('tx/avid/v1/user/follow-back/save', 'apis\TxUsersController@store');

//when two users are friends then followback user try to removeing friendship so he went to follow back button 
Route::post('tx/avid/v1/user/follow-back-from-friend/save', 'apis\TxUsersController@store');

//get followers list
Route::get('tx/avid/v1/user/get-followers-list/{country}/{lang}/{userToken}/{userId}/{count}', 'apis\TxUsersController@getUserFollowersList');

//get following list
Route::get('tx/avid/v1/user/get-following-list/{country}/{lang}/{userToken}/{userId}/{count}', 'apis\TxUsersController@getUserFollowingList');

//Friends Chat save
Route::post('tx/avid/v1/users/friends-chat/save', 'apis\ChatController@store');
//Friends Chat delete
Route::post('tx/avid/v1/users/friends-chat/delete', 'apis\ChatController@store');
//get friends chat data
Route::get('tx/avid/v1/users/friends-chat/get/{myCountry}/{myLang}/{myToken}/{myId}/{chaterCountry}/{chaterLang}/{chaterToken}/{chaterId}/{count}', 'apis\ChatController@show');
//get friends new chat
Route::get('tx/avid/v1/users/friends-new-chat/get/{myCountry}/{myLang}/{myToken}/{myId}/{chaterCountry}/{chaterLang}/{chaterToken}/{chaterId}/{lastChatId}', 'apis\ChatController@getNewChat');
//get chat users list
Route::get('tx/avid/v1/users/chat-users/get/{country}/{lang}/{userToken}/{userId}/{count}', 'apis\ChatController@getChatUsers');




//fetch videos
Route::get('tx/avid/v1/user-videos/{country}/{lang}/{userId}/{userToken}/{activeTbSerialId}/{visibleStatus}/{count}', 'apis\UsersVideosController@getSingleUserVideos');

Route::get('tx/avid/v1/user-liked-videos/{country}/{lang}/{userId}/{userToken}/{count}', 'apis\UsersVideosController@getSingleUserLikedVideos');

Route::get('tx/avid/v1/foryou-videos/{country}/{lang}/{userToken}/{userType}/{tbSerialId}/{count}', 'apis\UsersVideosController@getVideosForHomeScreenForYou');
Route::get('tx/avid/v1/following-videos/{uid}/{country}/{lang}/{userToken}/{userType}/{tbSerialId}/{count}', 'apis\UsersVideosController@getVideosForHomeScreenFollowing');

Route::get('tx/avid/v1/get-user-profile-videos-for-viewers/{country}/{lang}/{userId}/{activeTbSerialId}/{viewerType}/{viewerToken}/{viewerCountry}/{viewerLang}/{count}', 'apis\UsersVideosController@getUserProfileVideosForViewers');
Route::get('tx/avid/v1/get-user-profile-popular-videos-for-viewers/{country}/{lang}/{userId}/{activeTbSerialId}/{viewerType}/{viewerToken}/{viewerCountry}/{viewerLang}/{count}', 'apis\UsersVideosController@getUserProfilePopularVideosForViewers');


//get original sound video
Route::get('tx/avid/v1/sounds/get-original-sound-video/{viewerCountry}/{viewerLang}/{viewerId}/{viewerToken}/{viewerType}/{soundToken}/{soundHolderCountry}/{soundHolderLang}', 'apis\UsersVideosController@getOriginalSoundVideo');

//get use this sound videos
Route::get('tx/avid/v1/sounds/get-use-this-sound-videos/{viewerCountry}/{viewerLang}/{viewerId}/{viewerToken}/{viewerType}/{activeTbSerialId}/{soundToken}/{count}', 'apis\UsersVideosController@getUseThisSoundVideos');


//video report sending
Route::post('tx/avid/v1/reports/single-video-report', 'apis\TxReportsController@store');

//user report sending
Route::post('tx/avid/v1/reports/single-user-report', 'apis\TxReportsController@store');


//http://localhost/talentzexchange/public/api/tx/avid/v1/user-videos/in/telugu/124/45a22214388544cfbdb2d042aa72e9ff/2/private/0


//upload videos
//cover photo uploading
Route::post('tx/avid/v1/upload/cover-photo', 'apis\UsersVideosController@store');
//sound file uploading
Route::post('tx/avid/v1/upload/sound-file', 'apis\UsersVideosController@store');
//video file uploading
Route::post('tx/avid/v1/upload/video-file', 'apis\UsersVideosController@store');
//cover photo with sound name uploading
Route::post('tx/avid/v1/upload/cover-photo-with-sound-name', 'apis\UsersVideosController@store');

//video visible privacy and comment privacy
Route::post('tx/avid/v1/privacy/user/video/privacy-settings', 'apis\UsersVideosController@store');
//delete video 
Route::post('tx/avid/v1/privacy/user/video/privacy-settings/delete', 'apis\UsersVideosController@store');

//Add One view
Route::post('tx/avid/v1/video/views/add-one', 'apis\UsersVideosController@store');
//Add One share
Route::post('tx/avid/v1/video/shares/add-one', 'apis\UsersVideosController@store');

//Add Favorites
Route::post('tx/avid/v1/user/favorites/save', 'apis\FavoritesController@store');
//Delete Favorite Sounds
Route::post('tx/avid/v1/user/favorites/delete', 'apis\FavoritesController@store');
//Fetch  Favorites Sounds
Route::get('tx/avid/v1/user/favorite-sounds/get/{country}/{lang}/{userId}/{favType}/{count}', 'apis\FavoritesController@show');

//Fetch  Favorites Videos
Route::get('tx/avid/v1/user/favorite-videos/get/{country}/{lang}/{userId}/{favType}/{count}', 'apis\FavoritesController@show');


//Add Remove Likes
Route::post('tx/avid/v1/likes/add-remove-like', 'apis\LikesController@store');


//Post comment
Route::post('tx/avid/v1/comments/post-comment', 'apis\CommentsController@store');
//Show comments
Route::get('tx/avid/v1/comments/show-comments/{country}/{lang}/{userId}/{tbSerialId}/{videoId}/{commentId}/{userAuthToken}/{userAuthCountry}/{userAuthLang}/{count}', 'apis\CommentsController@show');

//Delete comment
Route::post('tx/avid/v1/comments/delete-comment', 'apis\CommentsController@store');

//Post comment reply
Route::post('tx/avid/v1/reply-comments/post-reply-comment', 'apis\CommentRepliesController@store');
//Show comments replies
Route::get('tx/avid/v1/comments/show-comments-replies/{country}/{lang}/{userId}/{tbSerialId}/{videoId}/{commentIdFk}/{commentReplyId}/{userAuthToken}/{userAuthCountry}/{userAuthLang}/{count}', 'apis\CommentRepliesController@show');



//Add remove Comment Like
Route::post('tx/avid/v1/comments/like-comment', 'apis\CommentLikesController@store');


//Add remove Comment Reply Like
Route::post('tx/avid/v1/reply-comments/like-comment-reply', 'apis\CommentRepliesController@store');

//Delete comment reply
Route::post('tx/avid/v1/reply-comments/delete-comment-reply', 'apis\CommentRepliesController@store');


//Search getting trending sounds
Route::get('tx/avid/v1/user/search/get-trending-sounds/{country}/{lang}/{userId}/{tbId}/{count}', 'apis\SearchController@getTrendingSounds');

//Search getting trending videos
Route::get('tx/avid/v1/user/search/get-trending-videos/{country}/{lang}/{userId}/{tbId}/{count}', 'apis\SearchController@getTrendingVideos');

//Search getting trending sounds for selection
Route::get('tx/avid/v1/user/search/get-trending-sounds-selection/{country}/{lang}/{userId}/{tbId}/{count}', 'apis\SearchController@getTrendingSoundsSelection');


//Search getting trending users
Route::get('tx/avid/v1/user/search/get-trending-users/{country}/{lang}/{userId}/{tbId}/{count}', 'apis\SearchController@getTrendingUsers');

//Users Search controller
Route::get('tx/avid/v1/user/search/get-search-users/{country}/{lang}/{keywords}/{count}', 'apis\SearchController@getSearchUsersList');
Route::get('tx/avid/v1/user/search/get-search-videos/{country}/{lang}/{userId}/{tbId}/{keywords}/{count}', 'apis\SearchController@getSearchVideosList');
Route::get('tx/avid/v1/user/search/get-search-users/{country}/{lang}/{keywords}/{count}', 'apis\SearchController@getSearchUsersList');
Route::get('tx/avid/v1/user/search/get-search-sounds/{country}/{lang}/{userId}/{tbId}/{keywords}/{count}', 'apis\SearchController@getSearchSoundsList');
Route::get('tx/avid/v1/user/search/get-user-search-sounds/{country}/{lang}/{userId}/{tbId}/{keywords}/{count}', 'apis\UsersSoundsController@getUserSearchSoundsList');

//hashtags
Route::get('tx/avid/v1/user/search/get-hashtags/{country}/{lang}/{tags}/{count}', 'apis\HTagsController@index');
Route::post('tx/avid/v1/user/search/save-hashtags', 'apis\HTagsController@store');
Route::get('tx/avid/v1/user/search/check-user-exit-by-username/{country}/{lang}/{username}', 'apis\SearchController@getUserIsExistByUserName');



//Inbox all Comments controller
Route::get('tx/avid/v1/user/inbox/get-inbox-messages/{country}/{lang}/{userId}/{count}', 'apis\InboxController@getInboxMessages');
//Inbox Likes controller
Route::get('tx/avid/v1/user/inbox/get-inbox-likes/{country}/{lang}/{userId}/{count}', 'apis\InboxController@getInboxLikes');
//get Comments
Route::get('tx/avid/v1/user/inbox/get-inbox-comments/{country}/{lang}/{userId}/{count}', 'apis\InboxController@getInboxComments');
//get Requests
Route::get('tx/avid/v1/user/inbox/get-inbox-requests/{country}/{lang}/{userId}/{count}', 'apis\InboxController@getInboxRequests');
//get inbox notifications count
Route::get('tx/avid/v1/users/inbox/notifications/count/{country}/{lang}/{userId}', 'apis\InboxController@getInboxNotifications');


//get single video
Route::get('tx/avid/v1/user/inbox/get-single-video/{country}/{lang}/{videoId}/{tbSerialId}/{viewerId}/{viewerCountry}/{viewerLang}', 'apis\UsersVideosController@getSingleVideo');







//testing
Route::get('tx/avid/v1/t1', 'apis\UsersSoundsController@index');

//testing
Route::get('tx/avid/v1/testing/{duetor}', 'apis\UsersVideosController@index');
