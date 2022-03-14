<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2020/08/15 - 14:50
 */
namespace Workable\FileUploader\Services;

use Workable\Base\Supports\CliEcho;
use Workable\FileUploader\Core\Exceptions\UploadFileException;
use Workable\FileUploader\Supports\FileUploaderConfigSupport;
use Uploader;

class FileUploaderService
{
    protected static $abstract = 'uploader';

    protected static function instanceFactory($config)
    {
        return Uploader::setConfig($config);
    }

    /**
     * Upload from form data
     * @param        $fileControl
     * @param string $key
     * @return null
     * User: Hungokata
     * Date: 2020/08/17 - 00:20
     */
    public static function upload($fileControl, $key='pdf_and_doc')
    {
        try
        {
            $config  = FileUploaderConfigSupport::get('upload_file.'.$key);
            $results = self::instanceFactory($config)->upload($fileControl);
        }
        catch (UploadFileException $e)
        {
//            self::logging($e);
            $results = null;
        }

        return $results;
    }

    /**
     * Upload from form data multi
     * @param        $fileControl
     * @param string $key
     * @return null
     * User: Hungokata
     * Date: 2020/08/17 - 00:20
     */
    public static function uploadMulti($fileControl, $key = 'pdf_and_doc')
    {
        try
        {
            $config  = FileUploaderConfigSupport::get('upload_file.'.$key);
            $results = self::instanceFactory($config)->uploadMulti($fileControl);
        }
        catch (UploadFileException $e)
        {
            self::logging($e);
            $results = null;
        }

        return $results;
    }

    /**
     * Note:
     * @param $base64
     * @param $key
     * @param array $optional
     * @return null
     * User: TranLuong
     * Date: 07/06/2021
     */
    public static function uploadFileBase64($base64, $key, $optional = [])
    {
        try
        {
            $config  = FileUploaderConfigSupport::get('upload_file.'.$key);
            $results = static::instanceFactory($config)->uploadFileBase64($base64, $optional);
        }
        catch (UploadFileException $e)
        {
            self::logging($e);
            $results = null;
        }

        return $results;
    }

    /**
     * Upload from link like: https, http
     * @param string      $link
     * @param string      $type
     * @param string $optional: watermark, crop, resize ...
     * @return mix|null
     * User: Hungokata
     * Date: 2020/08/16 - 15:49
     */
    public static function uploadFromLink($link, $key = 'pdf_and_doc')
    {
        try
        {
            $config  = FileUploaderConfigSupport::get('upload_file.'.$key);
            $results = static::instanceFactory($config)->uploadFromLink($link);
        }
        catch (UploadFileException $e)
        {
//            self::logging($e);
            $results = null;
        }

        return $results;
    }

    /**
     * Logging
     * @param UploadFileException $e
     * User: Hungokata
     * Date: 2020/08/17 - 00:20
     */
    protected static function logging(UploadFileException $e)
    {
        CliEcho::errornl($e->info());
    }
}
