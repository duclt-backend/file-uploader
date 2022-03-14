<?php
namespace Workable\FileUploader\Core\Driver;

use Workable\FileUploader\Core\Enum\UploadEnum;
use Workable\FileUploader\Core\Utils\DataUpload;
use Workable\FileUploader\Core\Contracts\UploadDriverInterface;
use Workable\FileUploader\Core\Exceptions\UploadFileException;
use Workable\FileUploader\Core\Utils\ResponseUpload;

class UploadMino implements UploadDriverInterface
{

	public function upload(DataUpload $dataUpload, $params = [])
	{
        $newFile             = $dataUpload->getNewFile();
        $pathUpload          = $dataUpload->getUploadFolderToday();
        $fullPath            = $dataUpload->getFullPathNewFile($pathUpload);
        $driverConfig        = $dataUpload->getDriverConfig();
        $diskName            = $dataUpload->getDiskName();

		try
        {
            $fileContent      = $dataUpload->getContentTmpNameFile();
			\Storage::disk($diskName)->put($fullPath, $fileContent);
			$size = \Storage::disk($diskName)->size($fullPath, $fileContent);

			return (new ResponseUpload($newFile, static_url($pathUpload), $size, UploadEnum::DRIVER_MINIO, $driverConfig))
                    ->setFileNameUpload($dataUpload->getFileName())
                    ->setExtensionUpload($dataUpload->getExtension());
		}
		catch (UploadFileException $e)
        {
            \Log::error('-- Error upload: '.$e->info());
		}

		return new ResponseUpload();
	}

	public function delete($dataDelete)
    {

    }
}
