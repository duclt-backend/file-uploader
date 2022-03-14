<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 5/13/19
 * Time: 15:16
 */

namespace Workable\FileUploader\Core\Utils;

use Workable\FileUploader\Core\Enum\UploadTypeEnum;
use Workable\FileUploader\Supports\Filesystem;

class DataUpload
{
    /**
     * @var mixed|null
     */
    protected $upload_folder = null;

    /**
     * Chứa dữ liệu gửi lên.
     * @var mixed|null
     */
    protected $tmp_name = null;

    /**
     * @var mixed|null
     */
    protected $new_file = null;

    /**
     * @var array|mixed
     */
    protected $driver_config = [];

    /**
     * Tên file mặc định upload.
     * @var string
     */
    protected $fileName = '';

    /**
     * Extension upload
     * @var string
     */
    protected $extension = '';

    /**
     * @var mixed|null
     */
    protected $type_upload = null;

    /**
     * @var mixed|null
     */
    protected $content = null;

    /**
     * @var null|Filesystem
     */
    protected $fileSystem = null;

    public function __construct()
    {
        $this->fileSystem = new Filesystem();
    }

    /**
     * @param mixed|null $tmp_name
     * @return $this
     */
    public function setTmpName($tmp_name)
    {
        $this->tmp_name = $tmp_name;
        return $this;
    }

    /**
     * Get temp name
     * @return mixed|null
     * User: Hungokata
     * Date: 8/14/19
     */
    public function getTmpName()
    {
        return $this->tmp_name;
    }

    public function setExtension($ext = '')
    {
        $this->extension = $ext;
        return $this;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setFileName($fileNameDefault = '')
    {
        $this->fileName = $fileNameDefault;
        return $this;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param mixed|null $new_file
     * @return $this
     */
    public function setNewFile($new_file)
    {
        $this->new_file = $new_file;
        return $this;
    }

    /**
     * Get new file
     * @return mixed|null
     * User: Hungokata
     * Date: 8/14/19
     */
    public function getNewFile()
    {
        return $this->new_file;
    }

    /**
     * @param array|null $driver
     */
    public function setDriverConfig($driver)
    {
        $this->driver_config = $driver;
        return $this;
    }

    /**
     * Get driver config
     * @return array|mixed
     * User: Hungokata
     * Date: 8/14/19
     */
    public function getDriverConfig()
    {
        return $this->driver_config;
    }

    /**
     * getDiskName
     * @return mixed|null
     * User: Hungokata
     * Date: 2021/08/29 - 16:54
     */
    public function getDiskName()
    {
        return $this->driver_config['disk_name'] ?? null;
    }

    /**
     * @param mixed|null $type_upload
     * @return $this
     */
    public function setTypeUpload($type_upload)
    {
        $this->type_upload = $type_upload;
        return $this;
    }

    /**
     * Get type upload
     * @return mixed|null
     * User: Hungokata
     * Date: 8/14/19
     */
    public function getTypeUpload()
    {
        return $this->type_upload;
    }

    /**
     * @param bool|mixed|null|string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content TempFile
     * @return bool|mixed|null|string
     * User: Hungokata
     * Date: 8/14/19
     */
    public function getContentTmpNameFile()
    {
        if ($this->content) return $this->content;

        $content = null;
        switch ($this->type_upload) {
            case UploadTypeEnum::TYPE_BASE64:
                $data    = explode(',', $this->tmp_name);
                $content = base64_decode($data[1]);
                break;

            default;
                $content = FileContent::getContent($this->tmp_name);
                break;
        }

        return $content;
    }

    /**
     * @param mixed|null $folder
     */
    public function setUploadFolder($folder)
    {
        $this->upload_folder = $folder;
        return $this;
    }

    /**
     * Get upload path folder
     * @return string
     * User: Hungokata
     * Date: 8/14/19
     */
    private function getUploadPathFolder()
    {
        return $this->driver_config['path'] . '/' . ltrim($this->upload_folder, '/');
    }

    /**
     * Get upload folder today
     * @return string
     * User: Hungokata
     * Date: 8/14/19
     * Mix
     */
    public function getUploadFolderToday()
    {
        $pathUpload = $this->getUploadPathFolder();
        $pathUpload .= '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';

        return $pathUpload;
    }

    /**
     * Get pull
     * @param $pathUpload
     * @return string
     * User: Hungokata
     * Date: 8/14/19
     */
    public function getFullPathNewFile($pathUpload = '')
    {
        return rtrim($pathUpload, '/') . '/' . ltrim($this->new_file, '/');
    }
}
