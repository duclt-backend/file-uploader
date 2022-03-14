<?php
namespace Workable\FileUploader;
use Illuminate\Support\Arr;
use Workable\FileUploader\Supports\UploadConfig;

/**
 * Class FileUploader
 * @package Workable\FileUploader
 */
class FileUploader
{
    private $configUpload        = [];
    private $driverNameUpload    = '';
    private $bucketName          = '';

    protected static $instance   = null;

    public function __construct($configUpload = [])
    {
        $this->configUpload      = $configUpload ? $configUpload : UploadConfig::get('upload');
        $this->driverNameUpload  = $this->configUpload['default'];
        $this->bucketName        = Arr::get($this->configUpload, 'driver.'.$this->driverNameUpload.'.bucket');
    }

    public static function instance()
    {
        if (!self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get host cdn phù hợp dựa vào link truyền vào. Vì có thể xảy ra img, file, font... cdn khác nhau
     * @param       $link
     * @param array $option
     * @return string
     */
    public function getStaticUrl($link, $option = [])
    {
        $bucketName = $this->getBucketName();
        $host       = $this->getStaticUrlNoBucket();
        $host      .= $bucketName ? $bucketName .'/' : '';

        return $host . ltrim($link, '/');
    }

    /**
     * B
     * @param string $link
     * @return string
     * User: Hungokata
     * Date: 8/15/19
     */
    public function getStaticUrlNoBucket($link = '')
    {
        return rtrim($this->configUpload['static_url'], '/'). '/';
    }

    public function driverNameUpload()
    {
        return $this->driverNameUpload;
    }

    /**
     * Get bucketName
     * @return string
     * User: Hungokata
     * Date: 8/15/19
     */
    public function getBucketName()
    {
        return $this->bucketName;
    }
}
