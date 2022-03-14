<?php

namespace Workable\FileUploader\Tests\Feature;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use function json_decode;

class ImageUploaderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUploadImageSuccess()
    {
        $client  = new Client();
        $request = $client->post(route('post.api.image.upload'), [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => file_get_contents( __DIR__ . "/../files/test_image.jpeg"),
                    'filename' => "test_image.jpeg"
                ]
            ]
        ]);

        $response = json_decode($request->getBody()->getContents(), 1);
        $this->assertIsString($response['file_name']);
    }

    public function testUploadImageMultiSuccess()
    {
        $client  = new Client();
        $request = $client->post(route('post.api.image.multi'), [
            'multipart' => [
                [
                    'name'     => 'files[]',
                    'contents' => file_get_contents( __DIR__ . "/../files/test_image.jpeg"),
                    'filename' => "test_image.jpeg"
                ],
                [
                    'name'     => 'files[]',
                    'contents' => file_get_contents( __DIR__ . "/../files/test_image.jpeg"),
                    'filename' => "test_image.jpeg"
                ],
                [
                    'name'     => 'files[]',
                    'contents' => file_get_contents( __DIR__ . "/../files/test_image.jpeg"),
                    'filename' => "test_image.jpeg"
                ]
            ]
        ]);
        $response = json_decode($request->getBody()->getContents(), 1);
        $this->assertEquals(3, count($response));

        foreach ($response as $item) {
            $this->assertIsString($item['file_name']);
        }
    }

    public function testUploadResizeSuccess()
    {
        Config::set("packages.file-uploader.upload_image.logo.check_resize", 1);

        $_FILES = [
            'file' => [
                'name' => "test_image.jpeg",
                'type' => "image/jpg",
                'size' => 302732,
                'tmp_name' => __DIR__ . "/../files/test_image.jpeg",
                'error' => 0
            ]
        ];

        $request = $this->call("POST", route("post.api.image.upload"), [], [] , $_FILES);
        $response = $request->getContent();
        $response = json_decode($response, true);

        $checkResize = $response['meta']['config_upload']['check_resize'];
        $countImgResize = count($response['thumbs']);

        $this->assertGreaterThan(0, $countImgResize);
        $this->assertEquals(1, $checkResize);
    }

    public function testUploadImageFromBase64Success()
    {
        $uri  = route('post.api.image.base64');
        $request = $this->post($uri, [
            'base64' => file_get_contents(__DIR__ . "/../files/base64.txt")
        ]);

        $response = json_decode($request->getContent(), 1);

        $this->assertIsString($response['file_name']);
    }

    public function testUploadImageFromLinkSuccess()
    {
        $link = "https://123job.vn/images/logo/logo349x137tim.png";
        $uri = route('post.api.image.link');

        $request = $this->post($uri, [
            'link' => $link
        ]);

        $response = json_decode($request->getContent(), 1);

        $this->assertIsString($response['file_name']);
    }

    public function testDeleteImageSuccess()
    {
        $fileName = $this->__makeFileToDelete();
        $uri      = route('post.api.image.delete');
        $response = $this->post($uri, [
            'file_name' => $fileName
        ]);
        $content  = $response->getContent();
        $content  = json_decode($content, true);
        $this->assertEquals(1, $content);
    }

    public function testUploadImageFailWithWrongFormat()
    {
        $client  = new Client();
        $request = $client->post(route('post.api.image.upload'), [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => file_get_contents( __DIR__ . "/../files/test_file.docx"),
                    'filename' => "test_image.docx"
                ]
            ]
        ]);
        $response = json_decode($request->getBody()->getContents(), 1);
        $this->assertEmpty($response);
    }

    public function testUploadImageFailWithoutFile()
    {
        $client  = new Client();
        $request = $client->post(route('post.api.image.upload'));

        $response = json_decode($request->getBody()->getContents(), 1);
        $this->assertEmpty($response);
    }

    public function testUploadImageFailWithBigSize()
    {
        Config::set("packages.file-uploader.upload_image.logo.file_size", 5);

        $_FILES = [
            'file' => [
                'name' => "test_image.jpeg",
                'type' => "image/jpeg",
                'size' => 302732,
                'tmp_name' => __DIR__ . "/../files/test_image.jpeg",
                'error' => 0
            ]
        ];

        $request = $this->call("POST", route("post.api.image.upload"), [], [] , $_FILES);
        $response = $request->getContent();
        $response = json_decode($response, true);

        $this->assertEmpty($response);
    }

    public function testUploadImageFailWithWrongLink()
    {
        $link = "https://123job.vn/images/logo/alo.png";
        $uri = route('post.api.image.link');

        $request = $this->post($uri, [
            'link' => $link
        ]);

        $response = json_decode($request->getContent(), 1);
        $this->assertEmpty($response);
    }

    public function testUploadImageFromLinkFailWithWrongFormat()
    {
        $link = "https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/5eeea355389655.59822ff824b72.gif";
        $uri = route('post.api.image.link');

        $request = $this->post($uri, [
            'link' => $link
        ]);

        $response = json_decode($request->getContent(), 1);
        $this->assertEmpty($response);
    }

    private function __makeFileToDelete()
    {
        $year = date("Y");
        $month = date("m");
        $day = date("d");

        $folder = Config::get('packages.file-uploader.upload_image.banner.upload_folder');
        $path = public_path() . "/$folder/$year/$month/$day/";
        $fileName = $year . "_" . "$month" . "_" . "$day" . "______test.jpeg";

        if (!file_exists($path . $fileName)) {
            $file =  file_get_contents( __DIR__ . "/../files/test_image.jpeg");
            file_put_contents($path . $fileName, $file);
        }

        return $fileName;
    }
}
