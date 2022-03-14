<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2021/02/11 - 16:42
 */

namespace Workable\FileUploader\Core\Contracts;


interface ImageInterface
{
    /**
     * Crop multi image
     * @param string $fullPathFile
     * @param string $pathUpload
     * @param array $arrayCrop
     * @return mixed
     */
    public function crop($fullPathFile= '', $pathUpload = '', $arrayCrop=[]);

    /**
     * Resize multi image
     * @param $fullPathFile
     * @param $pathUpload
     * @param array $arrayResize
     * @return mixed
     */
    public function resize($fullPathFile, $pathUpload, $arrayResize = []);

    /**
     * Resize one image
     * @param $fullPathFile
     * @param $pathUpload
     * @param array $param
     * @return mixed
     */
    public function resizeOne($fullPathFile, $pathUpload, $param = []);

    /**
     * Tạo water mark
     * @param string $fullPathFile
     * @param array $params
     * @return mixed
     */
    public function insertWaterMark($fullPathFile = '', $params=[]);

    /**
     * Convert image
     * @param $from
     * @param $to
     * @param $quantity
     * @param $params
     * @return mixed
     */
    public function convert($from, $to, $quantity, $params = []);
}
