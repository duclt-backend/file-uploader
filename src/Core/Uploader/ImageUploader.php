<?php

namespace Workable\FileUploader\Core\Uploader;

use Workable\FileUploader\Core\Store\ImageOptimizeManagerDriver;
use Workable\FileUploader\Core\Utils\FileContent;
use Workable\FileUploader\Core\Utils\ResponseUpload;
use Workable\FileUploader\Core\Utils\ResultUpload;
use Workable\FileUploader\Supports\ImageConfig;

class ImageUploader
{
    /**
     * @var
     */
    protected $configUpload;

    /**
     * @var
     */
    protected $imageOptimize;

    /**
     * @var Uploader
     */
    protected $uploader;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $maxWidth;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $maxSize;

    /**
     * @var
     */
    protected $fileContent;

    /**
     * ImageFactory constructor.
     * @param Uploader $uploader
     */
    public function __construct(Uploader $uploader)
    {
        $this->uploader    = $uploader;
        $this->maxWidth    = ImageConfig::getMaxWidth();
        $this->maxSize     = ImageConfig::getMaxSize();
        $this->fileContent = new FileContent();

        $this->imageOptimize = new ImageOptimizeManagerDriver($this->uploader->driverDefaultName);
    }

    /**
     * Set config
     * @param array $config
     * @return $this
     * User: Hungokata
     * Date: 8/15/19
     */
    public function setConfig($config = [])
    {
        $this->uploader->setConfig($config);
        $this->configUpload = $config;

        return $this;
    }

    /**
     * Upload file
     * @param        $fileControl
     * @param array $arrayThumbs
     * @param string $optional
     * @return ResultUpload
     * User: Hungokata
     * @throws \Workable\FileUploader\Core\Exceptions\FileTypeIsNotAllowedException
     * @throws \Workable\FileUploader\Core\Exceptions\NoFileSelectedException
     * @throws \Workable\FileUploader\Core\Exceptions\UploadException
     * @throws \Workable\FileUploader\Core\Exceptions\UploadMaxFileSizeException
     */
    public function upload($fileControl, $arrayThumbs = [], $optional = 'resize')
    {
        $dataFileName = $this->uploader->upload($fileControl);
        return $this->response($dataFileName, $arrayThumbs, $optional);
    }

    /**
     * Upload multi
     * @param        $fileControl
     * @param array $arrayThumbs
     * @param string $optional
     * @return array
     * User: Hungokata
     * Date: 8/15/19
     * @throws \Workable\FileUploader\Core\Exceptions\FileTypeIsNotAllowedException
     * @throws \Workable\FileUploader\Core\Exceptions\NoFileSelectedException
     * @throws \Workable\FileUploader\Core\Exceptions\UploadException
     * @throws \Workable\FileUploader\Core\Exceptions\UploadMaxFileSizeException
     */
    public function uploadMulti($fileControl, $arrayThumbs = [], $optional = 'resize')
    {
        $dataFileName = $this->uploader->uploadMulti($fileControl);
        return $this->responseArray($dataFileName, $arrayThumbs, $optional);
    }

    /**
     * Upload from link
     * @param        $link
     * @param array $arrayThumbs
     * @param string $optional
     * @return ResultUpload
     * User: Hungokata
     * Date: 8/15/19
     * @throws \Workable\FileUploader\Core\Exceptions\FileTypeIsNotAllowedException
     * @throws \Workable\FileUploader\Core\Exceptions\UploadMaxFileSizeException
     * @throws \Workable\FileUploader\Core\Exceptions\UploadPathDoesNotExistException
     */
    public function uploadFromLink($link, $arrayThumbs = [], $optional = 'resize')
    {
        $ext = $this->fileContent->getMimeType($link);

        $original = time() . '.' . $ext;
        $params = [
            'original' => $original,
            'contentLink' =>  $this->fileContent->get()
        ];
        $dataFileName = $this->uploader->uploadFromLink($link, $params);

        return $this->response($dataFileName, $arrayThumbs, $optional, $link);
    }

    /**
     * B
     * @param        $fileBase64
     * @param array $arrayThumbs
     * @param string $optional
     * @return ResultUpload
     * User: Hungokata
     * Date: 8/15/19
     * @throws \Workable\FileUploader\Core\Exceptions\FileTypeIsNotAllowedException
     * @throws \Workable\FileUploader\Core\Exceptions\UploadMaxFileSizeException
     */
    public function uploadBase64($fileBase64, $arrayThumbs = [], $optional = 'resize')
    {
        $dataFileName = $this->uploader->uploadImageBase64($fileBase64);

        return $this->response($dataFileName, $arrayThumbs, $optional);
    }

