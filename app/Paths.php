<?php

namespace App;
use Config;
use Illuminate\Database\Eloquent\Model;

class Paths extends Model
{
    //

public function getProfilePhotoPath($country,$lang){

    return storage_path()."/app/public/".Config::get('constants.STORAGE_BUCKET').$lang."/"."profile-photos/large/";
  
}


public function getProfileSmallPhotoPath($country,$lang){

    return storage_path()."/app/public/".Config::get('constants.STORAGE_BUCKET').$lang."/"."profile-photos/small/";
  
  
}

public function getStoredVideosPath($country,$lang,$tbid){

    return "public/".Config::get('constants.STORAGE_BUCKET').$lang."/videos"."/".$tbid;
  
   
}

public function getStoredSoundsPath($country,$lang,$tbid){

    return "public/".Config::get('constants.STORAGE_BUCKET').$lang."/sounds"."/".$tbid;
  
  
}

//this is for retrofit sending image like file
public function getStoredCoverPhotosFilePath($country,$lang,$tbid){

    return "public/".Config::get('constants.STORAGE_BUCKET').$lang."/"."cover-photos/".$tbid;
  
  
}


// this for volley sending image like base64 encode string
public function getStoredCoverPhotosPath($country,$lang,$tbid){

    return storage_path()."/app/public/".Config::get('constants.STORAGE_BUCKET').$lang."/"."cover-photos/".$tbid."/";
  
  
}











}
