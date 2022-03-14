<?php

namespace Workable\FileUploader\Core\Contracts;

use \Intervention\Image\ImageManagerStatic as BaseImage;

abstract class ImageAbstract
{
    public function getImage($resource = '')
    {
//        $image = BaseImage::configure()->make($resource);
        $image = BaseImage::make($resource);
        $mime = $image->mime();

        switch ($mime)
        {
            case 'image/gif':
                return $image->encode('gif');

            case 'image/jpeg':
            case 'image/jpg':
                return $image->encode('jpg', 85);

            case 'image/png':
                return $image->encode('png', 85);

            case 'image/bmp':
                return $image->encode('bmp');

            case 'webp':
            case 'image/webp':
            case 'image/x-webp':
                return $image->encode('webp', 85);

        }

        return $image;
    }

    public function getMime($image)
    {
        return $image->mime();
    }
}
