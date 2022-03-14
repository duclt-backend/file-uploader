<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 8/14/19
 * Time: 14:37
 */
namespace Workable\FileUploader\Supports;

class FileUploaderConfigSupport
{
    protected static $config = 'packages.file-uploader';

    public static function get($key = null)
    {
        $key    = $key ? self::$config . '.' . ltrim($key, '.') : self::$config;
        $config = config($key);

        return $config;
    }
}