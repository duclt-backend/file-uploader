<?php

namespace Workable\FileUploader\Tests\Feature;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use function json_decode;

class FileUploaderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUploadFileSuccess()
    {
        $client  = new Client();
        $request = $client->post(route('post.api.file.upload'), [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => file_get_contents( __DIR__ . "/../files/test_file.docx"),
                    'filename' => "test_file.docx"
                ]
            ]
        ]);

        $response = json_decode($request->getBody()->getContents(), 1);
        $this->assertIsString($response['file_name']);
    }

    public function testUploadFileMultiSuccess()
    {
        $client  = new Client();
        $request = $client->post(route('post.api.file.multi'), [
            'multipart' => [
                [
                    'name'     => 'files[]',
                    'contents' => file_get_contents( __DIR__ . "/../files/test_file.docx"),
                    'filename' => "test_file.docx"
                ],
                [
                    'name'     => 'files[]',
                    'contents' => file_get_contents( __DIR__ . "/../files/test_file.docx"),
                    'filename' => "test_file.docx"
                ],
                [
                    'name'     => 'files[]',
                    'contents' => file_get_contents( __DIR__ . "/../files/test_file.docx"),
                    'filename' => "test_file.docx"
                ]
            ]
        ]);

        $response = json_decode($request->getBody()->getContents(), 1);
        $this->assertEquals(3, count($response));

        foreach ($response as $item) {
            $this->assertIsString($item['file_name']);
        }
    }

    public function testUploadFileFromLinkSuccess()
    {
        $link = "https://www.orimi.com/pdf-test.pdf";
        $uri = route('post.api.file.link');

        $request = $this->post($uri, [
            'link' => $link
        ]);
        $response = json_decode($request->getContent(), 1);
        $this->assertIsString($response['file_name']);
    }

    public function testUploadFileFailWithoutFile()
    {
        $client  = new Client();
        $request = $client->post(route('post.file.upload'));
        $response = (string)$request->getBody()->getContents();

        $response = json_decode($request->getBody()->getContents(), 1);
        $this->assertEmpty($response);
    }

    public function testUploadFileFailWithWrongFormat()
    {
        $client  = new Client();
        $request = $client->post(route('post.file.upload'), [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => file_get_contents( __DIR__ . "/../files/test_image.jpeg"),
                    'filename' => "test_image.jpeg"
                ]
            ]
        ]);

        $response = json_decode($request->getBody()->getContents(), 1);
        $this->assertEmpty($response);
    }

    public function testUploadFileFailWithBigSize()
    {
        Config::set('packages.file-uploader.upload_file.pdf_and_doc.file_size', 5);

        $_FILES = [
            'file' => [
                'name' => "test_file.docx",
                'type' => "application/docx",
                'size' => 6340,
                'tmp_name' => __DIR__ . "/../files/test_file.docx",
                'error' => 0
            ]
        ];

        $request = $this->call("POST", route("post.file.upload"), [], [] , $_FILES);
        $response = $request->getContent();
        $response = json_decode($response, true);
        $this->assertEmpty($response);
    }
}