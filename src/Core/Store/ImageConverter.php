<?php
namespace Workable\FileUploader\Core\Store;

use Workable\FileUploader\Core\Enum\UploadEnum;

class ImageConverter
{
    protected $options = [];

    protected $imageOptManagerDriver;

    public function __construct()
    {
        $this->imageOptManagerDriver = new ImageOptimizeManagerDriver(UploadEnum::DRIVER_MINIO);
    }

    /**
     * @param array $options
     */
    public function options($options = [])
    {
        $this->options = $options;
    }

    /**
     * @param $from
     * @param $to
     * @param int $qty
     * @param array $options
     */
    public function convert($from, $to, $qty = 85, $options = [])
    {
        $this->imageOptManagerDriver->convert($from, $to, $qty, $options);
    }

}
