<?php

namespace Workable\FileUploader\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use function parse_url_file;
use function static_url;

class FileUploadFunctionTest extends TestCase
{
    private $urlLocal = "http://api.123job.abc/";
    private $urlProd  = "https://cdn.123job.vn/";

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testFunctionStaticUrlInLocalEnvSuccess()
    {
        Config::set('app.env', 'local');
        Config::set('packages.file-uploader.upload.static_url', $this->urlLocal);
        Config::set('packages.file-uploader.upload.default', 'local');

        $expectation = $this->urlLocal;
        $result      = static_url();

        $this->assertEquals($expectation, $result);
    }

    public function testFunctionStaticUrlNoBucketInLocalEnvSuccess()
    {
        Config::set('app.env', 'local');
        Config::set('packages.file-uploader.upload.static_url', $this->urlLocal);
        Config::set('packages.file-uploader.upload.default', 'local');

        $expectation = $this->urlLocal;
        $result      = static_url();

        $this->assertEquals($expectation, $result);
    }

    public function testFunctionParseUrlInLocalEnvSuccess()
    {
        Config::set('app.env', 'local');
        Config::set('packages.file-uploader.upload.static_url', $this->urlLocal);
        Config::set('packages.file-uploader.upload.default', 'local');

        $dataFile = $this->__makeFileName();

        $expectation = $this->urlLocal . 'uploads/' . $dataFile['fullPath'];
        $result      = parse_url_file($dataFile['file'], 'uploads');

        $this->assertEquals($expectation, $result);
    }

    private function __makeFileName()
    {
        $year  = date('Y');
        $month = date('m');
        $day   = date('d');

        $file = $year . "_" . $month . "_" . $day . "___test.jpeg";

        return [
            'fullPath'  => "$year/$month/$day/" . $year . "_" . $month . "_" . $day . "___test.jpeg",
            'file'  => $file
        ];
    }
}