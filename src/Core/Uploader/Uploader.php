<?php namespace Workable\FileUploader\Core\Uploader;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use Workable\FileUploader\Core\Exceptions\FileTypeIsNotAllowedException;
use Workable\FileUploader\Core\Exceptions\NoFileSelectedException;
use Workable\FileUploader\Core\Exceptions\UploadException;
use Workable\FileUploader\Core\Exceptions\UploadMaxFileSizeException;
use Workable\FileUploader\Core\Exceptions\UploadPathDoesNotExistException;

use Workable\FileUploader\Core\Utils\DataUpload;
use Workable\FileUploader\Core\Enum\UploadTypeEnum;
use Workable\FileUploader\Core\Store\UploadManagerDriver;
use Workable\FileUploader\Core\Utils\FileContent;
use Workable\FileUploader\Supports\UploadConfig;

class Uploader
{
    /**
     * @var mixed|string
     */
    public $uploadFolder = 'uploads';

    /**
     * Cấu hình driver upload
     * @var
     */
    public $driveConfig = [];

    /**
     * Cấu hình extension upload.
     * @var mixed|null
     */
    protected $extensions = null;

    /**
     * Cấu hình fileSize upload.
     * @var mixed|null
     */
    protected $fileSize = null;

    /**
     * Tên file upload
     * @var string
     */
    protected $extensionUpload;

    /**
     * @var
     */
    protected $uploadManager;

    /**
     * @var
     */
    protected $fileContent;

    /**
     * Name driver Upload
     * @var mixed
     */
    public $driverDefaultName;

    /**
     * Upload constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (empty($config)) {
            $config = (array)UploadConfig::get('upload');
        }

        $this->extensions   = Arr::get($config, 'extensions');
        $this->fileSize     = Arr::get($config, 'file_size');
        $this->uploadFolder = Arr::get($config, 'upload_folder', 'uploads');

        $this->driverDefaultName = Arr::get($config, 'default');
        $this->driveConfig       = Arr::get($config, 'driver.' . $this->driverDefaultName);
        $this->uploadManager     = new UploadManagerDriver($this->driverDefaultName);
        $this->fileContent       = new FileContent();
    }

    /**
     * @param array $config
     */
    public function setConfig($config = [])
    {
        $this->setExtension($config['extensions'])
            ->setFileSize($config['file_size'])
            ->setUploadFolder($config['upload_folder']);
        return $this;
    }

    /**
     * B
     * @param array $extensions
     * User: Hungokata
     * Date: 8/14/19
     */
    public function setExtension($extensions = [])
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * B
     * @param int $fileSize
     * User: Hungokata
     * Date: 8/14/19
     */
    public function setFileSize($fileSize = 2560)
    {
        $this->fileSize = $fileSize;
        return $this;
    }

    /**
     * B
     * @param string $folder
     * User: Hungokata
     * Date: 8/14/19
     */
    public function setUploadFolder($folder = 'upload_cvs')
    {
        $this->uploadFolder = $folder;

        return $this;
    }

    /**
     * Upload file control
     * @param $fileControl
     * @return mixed
     * @throws FileTypeIsNotAllowedException
     * @throws NoFileSelectedException
     * @throws UploadException
     * @throws UploadMaxFileSizeException
     */
    public function upload($fileControl)
    {
        if (!isset($_FILES[$fileControl])) {
            throw new NoFileSelectedException("Chưa chọn file upload");
        }

        $uploadErrorCode = $_FILES[$fileControl]['error'];
        $fileName        = $_FILES[$fileControl]['name'];

        if ($uploadErrorCode > 0) {
            throw new UploadException($uploadErrorCode);
        }

        if ($this->checkExtension($fileName) == false) {
            throw new FileTypeIsNotAllowedException($this->getExtensions());
        }

        if ($this->checkFileSizeLimit($_FILES[$fileControl]['tmp_name']) == false) {
            throw new UploadMaxFileSizeException($this->getFileSizeLimit());
        }

        $newFileName = $this->generateNewFileName($fileName);

        $dataUpload = new DataUpload();
        $dataUpload->setTmpName($_FILES[$fileControl]['tmp_name'])
                    ->setFileName($fileName)
                    ->setExtension($this->extensionUpload)
                    ->setNewFile($newFileName)
                    ->setUploadFolder($this->uploadFolder)
                    ->setDriverConfig($this->driveConfig);

        return $this->uploadManager->upload($dataUpload);
    }

