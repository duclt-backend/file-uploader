<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 8/14/19
 * Time: 15:45
 */
namespace Workable\FileUploader\Core\Exceptions;
use Throwable;

class UploadFileException extends \Exception
{
    public function __construct($message="", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function info()
    {
        return '- File:' . $this->getFile() .
               '- Line:' . $this->getLine() .
               '- Message:'. $this->getMessage();
    }
}