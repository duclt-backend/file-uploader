<?php

namespace Workable\FileUploader\Core\Driver;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;
use Workable\FileUploader\Core\Contracts\UploadDriverInterface;
use Workable\FileUploader\Core\Enum\UploadEnum;
use Workable\FileUploader\Core\Exceptions\UploadFileException;
use Workable\FileUploader\Core\Utils\ResponseUpload;
use Workable\FileUploader\Core\Utils\DataUpload;
use function public_path;

class UploadDefault implements UploadDriverInterface
{
    public function upload(DataUpload $dataUpload, $params = [])
    {
        $newFile    = $dataUpload->getNewFile();
        $pathUpload = $dataUpload->getUploadFolderToday();
        $fullPath   = $dataUpload->getFullPathNewFile($pathUpload);
        $diskName   = $dataUpload->getDiskName();

        try
        {
            Storage::disk($diskName)->put($fullPath, $dataUpload->getContentTmpNameFile());
            return (new ResponseUpload($newFile, public_path($pathUpload), filesize(public_path($fullPath)), UploadEnum::DRIVER_LOCAL))
                    ->setFileNameUpload($dataUpload->getFileName())
                    ->setExtensionUpload($dataUpload->getExtension());
        }
        catch (UploadFileException $e)
        {
            Log::error('-- Error upload: ' . $e->info());
        }

        return new ResponseUpload();
    }

    public function delete($dataDelete=[])
    {
        try
        {
            $diskName = $dataDelete['drive']['disk_name'];
            $path     = $dataDelete['path'];
            Storage::disk($diskName)->delete($path);

            return 1;
        }
        catch (UploadFileException $e)
        {
            Log::error('-- Error delete: ' . $e->info());
        }
        return 1;
    }
}