    /**
     * Upload multi
     * @param $fileControl
     * @return array
     * @throws FileTypeIsNotAllowedException
     * @throws NoFileSelectedException
     * @throws UploadException
     * @throws UploadMaxFileSizeException
     */
    public function uploadMulti($fileControl)
    {
        if (!isset($_FILES[$fileControl])) {
            throw new NoFileSelectedException("Chưa chọn file upload");
        }

        $result = [];
        $files  = $this->createArrayFiles($_FILES[$fileControl]);
        foreach ($files as $file) {
            //Upload code
            $uploadErrorCode = $file['error'];
            $fileName        = $file['name'];

            if ($uploadErrorCode > 0) {
                throw new UploadException($uploadErrorCode);
            }

            if ($this->checkExtension($fileName) == false) {
                throw new FileTypeIsNotAllowedException($this->getExtensions());
            }

            if ($this->checkFileSizeLimit($file['tmp_name']) == false) {
                throw new UploadMaxFileSizeException($this->getFileSizeLimit());
            }

            $newFileName = $this->generateNewFileName($fileName);

            $dataUpload = new DataUpload();
            $dataUpload->setTmpName($file['tmp_name'])
                        ->setFileName($fileName)
                        ->setNewFile($newFileName)
                        ->setExtension($this->extensionUpload)
                        ->setUploadFolder($this->uploadFolder)
                        ->setDriverConfig($this->driveConfig);

            $result[] = $this->uploadManager->upload($dataUpload);
        }

        return $result;
    }

    /**
     * Upload image base64
     * @param $fileBase64
     * @return mixed
     * @throws FileTypeIsNotAllowedException
     * @throws UploadMaxFileSizeException
     */
    public function uploadImageBase64($fileBase64)
    {
        $patternExt = "/^data:image\/(?<extension>(?:png|jpg|jpeg));base64,(?<image>.+)$/";
        if (!preg_match($patternExt, $fileBase64, $matching)) {
            throw new FileTypeIsNotAllowedException($this->getExtensions());
        }

        $size = (int)(strlen(rtrim($fileBase64, '=')) * 3 / 4) / 1024;
        if ($size >= $this->fileSize) {
            throw new UploadMaxFileSizeException($this->fileSize);
        }

        $dataUpload = new DataUpload();
        $dataUpload->setTmpName($fileBase64)
                    ->setNewFile($this->generateNewFileName(time() . '.png'))
                    ->setUploadFolder($this->uploadFolder)
                    ->setDriverConfig($this->driveConfig)
                    ->setTypeUpload(UploadTypeEnum::TYPE_BASE64);

        return $this->uploadManager->upload($dataUpload);
    }

    /**
     * Upload file base64
     * @param $fileBase64
     * @return mixed
     * @throws FileTypeIsNotAllowedException
     * @throws UploadMaxFileSizeException
     */
    public function uploadFileBase64($fileBase64)
    {
        $patternExt = "/^data:application.*$/";
        if (!preg_match($patternExt, $fileBase64, $matching)) {
            throw new FileTypeIsNotAllowedException($this->getExtensions());
        }

        $extension_parse = explode('/', mime_content_type($fileBase64))[1];
        if ($extension_parse == 'msword') {
            $extension = 'doc';
        } else if ($extension_parse == 'pdf') {
            $extension = 'pdf';
        } else {
            $extension = 'docx';
        }

        $size = (int)(strlen(rtrim($fileBase64, '=')) * 3 / 4) / 1024;
        if ($size >= $this->fileSize) {
            throw new UploadMaxFileSizeException($this->fileSize);
        }

        $newFileName = $this->generateNewFileName(time() . '.' . $extension);

        $dataUpload  = new DataUpload();
        $dataUpload->setTmpName($fileBase64)
                    ->setNewFile($newFileName)
                    ->setExtension($extension)
                    ->setUploadFolder($this->uploadFolder)
                    ->setDriverConfig($this->driveConfig)
                    ->setTypeUpload(UploadTypeEnum::TYPE_BASE64);

        return $this->uploadManager->upload($dataUpload);
    }

