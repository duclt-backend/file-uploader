<?php
/**
 * Class response upload from driver
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 8/15/20 - 07:25
 */
namespace Workable\FileUploader\Core\Utils;


class ResponseUpload
{
    /**
     * Kết quả tện file mới tạo trả về từ upload
     * @var string
     */
    protected $files       = '';

    /**
     * Đường dẫn upload
     * @var string
     */
    protected $path_upload = '';

    /**
     * Kích thước upload
     * @var string
     */
    protected $size        = '';

    /**
     * @var string Drive upload
     */
    protected $driver      = '';

    /**
     * Tên file upload lên.
     * @var string
     */
    protected $fileName = '';

    /**
     * Extension upload
     * @var string
     */
    protected $extension = '';

    /**
     * @var array
     */
    protected $driverConfig = [];

    /**
     * ResponseUpload constructor.
     * @param string $files
     * @param string $path_upload
     * @param string $size
     * @param string $driver
     * @param array $driverConfig
     */
    public function __construct($files = '', $path_upload = '', $size = '', $driver='', $driverConfig = [])
    {
        $this->files       = $files;
        $this->path_upload = $path_upload;
        $this->size        = $size;
        $this->driver      = $driver;
        $this->driverConfig = $driverConfig;
    }

    public function setFileNameUpload($file = '')
    {
        $this->fileName = $file;
        return $this;
    }

    public function setExtensionUpload($ext = '')
    {
        $this->extension = $ext;
        return $this;
    }

    /**
     * Files
     * @return string
     * User: Hungokata
     * Date: 8/15/19
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Path folder
     * @return string
     * User: Hungokata
     * Date: 8/15/19
     */
    public function getPathUpload()
    {
        return $this->path_upload;
    }

    /**
     * Size folder
     * @return string
     * User: Hungokata
     * Date: 8/15/19
     */
    public function getSizeUpload()
    {
        return $this->size;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getDriverConfig()
    {
        return $this->driverConfig;
    }

    public function toArray()
    {
        return [
            'file_name'   => $this->files,
            'size'        => $this->size,
            'driver'      => $this->driver,
            "name"        => $this->fileName,
            "extension"   => $this->extension
        ];
    }
}
