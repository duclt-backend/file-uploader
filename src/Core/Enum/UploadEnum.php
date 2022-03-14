<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2021/09/22 - 09:12
 */

namespace Workable\FileUploader\Core\Enum;


class UploadEnum
{
    // Disk system
    const DISK_PUBLIC = "public";
    const DISK_MINIO  = "minio";


    // Driver upload.
    const DRIVER_LOCAL = "local";
    const DRIVER_MINIO = "minio";
}