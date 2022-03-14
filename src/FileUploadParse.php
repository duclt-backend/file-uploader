<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2021/11/07 - 21:49
 */

namespace Workable\FileUploader;


class FileUploadParse
{
    protected static $instance = null;

    public static function instance()
    {
        if (!self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function parsePath($url, $folderName = '', $optional = [])
    {
        if (!$url) return '';

        if (strpos($url, 'http') !== false) return $url;

        $explode = explode('___', $url);
        if (isset($explode[0]) && isset($explode[1]))
        {
            $time = $explode[0];
            $time = str_replace(['sm_', 'md_', 'lg_'], '', $time);
            $time = str_replace('_', '/', $time);
            $time = date('Y/m/d', strtotime($time));
            $url = rtrim($folderName, '/') . '/' .$time . '/'. ltrim($url, '/');
            $url = static_url($url);
        }
        else
        {
            $url = '/' . ltrim($url, '/');
        }
        return $url;
    }
}