    /**
     * @param string $fileName
     * User: QuangHa
     * Date: 9/9/2021
     */
    public function deleteImage($fileName)
    {
        return $this->uploader->deletedImage($fileName);
    }

    /**
     * B
     * @param ResponseUpload $responseUpload
     * @param array $arrayThumbs
     * @param string $optional
     * @param string $link
     * @return ResultUpload
     * User: Hungokata
     * Date: 2020/08/15
     */
    private function response(ResponseUpload $responseUpload, $arrayThumbs = [], $optional = 'resize', $link = '')
    {
        $resultUpload = new ResultUpload();

        if ($fileName = $responseUpload->getFiles()) {
            $pathUpload = $responseUpload->getPathUpload();
            $fullPath = rtrim($pathUpload, '/') . '/' . $fileName;

            $thumbs = $this->makeThumb($fullPath, $pathUpload, $arrayThumbs, $optional);
            list($width, $height) = $this->getImageSize($fullPath);

//            if ($optional == 'watermark') $this->insertWaterMark($fullPath);

//            $this->checkSize($fileName, $pathUpload, $responseUpload->getSizeUpload());

            $resultUpload->setThumbs($thumbs)
                ->setFileName($fileName)
                ->setSize($responseUpload->getSizeUpload())
                ->setWidth($width)
                ->setHeight($height)
                ->setDriver($responseUpload->getDriver())
                ->setConfigUpload($this->configUpload);
        }

        $resultUpload->setLinkAttach($link);
        return $resultUpload;
    }

    /**
     * Response Array
     * @param array $dataFileName
     * @param array $arrayThumbs
     * @param string $optional
     * @return array
     * User: Hungokata
     * Date: 8/15/19
     */
    private function responseArray(array $dataFileName, $arrayThumbs = [], $optional = 'resize')
    {
        if (count($dataFileName) == 0) return [];
        $data = [];

        foreach ($dataFileName as $item) {
            $data[] = $this->response($item, $arrayThumbs, $optional);
        }
        return $data;
    }

    /**
     * B
     * @param $fullPath
     * @return array
     * User: Hungokata
     * Date: 2020/08/15 - 09:01
     */
    private function getImageSize($fullPath)
    {
        try {
            list($width, $height) = getimagesize($fullPath);
        } catch (\Exception $e) {
            $width = NULL;
            $height = NULL;
        }
        return [
            $width, $height
        ];
    }

    /**
     * Make thumb
     * @param $fullPath
     * @param $pathUpload
     * @param $arrayThumbs
     * @param $optional
     * @return array
     * User: Hungokata
     * Date: 8/15/19
     */
    private function makeThumb($fullPath = '', $pathUpload = '', $arrayThumbs = [], $optional = '')
    {
        if (!$arrayThumbs) return [];

        switch ($optional)
        {
            case 'resize':
                $thumbs = $this->imageOptimize->resize($fullPath, $pathUpload, $arrayThumbs);
                break;

            case  'crop':
                $thumbs = $this->imageOptimize->crop($fullPath, $pathUpload, $arrayThumbs);
                break;

            default:
                $thumbs = [];
                break;
        }

        return $thumbs;
    }

    /**
     * Insert a watermark to image
     * @param string $fullPath
     * @return mixed
     * User: Hungokata
     * Date: 2020/08/16 - 23:17
     */
    public function insertWaterMark($fullPath = '')
    {
        return $this->imageOptimize->insertWaterMark($fullPath);
    }

    /**
     * Check size
     * @param $fileName
     * @param $pathUpload
     * @param $fileSize
     * @return bool
     * User: Hungokata
     * Date: 8/15/19
     */
    private function checkSize($fileName, $pathUpload, $fileSize)
    {
        $checkSize = $this->configUpload['check_resize'] ?? null;
        if (!$checkSize) {
            return false;
        }

        $fileSize = (int)$fileSize / 1024;
        if ($fileSize > $this->maxSize) {
            $fullPath = rtrim($pathUpload, '/') . '/' . $fileName;
            $this->imageOptimize->resizeOne($fullPath, $pathUpload);
        }
    }
}
