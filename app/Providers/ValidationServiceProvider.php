<?php

namespace App\Providers;

use App\Models\MediaUpload;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        Validator::extend('mediatype',function($field_name,$value,$parameters){
            if (empty($value)){
                return false;
            }
            $media_details = MediaUpload::find($value);
            if (is_null($media_details)){
                return false;
            }
            if (!file_exists(public_path('uploads/media-upload/'.$media_details->path))){
                return false;
            }
            if (!in_array($media_details->extension, $parameters)){
                return false;
            }

            return true;
        },"file type does not matched ");
    }
}
