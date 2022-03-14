<?php
namespace Workable\FileUploader\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workable\FileUploader\Core\Store\ImageConverter;
use Workable\FileUploader\Services\ImageUploaderService;

class ImageUploaderController extends Controller
{
    public function getUploadImage()
    {
        $url = '2021_09_22______6551856851846f8c231bdaa5e3ae18cc.jpg';
        $link = parse_url_file($url,'uploads');
        echo_array($link);

        return view('packages.file-uploader::upload_image')->with([]);
    }

    // Upload
    public function postUploadImage()
    {
        $result = ImageUploaderService::upload('file', 'logo');
        echo_array($result);
    }

    // Upload multi
    public function postUploadImageMulti(Request $request)
    {
        $result = ImageUploaderService::uploadMulti('file', 'logo');
        echo_array($result);
    }

    // Upload base 64
    public function postUploadBase64(Request $request)
    {
        $base64 = $request->base64;
        $results = ImageUploaderService::uploadFromBase64($base64, 'logo', 'resize');
        echo_array($results);
    }

    // Upload link
    public function postUploadLink(Request $request)
    {
        $link = $request->link;
        $results = ImageUploaderService::uploadFromLink($link, 'logo', 'crop');
        echo_array($results);
    }

    public function convert(Request $request)
    {
        $from = "https://cdn.123job.vn/data-test/uploads/2021/02/16/2021_02_16______69dd53207924f6d8777c0fb7da04f01a.jpeg";
        $to  = '/uploads/2021/02/16/2021_02_16______69dd53207924f6d8777c0fb7da04f01a.jpeg.webp';

        $imageConverted = new ImageConverter();
        $imageConverted->convert($from, $to);
    }
}
