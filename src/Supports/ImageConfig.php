<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2020/08/15 - 09:23
 */

namespace Workable\FileUploader\Supports;


class ImageConfig extends FileUploaderConfigSupport
{
    public static function getMaxWidth($key = 'image.max_width')
    {
        return static::get($key);
    }

    public static function getMaxSize($key = 'image.max_size')
    {
        return static::get($key);
    }
}