    /**
     * Upload from link
     * @param       $link
     * @param array $param
     * @return mixed
     * @throws FileTypeIsNotAllowedException
     * @throws UploadMaxFileSizeException
     * @throws UploadPathDoesNotExistException
     */
    public function uploadFromLink($link, $param = [])
    {
        if (!$param) {
            $ext      = $this->fileContent->getMimeType($link);
            $fileName = time() . '.' . $ext;
            $param    = [
                'original'    => $fileName,
                'contentLink' => $this->fileContent->get()
            ];
        }

        $fileName    = $param['original'];
        $contentLink = $param['contentLink'];

        $newFile = $this->generateNewFileName($fileName);
        if (!Str::contains($link, ['http://', 'https://'])) {
            throw new UploadPathDoesNotExistException("Đường dẫn không đúng định dạng (http:// hoặc https://) ");
        }

        if (!$this->checkExtension($newFile)) {
            throw new FileTypeIsNotAllowedException($this->getExtensions());
        }

        $fileSize   = strlen($contentLink);
        $fileSizeKb = $fileSize / 1024;
        if ($fileSizeKb > $this->fileSize) {
            throw new UploadMaxFileSizeException($this->getFileSizeLimit());
        }

        $dataUpload = new DataUpload();
        $dataUpload->setTmpName($link)
                    ->setFileName($fileName)
                    ->setNewFile($newFile)
                    ->setExtension($this->extensionUpload)
                    ->setUploadFolder($this->uploadFolder)
                    ->setDriverConfig($this->driveConfig)
                    ->setContent($contentLink)
                    ->setTypeUpload(UploadTypeEnum::TYPE_LINK);

        return $this->uploadManager->upload($dataUpload);
    }

    /**
     * @param $fileName
     */
    public function deletedImage($fileName)
    {
        if (!$fileName)
        {
            throw new NoFileSelectedException("Chưa chọn file delete");
        }

        $path       = $this->getPathFromFileName($fileName, $this->uploadFolder);
        $dataDelete = [
            'drive'     => $this->driveConfig,
            'file_name' => $fileName,
            'folder'    => $this->uploadFolder,
            'path'      => $path
        ];
        return $this->uploadManager->delete($dataDelete);
    }

    private function getPathFromFileName($fileName, $folder = '')
    {
        $explode = explode('___', $fileName);

        if (isset($explode[0]) && isset($explode[1])) {
            $time = $explode[0];
            $time = str_replace(['sm_', 'md_', 'lg_'], '', $time);
            $time = str_replace('_', '/', $time);
            $time = date('Y/m/d', strtotime($time));
            $url  = rtrim($folder, '/') . '/' . $time . '/' . $fileName;
        } else {
            $url = '/' . ltrim($fileName, '/');
        }

        return $url;
    }

    /**
     * B
     * @param $file_post
     * @return array
     * User: Hungokata
     * Date: 8/14/19
     */
    private function createArrayFiles(&$file_post)
    {

        $file_ary   = [];
        $file_count = count($file_post['name']);
        $file_keys  = array_keys($file_post);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
            if (!$file_ary[$i]['name']) unset($file_ary[$i]);
        }

        return $file_ary;
    }

    /**
     * Generate new file name
     * @param string $filename
     *
     * @return string
     */
    private function generateNewFileName($filename)
    {
        $ipClient    = time() . uniqid() . rand(111111, 999999) . rand(111111, 999999);
        $frefix      = date("Y_m_d") . '___' . strtotime(date("Y_m_d")) . '___';
        $nFilename   = str_replace('.', '--', $filename);
        $nFilename   = Str::slug($nFilename);
        $filenameMd5 = $frefix . md5($nFilename . $ipClient);

        return $filenameMd5 . '.' . $this->getExtension($filename);
    }

    /**
     * Get config limit file size
     *
     * @return integer
     */
    private function getFileSizeLimit()
    {
        return $this->fileSize;
    }

    /**
     * Get extension
     *
     * @param string $filename
     *
     * @return mixed
     */
    private function getExtension($filename)
    {
        $info = new \SplFileInfo($filename);

        $this->extensionUpload = strtolower($info->getExtension());

        return $this->extensionUpload;
    }

    /**
     * Get config extensions
     *
     * @return array
     */
    private function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Kiem tra extension
     *
     * @param  [type] $filename [description]
     *
     * @return [true | false]           [description]
     */
    private function checkExtension($filename)
    {
        $extension = $this->getExtension($filename);
        if (!in_array($extension, $this->extensions)) {
            return false;
        }

        return true;
    }

    /**
     * Kiem tra dung luong upload cho phep
     *
     * @param  [type] $filename [description]
     *
     * @return [true | false]           [description]
     */
    private function checkFileSizeLimit($filename)
    {
        if (filesize($filename) / 1024 > $this->fileSize) {
            return false;
        }

        return true;
    }
}
