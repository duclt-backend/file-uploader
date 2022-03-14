<?php
namespace Workable\FileUploader\Core\Driver;

use Workable\FileUploader\Core\Contracts\ImageAbstract;
use Workable\FileUploader\Core\Contracts\ImageInterface;

class ImageOptimizeDefault extends ImageAbstract implements ImageInterface
{
    protected $drive;

    public function __construct($drive = '')
    {
        $this->drive = $drive ?? '';
    }

    /**
     * @param $fullPathFile
     * @param $pathUpload
     * @param array $param
     * Source: http://image.intervention.io/api/resize
     */
    public function resizeOne($fullPathFile, $pathUpload, $param = [])
    {
        $width          = $param['width'] ?? 300;
        $image          = $this->getImage($fullPathFile);

        $image->backup();
        $image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($fullPathFile);
        $image->reset();

    }

    /**
     * Resize ảnh theo cấu hình thumb
     * @param       $fullPathFile
     * @param       $pathUpload
     * @param array $arrayResize
     * @return array
     */
    public function resize($fullPathFile, $pathUpload, $arrayResize = array())
    {
        // Create new instance of Image
        $result = [];
        $image    = $this->getImage($fullPathFile);
        $fileName = explode(DIRECTORY_SEPARATOR, $fullPathFile);
        $fileName = end($fileName);

        foreach ($arrayResize as $imgType => $imgInfo)
        {
            $optionFileName = $pathUpload . $imgType . $fileName;
            $image->backup();

            // Resize with auto height
            $image->resize($imgInfo['width'], null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($optionFileName);

            $image->reset();

            $result[$imgType] = $imgType . $fileName;
        }

        return $result;
    }

    /**
     * B
     * @param string $fullPathFile
     * @param string $pathUpload
     * @param array $arrayCrop
     * @return array
     * User: Hungokata
     * Date: 2021/02/11 - 16:38
     */
    public function crop($fullPathFile = '', $pathUpload = '', $arrayCrop = [])
    {
        $image    = $this->getImage($fullPathFile);
        $fileName = explode(DIRECTORY_SEPARATOR, $fullPathFile);
        $fileName = end($fileName);
        $result   = [];

        foreach ($arrayCrop as $imgType => $imgInfo)
        {
            $optionFileName = $pathUpload . $imgType . $fileName;

            $image->backup();
            $image->fit($imgInfo['width'], $imgInfo['height'])
                ->save($optionFileName);
            $image->reset();

            $result[$imgType] = $imgType . $fileName;
        }
        $image->destroy();

        return $result;
    }


    /**
     * @param $from
     * @param $to
     * @param $quantity
     * @param $params
     * @return mixed|void
     */
    public function convert($from, $to, $quantity = 85, $params = [])
    {
        $image = $this->getImage($from);
        $image->backup();
        $image->save($to, $quantity);
        $image->reset();

        return null;
    }

    /**
     * Insert watermark
     * @param string $fullPathFile
     * User: Hungokata
     * Date: 2020/08/16 - 23:20
     * Source: http://image.intervention.io/api/insert
     */
    public function insertWaterMark($fullPathFile = '', $params=[])
    {
        $image = $this->getImage($fullPathFile);
        $image->backup();
        $image->insert(public_path('water_mark.jpg'))
              ->save($fullPathFile);
        $image->reset();

        return null;
    }
}
