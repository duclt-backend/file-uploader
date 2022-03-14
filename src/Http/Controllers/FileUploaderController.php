<?php
namespace Workable\FileUploader\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workable\FileUploader\Services\FileUploaderService;
use Workable\FileUploader\Supports\FileUploaderConfigSupport;

class FileUploaderController extends Controller
{
    public function index()
    {
        $config = FileUploaderConfigSupport::get();
        $viewData = [
            'config' => $config
        ];
        return view('packages.file-uploader::welcome')->with($viewData);
    }

    public function getUpload()
    {
        $file = "2021_09_22______7011124a2042428337113411d8d9be9a.pdf";
        echo parse_url_file($file, "upload_files");
        return view('packages.file-uploader::upload_file')->with([]);
    }

    public function postUpload(Request $request)
    {
        $result = FileUploaderService::upload('file');

        return $result ? response()->json($result->toArray()) : response()->json(null);
    }

    public function postUploadMulti(Request $request)
    {
        $result = FileUploaderService::uploadMulti('file');
        echo_array($result);
    }

    public function postUploadBase64(Request $request)
    {
        $base64 = $request->base64;
        $results = FileUploaderService::uploadFileBase64($base64, 'file');
        echo_array($results);
    }

}
