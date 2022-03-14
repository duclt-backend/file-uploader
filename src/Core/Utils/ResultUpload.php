<?php
/**
 * File trả về kết quả upload
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2020/08/15 - 09:02
 */
namespace Workable\FileUploader\Core\Utils;

class ResultUpload
{
    protected $thumbs   = [];

    protected $fileName = null;

    protected $size     = null;

    protected $width    = 0;

    protected $height   = 0;

    /**
     * Đường dẫn link upload
     * @var null
     */
    protected $link_attach = null;

    protected $driver   = 'local';

    protected $configUpload = [];

    public function setThumbs($item =[])
    {
        $this->thumbs = $item;
        return $this;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Chứa đường dẫn khi upload bằng link
     * @param $link
     * @return $this
     * User: Hungokata
     * Date: 2021/02/11 - 15:03
     */
    public function setLinkAttach($linkAttach)
    {
        $this->link_attach = $linkAttach;
        return $this;
    }

    public function setDriver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    public function setConfigUpload($configUpload=[])
    {
        $this->configUpload = $configUpload;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getThumbs()
    {
        return $this->thumbs;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getMeta()
    {
        return [
            'config_upload' => $this->configUpload,
            'driver_upload' => null
        ];
    }

    public function toArray()
    {
        return [
            'file_name'   => $this->fileName,
            'thumbs'      => $this->thumbs,
            'size'        => $this->size,
            'width'       => $this->width,
            'height'      => $this->height,
            'driver'      => $this->driver,
            'link_attach' => $this->link_attach,
            'link_url' => '',
            'meta' => [
                'config_upload' => $this->configUpload,
                'driver_upload' => null
            ]
        ];
    }
}
