<?php
if (!function_exists('static_url'))
{
    function static_url($link = '', $option = [])
    {
        return \Workable\FileUploader\FileUploader::instance()->getStaticUrl($link, $option);
    }
}

if (!function_exists('static_url_no_bucket'))
{

    function static_url_no_bucket($link = '')
    {
        return \Workable\FileUploader\FileUploader::instance()->getStaticUrlNoBucket($link);
    }
}

if (!function_exists('parse_url_file'))
{
    function parse_url_file($url, $folderName = '', $optional = [])
    {
        return \Workable\FileUploader\FileUploadParse::instance()->parsePath($url, $folderName, $optional);
    }
}

