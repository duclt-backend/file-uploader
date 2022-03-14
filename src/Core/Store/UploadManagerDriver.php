<?php
namespace Workable\FileUploader\Core\Store;

use Workable\FileUploader\Core\Contracts\UploadDriverInterface;
use Workable\FileUploader\Core\Enum\UploadEnum;
use Workable\FileUploader\Core\Utils\DataUpload;
use Workable\FileUploader\Core\Driver\UploadDefault;
use Workable\FileUploader\Core\Driver\UploadMino;

class UploadManagerDriver
{
    protected $driverUpload;

    public function __construct($driverName)
    {
        switch ($driverName)
        {
            case UploadEnum::DRIVER_MINIO:
                $this->driverUpload = new UploadMino();
                break;

            default:
                $this->driverUpload = new UploadDefault();
        }
    }

    public function upload(DataUpload $dataUpload, $params = [])
    {
        if ($this->driverUpload instanceof UploadDriverInterface)
        {
            return $this->driverUpload->upload($dataUpload, $params);
        }
    }

    public function delete($dataDelete=[])
    {
        return $this->driverUpload->delete($dataDelete);
    }

}
