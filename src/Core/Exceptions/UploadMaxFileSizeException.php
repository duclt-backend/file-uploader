<?php
namespace Workable\FileUploader\Core\Exceptions;

class UploadMaxFileSizeException extends UploadFileException
{
    public function __construct($size)
    {
        $message = "Upload file maximum " . $size . " KB";
        parent::__construct($message, 1);
    }
}