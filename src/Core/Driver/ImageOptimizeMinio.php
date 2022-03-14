<?php
namespace Workable\FileUploader\Core\Driver;
use Intervention\Image\Image;
use Workable\FileUploader\Core\Contracts\ImageAbstract;
use Workable\FileUploader\Core\Contracts\ImageInterface;
use Workable\FileUploader\Core\Enum\UploadEnum;

class ImageOptimizeMinio extends ImageAbstract implements ImageInterface
{
    protected $drive;

    public function __construct($drive = '')
    {
        $this->drive = $drive ?: UploadEnum::DISK_MINIO;
    }

    /**
     * @param Image $image
     * @param $optionFileName: /uploads/2021/02/14/sm_2021_02_14______3e7cb8b7a07b9a0df583fe1f973ac4c1.jpg"
     */
    private function execute(Image $image, $optionFileName, $options = [])
    {
        $qtyStream    = $options['quantity'] ?? 90;
        $formatStream = $options['format_stream'] ?? null;

        $resource = $image->stream($formatStream, $qtyStream)->detach();

        \Storage::disk($this->drive)->put(
            $optionFileName,
            $resource
        );
        $image->reset();
    }

    /**
     * Resize with a image
     * @param       $fullPathFile
     * @param       $pathUpload
     * @param array $param
     * User: Hungokata
     * Date: 8/14/19
     */
    public function resizeOne($fullPathFile, $pathUpload, $param = [])
    {
        $width          = $param['width'] ?? 300;
        $domain         = rtrim(static_url(),DIRECTORY_SEPARATOR);
        $optionFileName = str_replace($domain,'', $fullPathFile);
        $image          = $this->getImage($fullPathFile);

        $image->backup();
        $image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $this->execute($image, $optionFileName);
    }

    /**
     * Resize with arraySize
     * @param       $fullPathFile
     * @param       $pathUpload
     * @param array $arrayResize
     * @return array
     * User: Hungokata
     * Date: 8/15/19
     */
	public function resize($fullPathFile, $pathUpload, $arrayResize = array())
	{
		$image    = $this->getImage($fullPathFile);
		$fileName = explode(DIRECTORY_SEPARATOR, $fullPathFile);
		$fileName = end($fileName);
		$result   = [];
		$domain   = rtrim(static_url(),DIRECTORY_SEPARATOR);

		foreach ($arrayResize as $imgType => $imgInfo)
		{
			$optionFileName = $pathUpload . $imgType . $fileName;
			$optionFileName =  str_replace($domain,'', $optionFileName);
			$image->backup();
			$image->resize($imgInfo['width'], null, function ($constraint) {
				$constraint->aspectRatio();
			});

            $this->execute($image, $optionFileName);
			$result[$imgType] = $imgType . $fileName;
		}

		return $result;
	}

    /**
     * Crop image
     * @param string $fullPathFile
     * @param string $pathUpload
     * @param array  $arrayCrop
     * @return array
     * User: Hungokata
     * Date: 2020/08/18 - 14:10
     */
	public function crop($fullPathFile = '', $pathUpload = '', $arrayCrop = [])
    {
        $result   = [];
        $image    = $this->getImage($fullPathFile);
        $fileName = explode(DIRECTORY_SEPARATOR, $fullPathFile);
        $fileName = end($fileName);
        $domain   = rtrim(static_url(),DIRECTORY_SEPARATOR);

        foreach ($arrayCrop as $imgType => $imgInfo)
        {
            $optionFileName = $pathUpload . $imgType . $fileName;
            $optionFileName =  str_replace($domain,'', $optionFileName);
            $image->backup()
                  ->fit($imgInfo['width'], $imgInfo['height']);

            $this->execute($image, $optionFileName);
            $result[$imgType] = $imgType . $fileName;
        }
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
        $domain         = rtrim(static_url(),DIRECTORY_SEPARATOR);
        $optionFileName =  str_replace($domain,'', $to);

        $image = $this->getImage($from);
        $image->backup();
        $this->execute($image, $optionFileName, [
            'quantity' => $quantity
        ]);
    }

    /**
     * Insert watermark
     * @param string $fullPathFile
     * User: Hungokata
     * Date: 2020/08/16 - 23:20
     */
    public function insertWaterMark($fullPathFile = '', $params=[])
    {

        // Something code
        return null;
    }

}
