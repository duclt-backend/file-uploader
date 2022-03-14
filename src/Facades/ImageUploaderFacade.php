<?php


namespace Workable\FileUploader\Facades;


use Illuminate\Support\Facades\Facade;

class ImageUploaderFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "image-uploader";
    }
}

