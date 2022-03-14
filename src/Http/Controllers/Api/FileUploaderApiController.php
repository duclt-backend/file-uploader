<?php

namespace Workable\FileUploader\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workable\FileUploader\Services\FileUploaderService;

class FileUploaderApiController extends Controller
{
    public function uploadFile(Request $request)
    {
        $result = FileUploaderService::upload('file');
        return $result ? response()->json($result->toArray()) : response()->json(null);
    }

    public function uploadFileMulti(Request $request)
    {
        $results = FileUploaderService::uploadMulti('files');
        foreach ($results as &$result) {
            $result = $result->toArray();
        }
        return $results ? response()->json($results) : response()->json(null);
    }

    public function uploadFromBase64(Request $request)
    {
        $base64 = $request->base64;
        $results = FileUploaderService::uploadFileBase64($base64, 'file');
        return $results ? response()->json($results) : response()->json(null);
    }

    // Upload link
    public function uploadFromLink(Request $request)
    {
        $link = $request->get('link');
        $result = FileUploaderService::uploadFromLink($link);
        return $result ? response()->json($result->toArray()) : response()->json(null);
    }
}
