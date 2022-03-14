<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 6/26/19
 * Time: 13:53
 */

namespace Workable\FileUploader\Core\Utils;


class FileContent
{
    private static $contentLink = null;

    private static $instance = null;

    private static $contentType = null;

    public static function instance()
    {
        if (self::$instance === null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * B
     * @param      $link
     * @param bool $ssl
     * @return bool|string
     * User: Hungokata
     * Date: 8/14/19
     */
    public static function getContent($link, $contentTypeCheck = false)
    {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"     =>false,
                "verify_peer_name" =>false,
            ),
        );

        $content = @file_get_contents($link, false, stream_context_create($arrContextOptions));

        // Content type
        if ($contentTypeCheck)
        {
            $pattern = "/^content-type\s*:\s*(.*)$/i";
            if (($header = array_values(preg_grep($pattern, $http_response_header))) &&
                (preg_match($pattern, $header[0], $match) !== false)) {
                self::$contentType = $match[1];
            }
        }

        self::$contentLink = $content;

        return self::$contentLink;
    }

    public function get()
    {
        return self::$contentLink;
    }

    /**
     * B
     * @param $link
     * @return array|mixed|null
     * User: Hungokata
     * Date: 8/14/19
     */
    public function getMimeType($link)
    {
        $this->getContent($link, true);
        $mime    = self::$contentType;
        $mime    = $mime ? explode("/", $mime) : [];
        $mime    = $mime[1] ?? null;
        return $mime;
    }
}