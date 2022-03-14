<?php

namespace Workable\FileUploader\Core\Store;

use Workable\FileUploader\Core\Contracts\ImageInterface;
use Workable\FileUploader\Core\Driver\ImageOptimizeDefault;
use Workable\FileUploader\Core\Driver\ImageOptimizeMinio;
use Workable\FileUploader\Core\Enum\UploadEnum;

class ImageOptimizeManagerDriver implements ImageInterface
{
    protected $imageOptimizeDriver;

    /**
     * ImageOptimizeManagerDriver constructor.
     * @param $driverName
     */
    public function __construct($driverName)
    {
        switch ($driverName)
        {
            case UploadEnum::DRIVER_MINIO:
                $this->imageOptimizeDriver = new ImageOptimizeMinio();
                break;

            default:
                $this->imageOptimizeDriver = new ImageOptimizeDefault();
        }
    }

    /**
     * @param string $fullPath
     * @param string $pathUpload
     * @param array $arrayThumbs
     * @return array
     */
    public function resize($fullPath = '', $pathUpload = '', $arrayThumbs = [])
    {
        return $this->imageOptimizeDriver->resize($fullPath, $pathUpload, $arrayThumbs);
    }

    /**
     * @param string $fullPath
     * @param string $pathUpload
     * @param array $arrayThumbs
     * @return array
     */
    public function crop($fullPath = '', $pathUpload = '', $arrayThumbs = [])
    {
        return $this->imageOptimizeDriver->crop($fullPath, $pathUpload, $arrayThumbs);
    }

    /**
     * @param string $fullPath
     * @param string $pathUpload
     * @param array $params
     */
    public function resizeOne($fullPath = '', $pathUpload = '', $params = [])
    {
        $this->imageOptimizeDriver->resizeOne($fullPath, $pathUpload, $params);
    }

    /**
     * @param string $fullPath
     * @param array $params
     * @return |null
     */
    public function insertWaterMark($fullPath = '', $params = [])
    {
        return $this->imageOptimizeDriver->insertWaterMark($fullPath, $params);
    }

    /**
     * @param $from
     * @param $to
     * @param $quantity
     * @param $params
     * @return mixed
     */
    public function convert($from, $to, $quantity = 85, $params = [])
    {
        return $this->imageOptimizeDriver->convert($from, $to, $quantity, $params);
    }
}
