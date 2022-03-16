<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
//echo date("Ymd").date("hism");
//echo storage_path()."/app/public/videos/";
 $default_hashtag = 'intelugu';
 echo date("Ymd").date("his").time();
 //echo str_replace(" ","_","shiva y");

 //echo  str_replace(" ","_","shiva kumar").substr("shiva12@gmail.com",1,2).date("d").date("m");
 
});

Route::get('data', 'txVideosController@index');